<?php
	class ShopAppModel extends AppModel {
		var $tablePrefix = 'shop_';

		var $actsAs = array(
			'Feed.Feedable'
		);

		/**
		 * Move data from session to cart.
		 *
		 * Takes the cart/wishlist data from the session and moves it to the
		 * database. for the cart it will update the count if the item exsists.
		 *
		 * @param array $datas the data that was in the session
		 * @param array $user Session->read('Auth.User') data
		 *
		 * @return bool true if all was saved, false if not saved.
		 */
		function moveSessionToDb($datas = null, $user = null){
			if(empty($user)){
				return false;
			}

			if(empty($datas)){
				return true;
			}

			$true = true;
			switch($this->alias){
				case 'Cart':
					return $this->_moveCart($datas, $user);
					break;

				case 'Wishlist':

					return $this->_moveWishlist($datas, $user);
					break;
			}

			return (bool)$true;
		}

		function _moveCart($datas, $user){
			$old = $this->getCartData($user['id']);
			$oldCartIds = Set::extract('/Cart/product_id', $old);

			$true = true;

			foreach($datas as &$data){
				unset($data['Product']);
				$data[$this->alias]['user_id'] = $user['id'];
				$update = false;

				if(in_array($data[$this->alias]['product_id'], $oldCartIds)){
					foreach($old as $oldCart){
						if($oldCart[$this->alias]['product_id'] == $data[$this->alias]['product_id']){
							$data[$this->alias]['id'] = $oldCart[$this->alias]['id'];
							$data[$this->alias]['quantity'] += $oldCart[$this->alias]['quantity'];
							$update = true;
							break;
						}
					}
				}

				if(!$update){
					$this->create();
				}
				$true &= $this->save($data);
			}

			return $true;
		}

		function _moveWishlist($datas, $user){
			$old = $this->getWishlistData($user['id']);
			$oldWishlistIds = Set::extract('/Wishlist/product_id', $old);
			$true = true;

			foreach($datas as &$data){
				unset($data['Product']);
				$data[$this->alias]['user_id'] = $user['id'];
				$update = false;

				if(in_array($data[$this->alias]['product_id'], $oldWishlistIds)){
					foreach($old as $oldWishlist){
						if($oldWishlist[$this->alias]['product_id'] == $data[$this->alias]['product_id']){
							$data[$this->alias]['id'] = $oldWishlist[$this->alias]['id'];
							$update = true;
							break;
						}
					}
				}

				// its already there so dont need to add it again
				if($update){
					break;
				}

				$this->create();
				$true &= $this->save($data);
			}

			return $true;
		}
	}