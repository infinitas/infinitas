<?php
	class ShopComponent extends Object{
		function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);
		}

		function sessionCartSave($product){
			$carts = $this->Controller->Session->read('Cart.TempCart');
			if(!empty($carts)){
				foreach($carts as &$cart){
					if($cart['Cart']['product_id'] == $this->Controller->params['named']['product_id']){
						if($cart['Cart']['quantity'] == 0){
							unset($cart);
							$this->Controller->Session->setFlash(__('Product was removed from the cart', true));
						}
						else{
							$cart['Cart']['quantity'] += $this->Controller->params['named']['quantity'];
							$cart['Cart']['price'] = $product['Product']['price'];
							$cart['Cart']['name'] = $product['Product']['name'];
							$this->Controller->Session->setFlash(__('Your cart was updated', true));
						}
					}
				}

				$this->Controller->Session->write('Cart.TempCart', $carts);
				$this->Controller->redirect($this->Controller->referer());
			}

			$currentCart[0]['Cart']['product_id'] = $this->Controller->params['named']['product_id'];
			$currentCart[0]['Cart']['quantity'] = $this->Controller->params['named']['quantity'];
			$currentCart[0]['Cart']['price'] = $product['Product']['price'];
			$currentCart[0]['Cart']['name'] = $product['Product']['name'];
			$this->Controller->Session->setFlash(__('The product was added to your cart', true));
			$this->Controller->redirect($this->Controller->referer());
		}

		function dbCartSave($product){
			$currentCart = $this->Controller->Cart->find(
				'first',
				array(
					'conditions' => array(
						'Cart.user_id' => $this->Controller->Session->read('Auth.User.id'),
						'Cart.product_id' => $this->Controller->params['named']['product_id'],
						'Cart.deleted' => array(0, 1)
					)
				)
			);

			if(!empty($currentCart)){
				if($this->Controller->params['named']['quantity'] == 0){
					if($this->Controller->Cart->delete($currentCart)){
						$this->Controller->Session->setFlash(__('Product was removed from the cart', true));
					}
					else{
						$this->Controller->Session->setFlash(__('Something went wrong', true));
					}
				}
				else{
					$currentCart['Cart']['quantity'] += $this->Controller->params['named']['quantity'];
					$currentCart['Cart']['deleted'] = 0;
					$currentCart['Cart']['deleted_date'] = '';
					$currentCart['Cart']['price'] = $product['Product']['price'];
					$currentCart['Cart']['name'] = $product['Product']['name'];

					if($this->Controller->Cart->save($currentCart)){
						$this->Controller->Session->setFlash(__('Your cart was updated', true));
					}
				}
				$this->Controller->redirect($this->Controller->referer());
			}

			$cart['Cart']['product_id'] = $this->Controller->params['named']['product_id'];
			$cart['Cart']['quantity'] = $this->Controller->params['named']['quantity'];
			$cart['Cart']['price'] = $product['Product']['price'];
			$cart['Cart']['name'] = $product['Product']['name'];

			$this->Controller->Cart->create();
			if($this->Controller->Cart->save($cart)){
				$this->Controller->Session->setFlash(__('The product was added to your cart', true));
				$this->Controller->redirect($this->Controller->referer());
			}
		}
	}
?>