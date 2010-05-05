<?php
	class Stock extends ShopAppModel{
		var $name = 'Stock';

		var $belongsTo = array(
			'ShopBranch' => array(
				'className' => 'Shop.ShopBranch',
				'foreignKey' => 'branch_id',
				'fields' => array(
					'ShopBranch.id',
					'ShopBranch.branch_id',
				)
			),
			'Product' => array(
				'className' => 'Shop.Product',
				'foreignKey' => 'product_id',
				'fields' => array(
					'Product.id',
					'Product.name',
				)
			)
		);
	}