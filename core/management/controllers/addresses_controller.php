<?php
	class AddressesController extends ManagementAppController{
		var $name = 'Addresses';

		function add(){
			if($this->Session->read('Auth.User.id') < 1){
				$this->Session->setFlash(__('You must be logged in to do that', true));
				$this->redirect('/');
			}

			if (!empty($this->data)) {
				$this->Address->create();
				if ($this->Address->saveAll($this->data)) {
					$this->Session->setFlash('Your address has been saved.');
					$this->redirect('/');
				}
			}

			$this->data['Address']['plugin'] = 'management';
			$this->data['Address']['model'] = 'user';
			$this->data['Address']['foreign_key'] = $this->Session->read('Auth.User.id');

			$countries = $this->Address->Country->find('list');
			$continents = array(0 => 'Other', 1 => 'Africa');
			$this->set(compact('referer', 'countries', 'continents'));
		}

		function edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That address could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Address->save($this->data)) {
					$this->Session->setFlash('Your address has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Address->read(null, $id);
			}

			$countries = $this->Address->Country->find('list');
			$continents = array(0 => 'Other', 1 => 'Africa');
			$this->set(compact('countries', 'continents'));
		}
	}