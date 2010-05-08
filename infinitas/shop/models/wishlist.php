<?php
	class Wishlist extends ShopAppModel{
		var $name = 'Wishlist';

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
			'sub_total' => 'Wishlist.quantity * Wishlist.price'
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

		function getWishlistData($user_id = null){
			if((int)$user_id > 0){
				$cacheName = cacheName('wishlist', $user_id);
				$wishlistData = Cache::read($cacheName, 'shop');
				if(!empty($wishlistData)){
					return $wishlistData;
				}

				$wishlistData = $this->find(
					'all',
					array(
						'conditions' => array(
							'Wishlist.user_id' => $user_id
						),
						'contain' => array(
							'User',
							'Product'
						)
					)
				);

				Cache::write($cacheName, $wishlistData, 'shop');

				return $wishlistData;
			}

			App::import('CakeSession');
			$this->Session = new CakeSession();

			$wishlistData = $this->Session->read('Wishlist.TempWishlist');

			return (array)$wishlistData;
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

			Cache::delete(cacheName('wishlist', $this->Session->read('Auth.User.id')), 'shop');

			return true;
		}
	}