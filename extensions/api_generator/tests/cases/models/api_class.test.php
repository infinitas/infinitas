<?php
/**
 * ApiClass test case
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.tests.models
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::import('Model', 'ApiGenerator.ApiClass');
App::import('Vendor', 'ApiGenerator.ClassDocumentor');
/**
 * ApiClassSampleClass doc block
 *
 * @package default
 */
class ApiClassSampleClass {
/**
 * foo property
 *
 * @var string
 **/
	public $foo = '';
/**
 * Test Function in Sample class
 *
 * @param string $one First parameter
 * @param string $two Second parameter
 * @return boolean
 **/
	public function testFunct($one, $two) {

	}
/**
 * non-extended method
 *
 * @return boolean
 **/
	public function extended() {

	}
}
/**
 * ApiClassSampleClass doc block
 *
 * @package default
 */
class ApiClassSampleClassChild extends ApiClassSampleClass {
/**
 * onlyMe
 *
 * @var string
 **/
	public $onlyMe;
/**
 * primary function
 *
 * @return void
 **/
	public function primary() {

	}
/**
 * extended-method this time
 *
 * @return void
 **/
	public function extended() {

	}
}

/**
 * ApiClassTestCase
 *
 * @package api_generator.tests
 **/
class ApiClassTestCase extends CakeTestCase {
/**
 * fixtures to use
 *
 * @var string
 **/
	var $fixtures = array('plugin.api_generator.api_class', 'plugin.api_generator.api_package');
/**
 * startTest
 *
 * @return void
 **/
	function startTest() {
		$this->_path = APP . 'plugins' . DS . 'api_generator';
		$this->_testAppPath = dirname(dirname(dirname(__FILE__))) . DS . 'test_app' . DS;

		Configure::write('ApiGenerator.filePath', $this->_path);
		$this->ApiClass = ClassRegistry::init('ApiClass');
	}
/**
 * endTest
 *
 * @return void
 **/
	function endTest() {
		unset($this->ApiClass);
	}
/**
 * Test Saving of the class docs to the db.
 *
 * @return void
 **/
	function testSaveClassDocs() {
		$docs = new ClassDocumentor('ApiClassSampleClass');

		$result = $this->ApiClass->saveClassDocs($docs);
		$this->assertTrue($result);

		$result = $this->ApiClass->read();
		$now = date('Y-m-d H:i:s');
		$expected = array(
			'ApiClass' => array(
				'id' => $this->ApiClass->id,
				'api_package_id' => null,
				'name' => 'ApiClassSampleClass',
				'slug' => 'api-class-sample-class',
				'file_name' => __FILE__,
				'property_index' => 'foo',
				'method_index' => 'testfunct extended',
				'flags' => ApiClass::CONCRETE_CLASS,
				'coverage_cache' => null,
				'created' => $now,
				'modified' => $now,
			)
		);
		$this->assertEqual($result['ApiClass'], $expected['ApiClass']);

		$docs = new ClassDocumentor('ApiClassSampleClassChild');
		$result = $this->ApiClass->saveClassDocs($docs);
		$this->assertTrue($result);
		$result = $this->ApiClass->read();
		$now = date('Y-m-d H:i:s');
		$expected = array(
			'ApiClass' => array(
				'id' => $this->ApiClass->id,
				'api_package_id' => null,
				'name' => 'ApiClassSampleClassChild',
				'slug' => 'api-class-sample-class-child',
				'file_name' => __FILE__,
				'property_index' => 'onlyme',
				'method_index' => 'primary extended',
				'flags' => ApiClass::CONCRETE_CLASS,
				'coverage_cache' => null,
				'created' => $now,
				'modified' => $now,
			)
		);
		$this->assertEqual($result['ApiClass'], $expected['ApiClass']);
	}
/**
 * test Saving of pseudo classes
 *
 * @return void
 **/
	function testSavePseudoClassDocs() {
		$file = CAKE_CORE_INCLUDE_PATH . DS . CAKE . 'basics.php';
		$ApiFile = ClassRegistry::init('ApiGenerator.ApiFile');
		$docs = $ApiFile->loadFile($file);

		$result = $this->ApiClass->savePseudoClassDocs($docs['function'], $file);
		$this->assertTrue($result);
	}
/**
 * test the search implementation
 *
 * @return void
 **/
	function testSearch() {
		//test match by name
		$result = $this->ApiClass->search('Dispatcher');
		$this->assertEqual(count($result), 2);
		$this->assertEqual(array_keys($result), array('Dispatcher', 'ShellDispatcher'));

		//test by partial slug
		$result = $this->ApiClass->search('acl-com');
		$this->assertEqual(count($result), 1);
		$this->assertEqual(array_keys($result), array('AclComponent'));

		//test by partial property match
		$result = $this->ApiClass->search('lidexten');
		$this->assertEqual(count($result), 1);
		$this->assertEqual(array_keys($result), array('Router'));

		//test by partial method match
		$result = $this->ApiClass->search('missing');
		$this->assertEqual(count($result), 1);
		$this->assertEqual(array_keys($result), array('ErrorHandler'));

		//test relevance in find
		$result = $this->ApiClass->search('acl');
		$this->assertEqual(count($result), 4);
		$this->assertEqual(array_keys($result), array('AclComponent', 'DbAcl', 'AclBase', 'IniAcl'));

		//test searching of global functions
		$result = $this->ApiClass->search('debug');
		$this->assertEqual(count($result), 1);
		$this->assertEqual(array_keys($result), array('debug'));

		$this->assertTrue($result['debug']['function']['debug'] instanceof FunctionDocumentor);
	}
/**
 * Test that files containing global functions always have the declaredInFile set to the file_name
 * in the index
 *
 * @return void
 **/
	function testSearchingForGlobalFilesInIndex() {
		$this->ApiClass->id = '498cee77-97c8-441a-99c3-80ed87460ad7';
		$this->ApiClass->saveField('file_name', $this->_testAppPath . 'basics.php');
		$result = $this->ApiClass->search('debug');
		$filename = $result['debug']['function']['debug']->info['declaredInFile'];
		$this->assertNoPattern('/test_basics/', $filename);
	}
/**
 * test that after clearing index there are no entries left
 *
 * @return void
 **/
	function testClearIndex() {
		$this->ApiClass->clearIndex();
		$result = $this->ApiClass->find('all');
		$this->assertFalse($result);
	}
/**
 * Test that getting the class index works as expected and that the PSEUDO_CLASS flag works
 *
 * @return void
 **/
	function testGetClassIndex() {
		$results = $this->ApiClass->getClassIndex();
		$this->assertEqual(count($results), 8);
		$this->assertFalse(in_array('basics.php', $results));

		$results = $this->ApiClass->getClassIndex(true);
		$this->assertEqual(count($results), 9);
		$this->assertTrue(in_array('basics.php', $results));
	}
/**
 * Test that DocBlockAnalyzer is correctly invoked
 *
 * @return void
 **/
	function testAnalyzeCoverage() {
		//dispatcher class
		$apiClass = $this->ApiClass->read(null, '498cee77-ddbc-4f12-b457-80ed87460ad7');
		$result = $this->ApiClass->analyzeCoverage($apiClass);

		$this->assertTrue(isset($result['sectionTotals']['properties']));
		$this->assertTrue(isset($result['sectionTotals']['methods']));
		$this->assertTrue(isset($result['sectionTotals']['classInfo']));

		$this->ApiClass->cacheQueries = false;
		$apiClass = $this->ApiClass->read(null, '498cee77-ddbc-4f12-b457-80ed87460ad7');
		$this->assertTrue(is_numeric($apiClass['ApiClass']['coverage_cache']));

		// try with pseudo class file.
		$this->expectException();
		$apiClass = $this->ApiClass->read(null, '498cee77-97c8-441a-99c3-80ed87460ad7');
		$result = $this->ApiClass->analyzeCoverage($apiClass);
		$this->assertFalse($result);
	}
}
