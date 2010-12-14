<?php
	/* Category Test cases generated on: 2010-08-16 23:08:27 : 1281999567*/
	App::import('Model', 'Categories.Category');

	class CategoryTestCase extends CakeTestCase {
		var $fixtures = array(
			'plugin.configs.config',
			'plugin.view_counter.view_count',
			
			'plugin.categories.category',
			'plugin.users.group',
		);

		function startTest() {
			$this->Category = ClassRegistry::init('Categories.Category');
		}

		function endTest() {
			unset($this->Category);
			ClassRegistry::flush();
		}

		function testFindActive(){
			$this->assertEqual(4, $this->Category->find('count'));

			$expected = array(1 => 1, 2 => 2, 3 => 3);
			$this->assertEqual($expected, $this->Category->getActiveIds());
		}
	}