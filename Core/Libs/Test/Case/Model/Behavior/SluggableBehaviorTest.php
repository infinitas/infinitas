<?php
	App::uses('SluggableBehavior', 'Libs.Model/Behavior');
	App::uses('GlobalContent', 'Contents.Model');
	
	class GlobalContentTest extends Model {
		public $useTable = 'global_contents';
		
		public $alias = 'GlobalContent';
		
		public $actsAs = array(
			'Libs.Sluggable' => array(
				'label' => 'title'
			)
		);
		
		public $belongsTo = array();
		
		public $hasMany = array();
	}

	/**
	 * SluggableBehavior Test Case
	 *
	 */
	class SluggableBehaviorTestCase extends CakeTestCase {
		public $fixtures = array(
			'plugin.contents.global_content'
		);
		
		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			
			$this->GlobalContent = ClassRegistry::init('GlobalContentTest');
			
			$this->Sluggable = new SluggableBehavior();
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			unset($this->Sluggable, $this->GlobalContent);

			parent::tearDown();
		}
		
		public function testSluggin() {
			$data = array('title' => 'abc 123');
			$this->assertTrue((bool)$this->GlobalContent->save(array('GlobalContent' => $data)));
			$id = $this->GlobalContent->id;
			
			$expected = array('GlobalContent' => array('title' => 'abc 123', 'slug' => 'abc-123'));
			$result = $this->GlobalContent->find('first', array('fields' => array('title', 'slug'), 'conditions' => array('id' => $id)));
			$this->assertEqual($result, $expected);
			
			$data['id'] = $id;
			$data['slug'] = 'custom';
			$this->assertTrue((bool)$this->GlobalContent->save(array('GlobalContent' => $data)));
			
			$expected = array('GlobalContent' => array('title' => 'abc 123', 'slug' => 'custom'));
			$result = $this->GlobalContent->find('first', array('fields' => array('title', 'slug'), 'conditions' => array('id' => $id)));
			$this->assertEqual($result, $expected);
			
			$data['id'] = $id;
			$data['slug'] = '';
			$this->assertTrue((bool)$this->GlobalContent->save(array('GlobalContent' => $data)));
			
			$expected = array('GlobalContent' => array('title' => 'abc 123', 'slug' => 'abc-123'));
			$result = $this->GlobalContent->find('first', array('fields' => array('title', 'slug'), 'conditions' => array('id' => $id)));
			$this->assertEqual($result, $expected);
		}
	}
