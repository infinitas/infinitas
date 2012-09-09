<?php
App::import('Model', 'AppModel');

class AppModelTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.configs.config',
		'plugin.themes.theme',
		'plugin.routes.route',
		'plugin.installer.plugin',
		'plugin.view_counter.view_counter_view',
	);

	public function setUp() {
		parent::setUp();
		$this->Route = ClassRegistry::init('Routes.Route');
		$this->Route->Behaviors->disable('Trashable');

		$this->Theme = ClassRegistry::init('Themes.Theme');
		$this->Theme->Behaviors->disable('Trashable');
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->Route);
	}

/**
 * @brief test getting the number of affected rows
 */
	public function testGetAffectedRows() {
		$first = $this->Route->find('first');
		$this->Route->save($first);

		$first = $this->Route->find('first');
		$this->Route->save($first);
		$this->assertEquals(1, $this->Route->getAffectedRows());

		$this->Route->find('count');
		$this->assertEquals(1, $this->Route->getAffectedRows());

		$this->Route->deleteAll(array('Route.id != 0'));
		$this->assertEquals(10, $this->Route->getAffectedRows());

		$this->Route->find('count');
		$this->assertEquals(1, $this->Route->getAffectedRows());

		$this->Route->find('all');
		$this->assertEquals(0, $this->Route->getAffectedRows());

		$this->Route->save($first);
		$this->assertEquals(1, $this->Route->getAffectedRows());
	}

/**
 * @brief test getting the full table name used in joins
 */
	public function testFullTableName() {
		$this->assertEquals('test.core_routes', $this->Route->fullTableName());

		$this->Route->useTable = 'core_plugins';
		$this->assertEquals('test.core_core_plugins', $this->Route->fullTableName());
	}

/**
 * @brief test getting a unique list from a model
 */
	public function testUniqueList() {
		$expected = array(
			9 => 'Admin Home',
			12 => 'Blog Home - Backend',
			13 => 'Blog Home - Frontend',
			18 => 'Blog Test',
			14 => 'Cms Home - Backend',
			15 => 'Cms Home - Frontend',
			7 => 'Home Page',
			11 => 'Management Home',
			16 => 'Newsletter Home - Backend',
			8 => 'Pages',
		);
		$result = $this->Route->uniqueList();
		$this->assertIdentical($expected, $result);

		$expected = array(
			7 => '7',
			8 => '8',
			9 => '9',
			11 => '11',
			12 => '12',
			13 => '13',
			14 => '14',
			15 => '15',
			16 => '16',
			18 => '18'
		);
		$result = $this->Route->uniqueList('id');
		$this->assertEqual($expected, $result);

		$expected = array(
			'Home Page' => '7',
			'Pages' => '8',
			'Admin Home' => '9',
			'Management Home' => '11',
			'Blog Home - Backend' => '12',
			'Blog Home - Frontend' => '13',
			'Cms Home - Backend' => '14',
			'Cms Home - Frontend' => '15',
			'Newsletter Home - Backend' => '16',
			'Blog Test' => '18',
		);
		$result = $this->Route->uniqueList('id', 'name');
		$this->assertIdentical($expected, $result);

		$expected = array(
			8 => 'Pages',
			16 => 'Newsletter Home - Backend',
			11 => 'Management Home',
			7 => 'Home Page',
			15 => 'Cms Home - Frontend',
			14 => 'Cms Home - Backend',
			18 => 'Blog Test',
			13 => 'Blog Home - Frontend',
			12 => 'Blog Home - Backend',
			9 => 'Admin Home',
		);
		$result = $this->Route->uniqueList(null, null, array('Route.name' => 'desc'));
		$this->assertIdentical($expected, $result);

		$expected = array(
			8 => 'Pages',
			16 => 'Newsletter Home - Backend',
			11 => 'Management Home',
			7 => 'Home Page',
			15 => 'Cms Home - Frontend',
			14 => 'Cms Home - Backend',
			18 => 'Blog Test',
			13 => 'Blog Home - Frontend',
			12 => 'Blog Home - Backend',
			9 => 'Admin Home',
		);
		$result = $this->Route->uniqueList(null, null, 'desc');
		$this->assertIdentical($expected, $result);
	}

/**
 * @brief get the full Plugin.Model name
 *
 * @todo remove
 */
	public function testModelName() {
		$this->assertEquals('Routes.Route', $this->Route->modelName());
	}

/**
 * @brief get the full Plugin.Model name
 */
	public function testFullModelName() {
		$this->assertEquals('Routes.Route', $this->Route->fullModelName());
	}

/**
 * @brief custom find for active rows
 */
	public function testFindActive() {
		$expected = array(
			array('Route' => array('id' => '7', 'active' => true)),
			array('Route' => array('id' => '8', 'active' => true)),
			array('Route' => array('id' => '9', 'active' => true)),
			array('Route' => array('id' => '11', 'active' => true)),
			array('Route' => array('id' => '12', 'active' => true)),
			array('Route' => array('id' => '13', 'active' => true)),
			array('Route' => array('id' => '14', 'active' => true)),
			array('Route' => array('id' => '15', 'active' => true)),
			array('Route' => array('id' => '16', 'active' => true)),
			array('Route' => array('id' => '18', 'active' => true)));
		$results = $this->Route->find('active', array('fields' => array('Route.id', 'Route.active')));
		$this->assertEquals($expected, $results);

		$this->Route->id = '18';
		$this->Route->saveField('active', false);

		$expected = array(
			array('Route' => array('id' => '7', 'active' => true)),
			array('Route' => array('id' => '8', 'active' => true)),
			array('Route' => array('id' => '9', 'active' => true)),
			array('Route' => array('id' => '11', 'active' => true)),
			array('Route' => array('id' => '12', 'active' => true)),
			array('Route' => array('id' => '13', 'active' => true)),
			array('Route' => array('id' => '14', 'active' => true)),
			array('Route' => array('id' => '15', 'active' => true)),
			array('Route' => array('id' => '16', 'active' => true)));
		$results = $this->Route->find('active', array('fields' => array('Route.id', 'Route.active')));
		$this->assertEquals($expected, $results);
	}

/**
 * @brief custom find for inactive rows
 */
	public function testFindInactive() {
		$this->Route->id = '18';
		$this->Route->saveField('active', false);

		$expected = array(
			array('Route' => array('id' => '18', 'active' => false)));
		$results = $this->Route->find('inactive', array('fields' => array('Route.id', 'Route.active')));
		$this->assertEquals($expected, $results);

		$this->Route->id = '16';
		$this->Route->saveField('active', false);

		$expected = array(
			array('Route' => array('id' => '16', 'active' => false)),
			array('Route' => array('id' => '18', 'active' => false)));
		$results = $this->Route->find('inactive', array('fields' => array('Route.id', 'Route.active')));
		$this->assertEquals($expected, $results);
	}

	public function testRawSave() {
		$this->Theme->deleteAll(array('Theme.id != 0'));

		$data = array(
			array('id' => 1, 'name' => 'test 1', 'madeup' => false));
		$this->Theme->rawSave($data);
		$this->assertEquals(1, $this->Theme->find('count'));

		$data = array(
			array('Theme' => array('id' => 2, 'name' => 'test 2', 'madeup' => false)),
			array('Theme' => array('id' => 3, 'name' => 'test 3', 'madeup' => false)));
		$this->Theme->rawSave($data);
		$this->assertEquals(3, $this->Theme->find('count'));

		$data = array(
			array('Theme' => array('id' => 4, 'name' => 'test 4', 'madeup' => false)),
			array('Theme' => array('id' => 5, 'name' => 'test 5', 'madeup' => false)),
			array('Theme' => array('id' => 6, 'name' => 'test 6', 'madeup' => false)),
			array('Theme' => array('id' => 7, 'name' => 'test 7', 'madeup' => false)),
			array('Theme' => array('id' => 8, 'name' => 'test 8', 'madeup' => false)),
			array('Theme' => array('id' => 9, 'name' => 'test 9', 'madeup' => false)),
			array('Theme' => array('id' => 10, 'name' => 'test 10', 'madeup' => false)));
		$this->Theme->rawSave($data, array('chunk' => 2));
		$this->assertEquals(10, $this->Theme->find('count'));

		$this->Theme->deleteAll(array('Theme.id != 0'));

		$data = array(
			array('Theme' => array('id' => 1, 'name' => 'test 1', 'madeup' => false)),
			array('Theme' => array('name' => 'test 2', 'madeup' => false, 'id' => 2)));
		$this->Theme->rawSave($data);
		$this->assertEquals(2, $this->Theme->find('count'));

		$expected = array(
			1 => 'test 1',
			2 => 'test 2'
		);
		$result = $this->Theme->find('list');
		$this->assertEquals($expected, $result);

		$data = array(
			array('Theme' => array('name' => 'test 3', 'madeup' => false)),
			array('Theme' => array('name' => 'test 4', 'madeup' => false)));
		$this->Theme->rawSave($data);
		$this->assertEquals(4, $this->Theme->find('count'));

		$expected = array(
			'test 1',
			'test 2',
			'test 3',
			'test 4'
		);
		$result = array_values($this->Theme->find('list'));
		sort($result);
		$this->assertEquals($expected, $result);
	}
}