<?php
	class PaymentsController extends OrderAppController{
		var $name = 'Payments';

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
				),
				'contain' => array(
					'Order',
					'User'
				)
			);

			$payments = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'order_id' => $this->Payment->Order->find('list'),
				'user_id' => $this->Payment->User->find('list')
			);
			$this->set(compact('payments','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Payment->create();
				if ($this->Payment->saveAll($this->data)) {
					$this->Session->setFlash('The manual payment has been entered');
					$this->redirect(array('action' => 'index'));
				}
			}

			$orders = $this->Payment->Order->find('list');
			$users = $this->Payment->User->find('list');
			$this->set(compact('orders', 'users'));
		}
	}