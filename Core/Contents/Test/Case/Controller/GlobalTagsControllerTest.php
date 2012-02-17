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
 * @subpackage	plugins.tags.tests.cases.controllers
 */

App::uses('GlobalTagsController', 'Contents.Controller');
App::uses('GlobalTag', 'Contents.Model');

class TestTagsController extends GlobalTagsController {

	public $uses = array('Contents.GlobalTag');

	public $components = array('Paginator');
/**
 * Auto render
 *
 * @var boolean
 * @access public
 */
	public $autoRender = false;

/**
 * Redirect URL
 *
 * @var mixed
 * @access public
 */
	public $redirectUrl = null;

/**
 * Override controller method for testing
 */
	public function beforeRedirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
		return false;
	}

/**
 * Override controller method for testing
 */
	public function render($action = null, $layout = null, $file = null) {
		$this->renderedView = $action;
	}
}

class GlobalTagsControllerTest extends CakeTestCase {

/**
 *
 */
	public $fixtures = array(
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag',
		'plugin.configs.config');
/**
 * Tags Controller Instance
 *
 * @return void
 * @access public
 */
	public $Tags = null;

/**
 * startTest
 *
 * @return void
 * @access public
 */
	public function startTest() {
		$this->GlobalTags = new TestTagsController(new CakeRequest('controller_posts/admin_index'));
		$this->GlobalTags->params = array(
			'named' => array(),
			'url' => array());
		$this->GlobalTags->constructClasses();
	}

/**
 * endTest
 *
 * @return void
 * @access public
 */
	public function endTest() {
		unset($this->GlobalTags);
	}

/**
 * testTagsControllerInstance
 *
 * @return void
 * @access public
 */
	public function testTagsControllerInstance() {
		$this->assertTrue(is_a($this->GlobalTags, 'GlobalTagsController'));
	}

/**
 * testAdminIndex
 *
 * @return void
 * @access public
 */
	public function testAdminIndex() {
		$this->GlobalTags->admin_index();
		$this->assertTrue(!empty($this->GlobalTags->viewVars['tags']));
	}

/**
 * testAdminAdd
 *
 * @return void
 * @access public
 */
	public function testAdminAdd() {
		$this->GlobalTags->request->data = array(
			'Tag' => array(
				'tags' => 'tag1, tag2, tag3'));
		$this->GlobalTags->admin_add();
		$this->assertEqual($this->GlobalTags->redirectUrl, array('action' => 'index'));
	}

/**
 * testAdminEdit
 *
 * @return void
 * @access public
 */
	public function testAdminEdit() {
		$this->GlobalTags->admin_edit(1);
		$tag = array(
			'GlobalTag' => array(
				'id'  => '1',
				'identifier'  => null,
				'name'  => 'CakePHP',
				'keyname'  => 'cakephp',
				'weight' => '2',
				'created'  => '2008-06-02 18:18:11',
				'modified'  => '2008-06-02 18:18:37',
				'tags' => null
		));
		$this->assertEqual($this->GlobalTags->request->data, $tag);

		$this->GlobalTags->request->data = array(
			'GlobalTag' => array(
				'id' => 1,
				'name' => 'CAKEPHP'));
		$this->GlobalTags->admin_edit(1);
		$this->assertEqual($this->GlobalTags->redirectUrl, array('action' => 'index'));
	}
}
?>