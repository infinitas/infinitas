<?php
App::uses('InfinitasPaymentMethod', 'InfinitasPayments.Model');

/**
 * InfinitasPaymentMethod Test Case
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
		'plugin.infinitas_payments.infinitas_payment_log'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->InfinitasPaymentMethod = ClassRegistry::init('InfinitasPayments.InfinitasPaymentMethod');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InfinitasPaymentMethod);

		parent::tearDown();
	}

/**
 * testGetViewData method
 *
 * @return void
 */
	public function testGetViewData() {
	}

}
