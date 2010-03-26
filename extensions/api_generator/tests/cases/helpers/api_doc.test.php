<?php
/**
 * Api Doc Helper Test
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
 * @subpackage    api_generator.tests.helpers
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::import('Core', array('View', 'Controller'));
App::import('Helper', array('ApiGenerator.ApiDoc', 'Html'));

/**
* ApiDocHelper test case
*/
class ApiDocHelperTestCase extends CakeTestCase {
/**
 * startTest
 *
 * @return void
 **/
	function startTest() {
		$this->_pluginPath = dirname(dirname(dirname(dirname(__FILE__))));
		$controller = new Controller();
		$view = new View($controller);
		$view->set('basePath', $this->_pluginPath);
		$this->ApiDoc = new ApiDocHelper();
		$this->ApiDoc->Html = new HtmlHelper();
		
		Router::reload();
		Router::parse('/');
	}
/**
 * test inBasePath
 *
 * @return void
 **/
	function testInBasePath() {
		$this->assertFalse($this->ApiDoc->inBasePath('/some/random/path'));
		$this->assertTrue($this->ApiDoc->inBasePath(__FILE__));
	}
/**
 * test fileNameTrimming
 *
 * @return void
 **/
	function testTrimFileName() {
		$result = $this->ApiDoc->trimFileName($this->_pluginPath . '/tests/cases/helpers/api_doc.test.php');
		$this->assertEqual($result, 'tests/cases/helpers/api_doc.test.php');

		$result = $this->ApiDoc->trimFileName('/some/other/path/tests/cases/helpers/api_doc.test.php');
		$expected = 'tests/cases/helpers/api_doc.test.php';
		$this->assertEqual($result, $expected, 'Trim path with different bases is not working %s');
	}
/**
 * testFileLink
 *
 * Test file link / no link based on base path of file.
 *
 * @return void
 **/
	function testFileLink() {
		$result = $this->ApiDoc->fileLink('/foo/bar');
		$this->assertEqual($result, '/foo/bar');

		$testFile = $this->_pluginPath . '/views/helpers/api_doc.php';

		$result = $this->ApiDoc->fileLink($testFile);
		$expected = array(
			'a' => array('href' => '/api_generator/api_files/view_file/views/helpers/api_doc.php'),
			'views/helpers/api_doc.php',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->ApiDoc->fileLink($testFile, array('controller' => 'foo', 'action' => 'bar'));
		$expected = array(
			'a' => array('href' => '/api_generator/foo/bar/views/helpers/api_doc.php'),
			'views/helpers/api_doc.php',
			'/a'
		);
		$this->assertTags($result, $expected);
	}
/**
 * test generation of package links.
 *
 * @return void
 **/
	function testPackageLink() {
		$result = $this->ApiDoc->packageLink('foo');
		$expected = array(
			'a' => array('href' => '/api_generator/api_packages/view/foo'),
			'foo',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->ApiDoc->packageLink('some.package.deep');
		$expected = array(
			'a' => array('href' => '/api_generator/api_packages/view/deep'),
			'some.package.deep',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->ApiDoc->packageLink('  some.package.deep');
		$expected = array(
			'a' => array('href' => '/api_generator/api_packages/view/deep'),
			'  some.package.deep',
			'/a'
		);
		$this->assertTags($result, $expected);
	}
/**
 * test that parsing method links works.
 *
 * @return void
 **/
	function testParsingMethodLinks() {
		$this->ApiDoc->setClassIndex(array('JsHelper', 'Model'));
		$text = 'This is some JsHelper::method() more text here.';
		$expected = 'This is some <a href="/api_generator/api_classes/view_class/js-helper#method-JsHelpermethod">JsHelper::method()</a> more text here.';
		$result = $this->ApiDoc->parseText($text);
		$this->assertEqual($result, $expected);
	}
/**
 * endTest
 *
 * @return void
 **/
	function endTest() {
		ClassRegistry::flush();
		unset($this->ApiDoc);
	}
}
