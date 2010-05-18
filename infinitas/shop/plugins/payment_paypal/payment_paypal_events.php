<?php
	class PaymentPaypalEvents{
		function onLoadPaymentGateways(&$event){
			Configure::load('PaymentPaypal.config');
			return 'paypal';
		}
	}