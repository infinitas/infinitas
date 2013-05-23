<?php
App::uses('PaymentSocket', 'InfinitasPayments.Network');

class OfflineSocket extends PaymentSocket {

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
		return array(
			'ACK' => 'success',
			'TOKEN' => String::uuid(),
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'CORRELATIONID' => null
		);
	}

/**
 * Get the paid status of an order
 *
 * @param string $status
 *
 * @return array
 */
	protected function _paidStatus($status) {
		switch ($status) {
			case 'PaymentActionFailed':
				return self::$ERROR;

			case 'PaymentActionCompleted':
			case 'PaymentCompleted':
				return self::$PAID;

			default:
				return self::$PENDING;
		}
	}

/**
 * Prepare the order by sending the details to PayPal
 *
 * @param string $state the state of the call before / after
 * @param array $data
 *
 * @return array
 */
	protected function _paymentPrepare($state, array $request, array $response = array()) {
		if ($state == 'before') {
			return array();
		}
		$ShopList = ClassRegistry::init('Shop.ShopList');
		$shopList = $ShopList->find('details', $ShopList->field('id', array(
			$ShopList->alias . '.token' => $response['TOKEN']
		)));
		return array(
			'token' => $response['TOKEN'],
			'timestamp' => $response['TIMESTAMP'],
			'correlation' => $response['CORRELATIONID'],
			'redirect' => $this->_notifyUrl($shopList['ShopList']['user_id'], $response['TOKEN']),
			'raw' => array(
				'request' => json_encode($request),
				'response' => json_encode($response)
			)
		);
	}
	protected function _notifyUrl($userId, $token) {
		return InfinitasRouter::url(array(
			'action' => 'infinitas_payment_completed',
			'PayerID' => $userId,
			'token' => $token
		));
	}

	protected function _checkoutStatus($token) {
		$paymentLog = ClassRegistry::init('InfinitasPayments.InfinitasPaymentLog')->find('count', array(
			'conditions' => array(
				'InfinitasPaymentLog.transaction_id' => $token
			)	
		));

		if ($paymentLog) {
			return 'PaymentCompleted';
		}

		return null;
	}

/**
 * Get the details of a payment transaction
 *
 * before:
 *	Build up the details of the request to get the details of an order
 *
 * after:
 *	parse the return order details
 *
 * @param string $state before or after
 * @param array $data
 * @param array $response
 *
 * @return array
 */
	protected function _paymentStatus($state, array $request, array $response = array()) {
		if ($state == 'before') {
			return array();
		}
		$ShopList = ClassRegistry::init('Shop.ShopList');
		$shopList = $ShopList->find('details', $ShopList->field('id', array(
			$ShopList->alias . '.token' => $request['token']
		)));
		$shopListOverview = $ShopList->find('overview');

		$response['CHECKOUTSTATUS'] = $this->_checkoutStatus($request['token']);
		$return = array(
			'ack' => $response['ACK'],
			'token' => $request['token'],
			'checkout_status' => $response['CHECKOUTSTATUS'],
			'paid_status' => $this->_paidStatus($response['CHECKOUTSTATUS']),
			'timestamp' => $response['TIMESTAMP'],
			'correlation' => $response['CORRELATIONID'],
			'customer_id' => $shopList['ShopList']['user_id'],
			'customer_status' => null,
			'currency_code' => null, // @todo
			'shipping' => $shopList['ShopShipping']['shipping'],
			'handling' => $shopList['ShopShipping']['surcharge'],
			'insurance' => $shopList['ShopShipping']['insurance_rate'],
			'shipping_discount' => 0,
			'tax' => 0,
			'total' => $shopListOverview['value'] + $shopList['ShopShipping']['total'],
			'notify_url' => $this->_notifyUrl($shopList['ShopList']['user_id'], $response['TOKEN']),
			/*
			'user' => array(
				'full_name' => $response['SHIPTONAME'],
				'first_name' => $response['FIRSTNAME'],
				'last_name' => $response['LASTNAME'],
				'country_code' => $response['COUNTRYCODE'],
			),
			'arrdess' => array(
				'street' => $response['SHIPTOSTREET'],
				'city' => $response['SHIPTOCITY'],
				'state' => $response['SHIPTOSTATE'],
				'post_code' => $response['SHIPTOZIP'],
				'country_code' => $response['SHIPTOCOUNTRYCODE'],
				'country' => $response['SHIPTOCOUNTRYNAME'],
				'status' => $response['ADDRESSSTATUS'],
			),*/
			'raw' => array(
				'request' => serialize($request),
				'response' => serialize($response)
			),
			'orders' => array(
				array(
					'transaction_id' => $request['token'],
					'custom' => $shopList['ShopList']['id'],
					'token' => $request['token'],
					'infinitas_payment_log_id' => null
				)
			)
		);

		return $return;
	}

/**
 * Finalise the transaction
 *
 * @param string $state
 * @param array $request
 * @param array $response
 *
 * @return array
 */
	protected function _paymentFinalise($state, array $request, array $response = array()) {
		if ($state == 'before') {
			return array();
		}

		$return = self::_paymentStatus($state, $request, $response);
		$return['paid_status'] = self::$PAID;
		$return['transaction_id'] = String::uuid();
		$return['transaction_type'] = __CLASS__;
		$return['transaction_fee'] = 0;
		$return['order_time'] = date('Y-m-d H:i:s');
		$return['protection_eligibility'] = 0;

		$tmp = $return;
		unset($tmp['orders'], $tmp['raw']);
		foreach ($return['orders'] as $k => $order) {
			$return['orders'][$k] = array_merge($tmp, array_filter($order));
			$return['orders'][$k]['transaction_status'] = 1;
			$return['orders'][$k]['status'] = 1;
			$return['orders'][$k]['currency_code'] = ShopCurrencyLib::getCurrency();
		}

		return $return;
	}

}