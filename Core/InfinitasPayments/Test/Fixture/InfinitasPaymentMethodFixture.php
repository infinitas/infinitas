<?php
/**
 * @brief fixture file for InfinitasPaymentMethod tests.
 *
 * @package .Fixture
 * @since 0.9b1
 */
class InfinitasPaymentMethodFixture extends CakeTestFixture {
	public $name = 'InfinitasPaymentMethod';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'provider' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'live' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'sandbox' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'testing' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4),
		'active' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4),
		'infinitas_payment_log_count' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 'paypal',
			'name' => 'paypal',
			'slug' => 'paypal',
			'provider' => 'paypal',
			'live' => '{"config":"foobar"}',
			'sandbox' => '{"config":"foobaz"}',
			'testing' => 1,
			'active' => 1,
			'infinitas_payment_log_count' => 1,
			'created' => '2013-01-10 14:32:15',
			'modified' => '2013-01-10 14:32:15'
		),
		array(
			'id' => 'cash',
			'name' => 'cash',
			'slug' => 'cash',
			'provider' => 'cash',
			'live' => '{"config":"cashbar"}',
			'sandbox' => '{"config":"cashbaz"}',
			'testing' => 0,
			'active' => 1,
			'infinitas_payment_log_count' => 0,
			'created' => '2013-01-10 14:32:15',
			'modified' => '2013-01-10 14:32:15'
		),
		array(
			'id' => 'inactive',
			'name' => 'inactive',
			'slug' => 'inactive',
			'provider' => 'inactive',
			'live' => '{"config":"inactive"}',
			'sandbox' => '{"config":"inactive"}',
			'testing' => 0,
			'active' => 0,
			'infinitas_payment_log_count' => 0,
			'created' => '2013-01-10 14:32:15',
			'modified' => '2013-01-10 14:32:15'
		),
	);
}