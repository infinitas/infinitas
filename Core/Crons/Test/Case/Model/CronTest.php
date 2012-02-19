<?php
	/* Cron Test cases generated on: 2010-12-14 14:12:16 : 1292337136*/
	App::uses('Cron', 'Crons.Model');

	class CronTest extends CakeTestCase {
		var $fixtures = array(
			'plugin.crons.cron'
		);

		function startTest() {
			$this->Cron = ClassRegistry::init('Crons.Cron');
		}

		function endTest() {
			unset($this->Cron);
			ClassRegistry::flush();
		}

		/**
		 * test recording a cron run
		 *
		 * @expectedException PHPUnit_Framework_Error_Warning
		 */
		public function testStartAndStop(){
			$this->assertIsA($this->Cron, 'Cron');

			/**
			 * clear everything out
			 */
			$this->Cron->deleteAll(array('Cron.id != 1'));
			$this->assertEqual(array(), $this->Cron->find('all'));

			/**
			 * start a cron and check all the values are correct
			 */
			$this->assertTrue($this->Cron->start());

			$data = $this->Cron->find('all');
			$this->assertEqual(count($data), 1);
			$this->assertTrue(!empty($data[0]['Cron']));
			$this->assertTrue(is_array($data[0]['Cron']));
			$this->assertEqual($data[0]['Cron']['id'], $this->Cron->_currentProcess);
			$this->assertEqual($data[0]['Cron']['year'], date('Y'));
			$this->assertEqual($data[0]['Cron']['month'], date('m'));
			$this->assertEqual($data[0]['Cron']['day'], date('d'));

			$this->assertEqual($data[0]['Cron']['created'], date('Y-n-d ') . $data[0]['Cron']['start_time']);

			$this->assertNull($data[0]['Cron']['end_time']);
			$this->assertNull($data[0]['Cron']['end_mem']);
			$this->assertEquals(0, $data[0]['Cron']['end_load']);
			$this->assertNull($data[0]['Cron']['mem_ave']);
			$this->assertEquals(0, $data[0]['Cron']['load_ave']);
			$this->assertEquals(0, $data[0]['Cron']['tasks_ran']);
			$this->assertEquals(0, $data[0]['Cron']['done']);
			$this->assertNull($data[0]['Cron']['modified']);

			/**
			 * test ending the cron
			 */
			$data = $this->Cron->end(5, 1024, 0.04);
			$this->assertTrue($data['Cron']['done'] === 1);
			$this->assertEqual($data['Cron']['mem_ave'],   1024);
			$this->assertEqual($data['Cron']['load_ave'],  0.04);
			$this->assertEqual($data['Cron']['tasks_ran'], 5);

			$this->assertEqual(date('Y-n-d ') . $data['Cron']['end_time'], $this->Cron->getLastRun(), 'Not serious if 1 sec diff, just depends how the jobs runs');

			$this->assertEqual($this->Cron->countJobsAfter(date('Y-n-d ') . $data['Cron']['end_time']), 0);

			//expects error
			$this->Cron->end(1, 1, 1);
		}

		/**
		 * test clearing out the old logs
		 */
		public function testClearOutOldLogs(){
			$this->assertEqual($this->Cron->countJobsAfter('2010-12-7 06:47:06'), 89);

			$this->assertEqual('2010-12-7 14:21:01', $this->Cron->getLastRun());
			$this->assertEqual(139, $this->Cron->find('count'));
			Configure::write('Cron.clear_logs', '1 minute');

			$this->Cron->clearOldLogs();
			$this->assertEqual(0, $this->Cron->find('count'), 'Crons not cleared %s');
		}
	}