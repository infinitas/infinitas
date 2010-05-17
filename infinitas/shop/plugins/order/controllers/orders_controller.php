<?php
	class OrdersController extends OrderAppController{
		var $name = 'Orders';

		function checkout(){
			$user_id = $this->Session->read('Auth.User.id');
			if($user_id < 1){
				$this->Session->setFlash(__('You need to be logged in to checkout', true));
				$this->redirect(array('plugin' => 'shop', 'controller' => 'carts', 'action' => 'index'));
			}

			$cartItems = ClassRegistry::init('Shop.Cart')->getCartData($user_id);
			if(empty($cartItems)){
				$this->Session->setFlash(__('You dont have any products', true));
				$this->redirect(array('plugin' => 'shop', 'controller' => 'products', 'action' => 'dashboard'));
			}

			$this->data['Order']['status_id'] = $this->Order->Status->getFirst();
			$this->data['Order']['user_id'] = $user_id;
			$this->data['Order']['tracking_number'] = '';

			foreach($cartItems as $item){
				unset($item['Cart']['created']);
				unset($item['Cart']['modified']);
				unset($item['Cart']['sub_total']);
				unset($item['Cart']['deleted']);
				unset($item['Cart']['deleted_date']);
				$this->data['Item'][] = $item['Cart'];
			}

			if($this->Order->saveAll($this->data)){
				ClassRegistry::init('Shop.Cart')->clearCart($user_id);
				$this->Session->setFlash(__('Your order has been completed and now requires payment', true));
				$this->redirect(array('action' => 'pay'));
			}

			$this->Session->setFlash(__('Your order has been completed and now requires payment', true));
			$this->redirect(array('action' => 'pay'));
		}

		function pay(){

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