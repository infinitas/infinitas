<?php
App::uses('CommentableBehavior', 'Comments.Model/Behavior');

class CommentableBehaviorTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
		$this->Commentable = new CommentableBehavior();
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->Commentable);
	}

	public function testSomething() {
	}
}