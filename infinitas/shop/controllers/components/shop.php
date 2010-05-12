<?php
	class ShopComponent extends Object{
		function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);
		}

		function sessionCartSave($Model, $product){
			$carts = $this->Controller->Session->read($Model->alias.'.Temp'.$Model->alias);
			if(!empty($carts)){
				foreach($carts as &$cart){
					if($cart[$Model->alias]['product_id'] == $this->Controller->params['named']['product_id']){
						if($cart[$Model->alias]['quantity'] == 0){
							unset($cart);
							$this->Controller->Session->setFlash(__('Product was removed from the '.$Model->alias, true));
						}
						else{
							$cart[$Model->alias]['quantity'] += $this->Controller->params['named']['quantity'];
							$cart[$Model->alias]['price'] = $product['Product']['price'];
							$cart[$Model->alias]['name'] = $product['Product']['name'];
							$this->Controller->Session->setFlash(__('Your '.$Model->alias.' was updated', true));
						}
					}
				}

				$this->Controller->Session->write($Model->alias.'.TempCart', $carts);
				$this->Controller->redirect($this->Controller->referer());
			}

			$currentCart[0][$Model->alias]['product_id'] = $this->Controller->params['named']['product_id'];
			$currentCart[0][$Model->alias]['quantity'] = $this->Controller->params['named']['quantity'];
			$currentCart[0][$Model->alias]['price'] = $product['Product']['price'];
			$currentCart[0][$Model->alias]['name'] = $product['Product']['name'];

			$this->_updateAddCount($product['Product']);
			$this->Controller->Session->setFlash(__('The product was added to your '.$Model->alias, true));
			$this->Controller->redirect($this->Controller->referer());
		}

		function dbCartSave($Model,$product){
			$currentCart = $Model->find(
				'first',
				array(
					'conditions' => array(
						$Model->alias.'.user_id' => $this->Controller->Session->read('Auth.User.id'),
						$Model->alias.'.product_id' => $this->Controller->params['named']['product_id'],
						$Model->alias.'.deleted' => array(0, 1)
					)
				)
			);

			if(!empty($currentCart)){
				if($this->Controller->params['named']['quantity'] == 0){
					if($Model->delete($currentCart)){
						$this->Controller->Session->setFlash(__('Product was removed from the '.$Model->alias, true));
					}
					else{
						$this->Controller->Session->setFlash(__('Something went wrong', true));
					}
				}
				else{
					$currentCart[$Model->alias]['quantity'] += $this->Controller->params['named']['quantity'];
					$currentCart[$Model->alias]['deleted'] = 0;
					$currentCart[$Model->alias]['deleted_date'] = '';
					$currentCart[$Model->alias]['price'] = $product['Product']['price'];
					$currentCart[$Model->alias]['name'] = $product['Product']['name'];

					if($Model->save($currentCart)){
						$this->Controller->Session->setFlash(__('Your '.$Model->alias.' was updated', true));
					}
				}
				$this->Controller->redirect($this->Controller->referer());
			}

			$cart[$Model->alias]['product_id'] = $this->Controller->params['named']['product_id'];
			$cart[$Model->alias]['quantity'] = $this->Controller->params['named']['quantity'];
			$cart[$Model->alias]['price'] = $product['Product']['price'];
			$cart[$Model->alias]['name'] = $product['Product']['name'];
			$cart[$Model->alias]['user_id'] = $this->Controller->Session->read('Auth.User.id');

			$Model->create();
			if($Model->save($cart)){
				$this->_updateAddCount($Model, $product['Product']);
				$this->Controller->Session->setFlash(__('The product was added to your '.$Model->alias, true));
				$this->Controller->redirect($this->Controller->referer());
			}
		}

		function _updateAddCount($Model, $product = null){
			if(!$product || empty($product)){
				$this->errors[] = 'no product selected';
				return false;
			}
			switch(isset($product['added_to_cart'])){
				case true:
					return $Model->Product->updateAll(
						array(
							'Product.added_to_cart' => $product['added_to_cart'] + 1
						),
						array(
							'Product.id' => $product['id']
						)
					);
					break;

				case false:
					return $Model->Product->updateAll(
						array(
							'Product.added_to_wishlist' => $product['added_to_wishlist'] + 1
						),
						array(
							'Product.id' => $product['id']
						)
					);
					break;
			}
		}
	}