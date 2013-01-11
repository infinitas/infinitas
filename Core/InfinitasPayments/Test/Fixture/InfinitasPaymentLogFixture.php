<?php
/**
 * @brief fixture file for InfinitasPaymentLog tests.
 *
 * @package .Fixture
 * @since 0.9b1
 */
class InfinitasPaymentLogFixture extends CakeTestFixture {
	public $name = 'InfinitasPaymentLog';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'token' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'transaction_id' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'transaction_type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'transaction_fee' => array('type' => 'float', 'null' => false, 'default' => '0.000000', 'length' => '15,6'),
		'raw_request' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'raw_response' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'transaction_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'currency_code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 3, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'total' => array('type' => 'float', 'null' => false, 'default' => '0.000000', 'length' => '15,6'),
		'tax' => array('type' => 'float', 'null' => false, 'default' => '0.000000', 'length' => '15,6'),
		'custom' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 'payment-log-1',
			'token' => 'foo-bar',
			'transaction_id' => 'transaction xyz',
			'transaction_type' => 'payment',
			'transaction_fee' => 3.50,
			'raw_request' => '{"json":"request"}',
			'raw_response' => '{"json":"response"}',
			'transaction_date' => '2013-01-09 00:11:13',
			'currency_code' => 'GBP',
			'total' => 25.00,
			'tax' => 2.50,
			'custom' => 'custom-var',
			'status' => 'Complete',
			'created' => '2013-01-09 00:11:13',
			'modified' => '2013-01-09 00:11:13'
		),
	);
}