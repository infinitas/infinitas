<?php
App::uses('InfinitasPaymentMethod', 'InfinitasPayments.Model');

/**
 * ShopPaymentMethod Test Case
 *
 */
class InfinitasPaymentMethodTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.infinitas_payments.infinitas_payment_method',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('InfinitasPayments.InfinitasPaymentMethod');
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
 * test find config exception
 *
 * @expectedException InvalidArgumentException
 */
	public function testFindConfigEmptyException() {
		$this->Model->find('config');
	}

/**
 * test find config exception
 *
 * @expectedException CakeException
 */
	public function testFindConfigWrongException() {
		$this->Model->find('config', 'fake-payment-config');
	}

/**
 * test find config exception
 *
 * @expectedException CakeException
 */
	public function testFindConfigInactiveException() {
		$this->Model->find('config', 'inactive');
	}

/**
 * test find config
 *
 * @dataProvider findConfigDataProvider
 */
	public function testFindConfig($data, $expected) {
		$result = $this->Model->find('config', $data);
		$this->assertEquals($expected, $result);
	}

/**
 * find config data provider
 */
	public function findConfigDataProvider() {
		return array(
			'paypal' => array(
				'paypal',
				array(
					'name' => 'paypal',
					'slug' => 'paypal',
					'provider' => 'paypal',
					'live' => array('config' => 'foobar'),
					'sandbox' => array('config' => 'foobaz'),
					'testing' => 1,
				)
			),
			'cash' => array(
				'cash',
				array(
					'name' => 'cash',
					'slug' => 'cash',
					'provider' => 'cash',
					'live' => array('config' => 'cashbar'),
					'sandbox' => array('config' => 'cashbaz'),
					'testing' => 0,
				)
			)
		);
	}
}