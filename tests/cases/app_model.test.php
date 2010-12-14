<?php
	/* App Test cases generated on: 2010-12-14 01:12:59 : 1292290679*/
	App::import('Model', 'AppModel');

	class AppModelTestCase extends CakeTestCase {
		var $fixtures = array(
			'plugin.configs.config',
			'plugin.themes.theme',
			'plugin.routes.route',
			'plugin.view_counter.view_count'
		);
		function startTest() {
			$this->AppModel = new AppModel();
		}

		function endTest() {
			unset($this->AppModel);
			ClassRegistry::flush();
		}

		public function testStuff(){
		}
	}