<?php
	/**
	 * @brief The CronShell manages and dispaches cron jobs from a single location
	 *
	 * @todo track system load every few min
	 *
	 * @todo figure out the quite times
	 *
	 * @todo figure out the heavy load jobs
	 *
	 * @todo fun heavy jobs in quite times
	 */
	class CronShell extends Shell{
		public $tasks = array(
			'Event',
			'CronLock',
			'CronResource'
		);

		public $times = array(
			'months' => array(),
			'days'=> array(),
			'hours' => array(),
			'minutes' => array()
		);

		public $jobsRun = 0;

		/**
		 * @breif the EventCore object
		 *
		 * @property EventCore
		 */
		public $Event;

		/**
		 * @brief the CronLockTask keeps the crons running the way they should
		 *
		 * @property CronLock
		 */
		public $CronLock;

		/**
		 * @brief the CronResourceTask makes sure the server does not get overloaded
		 *
		 * @property CronResource
		 */
		public $CronResource;

		private $__verbose = false;

		private $__start;

		public function  __construct(&$dispatch) {
			parent::__construct($dispatch);
			$this->times['months'][date('m')]  = 1;
			$this->times['days'][date('j')]    = 1;
			$this->times['hours'][date('H')]   = 1;
			$this->times['minutes'][date('i')] = 1;
		}
		
		public function help(){
			$this->out('Infinitas Cron');
			$this->hr();
			$this->out('The Infinitas cron shell is designed to be set up and run');
			$this->out('every few minutes, and provides a method for running all sorts');
			$this->out('of crons from one simple command. To get started determin');
			$this->out('the frequency you would like to run the cron at and add the');
			$this->out('following command to your cron tab');
			$this->out('');
			$this->out('*/1 * * * * /path/to/cake/cake cron');
			$this->out('');
			$this->out('The advantage of this system is that there is one point');
			$this->out('of entry so you do not need to set up and manage many different');
			$this->out('cron jobs. Every run will be passed off to each plugin and they');
			$this->out('will determin if there are any jobs to run. The cron shell');
			$this->out('is also able to detect heavy server loads and defer processing');
			$this->out('till the server is less busy.');
			$this->hr();
			$this->out('If you want to run the cron manually you can do so by');
			$this->out('running cake cron -v which will output all the information');
			$this->out('to screen as well as the log files');
			$this->out('');
			$this->hr();
			$this->out();
		}

		public function main(){
			if(!$this->CronLock->checkTimePassed()){
				$this->CronResource->log(sprintf('skipping (%s)', date('Y-m-d H:i:s')));
				return false;
			}
			
			$this->CronResource->start = microtime(true);			
			if(!$this->_start()){
				$this->_abort();
			}

			$this->dispatchCrons();

			$this->_end();
		}

		public function dispatchCrons(){
			$plugins = $this->Event->pluginsWith('runCrons');

			$this->CronResource->logMemoryUsage(sprintf('→ Getting events (%s)', count($plugins)));
			
			$count = 0;
			foreach($plugins as $plugin){
				$data = $this->Event->trigger(sprintf('%s.runCrons', $plugin));

				$jobRan = current($data['runCrons']);
				if($jobRan){
					++$this->jobsRun;
				}

				$this->CronResource->logMemoryUsage(sprintf('%s - %s', $jobRan ? '✔' : '☐', $plugin));
			}
			unset($plugins);

			return true;
		}

		/**
		 * @brief The startup method records the start time and some mem stats
		 *
		 * @access public
		 */
		protected function _start(){
			$this->__verbose = $this->CronResource->verbose = isset($this->params['v']) && $this->params['v'];

			if($this->__verbose){ $this->Dispatch->clear(); }
			$this->CronResource->log('Infinitas Cron dispacher');
			$this->CronResource->log(sprintf('Cron started       :: %s', date('Y-m-d H:i:s')));
			if($this->__verbose){ $this->CronResource->hr(); }

			$this->CronResource->logMemoryUsage('startup');

			return $this->CronLock->start();
		}

		protected function _abort(){
			$this->CronResource->log('Cron aborted, previous job still running');
			exit;
		}

		/**
		 * @brief the end method records the end time and logs some stats
		 *
		 * @access protected
		 */
		protected function _end(){
			$this->CronResource->logMemoryUsage();

			$this->CronResource->log(sprintf('Cron Ended       :: %s', date('Y-m-d H:i:s')));

			if($this->__verbose){
				$this->CronResource->hr();
			}
			
			$this->CronResource->stats();

			return $this->CronLock->end($this->jobsRun, $this->CronResource->averageMemoryUsage(), $this->CronResource->averageLoad());
		}
	}