<?php
	if(!isset($usersCart)){
		$usersCart = Cache::read('cart', 'shop');

		if(empty($usersCart)){
			$usersCart = ClassRegistry::init('Shop.Cart')->getCartData();
		}
	}

	pr($usersCart);