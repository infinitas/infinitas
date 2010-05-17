<?php
	class PaymentNetcashEvents{
		function onShopLoad(&$event){
			Configure::load('PaymentNetcash.config');
			Configure::write('Shop.payment_methods', array_merge((array)Configure::read('Shop.payment_methods'), array('netcash')));
		}
	}