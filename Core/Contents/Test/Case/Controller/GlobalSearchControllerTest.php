<?php
App::uses('GlobalSearchController', 'Contents.Controller');

/**
 * GlobalSearchController Test Case
 *
 */
class GlobalSearchControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.contents.global_content',
		'plugin.contents.global_layout',
		'plugin.contents.global_category',
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag',

		'plugin.themes.theme',
		'plugin.users.group',
		'plugin.view_counter.view_counter_view',
		'plugin.users.user',
	);

/**
 * testSearch method
 *
 * @return void
 */
	public function testSearch() {
	}

}
