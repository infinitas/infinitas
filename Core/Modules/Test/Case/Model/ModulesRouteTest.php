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
		'plugin.modules.module_position',
		'plugin.modules.module',
		'plugin.routes.route',
		'plugin.installer.plugin',
		'plugin.modules.modules_route',
		'plugin.locks.global_lock',
		'plugin.management.ticket',
		'plugin.users.user',
		'plugin.users.group',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ModulesRoute = ClassRegistry::init('Modules.ModulesRoute');
		$this->ModulesRoute->Behaviors->attach('Libs.Validation');
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
					'module_id' => array('A module is required'),
					'route_id' => array('A route is required'),
				)
			),
			array(
				array(
					'module_id' => 'fake-module',
					'route_id' => 'fake-route'
				),
				array(
					'module_id' => array('The selected module is not valid'),
					'route_id' => array('The selected route is not valid'),
				)
			),
			array(
				array(
					'module_id' => 'module-login',
					'route_id' => 'fake-route'
				),
				array(
					'route_id' => array('The selected route is not valid'),
				)
			),
			array(
				array(
					'module_id' => 'fake-module',
					'route_id' => 7
				),
				array(
					'module_id' => array('The selected module is not valid'),
				)
			),
			array(
				array(
					'module_id' => 'module-login',
					'route_id' => 7
				),
				array()
			),
		);
	}
}
