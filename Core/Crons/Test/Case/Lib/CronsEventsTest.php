<?php
	class CronsEventsTest extends CakeTestCase {
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
			$expected = array('areCronsSetup' => array('Crons' => '2010-12-07 14:21:01'));
			$this->assertEqual($expected, $this->Event->trigger($this, 'Crons.areCronsSetup'));

			$this->Cron->deleteAll(array('Cron.id != 1'));
			$expected = array('areCronsSetup' => array('Crons' => false));
			$this->assertEqual($expected, $this->Event->trigger($this, 'Crons.areCronsSetup'));
		}

		/**
		 * test the todo list stuff is working fine
		 */
		public function testRequireTodoList(){
			/**
			 * were running but broke/stoped
			 */
			$expected = array('requireTodoList' => array('Crons' => array(
				array('name' => '/^The crons are not running, last run was (.*)$/', 'type' => 'error', 'url' => '#')
			)));
			$event = $this->Event->trigger($this, 'Crons.requireTodoList');
			$this->assertEqual($expected['requireTodoList']['Crons'][0]['type'], $event['requireTodoList']['Crons'][0]['type']);
			$this->assertEqual($expected['requireTodoList']['Crons'][0]['url'], $event['requireTodoList']['Crons'][0]['url']);
			$result = preg_match($expected['requireTodoList']['Crons'][0]['name'], $event['requireTodoList']['Crons'][0]['name']);
			$this->assertTrue($result === 1);

			/**
			 * have never been setup
			 */
			$this->Cron->deleteAll(array('Cron.id != 1'));
			$expected = array('requireTodoList' => array('Crons' => array(
				array('name' => 'Crons are not configured yet', 'type' => 'warning', 'url' => '#')
			)));
			$this->assertEqual($expected, $this->Event->trigger($this, 'Crons.requireTodoList'));

			/**
			 * running fine
			 */
			$this->assertTrue($this->Cron->start());
			$expected = array('requireTodoList' => array('Crons' => true));
			$this->assertEqual($expected, $this->Event->trigger($this, 'Crons.requireTodoList'));
		}
	}