<?php
App::uses('PaymentSocket', 'InfinitasPayments.Network');

class OfflineSocket extends PaymentSocket {

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
			return true;
		}

		return array(
			'token' => $response['TOKEN'],
			'timestamp' => $response['TIMESTAMP'],
			'correlation' => $response['CORRELATIONID'],
			'redirect' => String::insert($this->getConfig(null, 'site'), array(
				'token' => $response['TOKEN']
			)),
			'raw' => array(
				'request' => json_encode($request),
				'response' => json_encode($response)
			)
		);
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
			return true;
		}

		$return = array(
			'ack' => $response['ACK'],
			'token' => $response['TOKEN'],
			'checkout_status' => $response['CHECKOUTSTATUS'],
			'paid_status' => $this->_paidStatus($response['CHECKOUTSTATUS']),
			'timestamp' => $response['TIMESTAMP'],
			'correlation' => $response['CORRELATIONID'],
			'customer_id' => $response['PAYERID'],
			'customer_status' => $response['PAYERSTATUS'],
			'currency_code' => $response['CURRENCYCODE'],
			'shipping' => $response['SHIPPINGAMT'],
			'handling' => $response['HANDLINGAMT'],
			'insurance' => $response['INSURANCEAMT'],
			'shipping_discount' => $response['SHIPDISCAMT'],
			'tax' => $response['TAXAMT'],
			'total' => $response['AMT'],
			'notify_url' => $response['NOTIFYURL'],
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
			),
			'raw' => array(
				'request' => serialize($request),
				'response' => serialize($response)
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
			return true;
		}

		$uuid = String::uuid();
		$return = array(
			'status' => 1,
			'token' => $uuid,
			'timestamp' => time(),
			'correlation' => null,
			'transaction_id' => $uuid,
			'transaction_type' => 'payment',
			'transaction_status' => 1,
			'transaction_fee' => 0,
			'payment_type' => 1,
			'order_time' => time(),
			'total' => $response['AMT'],
			'tax' => $response['TAXAMT'],
			'currency_code' => $response['CURRENCYCODE'],
			'pending_reason' => $response['PENDINGREASON'],
			'reason_code' => null,
			'protection_eligibility' => false,
			'insurance_option_selected' => strtolower($response['INSURANCEOPTIONSELECTED']) == 'true' ? true : false,
			'shipping_option_default' => strtolower($response['SHIPPINGOPTIONISDEFAULT']) == 'true' ? true : false,
			'raw' => array(
				'request' => null,
				'response' => null,
			)
		);

		return $return;
	}

}