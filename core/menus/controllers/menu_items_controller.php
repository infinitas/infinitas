<?php
	/**
	 *
	 *
	 */
	class MenuItemsController extends MenusAppController{
		public $name = 'MenuItems';

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
				'menu_id' => array(null => __('All', true)) + $this->MenuItem->Menu->find('list'),
				'group_id' => array(null => __('Public', true)) + $this->MenuItem->Group->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('menuItems','filterOptions'));
		}

		public function admin_add(){
			parent::admin_add();

			// auto select parent when the + button is used
			if (isset($this->params['named']['parent_id'])) {
				$this->data['MenuItem']['parent_id'] = $this->params['named']['parent_id'];
			}

			$menus   = $this->MenuItem->Menu->find('list');
			$groups  = array(0 => __('Public', true)) + $this->MenuItem->Group->find('list');
			$parents = array(0 => __('Root', true)) + $this->MenuItem->generateTreeList(array('MenuItem.parent_id !=' => 0, 'MenuItem.menu_id' => reset(array_keys($menus))));
			$plugins = $this->MenuItem->getPlugins();
			$this->set(compact('menus', 'groups', 'parents', 'plugins'));
		}

		/**
		 * Get parent menus
		 *
		 * Used for the ajax getting of parent menus items
		 */
		public function admin_getParents() {
			$this->set('json', array(0 => __('Root', true)) + $this->MenuItem->generateTreeList(array('MenuItem.parent_id !=' => 0, 'MenuItem.menu_id' => $this->params['named']['plugin'])));
		}

		public function admin_edit($id = null){
			parent::admin_edit($id);

			$menus   = $this->MenuItem->Menu->find('list');
			$groups  = array(0 => __('Public', true)) + $this->MenuItem->Group->find('list');
			$parents = array(0 => __('Root', true)) + $this->MenuItem->generateTreeList(array('MenuItem.parent_id !=' => 0, 'MenuItem.menu_id' => $this->data['MenuItem']['menu_id']));
			$plugins = $this->MenuItem->getPlugins();
			$controllers = $this->MenuItem->getControllers($this->data['MenuItem']['plugin']);
			$actions = $this->MenuItem->getActions($this->data['MenuItem']['plugin'], $this->data['MenuItem']['controller']);
			$this->set(compact('menus', 'groups', 'parents', 'plugins', 'controllers', 'actions'));
		}
	}