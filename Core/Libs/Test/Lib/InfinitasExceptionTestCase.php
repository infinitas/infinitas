<?php
/**
 * @brief base class for testing exception data
 */

class InfinitasExceptionTestCase extends CakeTestCase {
/**
 * @brief test exception values
 *
 * This method is a wrapper to get data from an exception when testing. The method
 * will throw and catch the passed error and then run the selected function on it
 * to return the data.
 *
 * @param Exception $exception the exception to get data from
 * @param string $method valid method for the exception
 * @return string|array|integer
 */
	public function exceptionData(Exception $exception, $method = 'getMessage') {
		try {
			throw $exception;
		} catch(Exception $e) {
			return $e->{$method}();
		}
	}

}
