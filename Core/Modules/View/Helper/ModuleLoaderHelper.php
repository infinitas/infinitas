<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ModuleLoaderHelper extends InfinitasHelper{
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
		 * @params string $position this is the possition that is to be loaded, can be anything from the database
		 * @params bool $admin if true its a admin module that should be loaded.
		 */
		public function load($position = null, $admin = false){
			if (!$position) {
				$this->errors[] = 'No position selected to load modules';
				return false;
			}

			$modules = ClassRegistry::init('Modules.Module')->getModules($position, $admin);

			/**
			 * @todo if no modules based on possition, possition is a module -> $this->loadModule($position)
			 */

			$currentRoute = Configure::read('CORE.current_route');

			$out = '<div class="modules '.$position.'">';
			foreach($modules as $module) {
				if (
					($module['Theme']['name'] != '' && $module['Theme']['name'] != $this->theme) ||
					in_array($module['Module']['module'], $this->moduleIgnoreOverload)
				) {
					//skip modules that are not for the current theme
					continue;
				}
				$params = $this->__getModuleParams($module, $admin);
				
				if($params === false){
					continue; // from userland and its not active
				}

				$moduleOut = $this->loadModule($module['Module']['module'], $params);

				if (!empty($module['ModuleRoute']) && $currentRoute instanceof CakeRoute){
					foreach($module['ModuleRoute'] as $route) {
						if (empty($route['Route']['url']) || $route['Route']['url'] == $currentRoute->template) {
							$out .= $moduleOut;
						}
					}
				}

				else if (empty($module['ModuleRoute'])) {
					$out .= $moduleOut;
				}
			}
			$out .= '</div>';

			return $out;
		}

		/**
		 * Load single modules.
		 *
		 * This is used by the core module loader, and to load single modules. This
		 * can be handy when you just want to load a particular module inside a view.
		 *
		 * @params string $position this is the possition that is to be loaded, can be anything from the database
		 * @params bool $admin if true its a admin module that should be loaded.
		 */
		public function loadModule($module = null, $params = array()){
			if(!$module){
				return false;
			}

			if($params == null){
				$params = $this->__getModuleParams($module);
			}

			$class = isset($params['config']['class']) ? $params['config']['class'] : '';
			$id = isset($params['config']['id']) ? $params['config']['id'] : '';
			
			$moduleOut = $params['content'];
			if (!empty($module)) {
				$plugin = null;
				if(!empty($params['plugin'])) {
					$plugin = $params['plugin'];
					unset($params['plugin']);
				}
				
				$path = 'modules/';
				$moduleOut = $this->_View->element(
					implode('.', array(Inflector::camelize($plugin), $path . $module)),
					$params,
					true
				);


			}
			
			return sprintf('<div class="module %s %s">%s</div>', str_replace('/', '-', $module), $class, $moduleOut);

			return $moduleOut;
		}

		/**
		 * @brief load modules directly
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

			$admin = $this->_View->request->params['admin'] || (isset($params['admin']) && $params['admin']);
			if($admin && (isset($params['admin']) && $params['admin'] == false)) {
				$admin = false;
			}

			if($admin) {
				$module = sprintf('admin/%s', $module);
			}

			$module = sprintf('modules/%s', $module);

			$pluginDir = App::pluginPath($plugin) . 'View' . DS  . 'Elements' . DS . $module . '.ctp';
			$themeDir = App::themePath($this->theme) . $plugin . 'Elements' . DS . $module . '.ctp';

			if(!is_file($pluginDir)) {
				if(!is_file($themeDir)) {
					if(Configure::read('debug')) {
						return sprintf(
							'Could not find the module, looking in: <br/>%s<br/>%s',
							str_replace(APP, 'APP' . DS, $pluginDir),
							str_replace(APP, 'APP' . DS, $themeDir)
						);
					}
				}
			}
			
			$module = sprintf('%s.%s', $plugin, $module);
			
			try{
				return $this->_View->element($module, array('config' => $params));
			}
			
			catch(Exception $e) {
				$message = sprintf(
					'Error: Could not load module "%s" (%s)',
					$module,
					$e->getMessage()
				);
				
				throw new Exception($message);
			}
		}

		/**
		 * Get the params for the module being loaded, If loadModule is called
		 * from user land, they will not have the details of the module for it
		 * to load properly, and instead of making them do the db call manualy,
		 * it is done here automaticaly
		 *
		 * @param mixed $module string from userland, array (like find(first)) from core
		 * @param bool $admin affects the path the module is loaded from.
		 *
		 * @return array the params for loading the module.
		 */
		private function __getModuleParams($module, $admin = null){
			if(!$admin){
				$admin = isset($this->_View->request->params['admin']) ? $this->_View->request->params['admin'] : false;
			}

			if(is_string($module)) {
				$module = ClassRegistry::init('Modules.Module')->getModule($module, $admin);
				if(empty($module)){
					return false;
				}
			}

			$title = ($module['Module']['show_heading']) ?  : false;
			$content = (!empty($module['Module']['content'])) ? $module['Module']['content'] : false;
			
			return array(
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
		* @access protected
		* @params string $config some JSON data to be decoded.
		*/
		private function __getModuleConfig($config = ''){
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