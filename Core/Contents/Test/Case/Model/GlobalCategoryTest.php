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
		'plugin.locks.lock',
		'plugin.management.ticket'
	);
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Category = ClassRegistry::init('Contents.GlobalCategory');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Category);
	}

/**
 * test finding active categories
 */
	public function testFindActive() {
		$result = $this->Category->find('count');
		$expected = 4;
		$this->assertEquals($expected, $result);

		$result = $this->Category->getActiveIds();
		$expected = array(
			'category-1' => 'category-1',
			'category-2' => 'category-2',
			'category-2a' => 'category-2a'
		);
		$this->assertEquals($expected, $result);
	}
	
}