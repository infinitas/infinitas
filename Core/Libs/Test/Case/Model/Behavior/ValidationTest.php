<?php
App::import('Behavior', 'libs.Validation');
InfinitasPlugin::load('Users');

class ValidationBehaviorTestCase extends CakeTestCase {
	public $fixtures = array(
		'plugin.users.user',
		'plugin.management.ticket',
		'plugin.users.group',
		'plugin.installer.plugin',
	);

	public function startTest() {
		$this->User = ClassRegistry::init('Users.User');
		$this->User->Behaviors->attach('Libs.Validation');
	}

	public function endTest() {
		unset($this->Infinitas);
		ClassRegistry::flush();
	}

/**
 * check validation is attached
 */
	public function testValidationAttached() {
		$this->assertTrue($this->User->Behaviors->attached('Validation'));
	}

/**
 * @brief test json validation
 */
	public function testValidateJson() {
		$this->User->validate = array(
			'field_1' => array(
				'validateJson' => array(
					'rule' => 'validateJson',
					'message' => 'thats not json'
				)
			)
		);

		$expected = array('field_1' => array('thats not json'));

		$data['User']['field_1'] = 'foo';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, $expected);

		$data['User']['field_1'] = '';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, $expected);

		$data['User']['field_1'] = json_encode(array('asd' => 'meh'));
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, array());
	}

/**
 * @brief test either or
 *
 * @dataProvider eitherOrData
 */
	public function testValidateEitherOr($data, $expected) {
		if(empty($data['data'])) {
			$data['data'] = $data;
		}
		if(empty($data['validate'])) {
			$data['validate'] = array(
				'field_1' => array(
					'validateEitherOr' => array(
						'allowEmpty' => true,
						'rule' => array('validateEitherOr', array('field_1', 'field_2')),
						'message' => 'pick one, only one'
					)
				)
			);
		}
		$this->User->validate = $data['validate'];

		$this->User->set($data['data']);
		$this->User->validates();
		$this->assertEqual($this->User->validationErrors, $expected);
	}

/**
 * @brief tests is url or absolute path
 */
	public function testValidateUrlOrAbsolute() {
		$this->User->validate = array(
			'url' => array(
				'validateUrlOrAbsolute' => array(
					'rule' => 'validateUrlOrAbsolute',
					'message' => 'not url or absolute path'
				)
			)
		);

		$expected = array('url' => array('not url or absolute path'));

		/**
			* invalid
			*/
		$data['User']['url'] = 'this/is/not/an/absolute.path';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, $expected);

		$data['User']['url'] = 'http:/this.is/an/url.ext';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, $expected);

		/**
			* valid
			*/
		$data['User']['url'] = '/this/is/an/absolute.path';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, array());

		$data['User']['url'] = 'http://this.is/an/url.ext';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, array());

		$data['User']['url'] = 'ftp://this.is/an/url.ext';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, array());
	}

/**
 * @brief tests comparing passwords
 */
	public function testValidateComparePasswords() {
		$this->User->validate = array(
			'password' => array(
				'validateCompareFields' => array(
					'rule' => array('validateCompareFields', array('password', 'password_match')),
					'message' => 'fields dont match'
				)
			)
		);

		$data['User']['password'] = 'abc';
		$data['User']['password_match'] = 'xyz';

		$this->User->set($data); $this->User->validates();
		$expected = array('password' => array('fields dont match'));
		$this->assertEqual($this->User->validationErrors, $expected);

		$data['User']['password'] = Security::hash('abc', null, true);
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, $expected);

		$data['User']['password_match'] = 'abc';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, array());
	}

/**
 * @brief test comparing normal fields
 */
	public function testCompareFields() {
		$this->User->validate = array(
			'field_1' => array(
				'validateCompareFields' => array(
					'rule' => array('validateCompareFields', array('field_1', 'field_2')),
					'message' => 'fields dont match'
				)
			)
		);


		$data['User']['field_1'] = 'abc';
		$data['User']['field_2'] = 'xyz';
		$this->User->set($data); $this->User->validates();
		$expected = array('field_1' => array('fields dont match'));
		$this->assertEqual($this->User->validationErrors, $expected);

		$data['User']['field_2'] = 'abc';
		$this->User->set($data); $this->User->validates();
		$this->assertEqual($this->User->validationErrors, array());
	}

/**
 * @brief check a related record exists
 */
	public function testValidateRecordExists() {
		//Add validation rule for the user record
		$this->User->validate['group_id'] = array(
			'rule' => 'validateRecordExists',
			'message' => 'Invalid group',
			'required' => true
		);

		// Test for an non existing group
		$data = array(
			'User' => array(
				'username' => 'test-user',
				'group_id' => 'non-existing-group-id'
			)
		);

		$this->User->set($data);
		$this->assertFalse($this->User->validates());
		$expected = array('group_id' => array('Invalid group'));
		$this->assertEqual($this->User->validationErrors, $expected);

		//Test for an existing group
		$data['User']['group_id'] = 1;
		$this->User->set($data);
		$this->assertTrue($this->User->validates());
		$expected = array();
		$this->assertEqual($this->User->validationErrors, $expected);
	}

/**
 * @brief test validation
 *
 * @dataProvider pluginValidationData
 */
	public function testValidatePluginExists($data, $expected) {
		$this->User->validate = array(
			'plugin' => array(
				'rule' => 'validatePluginExists',
				'message' => 'plugin does not exist',
				'required' => true
			)
		);
		$this->User->set($data); $this->User->validates();
		$result = $this->User->validationErrors;
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test validation
 */
	public function testValidatePluginExistsAdvanced() {
		$this->User->validate = array(
			'plugin' => array(
				'validatePluginExists' => array(
					'allowEmpty' => true,
					'rule' => 'validatePluginExists',
					'message' => 'plugin does not exist'
				)
			)
		);

		$data = array(
			'plugin' => 'fake'
		);
		$this->User->create($data); $this->User->validates();
		$expected = array('plugin' => array('plugin does not exist'));
		$this->assertEquals($expected, $this->User->validationErrors);

		$data = array(
			'plugin' => 'DebugKit'
		);
		$this->User->create($data); $this->User->validates();
		$expected = array();
		$this->assertEquals($expected, $this->User->validationErrors);

		$this->User->validate = array(
			'plugin' => array(
				'validatePluginExists' => array(
					'allowEmpty' => true,
					'rule' => array('validatePluginExists', array('pluginType' => 'installed')),
					'message' => 'plugin does not exist'
				)
			)
		);

		$data = array(
			'plugin' => 'DebugKit'
		);
		$this->User->create($data); $this->User->validates();
		$expected = array();
		$this->assertEquals($expected, $this->User->validationErrors);

		$this->User->validate = array(
			'plugin' => array(
				'validatePluginExists' => array(
					'allowEmpty' => true,
					'rule' => array('validatePluginExists', array('pluginType' => 'core')),
					'message' => 'plugin does not exist'
				)
			)
		);

		$data = array(
			'plugin' => 'DebugKit'
		);
		$this->User->create($data); $this->User->validates();
		$expected = array('plugin' => array('plugin does not exist'));
		$this->assertEquals($expected, $this->User->validationErrors);

		$this->User->validate = array(
			'plugin' => array(
				'validatePluginExists' => array(
					'allowEmpty' => true,
					'rule' => array('validatePluginExists', array('pluginType' => 'core')),
					'message' => 'plugin does not exist'
				)
			)
		);

		$data = array(
			'plugin' => 'Menus'
		);
		$this->User->create($data); $this->User->validates();
		$expected = array();
		$this->assertEquals($expected, $this->User->validationErrors);
	}

/**
 * @brief test a controller exists within the selected plugin
 *
 * @dataProvider controllerValidationData
 */
	public function testValidateControllerExists($data, $expected) {
		$this->User->validate = array(
			'controller' => array(
				'validateControllerExists' => array(
					'rule' => array('ValidateControllerExists', array('pluginField' => 'plugin')),
					'message' => 'Invalid controller'
				)
			)
		);
		$this->User->create($data);
		$this->User->validates();

		$this->assertEquals($expected, $this->User->validationErrors);
	}

/**
 * @brief test a controller exists within hardcoded plugin
 *
 * @dataProvider controllerValidationAdvancedData
 */
	public function testValidateControllerAdvancedExists($data, $expected) {
		$this->User->validate = array(
			'controller' => array(
				'validateControllerExists' => array(
					'rule' => array('ValidateControllerExists', array('setPlugin' => 'Users')),
					'message' => 'Invalid controller'
				)
			)
		);

		$this->User->create($data);
		$this->User->validates();

		$this->assertEquals($expected, $this->User->validationErrors);
	}

/**
 * @brief test a controller exists within hardcoded plugin
 *
 * @dataProvider actionValidationData
 */
	public function testValidateActionExists($data, $expected) {
		$this->User->validate = array(
			'action' => array(
				'validateActionExists' => array(
					'rule' => 'validateActionExists',
					'message' => 'Invalid action'
				)
			)
		);

		$this->User->create($data);
		$this->User->validates();

		$this->assertEquals($expected, $this->User->validationErrors);
	}

/**
 * @brief pluginValidationData data provider
 */
	public function pluginValidationData() {
		return array(
			array(
				array('plugin' => 'foo'),
				array('plugin' => array('plugin does not exist'))
			),
			array(
				array('plugin' => ''),
				array('plugin' => array('plugin does not exist'))
			),
			array(
				array('plugin' => 123),
				array('plugin' => array('plugin does not exist'))
			),
			array(
				array('plugin' => 'Users'),
				array()
			)
		);
	}

/**
 * @brief testValidateEitherOr data provider
 */
	public function eitherOrData() {
		$validation = array(
			array(
				'field_1' => array(
					'validateEitherOr' => array(
						'allowEmpty' => true,
						'rule' => array('validateEitherOr', array('field_1', 'field_2')),
						'message' => 'pick one, only one'
					)
				),
				'field_2' => array(
					'validateEitherOr' => array(
						'allowEmpty' => true,
						'rule' => array('validateEitherOr', array('field_1', 'field_2')),
						'message' => 'cant pick both'
					)
				)
			),
			array(
				'field_2' => array(
					'validateEitherOr' => array(
						'allowEmpty' => true,
						'rule' => array('validateEitherOr', array('field_1', 'field_2')),
						'message' => 'cant pick both'
					)
				)
			)
		);

		return array(
			// test with first field having rules
			array(
				array('field_1' => '', 'field_2' => ''),
				array()),
			array(
				array('field_1' => 'foo', 'field_2' => 'bar'),
				array('field_1' => array('pick one, only one'))),
			array(
				array('field_1' => 'foo', 'field_2' => ''),
				array()),
			array(
				array('field_1' => '', 'field_2' => 'foo'),
				array()),

			// test with second field having rules
			array(
				array('validate' => $validation[1], 'data' => array('field_1' => 'asdf', 'field_2' => 'asdf')),
				array('field_2' => array('cant pick both'))),

			// test with both fields having rules
			array(
				array('validate' => $validation[0], 'data' => array('field_1' => '', 'field_2' => '')),
				array()),
			array(
				array('validate' => $validation[0], 'data' => array('field_1' => 'foo', 'field_2' => 'bar')),
				array('field_1' => array('pick one, only one'), 'field_2' => array('cant pick both'))),
			array(
				array('validate' => $validation[0], 'data' => array('field_1' => 'foo', 'field_2' => '')),
				array()),
			array(
				array('validate' => $validation[0], 'data' => array('field_1' => '', 'field_2' => 'bar')),
				array()),
		);
	}

/**
 * @brief controllerValidationData data provider
 * @return type
 */
	public function controllerValidationData() {
		return array(
			array(
				array('plugin' => 'false-plugin', 'controller' => 'false-controller'),
				array('controller' => array('Invalid controller'))),
			array(
				array('plugin' => 'Users', 'controller' => 'false-controller'),
				array('controller' => array('Invalid controller'))),
			array(
				array('plugin' => 'Users', 'controller' => 'UsersController'),
				array()),
			array(
				array('plugin' => 'fake-plugin', 'controller' => 'UsersController'),
				array('controller' => array('Invalid controller'))),
			array(
				array('plugin' => 'users', 'controller' => 'UsersController'),
				array())
		);
	}

/**
 * @brief controllerValidationAdvancedData data provider
 */
	public function controllerValidationAdvancedData() {
		return array(
			array(
				array('plugin' => 'false-plugin', 'controller' => 'false-controller'),
				array('controller' => array('Invalid controller'))),
			array(
				array('plugin' => 'Users', 'controller' => 'false-controller'),
				array('controller' => array('Invalid controller'))),
			array(
				array('plugin' => 'Users', 'controller' => 'UsersController'),
				array()),
			array( // hard coded plugin in the validation
				array('plugin' => 'fake-plugin', 'controller' => 'UsersController'),
				array()),
			array(
				array('plugin' => 'users', 'controller' => 'UsersController'),
				array()),
		);
	}

/**
 * @brief actionValidationData data provider
 */
	public function actionValidationData() {
		return array(
			array(
				array('plugin' => 'false-plugin', 'controller' => 'false-controller', 'action' => 'fake'),
				array('action' => array('Invalid action'))),
			array(
				array('plugin' => 'Users', 'controller' => 'false-controller', 'action' => 'fake'),
				array('action' => array('Invalid action'))),
			array(
				array('plugin' => 'Users', 'controller' => 'UsersController', 'action' => 'fake'),
				array('action' => array('Invalid action'))),
			array(
				array('plugin' => 'Users', 'controller' => 'UsersController', 'action' => 'register'),
				array()),
			array(
				array('plugin' => 'Users', 'controller' => 'UsersController', 'action' => 'admin_add'),
				array()),
			array(
				array('plugin' => 'fake-plugin', 'controller' => 'UsersController', 'action' => 'add'),
				array('action' => array('Invalid action'))),
			array(
				array('plugin' => 'fake-plugin', 'controller' => 'fake-controller', 'action' => 'add'),
				array('action' => array('Invalid action'))),
			array(
				array('plugin' => 'users', 'controller' => 'users_controller', 'action' => 'register'),
				array()),
			array(
				array('plugin' => 'Users', 'controller' => 'UsersController', 'action' => '_getUserData'),
				array('action' => array('Invalid action'))),
		);
	}
}