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
			'Address' => array(
				'className' => 'Management.Address'
			),
			'Status' => array(
				'className' => 'Order.Status',
				'fields' => array(
					'Status.id',
					'Status.name',
					'Status.ordering'
				),
				'counterCache' => true
			)
		);

		var $hasMany = array(
			'Item' => array(
				'className' => 'Order.Item'
			)
		);
	}