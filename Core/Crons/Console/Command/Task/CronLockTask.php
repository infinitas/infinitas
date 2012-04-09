<?php
	class CronLockTask extends AppShell {
		public $uses = array(
			'Crons.Cron'
		);

		/**
		 * @property Cron
		 */
		public $Cron;

		/**
		 * @brief This method checks if there is a cron already running befor starting a new one
		 *
		 * If there is already a cron running this method will prevent the cron
		 * from continuing. If It has been a long time since the cron has run
		 * it will send an email to alert the admin that there is a problem.
		 *
		 * @todo should see if there is a way to kill the process for self maintanence.
		 */
		public function start(){
			return $this->Cron->start();
		}

		public function end($tasksRan = 0, $memAverage = 0, $loadAverage = 0){
			return $this->Cron->end($tasksRan, $memAverage, $loadAverage);
		}

		/**
		 * @brief check if its time to do a run
		 *
		 * Make sure that enough time has passed since the last cron before running
		 * the next one. This has nothing to do with if the cron is already
		 * running and is only a tiny request if it fails.
		 *
		 * @return bool true if time has passed, false if time has not passed
		 */
		public function checkTimePassed(){
			$date = strtotime('-' . Configure::read('Cron.run_every'));
			return !(bool)$this->Cron->countJobsAfter(date('Y-m-d H:i:s', $date));
		}
	}
