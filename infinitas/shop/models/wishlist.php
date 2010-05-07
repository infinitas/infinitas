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
	}