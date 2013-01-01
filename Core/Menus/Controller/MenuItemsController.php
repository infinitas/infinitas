<?php
/**
 * The MenuItemsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Menus.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
/**
 * The MenuItemsController
 *
 * @package Infinitas.Menus.Controller
 */

class MenuItemsController extends MenusAppController {

/**
 * List all menu items
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
				'Menu',
				'Group'
			)
		);

		$menuItems = $this->Paginator->paginate(
			null,
			array_merge(array($this->modelClass . '.parent_id !=' => 0), $this->Filter->filter)
		);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'menu_id' => array(null => __d('menus', 'All')) + $this->{$this->modelClass}->Menu->find('list'),
			'group_id' => array(null => __d('menus', 'Public')) + $this->{$this->modelClass}->Group->find('list'),
			'active' => (array)Configure::read('CORE.active_options')
		);

		$this->set(compact('menuItems', 'filterOptions'));
	}

/**
 * Add menu items
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		if (isset($this->request->params['named']['parent_id'])) {
			$this->request->data[$this->modelClass]['parent_id'] = $this->request->params['named']['parent_id'];
		}

		$menus   = $this->{$this->modelClass}->Menu->find('list');
		if (empty($menus)) {
			$this->notice(__d('menus', 'Please add a menu before adding items'), array(
				'level' => 'notice',
				'redirect' => array(
					'controller' => 'menus'
				)
			));
		}

		$groups  = array(0 => __d('menus', 'Public')) + $this->{$this->modelClass}->Group->find('list');
		$parents = array(0 => __d('menus', 'Root')) + $this->{$this->modelClass}->generateTreeList(array(
			$this->modelClass . '.parent_id !=' => 0,
			$this->modelClass . '.menu_id' => reset(array_keys($menus))
		));
		$plugins = $this->{$this->modelClass}->getPlugins();
		$this->set(compact('menus', 'groups', 'parents', 'plugins'));
	}

/**
 * Edit menu items
 *
 * @param string $id the id of the menu item
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit($id);

		$menus = $this->{$this->modelClass}->Menu->find('list');
		$groups = array(0 => __d('menus', 'Public')) + $this->{$this->modelClass}->Group->find('list');
		$parents = $this->{$this->modelClass}->getParents($this->request->data[$this->modelClass]['menu_id']);
		$plugins = $this->{$this->modelClass}->getPlugins();
		$controllers = $this->{$this->modelClass}->getControllers($this->request->data[$this->modelClass]['plugin']);
		$actions = $this->{$this->modelClass}->getActions($this->request->data[$this->modelClass]['plugin'], $this->request->data[$this->modelClass]['controller']);
		$this->set(compact('menus', 'groups', 'parents', 'plugins', 'controllers', 'actions'));
	}

/**
 * Get parent menus
 *
 * Used for the ajax getting of parent menus items to populate the ajax
 * dropdown menus when building and editing menu items.
 *
 * @return void
 */
	public function admin_getParents() {
		if (empty($this->request->data[$this->modelClass]['menu_id'])) {
			return false;
		}

		$this->set('json', $this->{$this->modelClass}->getParents($this->request->data[$this->modelClass]['menu_id']));
	}
}