<?php
	class PaymentEftEvents{
		function onLoadPaymentGateways(&$event){
			Configure::load('PaymentEft.config');
			return 'eft';
		}
	}