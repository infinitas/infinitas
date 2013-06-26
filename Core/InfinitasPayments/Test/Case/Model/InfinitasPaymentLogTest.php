<?php
App::uses('InfinitasPaymentLog', 'InfinitasPayments.Model');

/**
 * InfinitasPaymentLog Test Case
 *
 */
class InfinitasPaymentLogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.infinitas_payments.infinitas_payment_log',
		'plugin.infinitas_payments.infinitas_payment_method'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('InfinitasPayments.InfinitasPaymentLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Model);

		parent::tearDown();
	}

/**
 * testSaveTransactionDetails method
 */
	public function testSaveTransactionDetails() {
		$data = $this->_data();
		$ids = $this->Model->saveTransactionDetails($data);
		$this->assertCount(2, $ids);
		$this->assertEquals($ids, array_unique($ids));

		foreach ($ids as $k => $id) {
			$this->assertEquals($id, $data['orders'][$k]['infinitas_payment_log_id']);
		}
	}

/**
 * test get transaction details
 */
	public function testGetTransactionDetails() {
		$clean = $data = $this->_data();
		$ids = $this->Model->getTransactionDetails($data);

		$this->assertEmpty($ids);
		$this->assertEquals($clean, $data);
		
		$this->assertTrue((bool)$this->Model->saveTransactionDetails($data));
		$this->Model->getTransactionDetails($clean);

		$this->assertEquals($data, $clean);

	}

	protected function _data() {
		return array(
			'token' => 'token',
			'transaction_id' => 'tx_id',
			'transaction_type' => 'tx_type',
			'transaction_fee' => 123,
			'order_time' => date('Y-m-d H:i:s'),
			'protection_eligibility' => 0,
			'total' => 456,
			'tax' => 789,
			'raw' => array(
				'request' => '{"request":"request"}',
				'response' => '{"response":"response"}'
			),
			'orders' => array(
				array(
					'token' => 'token',
					'transaction_id' => 'tx_id',
					'transaction_type' => 'tx_type',
					'transaction_fee' => 123,
					'transaction_status' => 'asd',
					'status' => 'asd',
					'currency_code' => 'GBP',
					'order_time' => date('Y-m-d H:i:s'),
					'protection_eligibility' => 0,
					'total' => 456,
					'tax' => 789,
					'raw' => array(
						'request' => '{"request":"request"}',
						'response' => '{"response":"response"}'
					)
				),
				array(
					'token' => 'token1',
					'transaction_id' => 'tx_id1',
					'transaction_type' => 'tx_type1',
					'transaction_fee' => 123,
					'transaction_status' => 'asd1',
					'status' => 'asd',
					'currency_code' => 'GBP',
					'order_time' => date('Y-m-d H:i:s'),
					'protection_eligibility' => 0,
					'total' => 456,
					'tax' => 789,
					'raw' => array(
						'request' => '{"request":"request"}',
						'response' => '{"response":"response"}'
					)
				)
			)
		);
	}

/**
 * test find transactions exception
 *
 * @expectedException InvalidArgumentException
 */
	public function testFindTransactionsException() {
		$this->Model->find('transactions');
	}

/**
 * testGetTransactionDetails method
 *
 * @dataProvider findTransactionDataProvider
 */
	public function testFindTransactions($data, $expected) {
		$result = $this->Model->find('transactions', array(
			'transaction_id' => $data
		));
		$this->assertEquals($expected, $result);
	}

	public function findTransactionDataProvider() {
		return array(
			array(
				'transaction xyz',
				array(
					'payment-log-1' => 'transaction xyz'
				)
			)
		);
	}

}
