<?php
class InfinitasPaymentLog extends InfinitasPaymentsAppModel {

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'transactions' => true
	);

	public $belongsTo = array(
		'InfinitasPaymentMethod' => array(
			'className' => 'InfinitasPayments.InfinitasPaymentMethod',
			'foreignKey' => 'infinitas_payment_method_id',
			'conditions' => array(),
			'counterCache' => true
		)
	);

/**
 * Save the details of a transaction
 *
 * Return a list of ids that were saved
 *
 * @param array $transaction the transaction details
 *
 * @return array
 */
	public function saveTransactionDetails(array &$transaction) {
		$data = array(
			'token' => $transaction['token'],
			'transaction_id' => $transaction['transaction_id'],
			'transaction_type' => $transaction['transaction_type'],
			'transaction_fee' => $transaction['transaction_fee'],
			'transaction_date' => date('Y-m-d H:i:s', strtotime($transaction['order_time'])),
			'protection' => $transaction['protection_eligibility'],
			'total' => $transaction['total'],
			'tax' => $transaction['tax'],
			'raw_request' => $transaction['raw']['request'],
			'raw_response' => $transaction['raw']['response'],
		);

		$ids = array();
		foreach ($transaction['orders'] as &$order) {
			$this->create();
			$saved = $this->save(array_merge($data, array(
				'transaction_id' => $order['transaction_id'],
				'transaction_type' => $order['transaction_type'],
				'transaction_status' => $order['transaction_status'],
				'transaction_fee' => $order['transaction_fee'],
				'transaction_date' => date('Y-m-d H:i:s', strtotime($order['order_time'])),
				'protection' => $order['protection_eligibility'],
				'status' => $order['status'],
				'currency_code' => $order['currency_code'],
				'total' => $order['total'],
				'tax' => $order['tax'],
			)));
			$order['infinitas_payment_log_id'] = null;
			if ($saved) {
				$ids[] = $order['infinitas_payment_log_id'] = $this->id;
			}
		}

		return $ids;
	}

	public function getTransactionDetails(array &$transaction) {
		$transactionIds = $this->find('transactions', array(
			'transaction_id' => Hash::extract($transaction['orders'], '{n}.transaction_id')
		));
		$transactionIds = array_flip($transactionIds);
		foreach ($transaction['orders'] as &$order) {
			$order['infinitas_payment_log_id'] = $transactionIds[$order['transaction_id']];
		}
	}

/**
 * Look up a transaction by the providers transaction id
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return string|null
 *
 * @throws InvalidArgumentException
 */
	protected function _findTransactions($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query['transaction_id'])) {
				throw new InvalidArgumentException('Gateway transaction id or token required');
			}
			$query['fields'] = array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.transaction_id'
			);

			$query['conditions'] = array_merge((array)$query['conditions'], array(
				'or' => array(
					$this->alias . '.' . $this->primaryKey => $query['transaction_id'],
					$this->alias . '.token' => $query['transaction_id'],
					$this->alias . '.transaction_id' => $query['transaction_id']
				)
			));

			return $query;
		}

		return Hash::combine($results,
			'{n}.' . $this->alias . '.' . $this->primaryKey,
			'{n}.' . $this->alias . '.transaction_id'
		);
	}
}
