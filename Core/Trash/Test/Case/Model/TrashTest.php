<?php
App::uses('Trash', 'Trash.Model');

/**
 * Trash Test Case
 *
 */
class TrashTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.trash.trash',
		'plugin.users.user',
		'plugin.users.group'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Trash = ClassRegistry::init('Trash.Trash');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Trash);

		parent::tearDown();
	}

/**
 * testRestore method
 *
 * @return void
 */
	public function testRestore() {
	}

}
