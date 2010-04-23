<?php
	class Unit extends ShopAppModel{
		var $name = 'Unit';

		var $hasMany = array(
			'Product' => array(
				'className' => 'Shop.Product'
			)
		);
	}