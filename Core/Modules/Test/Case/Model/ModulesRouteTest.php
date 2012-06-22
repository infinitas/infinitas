<?php
App::uses('ModulesRoute', 'Modules.Model');

/**
 * ModulesRoute Test Case
 *
 */
class ModulesRouteTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.modules.modules_route'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ModulesRoute = ClassRegistry::init('Modules.ModulesRoute');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ModulesRoute);

		parent::tearDown();
	}

/**
 * @brief test validation
 *
 * @dataProvider validationDataBad
 */
	public function testValidationFails($data, $expected) {
		$this->ModulesRoute->create();
		$this->ModulesRoute->set($data);
		$this->ModulesRoute->validates();
		$this->assertEquals($expected, $this->ModulesRoute->validationErrors);
	}

/**
 * @brief validationDataBad data provider
 */
	public function validationDataBad() {
		return array(
			array(
				array(),
				array(
					'position_id' => array('Please select the position this module will show in'),
					'module_id' => array('Please select the position this module will show in'),
				)
			),
		);
	}
}
