<?php
	/* Module Test cases generated on: 2010-03-13 11:03:28 : 1268471188*/
	App::uses('Module', 'Modules.Model');

	/**
	 *
	 *
	 */
	class ModuleTest extends Module {
		public $alias = 'Module';
		public $useDbConfig = 'test';
	}

	class ModuleTestCase extends CakeTestCase {
		public $fixtures = array(
			'plugin.modules.module_position',
			'plugin.modules.module',
			'plugin.themes.theme',
			'plugin.users.group',
			'plugin.routes.route',
			'plugin.modules.modules_route',
		);

		public function startTest() {
			$this->Module = new ModuleTest();
		}

		public function testGet(){
			$this->assertEqual(array(), $this->Module->getModules());
			$result = $this->Module->getModules('top');
			//pr($result);
		}

		public function endTest() {
			unset($this->Module);
			ClassRegistry::flush();
		}
	}