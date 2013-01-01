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
		'plugin.users.group',

		'plugin.installer.plugin'
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
	public function testFindMenu() {
		$result = $this->MenuItem->find('menu', 'main_menu');
		$expected = array('Blog', 'About Me', 'Sandbox');

		$this->assertEquals(count($result), 3);
		$this->assertEquals($expected, Set::extract('/MenuItem/name', $result));

		CakeSession::write('Auth.User.group_id', 2);
		$result = $this->MenuItem->find('menu', 'registered_users');
		$expected = array('About Me', 'Deep link');

		$this->assertEquals(count($result), 2);
		$this->assertEquals($expected, Set::extract('/MenuItem/name', $result));
		CakeSession::destroy();

		$result = $this->MenuItem->find('menu', 'registered_users');
		$this->assertEmpty($result);
	}

/**
 * test save
 */
	public function testSave() {
		$data = array();
		$this->assertEqual($this->MenuItem->find('count'), 7);

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
		$this->assertEqual($this->MenuItem->find('count'), 7);
		$this->assertTrue($this->MenuItem->hasContainer('public-menu'));
		$this->assertTrue($this->MenuItem->hasContainer('registered-menu'));
		$this->assertEqual($this->MenuItem->find('count'), 7);
	}

/**
 * @expectedException InvalidArgumentException
 */
	public function testContainerException() {
		$this->MenuItem->hasContainer('fake-id');
	}

/**
 * @expectedException InvalidArgumentException
 */
	public function testFindMenuException() {
		$this->MenuItem->find('menu');
	}

/**
 * test targets
 */
	public function testTargets() {
		$results = MenuItem::targets();
		$this->assertCount(5, $results);
	}

/**
 * test get parents
 */
	public function testGetParents() {
		$expected = array(
			1 => 'Main_menu',
			2 => '_Blog',
			3 => '_Sandbox',
			4 => '_About Me',
		);
		$results = $this->MenuItem->getParents('public-menu');
		$this->assertEquals($expected, $results);

		$expected = array(
			5 => 'Main_menu',
			6 => '_About Me',
			7 => '__Deep link'
		);
		$results = $this->MenuItem->getParents('registered-menu');
		$this->assertEquals($expected, $results);
	}
}