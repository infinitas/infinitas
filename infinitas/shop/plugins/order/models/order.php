<?php
	class Order extends OrderAppModel{
		var $name = 'Order';

		var $belongsTo = array(
			'User' => array(
				'className' => 'Management.User',
				'fields' => array(
					'User.id',
					'User.username',
					'User.email'
				)
			),
			'Product' => array(
				'className' => 'Shop.Product',
				'fields' => array(
					'Product.id',
					'Product.name',
					'Product.slug',
					'Product.description',
					'Product.image_id',
					'Product.cost',
					'Product.retail',
					'Product.price',
					'Product.active',
					'Product.image_id',
				)
			),
			'Address' => array(
				'className' => 'Management.Address'
			),
			'Status' => array(
				'className' => 'Order.Status',
				'fields' => array(
					'Status.id',
					'Status.name',
					'Status.ordering'
				)
			)
		);
	}