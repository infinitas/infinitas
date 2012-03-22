<?php
	App::uses('Menu', 'Menus.Model');

	/**
	 * Menu Test Case
	 *
	 */
	class MenuTestCase extends CakeTestCase {
		/**
		* Fixtures
		*
		* @var array
		*/
		public $fixtures = array(
			'plugin.menus.core_menu', 
			'plugin.menus.menu_item', 
			'plugin.users.group'
		);

		/**
		* setUp method
		*
		* @return void
		*/
		public function setUp() {
			parent::setUp();
			$this->Menu = ClassRegistry::init('Menus.Menu');
		}

		/**
		* tearDown method
		*
		* @return void
		*/
		public function tearDown() {
		unset($this->Menu);

		parent::tearDown();
	}
	
		public function testAddingMenu() {
			$this->assertEqual($this->Menu->find('count'), 2);
			
			$expected = array('name' => 'My menu', 'type' => 'custom-1', 'active' => 1);
			
			$this->Menu->create();
			$result = $this->Menu->save(array('Menu' => $expected));
			$this->assertTrue((bool)$result);
			$this->assertEqual($this->Menu->find('count'), 3);
			
			
			$this->assertEqual($result['Menu']['name'], $expected['name']);
			$this->assertEqual($result['Menu']['type'], $expected['type']);
			$this->assertEqual($result['Menu']['active'], $expected['active']);
			
			$expected = array('name' => 'My menu', 'type' => 'Invalid type', 'active' => 1);
			$this->Menu->create();
			$result = $this->Menu->save(array('Menu' => $expected));
			$this->assertFalse((bool)$result);
			$this->assertEqual($this->Menu->find('count'), 3);
		}
	}
