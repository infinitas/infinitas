<?php
	/* Infinitas Test cases generated on: 2010-03-13 14:03:31 : 1268484451*/
	App::uses('InfinitasBehavior', 'Libs.Model/Behavior');
	App::uses('Route', 'Routes.Model');

	class RouteTest1 extends Route {
		public $useDbConfig = 'test';

		public function someMethod($conditions = array()) {
			return $this->find('list', array('conditions' => $conditions));
		}
	}

	class RouteTest2 extends Route {
		public $useDbConfig = 'test';

		public function _getList($conditions = array()) {
			return $this->find('list', array('conditions' => $conditions));
		}
	}

	class InfinitasBehaviorTestCase extends CakeTestCase {

		//need something to set with
		public $fixtures = array(
			'plugin.routes.route',
			'plugin.themes.theme',
			'plugin.management.ticket',
			'plugin.users.user',
			'plugin.installer.plugin'
		);

		/**
		 * @expectedException PHPUNIT_FRAMEWORK_ERROR_WARNING
		 */
		public function setUp() {
			parent::setUp();

			$this->User = ClassRegistry::init('Users.User');
		}

		public function tearDown() {
			parent::tearDown();

			unset($this->User);
		}

		public function testGetJson() {
			// test wrong usage
			$this->assertFalse($this->User->getJson());

			// bad json
			$this->assertFalse($this->User->getJson(''));
			$this->assertFalse($this->User->getJson('some text'));
			$this->assertFalse($this->User->getJson(array(123 => 'abc')));
			$this->assertFalse($this->User->getJson('{123:"abc"}'));

			// good json
			$this->assertEqual($this->User->getJson('{"123":"abc"}'), array(123 => 'abc'));
			$this->assertEqual($this->User->getJson('{"abc":123}'), array('abc' => 123));

			// nested array
			$this->assertEqual(
				$this->User->getJson('{"abc":{"abc":{"abc":{"abc":{"abc":{"abc":123}}}}}}'),
				array('abc' => array('abc' => array('abc' => array('abc' => array('abc' => array('abc' => 123))))))
			);

			// get object back
			$expected = new stdClass();
			$expected->abc = 123;
			$this->assertEqual($this->User->getJson('{"abc":123}', array('assoc' => false)), $expected);

			//validate bad json
			$this->assertFalse($this->User->getJson('some text', array(), false));
			$this->assertTrue($this->User->getJson('{"abc":123}', array(), false));

			$data['Model']= array(
				'abc' => json_encode(array('abc' => 123)),
				'xyz' => array(
					123 => json_encode(array(1,2,3,4,5)),
					'xyz' => array(
						'abc123' => json_encode(array('a','b','c','d'))
					)
				)
			);

			// get recursive json
			$expected = array('Model' => array('abc' => array('abc' => 123),'xyz' => array(123 => array(1,2,3,4,5),'xyz' => array('abc123' => array('a','b','c','d')))));
			$this->assertEqual($expected, $this->User->getJsonRecursive($data));
			// string passed in
			$this->assertEqual(array(array('abc')), $this->User->getJsonRecursive(json_encode(array('abc'))));
		}

		public function testSingleDimentionArray() {
			//test wrong usage
			$this->assertEqual($this->User->singleDimentionArray(''), array());
			$this->assertEqual($this->User->singleDimentionArray(array()), array());

			//normal tests
			$this->assertEqual($this->User->singleDimentionArray(array(array('abc' => 123))), array());
			$this->assertEqual($this->User->singleDimentionArray(array('one' => array('abc' => 123), 'two' => 2)), array('two' => 2));
		}

		public function testGetPlugins() {
			$_allPlugins = InfinitasPlugin::listPlugins('all');
			$allPlugins = array() + array('' => 'None');
			foreach($_allPlugins as $k => $v) {
				$allPlugins[Inflector::underscore($v)] = $v;
			}
			// need to see how to do this
			//$this->assertEqual(count($this->User->getPlugins(true)), count($allPlugins));
		}

		/**
		 * @expectedException MissingDatasourceConfigException
		 */
		public function testMissingConnection() {
			$this->User->getTables('this_is_not_a_connection');
		}

		public function testGettingTableThings() {
			// find all the tables
			$expected = array(
				'core_plugins',
				'core_routes',
				'core_themes',
				'core_tickets',
				'core_users'
			);
			$this->assertEquals($expected, $this->User->getTables('test'));

			// find tables with a id field
			$expected = array(
				array('plugin' => 'Management', 'model' => 'Plugin', 'table' => 'core_plugins'),
				array('plugin' => 'Management', 'model' => 'Route', 'table' => 'core_routes'),
				array('plugin' => 'Management', 'model' => 'Theme', 'table' => 'core_themes'),
				array('plugin' => 'Management', 'model' => 'Ticket', 'table' => 'core_tickets'),
				array('plugin' => 'Management', 'model' => 'User', 'table' => 'core_users')
			);
			$this->assertEquals($expected, $this->User->getTablesByField('test', 'id'));

			// find tables with a pass field
			$expected = array(
				array('plugin' => 'Management', 'model' => 'Route', 'table' => 'core_routes')
			);
			$this->assertEquals($expected, $this->User->getTablesByField('test', 'pass'));

			// find a table when there is no field.
			$this->assertEquals(array(), $this->User->getTablesByField('test', 'no_such_field'));

			// no field passed
			$this->assertFalse($this->User->getTablesByField('test'));
		}

		public function testGetList() {
			$expected = array(
				7 => 'Home Page',
				8 => 'Pages',
				9 => 'Admin Home',
				11 => 'Management Home',
				12 => 'Blog Home - Backend',
				13 => 'Blog Home - Frontend',
				14 => 'Cms Home - Backend',
				15 => 'Cms Home - Frontend',
				16 => 'Newsletter Home - Backend',
				18 => 'Blog Test'
			);
			$this->assertEquals($expected, $this->User->getList('Management', 'Route'));
			$this->assertEquals($expected, $this->User->getList(null, 'CoreRoute'));

			// conditions
			$this->assertEqual(array(7 => 'Home Page'), $this->User->getList('Management', 'Route', null, array('Route.id' => 7)));

			// custom find method
			$this->assertEqual($expected, ClassRegistry::init('RouteTest1')->getList(null, null, 'someMethod'));
			$this->assertEqual(array(11 => 'Management Home'), $this->User->getList('Management', 'Route', 'someMethod', array('Route.id' => 11)));

			// _getList method tests
			$this->assertEquals($expected, ClassRegistry::init('RouteTest2')->getList());
			$this->assertEquals(array(), ClassRegistry::init('RouteTest2')->getList(null, null, null, array('RouteTest2.id' => 999)));
		}
	}