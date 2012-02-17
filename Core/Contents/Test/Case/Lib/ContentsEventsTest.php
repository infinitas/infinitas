<?php
	App::uses('Router', 'Routing');
	class ContentsEventsTest extends CakeTestCase {
		var $fixtures = array(
			'plugin.configs.config',
			'plugin.themes.theme',
			'plugin.routes.route',
			'plugin.view_counter.view_count',

			'plugin.contents.global_category',
			'plugin.users.group',
		);

		public function startTest() {
			$this->Event = EventCore::getInstance();
		}

		public function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testGenerateSiteMapData(){
			$this->assertIsA($this->Event, 'EventCore');

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
			$this->assertEqual($expected, $this->Event->trigger(new Object(), 'Contents.siteMapRebuild'));
		}
	}