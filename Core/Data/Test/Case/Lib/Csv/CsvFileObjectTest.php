<?php
App::uses('CsvFileObject', 'Data.Lib/Csv');

class CsvFileObjectTest extends CakeTestCase {
/**
 * @brief start up method
 */
	public function setUp() {
		$this->path = InfinitasPlugin::path('Data') . 'Test' . DS . 'Fixtures' . DS;
		parent::setUp();
	}

/**
 * @brief tear down method
 */
	public function tearDown() {
		parent::tearDown();
	}

/**
 * @brief test headings
 */
	public function testHeading() {
		$Csv = new CsvFileObject($this->path . 'file1.csv');

		$this->assertTrue($Csv->hasHeadings());
		$expected = array(
			array(
				'field_1' => '1a',
				'field_2' => '2a///\\\\\\',
				'field_3' => '3a'
			),
			array(
				'field_1' => '1b\'\'\'',
				'field_2' => '2b”””',
				'field_3' => '3b'
			),
			array(
				'field_1' => '1c',
				'field_2' => '2c,,,',
				'field_3' => '3c'
			)
		);
		$headings = array(
			'field_1',
			'field_2',
			'field_3'
		);

		for($i = 0; $i < 3; $i++) {
			$result = $Csv->read();
			$this->assertEquals($expected[$i], $result);

			$result = $Csv->headings();
			$this->assertEquals($headings, $result);
		}

		foreach($expected as &$v) {
			$v = array_values($v);
		}
		array_unshift($expected, array(
			'Field_1',
			'Field 2',
			'FIELD-3'
		));
		$headings = array();
		$Csv = new CsvFileObject($this->path . 'file1.csv', array(
			'heading' => false
		));

		$this->assertFalse($Csv->hasHeadings());

		for($i = 0; $i < 3; $i++) {
			$result = $Csv->read();
			$this->assertEquals($expected[$i], $result);

			$result = $Csv->headings();
			$this->assertEquals($headings, $result);
		}
	}

/**
 * @brief test rewind
 */
	public function testRewind() {
		$Csv = new CsvFileObject($this->path . 'file1.csv');
		$expected = array(
			array(
				'field_1' => 'Field_1',
				'field_2' => 'Field 2',
				'field_3' => 'FIELD-3'
			),
			array(
				'field_1' => '1a',
				'field_2' => '2a///\\\\\\',
				'field_3' => '3a'
			),
			array(
				'field_1' => '1b\'\'\'',
				'field_2' => '2b”””',
				'field_3' => '3b'
			),
			array(
				'field_1' => '1c',
				'field_2' => '2c,,,',
				'field_3' => '3c'
			)
		);

		$result = $Csv->read();
		$this->assertEquals($expected[1], $result);

		$result = $Csv->read();
		$this->assertEquals($expected[2], $result);

		$Csv->rewind();

		$result = $Csv->read();
		$this->assertEquals($expected[0], $result);
	}

/**
 * @brief test reading csv files
 */
	public function testRead() {
		$Csv = new CsvFileObject($this->path . 'file1.csv');
		$expected = array(
			array(
				'field_1' => '1a',
				'field_2' => '2a///\\\\\\',
				'field_3' => '3a'
			),
			array(
				'field_1' => '1b\'\'\'',
				'field_2' => '2b”””',
				'field_3' => '3b'
			),
			array(
				'field_1' => '1c',
				'field_2' => '2c,,,',
				'field_3' => '3c'
			)
		);

		for($i = 0; $i < 3; $i++) {
			$result = $Csv->read();
			$this->assertEquals($expected[$i], $result);
		}

		for($i = 0; $i < 3; $i++) {
			$this->assertEquals(array(), $Csv->read());
		}
	}

/**
 * @brief test reading with a default model defined
 */
	public function testReadDefaultModel() {
		$Csv = new CsvFileObject($this->path . 'file1.csv', array(
			'model' => 'MyModel'
		));
		$expected = array(
			array(
				'MyModel' => array(
					'field_1' => '1a',
					'field_2' => '2a///\\\\\\',
					'field_3' => '3a'
				)
			),
			array(
				'MyModel' => array(
					'field_1' => '1b\'\'\'',
					'field_2' => '2b”””',
					'field_3' => '3b'
				)
			),
			array(
				'MyModel' => array(
					'field_1' => '1c',
					'field_2' => '2c,,,',
					'field_3' => '3c'
				)
			)
		);

		for($i = 0; $i < 3; $i++) {
			$result = $Csv->read();
			$this->assertEquals($expected[$i], $result);
		}

		for($i = 0; $i < 3; $i++) {
			$this->assertEquals(array(), $Csv->read());
		}
	}

/**
 * test read different models
 */
	public function testReadDifferentModels() {
		$Csv = new CsvFileObject($this->path . 'file2.csv');
		$expected = array(
			array(
				'Model' => array(
					'field' => '1a'
				),
				'OtherModel' => array(
					'field' => '2a///\\\\\\'
				),
				'SomeModel' => array(
					'field' => '3a'
				),
				'nomodel' => 1
			),
			array(
				'Model' => array(
					'field' => '1b\'\'\''
				),
				'OtherModel' => array(
					'field' => '2b”””'
				),
				'SomeModel' => array(
					'field' => '3b'
				),
				'nomodel' => 2
			),
			array(
				'Model' => array(
					'field' => '1c',
				),
				'OtherModel' => array(
					'field' => '2c,,,',
				),
				'SomeModel' => array(
					'field' => '3c'
				),
				'nomodel' => 3
			)
		);

		for($i = 0; $i < 3; $i++) {
			$result = $Csv->read();
			$this->assertEquals($expected[$i], $result);
		}

		for($i = 0; $i < 3; $i++) {
			$this->assertEquals(array(), $Csv->read());
		}
	}

/**
 * @brief test read different models custom
 */
	public function testReadDifferentModelsCustom() {
		$Csv = new CsvFileObject($this->path . 'file2.csv', array(
			'model' => 'MyModel'
		));
		$expected = array(
			array(
				'Model' => array(
					'field' => '1a'
				),
				'OtherModel' => array(
					'field' => '2a///\\\\\\'
				),
				'SomeModel' => array(
					'field' => '3a'
				),
				'MyModel' => array(
					'nomodel' => 1
				)
			),
			array(
				'Model' => array(
					'field' => '1b\'\'\''
				),
				'OtherModel' => array(
					'field' => '2b”””'
				),
				'SomeModel' => array(
					'field' => '3b'
				),
				'MyModel' => array(
					'nomodel' => 2
				)
			),
			array(
				'Model' => array(
					'field' => '1c',
				),
				'OtherModel' => array(
					'field' => '2c,,,',
				),
				'SomeModel' => array(
					'field' => '3c'
				),
				'MyModel' => array(
					'nomodel' => 3
				)
			)
		);

		for($i = 0; $i < 3; $i++) {
			$result = $Csv->read();
			$this->assertEquals($expected[$i], $result);
		}

		for($i = 0; $i < 3; $i++) {
			$this->assertEquals(array(), $Csv->read());
		}

		$Csv = new CsvFileObject($this->path . 'file2.csv', array(
			'model' => 'OtherModel'
		));
		$expected = array(
			array(
				'Model' => array(
					'field' => '1a'
				),
				'OtherModel' => array(
					'field' => '2a///\\\\\\',
					'nomodel' => 1
				),
				'SomeModel' => array(
					'field' => '3a'
				)
			),
			array(
				'Model' => array(
					'field' => '1b\'\'\''
				),
				'OtherModel' => array(
					'field' => '2b”””',
					'nomodel' => 2
				),
				'SomeModel' => array(
					'field' => '3b'
				)
			),
			array(
				'Model' => array(
					'field' => '1c',
				),
				'OtherModel' => array(
					'field' => '2c,,,',
					'nomodel' => 3
				),
				'SomeModel' => array(
					'field' => '3c'
				)
			)
		);

		for($i = 0; $i < 3; $i++) {
			$result = $Csv->read();
			$this->assertEquals($expected[$i], $result);
		}

		for($i = 0; $i < 3; $i++) {
			$this->assertEquals(array(), $Csv->read());
		}
	}

}