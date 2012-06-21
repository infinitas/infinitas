<?php
	class ConfigsEventsTest extends CakeTestCase {
		var $fixturesasd = array(
			'plugin.configs.config',
			'plugin.themes.theme',
			'plugin.routes.route',
			'plugin.view_counter.view_counter_view',

			'plugin.categories.category',
			'plugin.users.group',
		);

		public function startTest() {
			$this->Event = EventCore::getInstance();
		}

		public function endTest() {
			unset($this->Event);
			ClassRegistry::flush();
		}

		public function testGenerateSiteMapData() {
			$this->assertInstanceOf('EventCore', $this->Event);
		}
	}