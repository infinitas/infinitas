<?php
App::uses('InfinitasGateway', 'InfinitasPayments.Lib');

/**
 *
 */
class InfinitasPaymentsComponent extends InfinitasComponent {

	protected function _queryData() {
		$this->Controller->request->query = array_merge(array(
			'token' => null,
			'PayerID' => null
		), array_filter($this->Controller->request->params['named']), $this->Controller->request->query);

		return array(
			'token' => $this->Controller->request->query['token'],
			'customer_id' => $this->Controller->request->query['PayerID']
		);
	}

	public function actionInfinitasPaymentCompleted() {
		$data = $this->_queryData();

		$paymentDetails = $this->_gateway()->status($data);

		$result = array();
		$oldPaymentStatus = $paymentDetails['paid_status'];
		if ($paymentDetails['paid_status'] != InfinitasGateway::$PAID) {
			$data = array_merge($data, array(
				'currency_code' => $paymentDetails['currency_code'],
				'total' => $paymentDetails['total']
			));
			$result = $this->_gateway()->finalise($data);
			$paymentDetails = $this->_gateway()->status($data);
		}

		$orders = $paymentDetails['orders'];
		unset($paymentDetails['orders']);
		foreach ($orders as $order) {
			$results = $this->Controller->Event->trigger('paymentCompleted', array(
				'details' => $paymentDetails,
				'order' => $order
			));
		}
	}

	public function actionInfinitasPaymentCanceled() {
		$this->Controller->Event->trigger('paymentCanceled', $this->Controller->request->query);
	}

	protected function _gateway() {
		if (!empty($this->_Gateway)) {
			return $this->_Gateway;
		}

		$this->_Gateway = new InfinitasGateway();
		$this->_Gateway->provider('cash-payments');
		return $this->_Gateway;
	}
}