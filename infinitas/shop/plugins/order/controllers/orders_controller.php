<?php
	class OrdersController extends OrderAppController{
		var $name = 'Orders';

		var $helpers = array(
			'Filter.Filter'
		);

		function checkout(){
			$this->data['Order']['status_id'] = $this->Order->Status->getFirst();
		}

		function admin_index(){
			$this->paginate = array(
				'contain' => array(
					'User',
					'Address',
					'Status'
				),
				'order' => array(
					'Status.ordering' => 'ASC'
				)
			);

			$orders = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'user_id' => $this->Order->User->find('list'),
				'status_id' => $this->Order->Status->find('list'),
				'payment_method' => array(),
				'shipping_method' => array()
			);
			$this->set(compact('orders','filterOptions'));
		}
	}