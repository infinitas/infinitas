<?php
	/**
	 *
	 *
	 */
	class IpAddressesController extends ManagementAppController{
		var $name = 'IpAddresses';


		function admin_index() {
			$this->IpAddress->recursive = 1;
			$ipAddresses = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'ip_address',
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('ipAddresses', 'filterOptions'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				$this->IpAddress->create();
				if ($this->IpAddress->save($this->data)) {
					$this->Session->setFlash('The ip address / range has been blocked.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That record could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->IpAddress->save($this->data)) {
					$this->Session->setFlash(__('The record has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('The record could not be updated.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->IpAddress->read(null, $id);
			}
		}
	}
?>