<?php
/**
 * ModulesController
 *
 * @package Infinitas.Modules.Controller
 */

/**
 * ModulesController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Modules.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ModulesController extends ModulesAppController {
/**
 * List all modules
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'fields' => array(
				'Module.id',
				'Module.name',
				'Module.plugin',
				'Module.ordering',
				'Module.active',
				'Position.id',
				'Position.name',
				'Group.id',
				'Group.name',
				'Theme.name',
			),
			'joins' => array(
				$this->{$this->modelClass}->autoJoinModel('Modules.Position'),
				$this->{$this->modelClass}->autoJoinModel('Users.Group'),
				$this->{$this->modelClass}->autoJoinModel('Themes.Theme')
			)
		);

		$modules = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'plugin' => $this->Module->getPlugins(),
			'theme_id' => array(null => __d('modules', 'All')) + $this->Module->Theme->find('list'),
			'position_id' => array(null => __d('modules', 'All')) + $this->Module->Position->find('list'),
			'group_id' => array(null => __d('modules', 'Public')) + $this->Module->Group->find('list'),
			'active' => Configure::read('CORE.active_options')
		);

		$this->set(compact('modules', 'filterOptions'));
	}

/**
 * Create a module
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		$positions = $this->Module->Position->find('list');
		$groups = $this->Module->Group->find('list');
		$routes = array(0 => __d('modules', 'All Pages')) + $this->Module->Route->find('list');
		$themes = array(0 => __d('modules', 'All Themes')) + $this->Module->Theme->find('list');
		$plugins = $this->Module->getPlugins();
		$modules = $this->Module->getModuleList();
		$this->set(compact('positions', 'groups', 'routes', 'themes', 'plugins', 'modules'));
	}

/**
 * Edit a module
 *
 * @param string $id the module id
 *
 * @return void
 */
	public function admin_edit($id = null) {
		if (!empty($this->request->data['ModuleConfig'])) {
			$this->request->data['ModuleConfig'] = array_filter($this->request->data['ModuleConfig']);
			$this->request->data[$this->modelClass]['config'] = null;
			if ($this->request->data['ModuleConfig']) {
				$this->request->data[$this->modelClass]['config'] = json_encode($this->request->data['ModuleConfig']);
			}
		}
		parent::admin_edit($id, array('fields' => array('Module.*')));

		if (empty($this->request->data['ModuleConfig'])) {
			$this->request->data['ModuleConfig'] = json_decode($this->request->data[$this->modelClass]['config'], true);
		}

		$positions = $this->Module->Position->find('list');
		$groups = $this->Module->Group->find('list');
		$routes = array(0 => __d('modules', 'All Pages')) + $this->Module->Route->find('list');
		$themes = array(0 => __d('modules', 'All Themes')) + $this->Module->Theme->find('list');
		$plugins = $this->Module->getPlugins();

		$modules = $this->Module->getModuleList($this->request->data['Module']['plugin']);
		$this->set(compact('positions', 'groups', 'routes', 'themes', 'plugins', 'modules'));
	}

/**
 * get a list of modules that can be used.
 *
 * Gets the plugin name from POST data to find the required modules.
 *
 * @return void
 */
	public function admin_getModules() {
		if (!isset($this->request->data[$this->modelClass]['plugin'])) {
			$this->set('json', array('error'));
			return;
		}

		$this->set('json', $this->{$this->modelClass}->getModuleList($this->request->data[$this->modelClass]['plugin']));
	}

	public function admin_config() {
		$error = empty($this->request->data[$this->modelClass]['plugin']) || empty($this->request->data[$this->modelClass]['module']);
		if ($error) {
			return $this->set('json', array('error'));
		}

		$this->set('module', $this->request->data[$this->modelClass]);
	}

}