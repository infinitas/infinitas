<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('CommentsComponent', 'Comments.Controller/Component');

/**
 * CommentsComponent Test Case
 *
 */
class CommentsComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Comments = new CommentsComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Comments);

		parent::tearDown();
	}

/**
 * testActionComment method
 *
 * @return void
 */
	public function testActionComment() {
	}

}
