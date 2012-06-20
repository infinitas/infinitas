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

App::uses('GlobalTagged', 'Contents.Model');

class GlobalTaggedTest extends CakeTestCase {
/**
 *
 */
	public $GlobalTagged = null;

/**
 *
 */
	public $fixtures = array(
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag',
		'plugin.contents.article',

		'plugin.contents.global_content',
		'plugin.installer.plugin'
	);

/**
 *
 *
 * @return void
 * @access public
 */
	public function startTest() {
		$this->GlobalTagged = ClassRegistry::init('Contents.GlobalTagged');
	}

/**
 *
 *
 * @return void
 * @access public
 */
	public function endTest() {
		unset($this->GlobalTagged);
		ClassRegistry::flush();
	}

/**
 *
 */
	function testTaggedInstance() {
		$this->assertInstanceOf('GlobalTagged', $this->GlobalTagged);
	}

/**
 *
 */
	function testTaggedFind() {
		$this->GlobalTagged->recursive = -1;
		$result = $this->GlobalTagged->find('first');
		$this->assertNotEmpty($result);

		$expected = array(
			'GlobalTagged' => array(
				'id' => '49357f3f-c464-461f-86ac-a85d4a35e6b6',
				'foreign_key' => 1,
				'tag_id' => 1, //cakephp
				'model' => 'Article',
				'language' => 'eng',
				'created' => '2008-12-02 12:32:31',
				'modified' => '2008-12-02 12:32:31'));

		$this->assertEquals($expected, $result);
	}

/**
 *
 */
	public function testFindCloud() {
		$result = $this->GlobalTagged->find('cloud', array(
			'model' => 'Article'));
		$this->assertCount(3, $result);
		$this->assertTrue(isset($result[0][0]['occurrence']));
		$this->assertEquals(1, $result[0][0]['occurrence']);
	}

/**
 * Test custom findTagged method
 *
 * @return void
 */
	public function testFindTagged() {
		$result = $this->GlobalTagged->find('tagged', array(
			'by' => 'cakephp',
			'model' => 'Article'));
		$this->assertCount(1, $result);
		$this->assertEquals(1, $result[0]['Article']['id']);

		$result = $this->GlobalTagged->find('tagged', array(
			'model' => 'Article'));
		$this->assertCount(2, $result);

		// Test call to paginateCount by Controller::pagination()
		$result = $this->GlobalTagged->paginateCount(array(), 1, array(
			'model' => 'Article',
			'type' => 'tagged'));
		$this->assertEquals(2, $result);
	}

}