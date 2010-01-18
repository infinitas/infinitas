<?php
	/**
	 *
	 *
	 */
	class ModulesController extends ManagementAppController{
		var $name = 'Modules';

		function beforeFilter() {
			parent::beforeFilter();
		}

		function admin_index() {
			$modules = $this->paginate();
			$this->set(compact('modules'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				$this->Module->create();
				if ($this->Module->saveAll($this->data)) {
					$this->Session->setFlash('Your moodule has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$positions = $this->Module->Position->find('list');
			$groups = $this->Module->Group->find('list');
			$routes = $this->Module->Route->find('all');
			$this->set(compact('positions', 'groups', 'routes'));
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That module could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Module->save($this->data)) {
					$this->Session->setFlash(__('Your module has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your module could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Module->read(null, $id);
			}
		}
	}
?>