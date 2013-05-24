<?php
/**
 * InfinitasGatewayTest
 */
App::uses('InfinitasGateway', 'InfinitasPayments.Lib');
App::uses('PaymentSocket', 'InfinitasPayments.Network');

/**
 * DummyPayment
 */
class DummyPayment extends PaymentSocket {

	public function post($uri = null, $data = array(), $request = array()) {
		return (object)array(
			'uri' => $uri,
			'data' => $data,
			'request' => $request,
			'code' => 200,
			'body' => 'body'
		);
	}

	protected function _paymentSend($state, $data, $response = array()) {
		return array(
			'state' => $state,
			'data' => $data,
			'response' => $response
		);
	}
}

/**
 * InfinitasGatewayTest
 */
class InfinitasGatewayTest extends CakeTestCase {

	public $fixtures = array(
		'plugin.routes.route',
		'plugin.themes.theme'
	);

	public function setUp() {
		$this->Gateway = new InfinitasGateway('Something');
		parent::setUp();
	}

	public function tearDown() {
		unset($this->Gateway);
		parent::tearDown();
	}

/**
 * test types are done correctly
 */
	public function testAddTypes() {
		$expected = array(
			'shipping' => (float)1,
			'bar' => '2',
			'notify_url' => 'http://localhost/a/b',
			'items' => array(
				array(
					'name' => 'abc',
					'description' => null,
					'selling' => 0,
					'quantity' => 0,
					'tax' => 0,
					'subtotal' => 0,
					'tax_subtotal' => 0
				)
			)
		);
		$this->Gateway->shipping(1);
		$this->Gateway->bar(2);
		$this->Gateway->notifyUrl(array(
			'controller' => 'a',
			'action' => 'b'
		));
		$this->Gateway->item(array(
			'name' => 'abc'
		));

		$result = current($this->Gateway->orders());
		$this->assertIdentical($expected, $result);
	}

/**
 *test adding items to an order
 */
	public function testAddItems() {
		$expected = array(
			array(
				'name' => 'item 1',
				'description' => null,
				'selling' => 1,
				'quantity' => 1,
				'tax' => 0,
				'subtotal' => 1,
				'tax_subtotal' => 0
			),
			array(
				'name' => 'item 2',
				'description' => null,
				'selling' => 2.2,
				'quantity' => 5,
				'tax' => 3,
				'subtotal' => 11,
				'tax_subtotal' => 15
			)
		);
		$this->Gateway->item(array(
			'name' => 'item 1',
			'selling' => 1,
			'quantity' => 1,
			'tax' => 0
		))->item(array(
			'name' => 'item 2',
			'selling' => 2.2,
			'quantity' => 5,
			'tax' => 3
		));
		$result = current($this->Gateway->orders());
		$this->assertEquals($expected, $result['items']);
	}

/**
 * test adding user details
 */
	public function testAddUserDetails() {
		$expected = array(
			'user' => array(
				'id' => 123,
				'username' => 'bob',
				'email' => 'sam@foobar.com',
				'salutation' => null,
				'full_name' => null,
				'first_name' => null,
				'middle_name' => null,
				'last_name' => null,
				'suffix' => null,
				'phone' => null,
			),
			'address' => array(
				'address_1' => 'foo',
				'address_2' => 'bar',
				'city' => 'etc',
				'state' => null,
				'post_code' => null,
				'country_code' => null,
				'country' => null,
			)
		);
		$this->Gateway->user(array(
			'id' => 123,
			'username' => 'bob',
			'email' => 'sam@foobar.com'
		))->address(array(
			'address_1' => 'foo',
			'address_2' => 'bar',
			'city' => 'etc'
		));
		$result = end($this->Gateway->orders());
		$this->assertEquals($expected, $result);
	}

/**
 * test counting items in an order
 */
	public function testItemCount() {
		$this->assertEquals(0, $this->Gateway->itemCount());

		$this->Gateway->item(array(
			'name' => '123'
		));
		$this->assertEquals(1, $this->Gateway->itemCount());
	}

/**
 * test completing an order
 */
	public function testComplete() {
		$this->Gateway->item(array('name' => 123, 'selling' => 1, 'quantity' => 1))
			->complete()
			->item(array('name' => 321, 'selling' => 2, 'quantity' => 1));

		$this->assertCount(2, $this->Gateway->orders());
		$this->Gateway->complete();
		$this->assertCount(2, $this->Gateway->orders());

		$this->assertCount(2, $this->Gateway->complete()->complete()->complete()->orders());
	}

/**
 * test calculating the various totals is correct
 */
	public function testMath() {
		$expected = array(
			'total' => 86.3,
			'subtotal' => 64,
			'shipping' => 2.4,
			'handling' => 1.5,
			'insurance' => .9,
			'tax' => 17.5,
			'items' => array(
				array(
					'name' => 123,
					'description' => null,
					'selling' => 10.5,
					'quantity' => 3,
					'tax' => 2.5,
					'subtotal' => 31.5,
					'tax_subtotal' => 7.5
				),
				array(
					'name' => 321,
					'description' => null,
					'selling' => 3.25,
					'quantity' => 10,
					'tax' => 1,
					'subtotal' => 32.5,
					'tax_subtotal' => 10
				)
			),
			'user' => array(
				'id' => null,
				'salutation' => null,
				'email' => null,
				'username' => null,
				'full_name' => null,
				'first_name' => null,
				'middle_name' => null,
				'last_name' => null,
				'suffix' => null,
				'phone' => null,
			),
			'address' => array(
				'address_1' => null,
				'address_2' => null,
				'city' => null,
				'state' => null,
				'post_code' => null,
				'country_code' => null,
				'country' => null,
			)
		);

		$this->Gateway
			->item(array('name' => 123, 'selling' => 10.5, 'quantity' => 3, 'tax' => 2.5))
			->item(array('name' => 321, 'selling' => 3.25, 'quantity' => 10, 'tax' => 1))
			->shipping(2.4)
			->insurance(.9)
			->handling(1.5)
			->complete();
		$result = end($this->Gateway->orders());
		$this->assertEquals($expected, array_filter($result));
	}

/**
 * test the process method
 */
	public function testProcess() {
		$result = $this->Gateway
			->item(array('name' => 'foo', 'selling' => 123, 'quantity' => 1))
			->provider('DummyPayment', null, array(
				'live' => array(

				)
			))
			->process();

		//$result = $this->Gateway->orders();
		//$this->assertEquals(123, $result[0]['total']);
	}
}