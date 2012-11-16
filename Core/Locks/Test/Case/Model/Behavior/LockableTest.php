<?php
/**
 * model for testing
 */
class LockableContent extends CakeTestModel{
	public $useDbConfig = 'test';

	public $useTable = 'cms_contents';

	public $belongsTo = array();

	public $hasOne = array();

	public $actsAs = array(
		'Locks.Lockable'
	);

	public function fullModelName() {
		return 'Testing.LockableContent';
	}
}

class LockableTestCase extends CakeTestCase {
	public $fixtures = array(
		'plugin.cms.cms_content',
		'plugin.contents.global_category',
		'plugin.management.ticket',
		'plugin.users.user',
		'plugin.users.group',

		'plugin.locks.lock'
	);
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->LockableContent = new LockableContent();
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->LockableContent);
	}

	public function testLocking() {
		$content = $this->LockableContent->find('first');
		unset($content['LockableContent']['id']);
		unset($content['LockableContent']['locked']);
		unset($content['LockableContent']['locked_since']);
		unset($content['LockableContent']['locked_by']);
		unset($content['LockableContent']['views']);
		unset($content['LockableContent']['created']);
		unset($content['LockableContent']['modified']);

		$content['LockableContent']['created_by'] = 'bob';

		$this->LockableContent->create();
		$this->LockableContent->save($content);

		$new = $this->LockableContent->find('first', array('conditions' => array('LockableContent.id' => $this->LockableContent->id)));
		//$this->assertEqual(0, $new['LockableContent']['locked']);
	}

}