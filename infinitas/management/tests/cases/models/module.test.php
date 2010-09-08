<?php
	/* Module Test cases generated on: 2010-03-13 11:03:28 : 1268471188*/
	App::import('Model', 'management.Module');

	/**
	 *
	 *
	 */
	class ModuleTest extends Module{
		public $useDbConfig = 'test';
	}

	class ModuleTestCase extends CakeTestCase {
		public $fixtures = array(
			'plugin.management.module_position',
			'plugin.management.module',
			'plugin.management.theme',
			'plugin.management.group',
			'plugin.management.route',
			'plugin.management.modules_route',
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