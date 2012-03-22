<?php
	App::uses('MenuItem', 'Menus.Model');

	/**
	 * MenuItem Test Case
	 */
	class MenuItemTestCase extends CakeTestCase {
		/**
		 * Fixtures
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.menus.core_menu', 
			'plugin.menus.core_menu_item',  
			'plugin.users.group'
		);

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$this->MenuItem = ClassRegistry::init('Menus.MenuItem');
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			unset($this->MenuItem);

			parent::tearDown();
		}

		/**
		 * testValidateEmptyOrCssClass method
		 *
		 * @return void
		 */
		public function testValidateEmptyOrCssClass() {
			$this->assertTrue($this->MenuItem->validateEmptyOrCssClass(array()));
			$this->assertTrue($this->MenuItem->validateEmptyOrCssClass(array('abc')));
			$this->assertTrue($this->MenuItem->validateEmptyOrCssClass(array('abc_xyz')));
			$this->assertTrue($this->MenuItem->validateEmptyOrCssClass(array('Abc')));
			$this->assertTrue($this->MenuItem->validateEmptyOrCssClass(array('abc-10')));
			
			$this->assertFalse($this->MenuItem->validateEmptyOrCssClass(array(0)));
			$this->assertFalse($this->MenuItem->validateEmptyOrCssClass(array('10-abc')));
			$this->assertFalse($this->MenuItem->validateEmptyOrCssClass(array('^-asd')));
			$this->assertFalse($this->MenuItem->validateEmptyOrCssClass(array('asd-$')));
		}
		
		/**
		 * testGetMenu method
		 *
		 * @return void
		 */
		public function testGetMenu() {
			$this->assertFalse($this->MenuItem->getMenu());
			
			$result = $this->MenuItem->getMenu('main_menu');
			$expected = array('Blog', 'About Me', 'Sandbox');
			
			$this->assertEqual(count($result), 3);
			$this->assertEqual($expected, Set::extract('/MenuItem/name', $result));
			
			$result = $this->MenuItem->getMenu('registered_users');
			$expected = array('About Me');
			
			$this->assertEqual(count($result), 1);
			$this->assertEqual($expected, Set::extract('/MenuItem/name', $result));
		}
		
		public function testSave() {
			$data = array();
			$this->assertEqual($this->MenuItem->find('count'), 6);
			
			$this->assertFalse($this->MenuItem->save($data));
			$expected = array(
				'name' => array('Please enter the name of the menu item, this is what users will see'), 
				'link' => array('Please only use external link or the route'),
				'group_id' => array('Please select the group that can see this link'), 
				'menu_id' => array('Please select the menu this item belongs to')
			);
			$this->assertEqual($this->MenuItem->validationErrors, $expected);
			
			$data = array(
				'name' => 'test',
				'group_id' => 1,
				'menu_id' => 'public-menu',
				'link' => '/some/url',
				'parent_id' => null
			);
			$this->MenuItem->create();
			$this->assertTrue((bool)$this->MenuItem->save($data));
			
			$expected = $data;
			$expected['parent_id'] = 1;
			$result = $this->MenuItem->read();
			
			$this->assertEqual($result['MenuItem']['name'], $expected['name']);
			$this->assertEqual($result['MenuItem']['group_id'], $expected['group_id']);
			$this->assertEqual($result['MenuItem']['menu_id'], $expected['menu_id']);
			$this->assertEqual($result['MenuItem']['parent_id'], $expected['parent_id']);
			
			$data = array(
				'name' => 'test-plugin',
				'group_id' => 1,
				'menu_id' => 'public-menu',
				'link' => '',
				'plugin' => 'Configs',
				'parent_id' => null
			);
			$this->MenuItem->create();
			$this->assertTrue((bool)$this->MenuItem->save($data));
			
			$data = array(
				'name' => 'test-plugin',
				'group_id' => 1,
				'menu_id' => 'made-up-name',
				'link' => '',
				'plugin' => 'Configs',
				'parent_id' => null
			);
			$this->MenuItem->create();
			$this->assertFalse((bool)$this->MenuItem->save($data));
		}
		
		/**
		 * testHasContainer method
		 *
		 * @return void
		 */
		public function testHasContainer() {
			$this->assertEqual($this->MenuItem->find('count'), 6);
			$this->assertTrue($this->MenuItem->hasContainer('public-menu'));
			$this->assertTrue($this->MenuItem->hasContainer('registered-menu'));
			$this->assertEqual($this->MenuItem->find('count'), 6);
			
			$this->assertFalse($this->MenuItem->hasContainer('test-menu'));
			$this->assertEqual($this->MenuItem->find('count'), 6);
		}
	}
