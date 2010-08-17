	<?php
	/* Infinitas Test cases generated on: 2010-03-13 14:03:31 : 1268484451*/
	App::import('Behavior', 'libs.Infinitas');
	App::import('Model', 'Management.route');
	class RouteTest1 extends Route{
		public $useDbConfig = 'test';

		public function someMethod($conditions = array()){
			return $this->find('list', array('conditions' => $conditions));
		}
	}
	class RouteTest2 extends Route{
		public $useDbConfig = 'test';

		function _getList($conditions = array()){
			return $this->find('list', array('conditions' => $conditions));
		}
	}

	class InfinitasBehaviorTestCase extends CakeTestCase {

		//need something to set with
		var $fixtures = array(
			'plugin.management.route',
			'plugin.management.theme'
		);

		function startTest() {
			$this->Infinitas =& new InfinitasBehavior();

			App::import('AppModel');
			$this->AppModel = new AppModel(array('table' => false));
		}

		function testGetJson(){
			// test wrong usage
			$this->expectError();
			$this->assertFalse($this->Infinitas->getJson());
			$this->assertFalse($this->Infinitas->getJson($this->AppModel));

			// bad json
			$this->assertFalse($this->Infinitas->getJson($this->AppModel, ''));
			$this->assertFalse($this->Infinitas->getJson($this->AppModel, 'some text'));
			$this->assertFalse($this->Infinitas->getJson($this->AppModel, array(123 => 'abc')));
			$this->assertFalse($this->Infinitas->getJson($this->AppModel, '{123:"abc"}'));

			// good json
			$this->assertEqual($this->Infinitas->getJson($this->AppModel, '{"123":"abc"}'), array(123 => 'abc'));
			$this->assertEqual($this->Infinitas->getJson($this->AppModel, '{"abc":123}'), array('abc' => 123));

			// nested array
			$this->assertEqual(
				$this->Infinitas->getJson($this->AppModel, '{"abc":{"abc":{"abc":{"abc":{"abc":{"abc":123}}}}}}'),
				array('abc' => array('abc' => array('abc' => array('abc' => array('abc' => array('abc' => 123))))))
			);

			// get object back
			$expected = new stdClass();
			$expected->abc = 123;
			$this->assertEqual($this->Infinitas->getJson($this->AppModel, '{"abc":123}', array('assoc' => false)), $expected);

			//validate bad json
			$this->assertFalse($this->Infinitas->getJson($this->AppModel, 'some text', array(), false));
			$this->assertTrue($this->Infinitas->getJson($this->AppModel, '{"abc":123}', array(), false));

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
			$this->assertEqual($expected, $this->Infinitas->getJsonRecursive($this->AppModel, $data));
			// string passed in
			$this->assertEqual(array(array('abc')), $this->Infinitas->getJsonRecursive($this->AppModel, json_encode(array('abc'))));

			$this->assertFalse($this->Infinitas->validateJson($this->AppModel));
			$this->assertFalse($this->Infinitas->validateJson($this->AppModel, array('array')));
			$this->assertFalse($this->Infinitas->validateJson($this->AppModel, 'string'));
			$this->assertTrue($this->Infinitas->validateJson($this->AppModel, 123));
			$this->assertTrue($this->Infinitas->validateJson($this->AppModel, json_encode(array(1,2,3,4,5,6))));
		}

		function testSingleDimentionArray(){
			//test wrong usage
			$this->assertEqual($this->Infinitas->singleDimentionArray($this->AppModel, ''), array());
			$this->assertEqual($this->Infinitas->singleDimentionArray($this->AppModel, array()), array());

			//normal tests
			$this->assertEqual($this->Infinitas->singleDimentionArray($this->AppModel, array(array('abc' => 123))), array());
			$this->assertEqual($this->Infinitas->singleDimentionArray($this->AppModel, array('one' => array('abc' => 123), 'two' => 2)), array('two' => 2));
		}

		function testGetPlugins(){
			$_allPlugins = Configure::listObjects('plugin');
			$allPlugins = array() + array('' => 'None');
			foreach($_allPlugins as $k => $v){
				$allPlugins[Inflector::underscore($v)] = $v;
			}
			// need to see how to do this
			//$this->assertEqual(count($this->Infinitas->getPlugins($this->AppModel, true)), count($allPlugins));
		}

		function testGettingTableThings(){
			// find all the tables
			$expected = array(
				'core_routes',
				'core_themes'
			);
			$this->assertEqual($expected, $this->Infinitas->getTables($this->AppModel, 'test'));

			$this->expectError();
			$this->Infinitas->getTables($this->AppModel, 'this_is_not_a_connection');

			// find tables with a id field
			$expected = array(
				array('plugin' => 'Management', 'model' => 'Route', 'table' => 'core_routes'),
				array('plugin' => 'Management', 'model' => 'Theme', 'table' => 'core_themes')
			);
			$this->assertEqual($expected, $this->Infinitas->getTablesByField($this->AppModel, 'test', 'id'));

			// find tables with a pass field
			$expected = array(
				array('plugin' => 'Management', 'model' => 'Route', 'table' => 'core_routes')
			);
			$this->assertEqual($expected, $this->Infinitas->getTablesByField($this->AppModel, 'test', 'pass'));

			// find a table when there is no field.
			$this->assertEqual(array(), $this->Infinitas->getTablesByField($this->AppModel, 'test', 'no_such_field'));

			// no field passed
			$this->assertFalse($this->Infinitas->getTablesByField($this->AppModel, 'test'));
		}

		function testGetList(){
			// wrong settings
			$this->assertFalse($this->Infinitas->getList($this->AppModel));

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
			$this->assertEqual($expected, $this->Infinitas->getList($this->AppModel, 'Management', 'Route'));
			$this->assertEqual($expected, $this->Infinitas->getList($this->AppModel, null, 'CoreRoute'));

			// conditions
			$this->assertEqual(array(7 => 'Home Page'), $this->Infinitas->getList($this->AppModel, 'Management', 'Route', null, array('Route.id' => 7)));

			// custom find method
			$this->assertEqual($expected, $this->Infinitas->getList(new RouteTest1(), null, null, 'someMethod'));
			$this->assertEqual(array(11 => 'Management Home'), $this->Infinitas->getList($this->AppModel, 'Management', 'Route', 'someMethod', array('Route.id' => 11)));

			// _getList method tests
			$this->assertEqual($expected, $this->Infinitas->getList(new RouteTest2()));
			$this->assertEqual(array(), $this->Infinitas->getList(new RouteTest2(), null, null, null, array('Route.id' => 999)));
		}

		function endTest() {
			unset($this->Infinitas);
			ClassRegistry::flush();
		}
	}