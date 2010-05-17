<?php
	class ItemsController extends OrderAppController{
		var $name = 'Items';

		function admin_index(){
			$this->paginate = array(
				'contain' => array(
					'Product',
					'Order'
				)
			);

			$items = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'order_id' => $this->Item->Order->find('list'),
				'product_id' => $this->Item->Product->find('list'),
			);
			$this->set(compact('items','filterOptions'));
		}
	}