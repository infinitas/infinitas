<?php
	/**
	 * The MenuItemsController
	 *
	 * Used to show and manage the items in a menu group.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Menus
	 * @subpackage Infinitas.Menus.controllers.MenusItemsController
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class MenuItemsController extends MenusAppController {
		public function admin_index(){
			$this->paginate = array(
				'contain' => array(
					'Menu'
				)
			);

			$menuItems = $this->paginate(
				null,
				array_merge(array('MenuItem.parent_id !=' => 0), $this->Filter->filter)
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'menu_id' => array(null => __('All')) + $this->MenuItem->Menu->find('list'),
				'group_id' => array(null => __('Public')) + $this->MenuItem->Group->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('menuItems', 'filterOptions'));
		}

		public function admin_add(){
			parent::admin_add();

			// auto select parent when the + button is used
			if (isset($this->params['named']['parent_id'])) {
				$this->request->data['MenuItem']['parent_id'] = $this->params['named']['parent_id'];
			}

			$menus   = $this->MenuItem->Menu->find('list');
			if(empty($menus)){
				$this->notice(
					__('Please add a menu before adding items'),
					array(
						'level' => 'notice',
						'redirect' => array(
							'controller' => 'menus'
						)
					)
				);
			}

			$groups  = array(0 => __('Public')) + $this->MenuItem->Group->find('list');
			$parents = array(0 => __('Root')) + $this->MenuItem->generateTreeList(
				array(
					'MenuItem.parent_id !=' => 0,
					'MenuItem.menu_id' => reset(array_keys($menus))
				)
			);
			$plugins = $this->MenuItem->getPlugins();
			$this->set(compact('menus', 'groups', 'parents', 'plugins'));
		}

		public function admin_edit($id = null){
			parent::admin_edit($id);

			$menus   = $this->MenuItem->Menu->find('list');
			$groups  = array(0 => __('Public')) + $this->MenuItem->Group->find('list');
			$parents = array(0 => __('Root')) + $this->MenuItem->generateTreeList(
				array(
					'MenuItem.parent_id !=' => 0,
					'MenuItem.menu_id' => $this->request->data['MenuItem']['menu_id']
				)
			);
			$plugins = $this->MenuItem->getPlugins();
			$controllers = $this->MenuItem->getControllers($this->request->data['MenuItem']['plugin']);
			$actions = $this->MenuItem->getActions($this->request->data['MenuItem']['plugin'], $this->request->data['MenuItem']['controller']);
			$this->set(compact('menus', 'groups', 'parents', 'plugins', 'controllers', 'actions'));
		}

		/**
		 * Get parent menus
		 *
		 * Used for the ajax getting of parent menus items to populate the ajax
		 * dropdown menus when building and editing menu items.
		 *
		 * @access public
		 */
		public function admin_getParents() {
			$conditions = array('MenuItem.menu_id' => $this->params['named']['plugin']);
			$json = array_merge(
				array(0 => __('Root')),
				$this->MenuItem->generateTreeList($conditions)
			);
			$this->set(compact('json'));
		}
	}