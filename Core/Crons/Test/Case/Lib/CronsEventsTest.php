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
			$result = $this->Event->trigger($this, 'Crons.areCronsSetup');
			$expected = array('areCronsSetup' => array('Crons' => '2010-12-07 14:21:01'));
			$this->assertEquals($expected, $result);

			$this->Cron->deleteAll(array('Cron.id != 1'));
			$result = $this->Event->trigger($this, 'Crons.areCronsSetup');
			$expected = array('areCronsSetup' => array('Crons' => false));
			$this->assertEquals($expected, $result);
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
			$this->assertEquals($expected['requireTodoList']['Crons'][0]['type'], $event['requireTodoList']['Crons'][0]['type']);
			$this->assertEquals($expected['requireTodoList']['Crons'][0]['url'], $event['requireTodoList']['Crons'][0]['url']);
			$result = preg_match($expected['requireTodoList']['Crons'][0]['name'], $event['requireTodoList']['Crons'][0]['name']);
			$this->assertSame(1, $result);

			/**
			 * have never been setup
			 */
			$this->Cron->deleteAll(array('Cron.id != 1'));
			$result = $this->Event->trigger($this, 'Crons.requireTodoList');
			$expected = array('requireTodoList' => array('Crons' => array(
				array('name' => 'Crons are not configured yet', 'type' => 'warning', 'url' => '#')
			)));
			$this->assertEquals($expected, $result);

			/**
			 * running fine
			 */
			$result = $this->Cron->start();
			$this->assertTrue($result);

			$result = $this->Event->trigger($this, 'Crons.requireTodoList');
			$expected = array('requireTodoList' => array('Crons' => true));
			$this->assertEqual($expected, $result);
		}
	}