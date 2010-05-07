<?php
	if(!isset($usersWishlist)){
		$usersWishlist = Cache::read('cart', 'shop');

		if(empty($usersWishlist)){
			$usersWishlist = ClassRegistry::init('Shop.Cart')->getCartData();
		}
	}

	pr($usersWishlist);