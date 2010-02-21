<?php
	class MenusController extends ManagementAppController{
		var $name = 'Menus';

		var $helpers = array('Libs.Infinitas');

		function admin_index(){
			$this->Menu->recursive = 0;

			$menus = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'type',
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('menus','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Menu->create();
				if ($this->Menu->saveAll($this->data)) {
					$this->Session->setFlash('Your menu has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$this->set(compact());
		}

		function admin_edit($id = null){

		}
	}
?>