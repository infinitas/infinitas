<?php
/**
 * CronLockTask
 *
 * @package Infinitas.Crons.Console.Task
 */

/**
 * CronLockTask
 *
 * @property Cron $Cron
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Crons.Console.Task
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class CronLockTask extends AppShell {
/**
 * Models to load
 *
 * @var array
 */
	public $uses = array(
		'Crons.Cron'
	);

/**
 * This method checks if there is a cron already running befor starting a new one
 *
 * If there is already a cron running this method will prevent the cron
 * from continuing. If It has been a long time since the cron has run
 * it will send an email to alert the admin that there is a problem.
 *
 * @todo should see if there is a way to kill the process for self maintanence.
 *
 * @return boolean
 */
	public function start() {
		return $this->Cron->start();
	}

/**
 * End a cron run
 *
 * @param integer $tasksRan the number of jobs run
 * @param float $memAverage the average memory usage
 * @param float $loadAverage the average load
 *
 * @return boolean|array
 */
	public function end($tasksRan = 0, $memAverage = 0, $loadAverage = 0) {
		return $this->Cron->end($tasksRan, $memAverage, $loadAverage);
	}

/**
 * check if its time to do a run
 *
 * Make sure that enough time has passed since the last cron before running
 * the next one. This has nothing to do with if the cron is already
 * running and is only a tiny request if it fails.
 *
 * @return boolean
 */
	public function checkTimePassed() {
		$date = strtotime('-' . Configure::read('Cron.run_every'));
		return !(bool)$this->Cron->countJobsAfter(date('Y-m-d H:i:s', $date));
	}

}