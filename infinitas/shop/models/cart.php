<?php
	class Cart extends ShopAppModel{
		var $name = 'Cart';

		/**
		 * SoftDeletable does not auto load for non admin
		 *
		 * @var unknown_type
		 */
		var $actsAs = array(
			'Libs.SoftDeletable'
		);

		/**
		 * sub_total is the line total
		 * @var unknown_type
		 */
		var $virtualFields = array(
			'sub_total' => 'Cart.quantity * Cart.price'
		);

		var $belongsTo = array(
			'Product' => array(
				'className' => 'Shop.Product',
				'fields' => array(
					'Product.id',
					'Product.name',
					'Product.slug'
				)
			),
			'User' => array(
				'className' => 'Management.User',
				'fields' => array(
					'User.id',
					'User.username'
				)
			)
		);

		function getCartData($user_id = null){
			if((int)$user_id > 0){
				$cacheName = cacheName('cart', $user_id);
				$cartData = Cache::read($cacheName, 'shop');
				if(!empty($cartData)){
					return $cartData;
				}

				$cartData = $this->find(
					'all',
					array(
						'conditions' => array(
							'Cart.user_id' => $user_id
						),
						'contain' => array(
							'User',
							'Product'
						)
					)
				);

				Cache::write($cacheName, $cartData, 'shop');

				return $cartData;
			}

			App::import('CakeSession');
			$this->Session = new CakeSession();

			$data = $this->Session->read('Cart.TempCart');

			return (array)$data;
		}

		function afterSave($created){
			return $this->dataChanged('afterSave');
		}

		function afterDelete(){
			return $this->dataChanged('afterDelete');
		}

		function dataChanged($from){
			App::import('CakeSession');
			$this->Session = new CakeSession();

			Cache::delete(cacheName('cart', $this->Session->read('Auth.User.id')), 'shop');

			return true;
		}
	}