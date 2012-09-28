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
		'plugin.contents.theme',
		'plugin.contents.global_category',
		'plugin.contents.group',
		'plugin.contents.view_counter_view',
		'plugin.contents.user',
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag'
	);

/**
 * testSearch method
 *
 * @return void
 */
	public function testSearch() {
	}

}
