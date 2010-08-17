<?php
	/* Category Test cases generated on: 2010-08-16 23:08:27 : 1281999567*/
	App::import('Model', 'Categories.Category');

	/**
	 *
	 *
	 */
	class CategoryTest extends Category{
		var $useDbConfig = 'test';
		var $belongsTo = array();
	}

	class CategoryTestCase extends CakeTestCase {
		var $fixtures = array(
			'plugin.categories.global_category',
		);

		function startTest() {
			$this->Category =& new CategoryTest();
		}

		function testFindActive(){
			$this->assertEqual(4, $this->Category->find('count'));

			$expected = array(1 => 1, 2 => 2, 3 => 3);
			$this->assertEqual($expected, $this->Category->getActiveIds());
		}

		function endTest() {
			unset($this->Category);
			ClassRegistry::flush();
		}
	}