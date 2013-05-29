<?php
/**
 * ModuleLoaderHelper
 *
 * @package Infinitas.Modules.Helper
 */

App::uses('InfinitasHelper', 'Libs.View/Helper');

/**
 * ModuleLoaderHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Modules.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ModuleLoaderHelper extends InfinitasHelper {

/**
 * Skip modules.
 *
 * Add the names of modules you want to skip to this var and they will not
 * be loaded.
 *
 * @var array
 */
	public $moduleIgnoreOverload = array();

/**
 * Module Loader.
 *
 * This is used to load modules. it generates a wrapper div with the class
 * set as the module name for easy styleing, and will create a header if set
 * in the backend.
 *
 * @param string $position this is the possition that is to be loaded, can be anything from the database
 * @param boolean $admin if true its a admin module that should be loaded.
 *
 * @return string
 */
	public function load($position = null, $admin = false) {
		if (!$position) {
			$this->errors[] = 'No position selected to load modules';
			return false;
		}

		$modules = ClassRegistry::init('Modules.Module')->getModules($position, $admin);

		/**
		 * @todo if no modules based on possition, possition is a module -> $this->loadModule($position)
		 */

		$currentRoute = Configure::read('CORE.current_route');
		$out = array();
		foreach ($modules as $module) {
			$shouldSkip = ($module['Theme']['name'] != '' && $module['Theme']['name'] != $this->theme) ||
				in_array($module['Module']['module'], $this->moduleIgnoreOverload);
			if ($shouldSkip) {
				continue;
			}

			$params = $this->__getModuleParams($module, $admin);
			if ($params === false) {
				continue;
			}

			$moduleOut = $this->loadModule($module['Module']['module'], $params);

			$error = !empty($this->_View->viewVars['error']) && $this->_View->viewVars['error'] instanceof Exception;
			$match = empty($module['ModuleRoute']) || $error || ($currentRoute instanceof CakeRoute && $this->_routeMatch($module['ModuleRoute'], $currentRoute));
			if ($match) {
				$out[] = $moduleOut;
			}
		}

		return $this->Html->tag('div', implode('', $out), array(
			'class' => array(
				'modules',
				$position
			)
		));
	}

/**
 * Check if any routes match the currect route
 *
 * If the routes are empty it means the module will load on all pages eles if there is a match this route should be loaded
 * for the page passed in
 *
 * @param array $moduleRoutes the module routes
 * @param CakeRoute $currentRoute the current route
 */
	protected function _routeMatch(array &$moduleRoutes, CakeRoute $currentRoute) {
		foreach ($moduleRoutes as $route) {
			if (empty($route['Route']['url']) || $route['Route']['url'] == $currentRoute->template) {
				return true;
			}
		}
	}

/**
 * Load single modules.
 *
 * This is used by the core module loader, and to load single modules. This
 * can be handy when you just want to load a particular module inside a view.
 *
 * @param string $module the module to load
 * @param array $params the module params
 *
 * @return string
 */
	public function loadModule($module = null, $params = array()) {
		if (!$module) {
			return false;
		}

		if (empty($params)) {
			$params = $this->__getModuleParams($module);
		}

		$class = isset($params['config']['class']) ? $params['config']['class'] : '';
		$id = isset($params['config']['id']) ? $params['config']['id'] : '';

		$moduleOut = $params['content'];
		if (!empty($module)) {
			$plugin = null;
			if (!empty($params['plugin'])) {
				$plugin = $params['plugin'];
				unset($params['plugin']);
			}

			$path = 'modules/';

			try {
				$moduleFile = implode('.', array(Inflector::camelize($plugin), $path . $module));
				if (!$this->_View->elementExists($moduleFile)) {
					$moduleFile .= DS . 'module';
				}
				
				$cache = $this->_moduleCache(Inflector::underscore($plugin), $params['config']);
				if (!$cache) {
					$moduleOut = $this->_View->element($moduleFile, $params);
				} else {
					$moduleOut = $this->_View->element($moduleFile, $params, array(
						'cache' => $cache
					));
				}
			} catch(Exception $e) {
				$moduleOut = $e->getMessage();
			}
		}
		if (!$moduleOut) {
			return false;
		}

		return $this->Html->tag('div', $moduleOut, array(
			'class' => array(
				'module',
				str_replace('/', '-', $module),
				$class
			)
		));
	}

/**
 * Build the cache config for the module
 * 
 * @param array $params the modules params
 * 
 * @return boolean|array
 */
	protected function _moduleCache($plugin, &$params) {
		if (Configure::read('debug')) {
			return false;
		}

		$p = array_merge(array(
			'diable_cache' => false,
			'cache' => true
		), $params);
		if ($p['disable_cache'] == true || $p['cache'] == false) {
			var_dump('no cache');
			return false;
		}
		$cache = array(
			'cache_key' => $params['_module_id'],
			'cache_config' => $plugin
		);

		if (is_array($params['cache'])) {
			$cache = array_merge($cache, $params['cache']);
		}

		return array(
			'key' => $cache['cache_key'],
			'config' => $cache['config']
		);
	}

/**
 * Load the config for a module if available
 *
 * If a module has not been selected a message will be shown
 *
 * This method expects the following information:
 *	- plugin: the plugin of the module to load
 *	- module: the module to get the config of
 *	- admin: if its admin or not
 *	- config: the existing config options
 *
 * If there is no config available a textarea will be generated for entering json configs
 *
 * @param array $module the details for the module
 *
 * @return string
 */
	public function moduleConfig(array $module) {
		$module = array_merge(array(
			'plugin' => null,
			'module' => null,
			'admin' => null,
			'config' => null
		), $module);

		if (empty($module['plugin']) && empty($module['module'])) {
			return $this->Design->alert(__d('modules', 'Select a module before configuring'));
		}

		$module['module'] = implode('/', array(
			'modules',
			$module['module'],
			'config'
		));
		if ($module['admin']) {
			$module['module'] = 'admin/' . $module['module'];
		}
		$moduleConfig = implode('.', array($module['plugin'], $module['module']));
		if ($this->_View->elementExists($moduleConfig)) {
			return $this->_View->element($moduleConfig, array(
				'config' => !empty($module['config']) ? (array)json_decode($module['config']) : array()
			));
		}
		return $this->Form->input('config', array(
			'class' => 'title',
			'type' => 'textarea',
			'value' => $module['config']
		));
	}

/**
 * load modules directly
 *
 * This is basically a short cut method to using ->element() but does
 * all the checking link is the plugin available, does the module exist
 * etc.
 *
 * @param string $module the module to load
 * @param string $params the params to pass to element() call
 *
 * @return string
 */
	public function loadDirect($module, $params = array()) {
		list($plugin, $module) = pluginSplit($module);

		// @check plugin is loaded
		// if not plugin return 'plugin foobar is not loaded / does not exist'

		$admin = (isset($this->_View->request->params['admin']) && $this->_View->request->params['admin']) || (isset($params['admin']) && $params['admin']);
		if ($admin && (isset($params['admin']) && $params['admin'] == false)) {
			$admin = false;
		}

		if ($admin) {
			$module = sprintf('admin/%s', $module);
		}

		$module = sprintf('modules/%s', $module);

		$pluginDir = App::pluginPath($plugin) . 'View' . DS  . 'Elements' . DS . $module . '.ctp';
		$module = implode('.', array(
			$plugin,
			$module
		));

		if (!$this->_View->elementExists($module)) {
			$module .= '/module';
			if (!$this->_View->elementExists($module)) {
				return __d('modules', 'Error: Could not load module "%s"', $module);
			}
		}

		return $this->_View->element($module, array('config' => $params, '_module_id' => 'direct'));
	}

/**
 * Get module params
 *
 * Get the params for the module being loaded, If loadModule is called
 * from user land, they will not have the details of the module for it
 * to load properly, and instead of making them do the db call manualy,
 * it is done here automaticaly
 *
 * @param string|array $module string from userland, array (like find(first)) from core
 * @param boolean $admin affects the path the module is loaded from.
 *
 * @return array
 */
	private function __getModuleParams($module, $admin = null) {
		if (!$admin) {
			$admin = isset($this->_View->request->params['admin']) ? $this->_View->request->params['admin'] : false;
		}

		if (is_string($module)) {
			$module = ClassRegistry::init('Modules.Module')->getModule($module, $admin);
			if (empty($module)) {
				return false;
			}
		}

		$title = ($module['Module']['show_heading']) ?  : false;
		$content = (!empty($module['Module']['content'])) ? $module['Module']['content'] : false;

		return array(
			'_module_id' => $module['Module']['id'],
			'plugin' => $module['Module']['plugin'],
			'title' => $title,
			'config' => $this->__getModuleConfig($module['Module']),
			'content' => $content,
			'admin' => $admin
		);
	}

/**
 * Module Config.
 *
 * This method works out params from JSON data in the module. if there is something
 * wrong with the JSON code that is submitted it will return an empty array(), or it
 * will return an array with the config.
 *
 * @param string $config some JSON data to be decoded.
 *
 * @return array
 */
	private function __getModuleConfig($config = '') {
		if (empty($config['config'])) {
			return array();
		}

		$json = json_decode($config['config'], true);

		if (!$json) {
			$this->errors[] = 'module ('.$config['name'].'): has no json';
			return array();
		}

		return $json;
	}

}