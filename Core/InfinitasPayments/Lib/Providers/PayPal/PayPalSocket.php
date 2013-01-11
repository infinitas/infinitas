<?php
App::uses('PaymentSocket', 'InfinitasPayments.Network');

/**
 * @link PayPal developer https://developer.paypal.com/cgi-bin/devscr
 */
class PayPalSocket extends PaymentSocket {

/**
 * API version
 *
 * @var string
 */
	protected $_apiVersion = '63.0';

/**
 * Config options
 *
 * @var array
 */
	protected $_config = array(
		'live' => array(
			'site' => 'https://www.paypal.com/webscr/?cmd=_express-checkout&useraction=commit&token=:token',
			'api' => 'https://api-3t.paypal.com/nvp/',
		),
		'sandbox' => array(
			'site' => 'https://www.sandbox.paypal.com/webscr/?cmd=_express-checkout&useraction=commit&token=:token',
			'api' => 'https://api-3t.sandbox.paypal.com/nvp/',
		)
	);

	protected function _requestDefaults() {
		$config = $this->getConfig();
		return array(
			'VERSION' => $this->_apiVersion,
			'USER' => $config['email'],
			'PWD' => $config['password'],
			'SIGNATURE' => $config['signature']
		);
	}

/**
 * format the returned request data
 *
 * Run the normal request and then format the response into an array
 *
 * @param array $options the options for the request
 *
 * @return array
 */
	protected function _request(array $options) {
		$response = parent::_request($options);
		$parsed = array();
		parse_str($response , $parsed);

		$parsed = array_merge(array(
			'ACK' => null,
		), $parsed);

		if (strtolower($parsed['ACK']) == 'success') {
			return $parsed;
		}
		$errors = $this->_parseErrors($parsed);

		if (!empty($errors)) {
			throw new PaymentInvalidResponseException($errors);
		}

		throw new PaymentInvalidResponseException(__d('infinitas_payments',
			'There is a problem with the payment gateway. Please try again later.'
		));
	}

/**
 * Parse any errors from the returned data
 *
 * @param array $parsed the data from PayPal
 *
 * @return array
 */
	protected function _parseErrors(array $parsed) {
		$parsed = array_merge(array('L' => array()), Hash::expand($parsed, '_'));
		foreach ($parsed['L'] as $k => $v) {
			unset($parsed['L'][$k]);

			$matches = array();
			preg_match('/[0-9]/', $k, $matches);
			$count = current($matches);
			$parsed['L'][$count][str_replace($count, '', $k)] = $v;
		}
		return array_filter($parsed['L']);
	}
}