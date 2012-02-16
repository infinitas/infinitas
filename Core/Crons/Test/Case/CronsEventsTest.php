<?php	
	class CategoriesEventsTestCase extends CakeTestCase {
		public $fixtures = array(
			'plugin.crons.cron'
		);
		
		public function startTest() {
			$this->Event = EventCore::getInstance();
			$this->Cron = ClassRegistry::init('Crons.Cron');
		}

		public function endTest() {
			unset($this->Event, $this->Cron);
			ClassRegistry::flush();
		}

		/**
		 * test if the checks are working fine
		 */
		public function testAreCronsSetup(){
			$expected = array('areCronsSetup' => array('crons' => '2010-12-07 14:21:01'));
			$this->assertEqual($expected, $this->Event->trigger($this, 'crons.areCronsSetup'));

			$this->Cron->deleteAll(array('Cron.id != 1'));
			$expected = array('areCronsSetup' => array('crons' => false));
			$this->assertEqual($expected, $this->Event->trigger($this, 'crons.areCronsSetup'));
		}

		/**
		 * test the todo list stuff is working fine
		 */
		public function testRequireTodoList(){
			/**
			 * were running but broke/stoped
			 */
			$expected = array('requireTodoList' => array('crons' => array(
				array('name' => '/^The crons are not running, last run was (.*)$/', 'type' => 'error', 'url' => '#')
			)));
			$event = $this->Event->trigger($this, 'crons.requireTodoList');
			$this->assertEqual($expected['requireTodoList']['crons'][0]['type'], $event['requireTodoList']['crons'][0]['type']);
			$this->assertEqual($expected['requireTodoList']['crons'][0]['url'], $event['requireTodoList']['crons'][0]['url']);
			$this->assertTrue(preg_match($expected['requireTodoList']['crons'][0]['name'], $event['requireTodoList']['crons'][0]['name']));

			/**
			 * have never been setup
			 */
			$this->Cron->deleteAll(array('Cron.id != 1'));
			$expected = array('requireTodoList' => array('crons' => array(
				array('name' => 'Crons are not configured yet', 'type' => 'warning', 'url' => '#')
			)));
			$this->assertEqual($expected, $this->Event->trigger($this, 'crons.requireTodoList'));

			/**
			 * running fine
			 */
			$this->assertTrue($this->Cron->start());
			$expected = array('requireTodoList' => array('crons' => true));
			$this->assertEqual($expected, $this->Event->trigger($this, 'crons.requireTodoList'));
		}
	}