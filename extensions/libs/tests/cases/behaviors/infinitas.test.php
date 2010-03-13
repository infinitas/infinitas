<?php
/* Infinitas Test cases generated on: 2010-03-13 14:03:31 : 1268484451*/
App::import('Behavior', 'libs.Infinitas');

class InfinitasBehaviorTestCase extends CakeTestCase {

	//need something to set with
	var $fixtures = array(
		//'plugin.libs.config'
	);

	function startTest() {
		$this->Infinitas =& new InfinitasBehavior();

		App::import('AppModel');
		$this->AppModel = new AppModel(array('table' => false));
	}

	function testGetTables(){
		// if this test fails drop all tables and try again.
		//$result = $this->Infinitas->getTables($this->AppModel, 'test');
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

	function endTest() {
		unset($this->Infinitas);
		ClassRegistry::flush();
	}

}
?>