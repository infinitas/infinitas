<?php
	class ShopComponent extends Object{
		function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);
		}

		function sessionCartSave($Model, $product){
			$this->Controller->Session->write($Model->alias.'.Temp'.$Model->alias, '');
			$datas = $this->Controller->Session->read($Model->alias.'.Temp'.$Model->alias);

			if(!empty($datas)){
				$done = false;
				foreach($datas as &$data){
					if($data[$Model->alias]['product_id'] == $this->Controller->params['named']['product_id']){
						if($data[$Model->alias]['quantity'] == 0){
							unset($data);
							$this->Controller->Session->setFlash(__('Product was removed from the '.$Model->alias, true));
							$done = true;
						}
						else{
							$data[$Model->alias]['quantity'] += $this->Controller->params['named']['quantity'];
							$data[$Model->alias]['price']     = $product['Product']['price'];
							$data[$Model->alias]['name']      = $product['Product']['name'];
							$data[$Model->alias]['sub_total'] = $product['Product']['price'] * $data[$Model->alias]['quantity'];
							$this->Controller->Session->setFlash(__('Your '.$Model->alias.' was updated', true));
							$done = true;
						}
					}
				}

				if(!$done){
					$datas[] = array(
						$Model->alias => array(
							'product_id' => $this->Controller->params['named']['product_id'],
							'quantity'   => $this->Controller->params['named']['quantity'],
							'price'      => $product['Product']['price'],
							'sub_total'  => $product['Product']['price'] * $this->Controller->params['named']['quantity'],
							'name'       => $product['Product']['name']
						),
						'Product' => $product['Product']
					);
				}

				$this->Controller->Session->write($Model->alias.'.Temp'.$Model->alias, $datas);
				$this->Controller->redirect($this->Controller->referer());
			}

			$currentCart[0][$Model->alias]['product_id'] = $this->Controller->params['named']['product_id'];
			$currentCart[0][$Model->alias]['quantity'] = $this->Controller->params['named']['quantity'];
			$currentCart[0][$Model->alias]['price'] = $product['Product']['price'];
			$currentCart[0][$Model->alias]['sub_total'] = $product['Product']['price'] * $this->Controller->params['named']['quantity'];
			$currentCart[0][$Model->alias]['name'] = $product['Product']['name'];
			$currentCart[0]['Product'] = $product['Product'];

			$this->Controller->Session->write($Model->alias.'.Temp'.$Model->alias, $currentCart);

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