<?php
/**
 * CronResourceTask
 *
 * @package Infinitas.Contact.Model
 */

/**
 * CronResourceTask
 *
 * This task is used to measure and track resource usage while crons are running
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contact.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class CronResourceTask extends Shell {
/**
 * Memory usage results
 *
 * @var array
 */
	public $memoryUsage = array(
		'start' => array(),
		'end' => array()
	);

/**
 * Verbose
 *
 * @todo remove as 2.0 does this
 *
 * @var boolean
 */
	public $verbose = false;

/**
 * Internal cache of the memory usage
 *
 * @var array
 */
	protected $_memoryLog = array();

/**
 * Internal cache of the load
 *
 * @var array
 */
	protected $_loadLog = array();

/**
 * Constructor
 *
 * @param type $stdout
 * @param type $stderr
 * @param type $stdin
 *
 * @return void
 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		parent::__construct($stdout, $stderr, $stdin);

		$this->lastTime = microtime(true);
	}

/**
 * overload log to show on the screen when needed and log files
 *
 * @see http://api13.cakephp.org/class/object#method-Objectlog
 *
 * @param string $message the message to log
 *
 * @return boolean
 */
	public function log($message) {
		$this->out($message);

		return parent::log($message, 'cron_jobs');
	}

/**
 * overload the hr method so the logs are more readable
 *
 * @return void
 */
	public function hr() {
		$this->log('');
		$this->log('---------------------------------------------------------------');
		$this->log('');
	}

/**
 * Log the memory usage as we go along to make sure things dont get out of hand
 *
 * Eventually if something is hogging to much memory it will (should) be
 * stopped so that the server is not hogged.
 *
 * @param string $doing what is being done
 * @param string $prefix not used
 *
 * @todo remove $prefix
 *
 * @return array
 */
	public function logMemoryUsage($doing = 'running', $prefix = '') {
		$memoryUsage = memoryUsage(false);
		if (empty($this->memoryUsage['start'])) {
			$this->log(
				sprintf(
					"Memory %s %s %s %s %s %s",
					str_pad('Current:', 15, ' ', STR_PAD_RIGHT),
					str_pad('Max:',	15, ' ', STR_PAD_RIGHT),
					str_pad('Ave:',	15, ' ', STR_PAD_RIGHT),
					str_pad('Load:', 15, ' ', STR_PAD_RIGHT),
					str_pad('Taken:', 15, ' ', STR_PAD_RIGHT),
					str_pad('Doing:', 15, ' ', STR_PAD_RIGHT)
				)
			);
			$this->log("---------------------------------------------------------------------------------------------------------------");
			$this->memoryUsage['start'] = $memoryUsage;
		}

		$this->_memoryLog[] = substr($memoryUsage['current'], 0, -3);
		$average = $this->averageMemoryUsage();

		$load = $this->logServerLoad();

		$taken = round(microtime(true) - $this->lastTime, 4);
		$this->log(
			sprintf(
				"	   %s %s %s %s %s %s",
				str_pad($memoryUsage['current'], 15, ' ', STR_PAD_RIGHT),
				str_pad($memoryUsage['max'], 15, ' ', STR_PAD_RIGHT),
				str_pad($average, 15, ' ', STR_PAD_RIGHT),
				str_pad($load, 15, ' ', STR_PAD_RIGHT),
				str_pad($taken, 15, ' ', STR_PAD_RIGHT),
				str_pad($doing, 15, ' ', STR_PAD_RIGHT)
			)
		);

		unset($memoryUsage);
		$this->lastTime = microtime(true);
		return array('memory' => $average, 'load' => $load);
	}

/**
 * Log the server load while crons are running
 *
 * the 1 min load average if found, -1 if not able to figure out (windows etc)
 *
 * @return float
 */
	public function logServerLoad() {
		$load = serverLoad(false);
		if (!isset($this->memoryUsage['start']['load'])) {
			$this->memoryUsage['start']['load'] = $load[0];
		}

		$this->_loadLog[] = $load[0];

		return $load[0];
	}

/**
 * Get the current average memory usage
 *
 * @return float
 */
	public function averageMemoryUsage() {
		return round(array_sum($this->_memoryLog) / count($this->_memoryLog), 3);
	}

/**
 * Get the current average memory usage
 *
 * @return float
 */
	public function averageLoad() {
		return array_sum($this->_loadLog) / count($this->_loadLog);
	}

/**
 * Get the time elapsed since the start
 *
 * @return float
 */
	public function elapsedTime() {
		return round(microtime(true) - $this->start, 3);
	}

/**
 * output some stats for the cron that just ran
 *
 * @return void
 */
	public function stats() {
		if ($this->verbose) {
			$this->log('Below are the stats for the run');
			$this->hr();
		}

		$memoryUsage = $totalMemoryUsed = null;

		$memoryUsage = memoryUsage(false);
		$totalMemoryUsed = round(substr($memoryUsage['current'], 0, -3) - substr($this->memoryUsage['start']['current'], 0, -3), 3);

		$this->log(sprintf('Total time taken :: %s sec', $this->elapsedTime()));

		$this->log(sprintf('Load max		 :: %s', max($this->_loadLog)));
		$this->log(sprintf('Load average	 :: %s', $this->averageLoad()));

		$this->log(sprintf('Memory max	   :: %s', $memoryUsage['max']));
		$this->log(sprintf('Memory current   :: %s', $memoryUsage['current']));
		$this->log(sprintf('Memory average   :: %s mb', $this->averageMemoryUsage()));
		$this->log(sprintf('Memory used	  :: %s mb', $totalMemoryUsed));
	}

}
