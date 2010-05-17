<?php
	class Order extends OrderAppModel{
		var $name = 'Order';

		var $virtualFields = array(
			'grand_total' => 'Order.total + Order.shipping'
		);

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

		function getPendingOrders($user_id = null){
			if(!$user_id){
				return array();
			}

			return $this->find(
				'all',
				array(
					'conditions' => array(
						'Order.status_id' => $this->Status->getFirst(),
						'Order.user_id' => $user_id
					),
					'contain' => array(
						'User',
						'Item',
						'Address',
						'Status'
					)
				)
			);
		}
	}