<?php
App::uses('PayPalSocket', 'InfinitasPayments.Lib/Providers/PayPal');

class PayPalExpress extends PayPalSocket {

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
			$allowNotes = !empty($request['notes']) ? false : true;
			if (array_key_exists('allow_notes', $request) && $request['allow_notes'] == false) {
				$allowNotes = false;
			}

			$return = array_merge($this->_requestDefaults(), array(
				'METHOD' => 'SetExpressCheckout',

				'RETURNURL' => $request['return_url'],
				'CANCELURL' => $request['cancel_url'],

				'REQCONFIRMSHIPPING' => (int)(!empty($request['confirmed_shipping']) ? $request['confirmed_shipping'] : false),
				'NOSHIPPING' => 2,
				'ALLOWNOTE' => $allowNotes,
				'CHANNELTYPE' => 'Merchant',
				'BRANDNAME' => Configure::read('Website.name'),
			));

			foreach($request['orders'] as $k => $order) {
				$return = array_merge($return, array_filter(Hash::flatten(array(
					'PAYMENTREQUEST' => array(
						$k => array(
							'PAYMENTACTION' => 'Sale',
							'SELLERPAYPALACCOUNTID' => $this->getConfig(null, 'e'),
							'CURRENCYCODE' => $order['currency_code'],
							'AMT' => (float)$order['total'],
							'ITEMAMT' => $order['subtotal'],
							'CUSTOM' => $order['custom'],

							'SHIPPINGAMT' => $order['shipping'],
							'HANDLINGAMT' => $order['handling'],
							'INSURANCEAMT' => $order['insurance'],
							'SHIPDISCAMT' => null,
							'TAXAMT' => $order['tax'],

							'INSURANCEOPTIONOFFERED' => '0',
							'INVNUM' => $order['invoice_number'],
							'NOTIFYURL' => $order['notify_url']
						)
					)
				), '_')));

				$this->_pageStyle($order, $return, $k);
				$this->_items((array)$order['items'], $return, $k);
				$this->_user($order, $return, $k);
			}

			return $return;
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
			return array_merge($this->_requestDefaults(), array(
				'METHOD' => 'GetExpressCheckoutDetails',
				'TOKEN' => $request['token'],
			));
		}

		$response = Hash::expand($response, '_');
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
			'orders' => array(),
			'raw' => array(
				'request' => serialize($request),
				'response' => serialize($response)
			)
		);

		foreach ($response['PAYMENTREQUEST'] as $paymentRequest) {
			$subTotal = !empty($paymentRequest['ITEMAMT']) ? $paymentRequest['ITEMAMT'] : null;
			if (!$subTotal) {
				$subTotal = $paymentRequest['AMT'] - array_sum(array(
					$paymentRequest['SHIPPINGAMT'],
					$paymentRequest['HANDLINGAMT'],
					$paymentRequest['TAXAMT']
				));
			}
			$return['orders'][] = array(
				'currency_code' => $paymentRequest['CURRENCYCODE'],
				'total' => $paymentRequest['AMT'],
				'sub_total' => $subTotal,
				'shipping' => $paymentRequest['SHIPPINGAMT'],
				'handling' => $paymentRequest['HANDLINGAMT'],
				'tax' => $paymentRequest['TAXAMT'],
				'notify_url' => $paymentRequest['NOTIFYURL'],
				'insurance' => $paymentRequest['INSURANCEAMT'],
				'shipping_discount_amount' => $paymentRequest['SHIPDISCAMT'],
				'insurance_option_offered' => $paymentRequest['INSURANCEOPTIONOFFERED'],
				'transaction_id' => $paymentRequest['TRANSACTIONID'],
				'custom' => $paymentRequest['CUSTOM'],
				'arrdess' => array(
					'full_name' => $paymentRequest['SHIPTONAME'],
					'street' => $paymentRequest['SHIPTOSTREET'],
					'city' => $paymentRequest['SHIPTOCITY'],
					'state' => $paymentRequest['SHIPTOSTATE'],
					'post_code' => $paymentRequest['SHIPTOZIP'],
					'country_code' => $paymentRequest['SHIPTOCOUNTRYCODE'],
					'country' => $paymentRequest['SHIPTOCOUNTRYNAME'],
					'status' => $paymentRequest['ADDRESSSTATUS'],
				)
			);
		}

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
			return array_merge($this->_requestDefaults(), array(
				'METHOD' => 'DoExpressCheckoutPayment',
				'TOKEN' => $request['token'],
				'PAYERID' => $request['customer_id'],
				'PAYMENTACTION' => 'Sale',
				'CURRENCYCODE' => $request['currency_code'],
				'AMT'=> $request['total']
			));
		}
		$response = Hash::expand($response, '_');
		$return = array(
			'status' => $response['ACK'],
			'token' => $response['TOKEN'],
			'timestamp' => $response['TIMESTAMP'],
			'correlation' => $response['CORRELATIONID'],
			'transaction_id' => $response['TRANSACTIONID'],
			'transaction_type' => $response['TRANSACTIONTYPE'],
			'transaction_status' => $response['PAYMENTSTATUS'],
			'transaction_fee' => $response['FEEAMT'],
			'payment_type' => $response['PAYMENTTYPE'],
			'order_time' => $response['ORDERTIME'],
			'total' => $response['AMT'],
			'tax' => $response['TAXAMT'],
			'currency_code' => $response['CURRENCYCODE'],
			'pending_reason' => $response['PENDINGREASON'],
			'reason_code' => $response['REASONCODE'],
			'protection_eligibility' => $response['PROTECTIONELIGIBILITY'],
			'insurance_option_selected' => strtolower($response['INSURANCEOPTIONSELECTED']) == 'true' ? true : false,
			'shipping_option_default' => strtolower($response['SHIPPINGOPTIONISDEFAULT']) == 'true' ? true : false,
			'orders' => array(),
			'raw' => array(
				'request' => serialize($request),
				'response' => serialize($response),
			)
		);

		foreach ($response['PAYMENTINFO'] as $paymentInfo) {
			$return['orders'][] = array(
				'transaction_id' => $paymentInfo['TRANSACTIONID'],
				'transaction_type' => $paymentInfo['TRANSACTIONTYPE'],
				'transaction_status' => $paymentInfo['PAYMENTSTATUS'],
				'transaction_fee' => $paymentInfo['FEEAMT'],
				'payment_type' => $paymentInfo['PAYMENTTYPE'],
				'order_time' => $paymentInfo['ORDERTIME'],
				'total' => $paymentInfo['AMT'],
				'tax' => $paymentInfo['TAXAMT'],
				'currency_code' => $paymentInfo['CURRENCYCODE'],
				'pending_reason' => $paymentInfo['PENDINGREASON'],
				'reason_code' => $paymentInfo['REASONCODE'],
				'protection_eligibility' => $paymentInfo['PROTECTIONELIGIBILITY'],
				'error_code' => $paymentInfo['ERRORCODE'],
				'status' => $paymentInfo['ACK']
			);
		}

		return $return;
	}

/**
 * Parse the users details
 *
 * uptp 10 orders can be placed at once,
 *
 * @param array $request the raw data
 * @param array $return the data to be returned
 * @param integer $orderNumber the order number
 *
 * @return void
 */
	protected function _user(array $request, array &$return, $orderNumber = 0) {
		$return = array_merge(array_filter(array(
			'EMAIL' => $request['user']['email']
		)), $return);

		$return = array_merge($return, array_filter(Hash::flatten(array(
			'PAYMENTREQUEST' => array(
				$orderNumber => array(
					'SHIPTONAME' => $request['user']['full_name'],
					'SHIPTOSTREET' => $request['address']['address_1'],
					'SHIPTOSTREET2' => $request['address']['address_2'],
					'SHIPTOCITY' => $request['address']['city'],
					'SHIPTOSTATE' => $request['address']['state'],
					'SHIPTOZIP' => $request['address']['post_code'],
					'SHIPTOCOUNTRYCODE' => $request['address']['country_code'],
					'SHIPTOPHONENUM' => $request['user']['phone']
				)
			)
		), '_')));
	}

/**
 * Configure the page styles from the config
 *
 * @param array $request
 * @param array $return
 *
 * @return void
 */
	protected function _pageStyle(array $request, &$return) {
		$styles = array_merge(array(
			'page_style' => 'Copify',
			'header' => array(
				'image' => null,
				'border' => null,
				'background' => null
			),
			'page' => array(
				'background' => null
			)
		), (array)Configure::read('InfinitasPayments.PayPal.styles'));

		$return = array_merge(array_filter(array(
			'PAGESTYLE' => $styles['page_style'],
			'HDRIMG' => $styles['header']['image'],
			'HDRBORDERCOLOR' => $styles['header']['border'],
			'HDRBACKCOLOR' => $styles['header']['background'],
			'PAYFLOWCOLOR' => $styles['page']['background'],
		)), $return);
	}

	protected function _items(array $items, &$return, $orderNumber = 0) {
		foreach ($items as $k => $item) {
			$return['L_PAYMENTREQUEST_' . $orderNumber . '_NAME' . $k] = $item['name'];
			$return['L_PAYMENTREQUEST_' . $orderNumber . '_AMT' . $k] = (float)$item['selling'];
			$return['L_PAYMENTREQUEST_' . $orderNumber . '_QTY' . $k] = $item['quantity'];

			if (array_key_exists('description', $item) && $item['description']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_DESC' . $k] = $item['description'];
			}
			if (array_key_exists('tax', $item) && $item['tax']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_TAXAMT' . $k] = $item['tax'];
			}
			if (array_key_exists('url', $item) && $item['url']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMURL' . $k] = $item['url'];
			}
			if (array_key_exists('weight', $item) && $item['weight']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMWEIGHTVALUE' . $k] = $item['weight'];
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMWEIGHTUNIT' . $k] = 'g';
			}
			if (array_key_exists('length', $item) && $item['length']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMLENGTHVALUE' . $k] = $item['length'];
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMLENGTHUNIT' . $k] = 'mm';
			}
			if (array_key_exists('width', $item) && $item['width']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMWIDTHVALUE' . $k] = $item['width'];
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMWIDTHUNIT' . $k] = 'mm';
			}
			if (array_key_exists('height', $item) && $item['height']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMHEIGHTVALUE' . $k] = $item['height'];
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMHEIGHTUNIT' . $k] = 'mm';
			}

			$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMCATEGORY' . $k] = 'Physical';
			if (array_key_exists('digital', $item) && $item['digital']) {
				$return['L_PAYMENTREQUEST_' . $orderNumber . '_ITEMCATEGORY' . $k] = 'Digital';
			}
		}
	}
}
