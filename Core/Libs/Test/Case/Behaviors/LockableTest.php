	<?php
	App::import('Model', 'Cms.Content');
	class LockableContent extends CakeTestModel{
		public $useDbConfig = 'test';
		public $useTable = 'cms_content';
		public $belongsTo = array();
		public $hasOne = array();
		public $actsAs = array(
			'Libs.Lockable'
		);
	}

	class LockableTestCase extends CakeTestCase {

		//need something to set with
		var $fixtures = array(
			'plugin.categories.global_category',
			'plugin.cms.content',
			'plugin.management.ticket',
			'plugin.management.group'
		);

		function startTest() {
			$this->LockableContent = new LockableContent();
		}

		function testLocking(){
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

		function endTest() {
			unset($this->Infinitas);
			ClassRegistry::flush();
		}
	}