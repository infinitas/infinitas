<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('CsvHelper', 'Data.View/Helper');

/**
 * CsvHelper Test Case
 *
 */
class CsvHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Csv = new CsvHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Csv);

		parent::tearDown();
	}

}
