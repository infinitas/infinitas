<?php
App::uses('Module', 'Modules.Model');
CakePlugin::load('ViewCounter');

class ModuleTestCase extends CakeTestCase {
	public $fixtures = array(
		'plugin.modules.module_position',
		'plugin.modules.module',
		'plugin.themes.theme',
		'plugin.users.user',
		'plugin.users.group',
		'plugin.routes.route',
		'plugin.modules.modules_route',
		'plugin.installer.plugin',
		'plugin.locks.global_lock',
		'plugin.management.ticket',
	);

/**
 * @brief test startup
 */
	public function startTest() {
		$this->Module = new Module();
		$this->Module->Behaviors->attach('Libs.Validation');
	}

/**
 * @brief test cleanup
 */
	public function endTest() {
		unset($this->Module);
		ClassRegistry::flush();
	}

/**
 * @brief check validation rules
 *
 * @dataProvider validationFailData
 */
	public function testValidationRules($data, $expected) {
		$this->Module->create();

		$this->assertFalse($this->Module->save($data));
		$this->assertEquals($expected, $this->Module->validationErrors);
	}

/**
 * @brief check saving valid data
 *
 * @dataProvider correctData
 */
	public function testSave($data, $expected) {
		$this->Module->create();
		$this->assertTrue((bool)$this->Module->save($data));
		$result = $this->Module->read();
		$this->assertTrue(is_array($result['Module']));
		$this->assertTrue(is_array($result['ModuleRoute']));
		$this->assertEquals(array(), $this->Module->validationErrors);
	}

/**
 * @brief test correct data in finds
 */
	public function testFind() {
		$expected = array(
			'Module' => array(
				'id' => 'module-login',
				'name' => 'login',
				'content' => '',
				'module' => 'login',
				'config' => '',
				'theme_id' => '0',
				'position_id' => 'module-position-custom1',
				'group_id' => '2',
				'ordering' => '1',
				'admin' => false,
				'active' => true,
				'show_heading' => false,
				'core' => false,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://www.infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-01-19 00:30:53',
				'modified' => '2010-06-02 14:53:06',
				'plugin' => 'Management',
				'list_name' => 'login',
				'save_name' => 'login',
			),
			'ModuleRoute' => array(
				array(
					'ModuleRoute' => array(
						'id' => '65',
						'module_id' => 'module-login',
						'route_id' => '0',
					),
					'Route' =>
						array(
							'id' => null,
							'url' => null,
							'name' => null,
						)
				)
			)
		);

		$result = $this->Module->find('first', array('conditions' => array('Module.id' => 'module-login')));
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test finding a list of modules
 *
 * @dataProvider moduleListData
 */
	public function testGetModulesList($data, $expected) {
		$result = $this->Module->getModuleList($data);
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test getting module data
 *
 * @dataProvider getModuleData
 */
	public function testGetModule($data, $expected) {
		$result = $this->Module->getModule($data['module'], $data['admin']);
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test getting modules data
 *
 * @dataProvider getModulesData
 */
	public function testGetModules($data, $expected) {
		$result = $this->Module->getModules($data['position'], $data['admin']);
		$this->assertEquals($expected, $result);
	}

/**
 * validationFailData data provider
 *
 * @return void
 */
	public function validationFailData() {
		return array(
			array(
				array(),
				array(
					'name' => array('Please enter a name for this module'),
					'position_id' => array('Please select the position this module will show in'),
					'author' => array('Please enter the author of this module'),
					'url' => array('Please enter the url of the author'),
					'plugin' => array('Please select the plugin this module is loaded from'),
					'module' => array('Please select the module to load')
				)
			),
			array(
				array('name' => '#$@675472342@'),
				array(
					'name' => array('Please enter a valid name (alpha numeric with spaces)'),
					'position_id' => array('Please select the position this module will show in'),
					'author' => array('Please enter the author of this module'),
					'url' => array('Please enter the url of the author'),
					'plugin' => array('Please select the plugin this module is loaded from'),
					'module' => array('Please select the module to load')
				)
			),
			array(
				array(
					'name' => 'My Module',
					'position_id' => 99999,
					'author' => 'dogmatic69',
					'url' => 'wrong-url',
					'plugin' => 'Invalid Plugin',
					'module' => 'nothing-too-see'
				),
				array(
					'position_id' => array('Please select a valid position'),
					'url' => array('Please enter the url of the author'),
					'plugin' => array('Please select a valid plugin'),
					'module' => array('Please select a valid module')
				)
			)
		);
	}

/**
 * correctData data provider
 *
 * @return void
 */
	public function correctData() {
		return array(
			array(
				array(
					'name' => 'My Module',
					'position_id' => 'module-position-top',
					'author' => 'dogmatic69',
					'url' => 'http://infinitas-cms.org/module/updates',
					'plugin' => 'ViewCounter',
					'module' => 'admin/overall',
					'admin' => true
				),
				array(
					'Module' => array(

					),
					'ModuleRoute' => array(
						array(
							'ModuleRoute' => array(
								'id' => '65',
								'module_id' => 'module-login',
								'route_id' => '0',
							),
							'Route' =>
								array(
									'id' => null,
									'url' => null,
									'name' => null,
								)
						)
					)
				)
			)
		);
	}

/**
 * moduleListData data provider
 *
 * @return void
 */
	public function moduleListData() {
		return array(
			array(
				null,
				array('admin' => array(), 'user' => array())),
			array(
				'Users', array(
					'admin' => array('admin/registrations' => 'Registrations'),
					'user' => array('login' => 'Login'))),
			array(
				'ViewCounter', array(
					'admin' => array(
						'admin/overall' => 'Overall',
						'admin/popular_items' => 'Popular Items',
						'admin/quick_view' => 'Quick View'),
					'user' => array())));
	}

/**
 * getModuleData data provider
 *
 * @return void
 */
	public function getModuleData() {
		return array(
			array(
				array('module' => 'foo-bar', 'admin' => false), false),
			array(
				array('module' => 'foo-bar', 'admin' => true), false),
			array(
				array('module' => 'login', 'admin' => false),
				array(
					'Module' => array(
						'id' => 'module-login',
						'name' => 'login',
						'plugin' => 'Management',
						'content' => '',
						'module' => 'login',
						'config' => '',
						'show_heading' => false,
					),
					'ModuleRoute' => array(
						array(
							'ModuleRoute' =>
								array(
									'id' => '65',
									'module_id' => 'module-login',
									'route_id' => '0'),
								'Route' => array(
									'id' => null,
									'url' => null,
									'name' => null))),
					'Position' => array(

					),
					'Theme' => array(

					),
					'Group' => array(

					),
					'Route' => array(

					))),
			array(
				array('module' => 'login', 'admin' => true), false),
		);
	}

/**
 * getModulesData data provider
 *
 * @return void
 */
	public function getModulesData() {
		return array(
			array(
				array('position' => 'foo-bar', 'admin' => false), array()),
			array(
				array('position' => 'foo-bar', 'admin' => true), array()),
			array(
				array('position' => 'custom3', 'admin' => false),
				array(
					array(
						'Module' => array(
							'id' => 'module-some-news',
							'name' => 'News module',
							'plugin' => 'News',
							'content' => '',
							'module' => 'news',
							'config' => '',
							'show_heading' => true,
						),
						'Position' => array(
							'id' => 'module-position-custom3',
							'name' => 'custom3',
						),
						'Group' => array(
							'id' => '2',
							'name' => 'Users',
						),
						'Theme' => array(
							'id' => null,
							'name' => null,
						),
						'ModuleRoute' => array(
						)
					))),
			array(
				array('position' => 'custom3', 'admin' => true), array()),
		);
	}
}