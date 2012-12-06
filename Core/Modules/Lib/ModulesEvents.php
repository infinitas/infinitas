<?php
/**
 * ModulesEvents
 *
 * @package Infinitas.Modules.Lib
 */

/**
 * ModuleTemplate
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Modules.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ModuleTemplate{
/**
 * Data cache
 *
 * @var array
 */
	protected $_data = array();

/**
 * Magic getter
 *
 * @param string $key the key to get
 *
 * @return mixed
 */
	public function __get($key) {
		return isset($this->_data[$key]) ? $this->_data[$key] : null;
	}

/**
 * Check if a key is set
 *
 * @param string $key the key to check
 *
 * @return boolean
 */
	public function __isset($key) {
		if ($this->__isPosition($key)) {
			App::import('Helper', 'Modules.ModuleLoader');
			$ModuleLoader = new ModuleLoaderHelper();
			$this->_data[$key] = $ModuleLoader->load($key);
			return true;
		}
	}

/**
 * Check a module position is valid
 *
 * @param string $position the name of the position
 *
 * @return boolean
 */
	private function __isPosition($position) {
		return ClassRegistry::init('Modules.ModulePosition')->isPosition($position);
	}

}

/**
 * ModulesEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Modules.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ModulesEvents extends AppEvents {
/**
 * Configure module cache
 *
 * @return array
 */
	public function onSetupCache(Event $Event) {
		return array(
			'name' => 'modules',
			'config' => array(
				'prefix' => 'core.modules.'
			)
		);
	}

	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Modules',
			'description' => 'Add sections of output to your site with ease',
			'icon' => 'list-alt',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'modules', 'controller' => 'module_positions', 'action' => 'index')
		);
	}

/**
 * Get admin menu
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu = array(
			'main' => array(
				'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
				'Modules' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index'),
				'Module Positions' => array('controller' => 'module_positions', 'action' => 'index')
			),
			'filter' => array(
				'Frontend' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.admin' => 0),
				'Backend' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.admin' => 1),
			)
		);

		return $menu;
	}

/**
 * Load module helpers
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Modules.ModuleLoader'
		);
	}

	public function onRequireJavascriptToLoad(Event $Event) {
		if(!isset($Event->Handler->request->params['admin']) || !$Event->Handler->request->params['admin']) {
			return;
		}

		return array(
			'Modules.modules_admin'
		);
	}

/**
 * Load module templates
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireGlobalTemplates(Event $Event) {
		return array(
			'ModuleLoader' => new ModuleTemplate()
		);
	}

}