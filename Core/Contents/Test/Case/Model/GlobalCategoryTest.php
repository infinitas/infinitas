<?php
	/* Category Test cases generated on: 2010-08-16 23:08:27 : 1281999567*/
	App::uses('GlobalCategory', 'Contents.Model');

	class GlobalCategoryTest extends CakeTestCase {
		var $fixtures = array(
			'plugin.configs.config',
			'plugin.view_counter.view_count',
			'plugin.contents.global_category',
			'plugin.users.group',
		);

		function startTest() {
			$this->Category = ClassRegistry::init('Contents.GlobalCategory');
		}

		function endTest() {
			unset($this->Category);
			ClassRegistry::flush();
		}

		function testFindActive(){
			$result = $this->Category->find('count');
			$expected = 4;
			$this->assertEquals($expected, $result);

			$result = $this->Category->getActiveIds();
			$expected = array(1 => 1, 2 => 2, 3 => 3);
			$this->assertEquals($expected, $result);
		}
	}