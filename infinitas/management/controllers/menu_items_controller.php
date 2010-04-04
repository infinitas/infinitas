<?php
	/**
	 *
	 *
	 */
	class MenuItemsController extends ManagementAppController{
		var $name = 'MenuItems';

		var $helpers = array(
			'Filter.Filter'
		);

		function admin_index(){
			$this->MenuItem->recursive = 0;

			$menuItems = $this->paginate(
				null,
				$this->Filter->filter
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

		function admin_add(){
			if (!empty($this->data)) {
				$this->MenuItem->create();
				if ($this->MenuItem->saveAll($this->data)) {
					$this->Session->setFlash('Your menu item has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if (isset($this->params['named']['parent_id'])) {
				$this->data['MenuItem']['parent_id'] = $this->params['named']['parent_id'];
			}

			$menus   = $this->MenuItem->Menu->find('list');
			$groups  = array(0 => __('Public', true)) + $this->MenuItem->Group->find('list');
			$parents = array(0 => __('Root', true)) + $this->MenuItem->generateTreeList();
			$plugins = $this->MenuItem->getPlugins();
			$this->set(compact('menus', 'groups', 'parents', 'plugins'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That menu item could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->MenuItem->save($this->data)) {
					$this->Session->setFlash(__('Your menu item has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your menu item could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->MenuItem->read(null, $id);
			}

			$menus   = $this->MenuItem->Menu->find('list');
			$groups  = array(0 => __('Public', true)) + $this->MenuItem->Group->find('list');
			$parents = array(0 => __('Root', true)) + $this->MenuItem->generateTreeList();
			$plugins = $this->MenuItem->getPlugins();
			$controllers = $this->Route->getControllers($this->data['Route']['plugin']);
			$actions = $this->Route->getActions($this->data['Route']['plugin'], $this->data['Route']['controller']);
			$this->set(compact('menus', 'groups', 'parents', 'plugins', 'controllers', 'actions'));
		}
	}
?>