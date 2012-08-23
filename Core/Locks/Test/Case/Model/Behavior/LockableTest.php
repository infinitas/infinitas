<?php
/**
 * @brief model for testing
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

		'plugin.locks.global_lock'
	);

	public function startTest() {
		$this->LockableContent = new LockableContent();
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

		$this->LockableContent->create();
		$this->LockableContent->save($content);

		$new = $this->LockableContent->find('first', array('conditions' => array('LockableContent.id' => $this->LockableContent->id)));
		//$this->assertEqual(0, $new['LockableContent']['locked']);
	}

	public function endTest() {
		unset($this->LockableContent);
		ClassRegistry::flush();
	}
}