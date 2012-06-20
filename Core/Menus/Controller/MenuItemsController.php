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
		public function admin_index() {
			$this->Paginator->settings = array(
				'contain' => array(
					'Menu'
				)
			);

			$menuItems = $this->Paginator->paginate(
				null,
				array_merge(array($this->modelClass . '.parent_id !=' => 0), $this->Filter->filter)
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'menu_id' => array(null => __('All')) + $this->{$this->modelClass}->Menu->find('list'),
				'group_id' => array(null => __('Public')) + $this->{$this->modelClass}->Group->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('menuItems', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

			// auto select parent when the + button is used
			if (isset($this->request->params['named']['parent_id'])) {
				$this->request->data[$this->modelClass]['parent_id'] = $this->request->params['named']['parent_id'];
			}

			$menus   = $this->{$this->modelClass}->Menu->find('list');
			if(empty($menus)) {
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

			$groups  = array(0 => __('Public')) + $this->{$this->modelClass}->Group->find('list');
			$parents = array(0 => __('Root')) + $this->{$this->modelClass}->generateTreeList(
				array(
					$this->modelClass . '.parent_id !=' => 0,
					$this->modelClass . '.menu_id' => reset(array_keys($menus))
				)
			);
			$plugins = $this->{$this->modelClass}->getPlugins();
			$this->set(compact('menus', 'groups', 'parents', 'plugins'));
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id);

			$menus   = $this->{$this->modelClass}->Menu->find('list');
			$groups  = array(0 => __('Public')) + $this->{$this->modelClass}->Group->find('list');
			$parents = array(0 => __('Root')) + $this->{$this->modelClass}->generateTreeList(
				array(
					$this->modelClass . '.parent_id !=' => 0,
					$this->modelClass . '.menu_id' => $this->request->data[$this->modelClass]['menu_id']
				)
			);
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
		 * @access public
		 */
		public function admin_getParents() {
			if(empty($this->request->data[$this->modelClass]['menu_id'])) {
				return false;
			}
			
			try {
				$this->set('json', $this->{$this->modelClass}->getParents($this->request->data[$this->modelClass]['menu_id']));
			}
			
			catch(Exception $e) {
				throw $e;
			}
		}
	}