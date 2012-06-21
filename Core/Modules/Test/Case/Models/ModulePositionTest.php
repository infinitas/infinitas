<?php
/* ModulePosition Test cases generated on: 2010-03-13 11:03:53 : 1268471213*/
App::uses('ModulePosition', 'Modules.Model');

class ModulePositionTestCase extends CakeTestCase {
	public $fixtures = array(
		'plugin.modules.module_position',
		'plugin.modules.module',
		'plugin.themes.theme',
		'plugin.users.group',
		'plugin.routes.route',
		'plugin.modules.modules_route',
	);

	public function startTest() {
		$this->ModulePosition = ClassRegistry::init('Modules.ModulePosition');
	}

	public function endTest() {
		unset($this->ModulePosition);
		ClassRegistry::flush();
	}

/**
 * @brief check validation rules
 */
	public function testValidationRules() {
		$data = array();
		$expected = array('name' => array('Please enter a valid name, lowercase letters, numbers and underscores only'));
		$this->assertFalse($this->ModulePosition->save($data));
		$this->assertEquals($expected, $this->ModulePosition->validationErrors);

		$data = array('name' => '312$#534raf');
		$expected = array('name' => array('Please enter a valid name, lowercase letters, numbers and underscores only'));
		$this->assertFalse($this->ModulePosition->save($data));
		$this->assertEquals($expected, $this->ModulePosition->validationErrors);

		$data = array('name' => 'proper_name');
		$expected = array(
			'ModulePosition' => array(
				'name' => 'proper_name',
				'module_count' => '0'
			)
		);

		$this->ModulePosition->create();
		$result = $this->ModulePosition->save($data);
		unset($result['ModulePosition']['id'], $result['ModulePosition']['created'], $result['ModulePosition']['modified']);
		$this->assertEquals($expected, $result);

		$expected = array(
			'name' => array('There is already a position with that name')
		);

		$this->ModulePosition->create();
		$this->assertFalse($this->ModulePosition->save($data));
		$this->assertEquals($expected, $this->ModulePosition->validationErrors);

	}

/**
 * @brief test if a module position is valid
 *
 * @dataProvider positions
 */
	public function testIsPosition($data, $expected) {
		$this->assertEquals($expected, $this->ModulePosition->isPosition($data));
	}

/**
 * positions data provider
 *
 * @return void
 */
	public function positions() {
		return array(
			array('top', true),
			array('bottom', true),
			array('fake-position', false),
			array('', false),
			array(false, false),
			array(true, false),
			array(123, false),
			array(array(), false),
		);
	}
}