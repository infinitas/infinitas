<?php
/**
 * test email exceptions
 */
App::uses('InfinitasExceptionTestCase', 'Libs.Test/Lib');

class EmailsExceptionTest extends InfinitasExceptionTestCase {
/**
 * test exception message
 *
 * @dataProvider exceptionMessageDataProvider
 */
	public function testExceptionMessage($data, $expected) {
		$result = $this->exceptionData($data);
		$this->assertEquals($expected, $result);
	}

/**
 * exception message data provider
 *
 * @return array
 */
	public function exceptionMessageDataProvider() {
		return array(
			'config-option' => array(
				new EmailSocketConfigOptionException(array('abc')),
				'"abc" is not a valid config option'
			),
			'config-value' => array(
				new EmailSocketConfigValueException(array('foo', 'bar')),
				'"foo" is not a valid value for "bar"'
			),
			'error' => array(
				new EmailSocketCommunicationException(array('Some error')),
				'Error: Some error'
			)
		);
	}
	
}