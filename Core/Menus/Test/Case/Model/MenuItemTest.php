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

		}
		
		/**
		 * testGetMenu method
		 *
		 * @return void
		 */
		public function testGetMenu() {
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
			
			var_dump($this->MenuItem->save($data, array('validate' => true)));
			$this->assertEqual($this->MenuItem->find('count'), 6);
			
			pr($this->MenuItem->validationErrors);
		}
		
		/**
		 * testHasContainer method
		 *
		 * @return void
		 */
		public function testHasContainer() {
			
		}
	}
