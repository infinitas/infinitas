<?php
	class CartsController extends ShopAppController{
		var $name = 'Carts';

		var $helpers = array(
			'Filter.Filter'
		);

		function index(){
			$userId = $this->Session->read('Auth.User.id');

			if(!$userId){
				$this->Session->setFlash(__('You must be logged in to checkout', true));
				$this->redirect(
					array(
						'plugin' => 'management',
						'controller' => 'user',
						'action' => 'login'
					)
				);
			}

			$carts = $this->Cart->find(
				'all',
				array(
					'conditions' => array(
						'Cart.user_id' => $userId
					),
					'contain' => array(
						'Product'
					)
				)
			);

			$amounts['sub_total'] = array_sum((array)Set::extract('/Cart/sub_total', $carts));
			$eventData = $this->Event->trigger(
				'calculateShipping',
				array(
					'total' => $amounts['sub_total'] ,
					'items' => $carts,
					'method' => $this->Session->read('Shop.shipping_method')
				)
			);

			$amounts['shipping']   = (int)$eventData['calculateShipping']['shop'];
			$amounts['total_excl'] = $amounts['sub_total'] + $amounts['shipping'];
			$amounts['vat']        = Configure::read('Shop.vat_rate') > 0 ? ($amounts['total_excl'] / 100) * (int)Configure::read('Shop.vat_rate') : 0;
			$amounts['total_due']  = $amounts['total_excl'] + $amounts['vat'];

			$this->set(compact('carts', 'amounts'));
		}

		function adjust(){
			if(!isset($this->params['named']['product_id'])){
				$this->Session->setFlash(__('Invalid product selected'), true);
				$this->redirect($this->referer());
			}

			if(!isset($this->params['named']['quantity'])){
				$this->params['named']['quantity'] = 1;
			}

			$product = $this->Cart->Product->find(
				'first',
				array(
					'conditions' => array(
						'Product.id' => $this->params['named']['product_id']
					),
					'fields' => array(
						'Product.id',
						'Product.name',
						'Product.price',
						'Product.active'
					),
					'contain' => array(
						'Special'
					)
				)
			);

			if(empty($product) || $product['Product']['active'] == false){
				$this->Session->setFlash(__('That product does not exsist'), true);
				$this->redirect($this->referer());
			}

			$price = $product['Product']['price'];
			if(isset($product['Special']) && !empty($product['Special'][0])){
				$price = $product['Product']['price'] - (($product['Product']['price'] / 100) * $product['Special'][0]['discount']);
			}

			if($userId = $this->Session->read('Auth.User.id') > 0){
				$currentCart = $this->Cart->find(
					'first',
					array(
						'conditions' => array(
							'Cart.user_id' => $userId,
							'Cart.product_id' => $this->params['named']['product_id'],
							'Cart.deleted' => array(0, 1)
						)
					)
				);

				if(!empty($currentCart)){
					if($this->params['named']['quantity'] == 0){
						if($this->Cart->delete($currentCart)){
							$this->Session->setFlash(__('Product was removed from the cart', true));
						}
						else{
							$this->Session->setFlash(__('Something went wrong', true));
						}
					}
					else{
						$currentCart['Cart']['quantity'] += $this->params['named']['quantity'];
						$currentCart['Cart']['deleted'] = 0;
						$currentCart['Cart']['deleted_date'] = '';
						$currentCart['Cart']['price'] = $price;
						$currentCart['Cart']['name'] = $product['Product']['name'];

						if($this->Cart->save($currentCart)){
							$this->Session->setFlash(__('Your cart was updated', true));
						}
					}
					$this->redirect($this->referer());
				}

				$cart['Cart']['product_id'] = $this->params['named']['product_id'];
				$cart['Cart']['quantity'] = $this->params['named']['quantity'];
				$cart['Cart']['user_id'] = $userId;
				$cart['Cart']['price'] = $price;
				$cart['Cart']['name'] = $product['Product']['name'];

				$this->Cart->create();
				if($this->Cart->save($cart)){
					$this->Session->setFlash(__('The product was added to your cart', true));
					$this->redirect($this->referer());
				}
			}

			$carts = $this->Session->read('Cart.TempCart');
			if(!empty($carts)){
				foreach($carts as &$cart){
					if($cart['Cart']['product_id'] == $this->params['named']['product_id']){
						if($cart['Cart']['quantity'] == 0){
							unset($cart);
							$this->Session->setFlash(__('Product was removed from the cart', true));
						}
						else{
							$cart['Cart']['quantity'] += $this->params['named']['quantity'];
							$cart['Cart']['price'] = $price;
							$cart['Cart']['name'] = $product['Product']['name'];
							$this->Session->setFlash(__('Your cart was updated', true));
						}
					}
				}

				$this->Session->write('Cart.TempCart', $carts);
				$this->redirect($this->referer());
			}

			$currentCart[0]['Cart']['product_id'] = $this->params['named']['product_id'];
			$currentCart[0]['Cart']['quantity'] = $this->params['named']['quantity'];
			$currentCart[0]['Cart']['price'] = $price;
			$currentCart[0]['Cart']['name'] = $product['Product']['name'];
			$this->Session->setFlash(__('The product was added to your cart', true));
			$this->redirect($this->referer());
		}

		function change_shipping_method(){
			if(isset($this->data['Cart']['shipping_method']) && !empty($this->data['Cart']['shipping_method'])){
				$this->Session->write('Shop.shipping_method', $this->data['Cart']['shipping_method']);

				$this->Session->setFlash(__('Shipping method updated', true));
				$this->redirect(array('action' => 'index'));
			}
		}

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Cart.id',
					'Cart.user_id',
					'Cart.product_id',
					'Cart.price',
					'Cart.quantity',
					'sub_total',
					'Cart.created',
					'Cart.deleted',
					'Cart.deleted_date'
				),
				'conditions' => array(
					'Cart.deleted' => 1
				),
				'contain' => array(
					'User',
					'Product'
				),
				'order' => array(
					'User.username'
				)
			);

			$carts = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'user_id' => $this->Cart->User->find('list'),
				'product_id' => $this->Cart->Product->find('list'),
			);
			$this->set(compact('carts','filterOptions'));
		}
	}