<?php
App::uses('Router', 'Routing');
class ContentsEventsTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.configs.config',
		'plugin.themes.theme',
		'plugin.routes.route',
		'plugin.view_counter.view_counter_view',

		'plugin.contents.global_category',
		'plugin.users.user',
		'plugin.users.group',
		'plugin.locks.global_lock',
	);

/**
 * @brief start test
 */
	public function startTest() {
		$this->Event = EventCore::getInstance();
	}

/**
 * @brief end test
 */
	public function endTest() {
		unset($this->Event);
		ClassRegistry::flush();
	}

/**
 * @brief test generating a site map
 */
	public function testGenerateSiteMapData() {
		$this->assertInstanceOf('EventCore', $this->Event);

		$result = $this->Event->trigger(new Object(), 'Contents.siteMapRebuild');
		$expected = array('siteMapRebuild' => array('Contents' => array(
			array(
				'url' => Router::url('/', true) . 'contents/categories',
				'last_modified' => '2010-08-16 23:56:50',
				'change_frequency' => 'monthly'
			),
			array(
				'url' => Router::url('/', true) . 'contents/categories/view/slug:category-1',
				'last_modified' => '2010-08-16 23:56:50',
				'change_frequency' => 'monthly'
			),
			array(
				'url' => Router::url('/', true) . 'contents/categories/view/slug:category-2',
				'last_modified' => '2010-08-16 23:56:50',
				'change_frequency' => 'monthly'
			),
			array(
				'url' => Router::url('/', true) . 'contents/categories/view/slug:category-2a',
				'last_modified' => '2010-08-16 23:56:50',
				'change_frequency' => 'monthly'
			),
			array(
				'url' => Router::url('/', true) . 'contents/categories/view/slug:category-4',
				'last_modified' => '2010-08-16 23:56:50',
				'change_frequency' => 'monthly'
			),
		)));
		$this->assertEqual($expected, $result);
	}
}