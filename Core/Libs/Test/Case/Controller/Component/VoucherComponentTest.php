<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('VoucherComponent', 'Libs.Controller/Component');

/**
 * VoucherComponent Test Case
 *
 */
class VoucherComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Voucher = new VoucherComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Voucher);

		parent::tearDown();
	}

/**
 * testGetVoucher method
 *
 * @return void
 */
	public function testGetVoucher() {
	}

}
