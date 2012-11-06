<?php
App::uses('EmailSocket', 'Emails.Network');
class TestEmailSocket extends EmailSocket {
	public function logout() {}

	protected function _getStats() {}

	protected function _getList() {}

	protected function _getCapabilities() {}

	protected function _getMailboxes($ref = '', $wildcard = '*') {}

	public function noop() {}

	public function undoDeletes() {}
}

class EmailSocketTest extends CakeTestCase {
	public function setUp() {
		$this->Email = new TestEmailSocket();
		parent::setUp();
	}

	public function tearDown() {
		unset($this->Email);
		parent::tearDown();
	}

	public function testConstruct() {
		$this->assertTrue($this->Email->ParseMail instanceof ParseMail);
	}

/**
 * @brief test setting configs
 *
 * @dataProvider setDataProvider
 */
	public function testSet($data, $expected) {
		$this->Email->set($data);
		$this->assertEquals($expected, $this->Email->config);
	}

/**
 * @brief set data provider
 *
 * @return array
 */
	public function setDataProvider() {
		return array(
			'basic' => array(
				array(
					'host' => 'something.com',
					'port' => 123,
					'ssl' => false
				),
				array(
					'persistent' => false,
					'host' => 'something.com',
					'protocol' => 6,
					'port' => 123,
					'timeout' => 30,
					'ssl' => false
				)
			),
			'string-port' => array(
				array('port' => '123'),
				array(
					'persistent' => false,
					'host' => 'localhost',
					'protocol' => 6,
					'port' => 123,
					'timeout' => 30
				)
			),
			'ssl' => array(
				array('ssl' => true),
				array(
					'persistent' => false,
					'host' => 'localhost',
					'protocol' => 6,
					'port' => 80,
					'timeout' => 30,
					'ssl' => true,
					'request' => array('uri' => array(
						'scheme' => 'https'
					))
				)
			),
			'server' => array(
				array('server' => 'foo.com'),
				array(
					'persistent' => false,
					'host' => 'foo.com',
					'protocol' => 6,
					'port' => 80,
					'timeout' => 30
				)
			)
		);
	}

/**
 * @brief test set exceptions
 *
 * @dataProvider setExceptionsDataProvider
 * @expectedException EmailSocketException
 */
	public function testSetExceptions($data) {
		$this->Email->set($data);
	}

/**
 * @brief set exceptions data provider
 */
	public function setExceptionsDataProvider() {
		return array(
			'invalid-option' => array(
				array('foo' => 'bar')
			),
			'invalid-port' => array(
				array('port' => 'abc')
			),
			'empty-username' => array(
				array('username' => '')
			),
			'invalid-username' => array(
				array('username' => array(123))
			)
		);
	}

/**
 * @brief test is connected
 */
	public function testIsConnected() {
		$this->assertFalse($this->Email->isConnected());
	}

/**
 * @brief test loginException
 *
 * @expectedException EmailSocketCommunicationException
 */
	public function testLoginException() {
		$this->Email->set(array(
			'timeout' => 1
		));
		$this->Email->login();
	}

/**
 * @brief test errors
 */
	public function testErrors() {
		$expected = array();
		$result = $this->Email->errors();
		$this->assertEquals($expected, $result);

		$this->assertTrue($this->Email->error('foo'));
		$expected = array('foo');
		$result = $this->Email->errors();
		$this->assertEquals($expected, $result);

		$this->assertTrue($this->Email->error('bar'));
		$expected = array('foo', 'bar');
		$result = $this->Email->errors();
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test logs
 */
	public function testLogs() {
		$expected = array();
		$result = $this->Email->logs();
		$this->assertEquals($expected, $result);

		$this->assertTrue($this->Email->log('abcd', 125));
		$expected = array(array(
			'data' => 'ab',
			'size' => 125
		));
		$result = $this->Email->logs();
		$this->assertEquals($expected, $result);

		$this->assertTrue($this->Email->log('efgh', 321));
		$expected = array(array(
			'data' => 'ab',
			'size' => 125
		), array(
			'data' => 'ef',
			'size' => 321
		));
		$result = $this->Email->logs();
		$this->assertEquals($expected, $result);
	}

}