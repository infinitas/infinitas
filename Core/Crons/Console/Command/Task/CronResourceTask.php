<?php
	class CronResourceTask extends Shell {
		public $memoryUsage = array(
			'start' => array(),
			'end' => array()
		);

		public $verbose = false;

		protected $_memoryLog = array();

		protected $_loadLog = array();

		public function  __construct(&$dispatch) {
			parent::__construct($dispatch);

			$this->lastTime = microtime(true);
		}

		/**
		 * @brief overload log to show on the screen when needed and log files
		 *
		 * @see http://api13.cakephp.org/class/object#method-Objectlog
		 *
		 * @param string $message the message to log
		 *
		 * @return boolean Success of log write
		 */
		public function log($message) {
			$this->out($message);

			return parent::log($message, 'cron_jobs');
		}

		/**
		 * @brief overload the hr method so the logs are more readable
		 */
		public function hr(){
			$this->log('');
			$this->log('---------------------------------------------------------------');
			$this->log('');
		}

		/**
		 * @brief Log the memory usage as we go along to make sure things dont get out of hand
		 *
		 * Eventually if something is hogging to much memory it will (should) be
		 * stopped so that the server is not hogged.
		 *
		 * @access public
		 *
		 * @return float the average memory usage
		 */
		public function logMemoryUsage($doing = 'running', $prefix = ''){
			$memoryUsage = memoryUsage(false);
			if(empty($this->memoryUsage['start'])){
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
		 * @brief Log the server load while crons are running
		 *
		 * @access public
		 *
		 * @return float the 1 min load average if found, -1 if not
		 */
		public function logServerLoad(){
			$load = serverLoad(false);
			if(!isset($this->memoryUsage['start']['load'])){
				$this->memoryUsage['start']['load'] = $load[0];
			}

			$this->_loadLog[] = $load[0];

			return $load[0];
		}

		/**
		 * @brief Get the current average memory usage
		 */
		public function averageMemoryUsage(){
			return round(array_sum($this->_memoryLog) / count($this->_memoryLog), 3);
		}

		/**
		 * @brief Get the current average memory usage
		 */
		public function averageLoad(){
			return array_sum($this->_loadLog) / count($this->_loadLog);
		}

		/**
		 * @brief Get the time elapsed since the start
		 */
		public function elapsedTime(){
			return round(microtime(true) - $this->start, 3);
		}

		/**
		 * @brief output some stats for the cron that just ran
		 */
		public function stats(){
			if($this->verbose){
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
