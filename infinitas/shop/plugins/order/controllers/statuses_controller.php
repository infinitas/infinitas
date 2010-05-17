<?php
	class StatusesController extends OrderAppController{
		var $name = 'Statuses';

		function admin_index(){
			$this->paginate = array(
				'order' => array(
					'Status.ordering' => 'ASC'
				)
			);

			$statuses = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);
			$this->set(compact('statuses','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Status->create();
				if ($this->Status->saveAll($this->data)) {
					$this->Session->setFlash('Your status has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That status could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Status->saveAll($this->data)) {
					$this->Session->setFlash('Your status has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Status->read(null, $id);
			}
		}
	}