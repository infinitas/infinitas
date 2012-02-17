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
 * @subpackage	plugins.tags.tests.cases.behaviors
 */

App::uses('Model', 'Model');

class Article extends AppModel {
	public $useTable = 'articles';
	public $belongsTo = array();
	public $hasAndBelongsToMany = array();
	public $hasMany = array();
	public $hasOne = array();
	public $actsAs = array('Contents.Taggable', 'Containable');
}

class TaggableBehaviorTest extends CakeTestCase {
/**
 * Plugin name used for fixtures loading
 *
 * @var string
 * @access public
 */
	public $plugin = 'contents';

/**
 * Holds the instance of the model
 *
 * @var mixed $UsersAddon
 * @access public
 */
	public $Article = null;

/**
 * Fixtures associated with this test case
 *
 * @var array
 * @return void
 * @access public
 */
	public $fixtures = array(
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag',
		'plugin.contents.article');

/**
 * Method executed before each test
 *
 * @return void
 * @access public
 */
	public function startTest() {
		$this->Article = ClassRegistry::init('Article');
	}

/**
 * Method executed after each test
 *
 * @return void
 * @access public
 */
	public function endTest() {
		unset($this->Article);
		ClassRegistry::flush();
	}

/**
 * Testings saving of tags trough the specified field in the tagable model
 *
 * @return void
 * @access public
 */
	public function testTagSaving() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array('id' => 1),
			'contain' => array('GlobalTag')
		));
		$this->assertTrue(!empty($result['Article']['tags']));

		$data['tags'] = 'foo, developer, developer, php';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'contain' => array('GlobalTag'),
			'conditions' => array(
				'id' => 1)));
		$this->assertTrue(!empty($result['Article']['tags']));


		$data['tags'] = 'cakephp:foo, developer, cakephp:developer, cakephp:php';
		$this->Article->save($data, false);
		$result = $this->Article->GlobalTag->find('all', array(
			'recursive' => -1,
			'order' => 'GlobalTag.name ASC',
			'conditions' => array(
				'GlobalTag.identifier' => 'cakephp')));
		$result = Set::extract($result, '{n}.GlobalTag.keyname');
		$this->assertEqual($result, array(
			'developer', 'foo', 'php'));


		$this->assertFalse($this->Article->saveTags('foo, bar', null));
		$this->assertFalse($this->Article->saveTags(array('foo', 'bar'), 'something'));
	}

/**
 * Testings Taggable::tagArrayToString()
 *
 * @return void
 * @access public
 */
	public function testTagArrayToString() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'contain' => array('GlobalTag'),
			'conditions' => array(
				'id' => 1)));
		$result = $this->Article->tagArrayToString($result['GlobalTag']);
		$this->assertTrue(!empty($result));
		$this->assertInternalType('string', $result);

		$result = $this->Article->tagArrayToString();
		$this->assertTrue(empty($result));
		$this->assertInternalType('string',  $result);
	}

/**
 * Testings Taggable::multibyteKey()
 *
 * @return void
 * @access public
 */
	public function testMultibyteKey() {
		$result = $this->Article->multibyteKey('this is _ a Nice ! - _ key!');
		$this->assertEqual('thisisanicekey', $result);

		$result = $this->Article->multibyteKey('Äü-Ü_ß');
		$this->assertEqual('äüüß', $result);
	}

/**
 * testAfterFind callback method
 *
 * @return void
 * @access public
 */
	public function testAfterFind() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);

		$result = $this->Article->find('first', array(
			'conditions' => array('id' => 1),
			'contain' => array('GlobalTag')
		));
		$this->assertTrue(isset($result['GlobalTag']));

		$this->Article->Behaviors->Taggable->settings['Article']['unsetInAfterFind'] = true;
		$result = $this->Article->find('first', array(
			'conditions' => array('id' => 1),
			'contain' => array('GlobalTag')
		));
		$this->assertTrue(!isset($result['GlobalTag']));
	}

}

?>