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
			$this->assertInstanceOf('Cron', $this->Cron);

			/**
			 * clear everything out
			 */
			$this->Cron->deleteAll(array('Cron.id != 1'));

			$result = $this->Cron->find('all');
			$expected = array();
			$this->assertEquals($expected, $result);

			/**
			 * start a cron and check all the values are correct
			 */
			$result = $this->Cron->start();
			$this->assertTrue($result);

			$data = $this->Cron->find('all');
			$this->assertCount(1, $data);
			$this->assertNotEmpty($data[0]['Cron']);
			$this->assertInternalType('array', $data[0]['Cron']);
			$expected = $this->Cron->_currentProcess;
			$this->assertEquals($expected, $data[0]['Cron']['id']);
			$expected = date('Y');
			$this->assertEquals($expected, $data[0]['Cron']['year']);
			$expected = date('m');
			$this->assertEquals($expected, $data[0]['Cron']['month']);
			$expected = date('d');
			$this->assertEquals($expected, $data[0]['Cron']['day']);

			$expected = date('Y-n-d ') . $data[0]['Cron']['start_time'];
			$this->assertEquals($expected, $data[0]['Cron']['created']);

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
			$this->assertSame(1, $data['Cron']['done']);
			$expected = 1024;
			$this->assertEquals($expected, $data['Cron']['mem_ave']);
			$expected = 0.04;
			$this->assertEquals($expected, $data['Cron']['load_ave']);
			$expected = 5;
			$this->assertEquals($expected, $data['Cron']['tasks_ran']);

			$this->assertEquals(date('Y-n-d ') . $data['Cron']['end_time'], $this->Cron->getLastRun(), 'Not serious if 1 sec diff, just depends how the jobs runs');

			$expected = 0;
			$this->assertEquals($expected , $this->Cron->countJobsAfter(date('Y-n-d ') . $data['Cron']['end_time']));

			//expects error
			$this->Cron->end(1, 1, 1);
		}

		/**
		 * test clearing out the old logs
		 */
		public function testClearOutOldLogs(){
			$result = $this->Cron->countJobsAfter('2010-12-7 06:47:06');
			$expected = 89;
			$this->assertEquals($expected, $result);

			$result = $this->Cron->getLastRun();
			$expected = '2010-12-7 14:21:01';
			$this->assertEquals($expected, $result);

			$result = $this->Cron->find('count');
			$expected = 139;
			$this->assertEquals($expected, $result);
			Configure::write('Cron.clear_logs', '1 minute');

			$this->Cron->clearOldLogs();
			$result = $this->Cron->find('count');
			$expected = 0;
			$this->assertEquals($expected, $result, 'Crons not cleared %s');
		}
	}