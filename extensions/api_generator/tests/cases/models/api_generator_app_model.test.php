<?php
/**
 * ApiGenerator AppModel test
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
App::import('Model', 'ApiGenerator.AppModel');

class ApiGeneratorAppTestModel extends ApiGeneratorAppModel {
	public $name = 'ApiGeneratorAppTestModel';
	public $useTable = false;
	
}

class ApiGeneratorAppModelTestCase extends CakeTestCase {
/**
 * startTest
 *
 * @return void
 **/
	function startTest() {
		$this->Model = ClassRegistry::init('ApiGeneratorAppTestModel');
	}
/**
 * test slugPath
 *
 * @return void
 **/
	function testSlugPath() {
		Configure::write('ApiGenerator.filePath', '/this/is/');
		$result = $this->Model->slugPath('/this/is/a/path/to_my/file.php');
		$expected = 'a-path-to_my-file-php';
		$this->assertEqual($result, $expected);
		
		Configure::write('ApiGenerator.filePath', '/this/is/');
		$result = $this->Model->slugPath('/this/is/a/path/to_my/f i le.php');
		$expected = 'a-path-to_my-f-i-le-php';
		$this->assertEqual($result, $expected);
		
		Configure::write('ApiGenerator.filePath', 'C:\www');
		$result = $this->Model->slugPath('C:\www\my Path\is Very Windows\file.php');
		$expected = 'my-path-is-very-windows-file-php';
		$this->assertEqual($result, $expected);
		
		Configure::write('ApiGenerator.filePath', 'C:\www');
		$result = $this->Model->slugPath('C:\www\my Path\is Very Windows\file.php', false);
		$expected = 'c-www-my-path-is-very-windows-file-php';
		$this->assertEqual($result, $expected);
	}
/**
 * endTest
 *
 * @return void
 **/
	function endTest() {
		unset($this->Model);
	}
}
?>