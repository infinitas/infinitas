<?php
App::uses('GlobalCategory', 'Contents.Model');

class GlobalCategoryTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.contents.global_content',
		'plugin.contents.global_layout',
		'plugin.contents.global_category',
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag',

		'plugin.configs.config',
		'plugin.view_counter.view_counter_view',
		'plugin.users.user',
		'plugin.users.group',
		'plugin.locks.global_lock',
		'plugin.management.ticket'
	);

/**
 * @brief start test
 */
	public function startTest() {
		$this->Category = ClassRegistry::init('Contents.GlobalCategory');
	}

/**
 * @brief end test
 */
	public function endTest() {
		unset($this->Category);
		ClassRegistry::flush();
	}

/**
 * @brief test finding active categories
 */
	public function testFindActive() {
		$result = $this->Category->find('count');
		$expected = 4;
		$this->assertEquals($expected, $result);

		$result = $this->Category->getActiveIds();
		$expected = array(1 => 1, 2 => 2, 3 => 3);
		$this->assertEquals($expected, $result);
	}
}