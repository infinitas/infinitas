<?php
App::uses('PayPalSocket', 'InfinitasPayments.Lib/Providers/PayPal');

class PayPalDirect extends PayPalSocket {

	protected function _paymentDirect($state, array $data) {
		if ($state == 'before') {
			return array_merge($this->_expressDefaults(), array(
				'METHOD' => 'DoDirectPayment',
				'PAYMENTACTION' => 'SALE',

				'AMT' => $data['total'],
				'CURRENCYCODE' => $data['currency_code'],
				'IPADDRESS' => $data['ip_address'],

				// Credit Card Details
				'CREDITCARDTYPE' => $data['cc']['type'],
				'ACCT' => $data['cc']['number'],
				'EXPDATE' => $data['cc']['expires'],
				'CVV2' => $data['cc']['cvv2'],

				// Customer Details
				'SALUTATION' => $data['user']['salutation'],
				'FIRSTNAME' => $data['user']['first_name'],
				'MIDDLENAME' => $data['user']['middle_name'],
				'LASTNAME' => $data['user']['last_name'],
				'SUFFIX' => $data['user']['suffix'],

				// Billing Address
				'STREET' => $data['address']['address_1'],
				'STREET2' => $data['address']['address_2'],
				'CITY' => $data['address']['city'],
				'STATE' => $data['address']['state'],
				'COUNTRYCODE' => $data['address']['country_code'],
				'ZIP' => $data['address']['post_code'],
			));
		}
	}
}
