<?php
/**
 * CakePHP Tags Plugin
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *						1785 E. Sahara Avenue, Suite 490-423
 *						Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
 * @link	  http://github.com/CakeDC/Tags
 * @package   plugins.tags
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * @package		plugins.tags
 * @subpackage	plugins.tags.tests.cases.models
 */

App::uses('GlobalTag', 'Contents.Model');

class TestTag extends GlobalTag {
	public $useDbConfig = 'test';
	public $name = 'GlobalTag';
	public $cacheSources = false;
	public $hasAndBelongsToMany = array();
	public $belongsTo = array();
	public $hasOne = array();
	public $hasMany = array();
}

class GlobalTagTestCase extends CakeTestCase {
/**
 * Tag Instance
 *
 * @var instance
 * @access public
 */
	public $GlobalTag = null;

/**
 * startTest
 *
 * @var array
 * @access public
 */
	public $fixtures = array(
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag');

/**
 * startTest
 *
 * @return void
 * @access public
 */
	public function startTest() {
		$this->GlobalTag = new TestTag();
	}

/**
 * endTest
 *
 * @return void
 * @access public
 */
	public function endTest() {
		unset($this->GlobalTag);
	}

/**
 * testTagInstance
 *
 * @return void
 * @access public
 */
	public function testTagInstance() {
		$this->assertInstanceOf('GlobalTag', $this->GlobalTag);
	}

/**
 * testTagFind
 *
 * @return void
 * @access public
 */
	public function testTagFind() {
		$this->GlobalTag->recursive = -1;
		$results = $this->GlobalTag->find('first');
		$this->assertTrue(!empty($results));

		$expected = array(
			'GlobalTag' => array(
				'id'  => '1',
				'identifier'  => null,
				'name'  => 'CakePHP',
				'keyname'  => 'cakephp',
				'weight' => '2',
				'created'  => '2008-06-02 18:18:11',
				'modified'  => '2008-06-02 18:18:37',
				'description' => 'nice'));
		$this->assertEqual($results, $expected);
	}

/**
 * testView
 *
 * @return void
 * @access public
 */
	public function testView() {
		$result = $this->GlobalTag->getViewData('cakephp');
		$this->assertTrue(is_array($result));
		$this->assertEqual($result['GlobalTag']['keyname'], 'cakephp');

		$result = $this->GlobalTag->getViewData('invalid-key!!!');
		$this->assertTrue(empty($result));
	}

//methods below are not found(removed?) in infinitas, tests derive from tags plugin

/**
 * testAdd
 *
 * @return void
 * @access public
 */
	public function testAdd() {
//		$result = $this->GlobalTag->add(array(
//			'Tag' => array('tags' => 'tag1, tag2, tag3')
//			));
//		$this->assertTrue($result);
//		$result = $this->GlobalTag->find('all', array(
//			'recursive' => -1,
//			'fields' => array(
//				'GlobalTag.name')));
//		$result = Set::extract($result, '{n}.GlobalTag.name');
//		$this->assertTrue(in_array('tag1', $result));
//		$this->assertTrue(in_array('tag2', $result));
//		$this->assertTrue(in_array('tag3', $result));
	}

/**
 * testAdd
 *
 * @return void
 * @access public
 */
	public function testEdit() {
//		$this->assertNull($this->GlobalTag->edit(1));
//		$this->assertEqual($this->GlobalTag->data['Tag']['id'], 1);
//
//		$data = array(
//			'Tag' => array(
//				'id' => 1,
//				'name' => 'CAKEPHP'));
//		$this->assertTrue($this->GlobalTag->edit(1, $data));
//
//		$data = array(
//			'Tag' => array(
//				'id' => 1,
//				'name' => 'CAKEPHP111'));
//		$this->assertFalse($this->GlobalTag->edit(1, $data));
//
//		$data = array(
//			'Tag' => array(
//				'id' => 1,
//				'name' => 'CAKEPHP',
//				'keyname' => ''));
//		$this->assertEqual($this->GlobalTag->edit(1, $data), $data);
//
//		$this->expectException('Exception');
//		$this->assertTrue($this->GlobalTag->edit('invalid-id', array()));
	}

}

?>