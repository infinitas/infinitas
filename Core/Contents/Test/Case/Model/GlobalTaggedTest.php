<?php
/**
 * CakePHP Tags Plugin
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *						1785 E. Sahara Avenue, Suite 490-423
 *						Las Vegas, Nevada 89104
 *
 *
 *
 *
 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
 * @link	  http://github.com/CakeDC/Tags
 * @package   plugins.tags
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 */

App::uses('GlobalTagged', 'Contents.Model');

class GlobalTaggedTest extends CakeTestCase {
/**
 *
 */
	public $fixtures = array(
		'plugin.installer.plugin'
	);

/**
 *
 *
 * @return void
 */
	public function setUp() {
		$this->skipif (true);
		parent::setUp();
		$this->GlobalTagged = ClassRegistry::init('Contents.GlobalTagged');
	}

/**
 *
 *
 * @return void
 */
	public function tearDown() {
		parent::teardown();
		unset($this->GlobalTagged);
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

		$expected = array(
			'GlobalTagged' => array(
				'id' => '49357f3f-c464-461f-86ac-a85d4a35e6b6',
				'foreign_key' => 1,
				'tag_id' => 1, //cakephp
				'model' => 'BlogPost',
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
			'model' => 'BlogPost'));
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
		return; // @todo fix-tests
		$result = $this->GlobalTagged->find('tagged', array(
			'by' => 'cakephp',
			'model' => 'Blog.BlogPost'));
		$this->assertCount(1, $result);
		$this->assertEquals(1, $result[0]['BlogPost']['id']);

		$result = $this->GlobalTagged->find('tagged', array(
			'model' => 'BlogPost'));
		$this->assertCount(2, $result);

		// Test call to paginateCount by Controller::pagination()
		$result = $this->GlobalTagged->paginateCount(array(), 1, array(
			'model' => 'BlogPost',
			'type' => 'tagged'));
		$this->assertEquals(2, $result);
	}
}