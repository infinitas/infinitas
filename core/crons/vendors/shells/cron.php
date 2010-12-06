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
			'Infinitas',
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
			$this->Infinitas->h1('Cron Help');
			$this->Infinitas->p(
				'The Infinitas cron shell is designed to be set up and run '.
				'every minute or two, and provides a method for running all sorts '.
				'of crons from the one crontab. To get started determin '.
				'add the command below to your cron tab, then sit back and enjoy'
			);
			$this->Infinitas->p(
				'The advantage of this system is that there is one point '.
				'of entry so you do not need to set up and manage many different '.
				'cron jobs. Every run will be passed off to each plugin and they '.
				'will determin if there are any jobs to run. The cron shell '.
				'is also able to detect heavy server loads and defer processing '.
				'till the server is less busy.'
			);

			$this->Infinitas->p(
				'If you want to run the cron manually you can do so by '.
				'running cake cron -v which will output all the information '.
				'to screen as well as the log files'
			);
			$this->Infinitas->h2('YOUR CRON CONFIG');
			
			$cron = sprintf(
				'*/1 * * * * %svendors/cron_dispacher cron -console %s/cake/console -app %s',
				App::pluginPath('crons'),
				CAKE_CORE_INCLUDE_PATH,
				APP
			);
			$this->out($cron);
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