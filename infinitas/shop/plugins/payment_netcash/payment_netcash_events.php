<?php
	class PaymentNetcashEvents{
		function onLoadPaymentGateways(&$event){
			Configure::load('PaymentNetcash.config');
			return 'netcash';
		}
	}