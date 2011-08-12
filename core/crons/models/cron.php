<?php
	class Cron extends CronsAppModel{
		/**
		 * @copydoc AppModel::name
		 */
		public $name = 'Cron';

		/**
		 * @brief The process that is currently running
		 * 
		 * @property _currentProcess
		 */
		protected $_currentProcess;

		/**
		 * @copydoc AppModel::__construct()
		 *
		 * Create some virtual fields for easier finds later on
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->virtualFields['created']  = 'CONCAT(' . $this->alias . '.year, "-", ' . $this->alias . '.month, "-", `' . $this->alias.'.day, " ", ' .
					$this->alias . '.start_time)';
			$this->virtualFields['modified'] = 'CONCAT(' . $this->alias.'.year, "-", ' . $this->alias . '.month, "-", `' . $this->alias.'.day, " ", ' .
					$this->alias.'.end_time)';
		}

		/**
		 * @brief save the start of a cron run
		 *
		 * This is later used to check if any processes are running, along with
		 * some stats befor the cron starts. This will enable infinitas to show
		 * how much resources the crons are taking up.
		 *
		 * @return bool|void true if everything is cool, null if already running or could not start
		 */
		public function start(){			
			$data = null;
			$memUsage = memoryUsage(false, false);
			$serverLoad = serverLoad(false);
			$serverLoad[0] = ($serverLoad[0] >= 0) ? $serverLoad[0] : 0;
			
			$data['Cron'] = array(
				'process_id' => @getmypid(),
				'year'	=> date('Y'),
				'month' => date('m'),
				'day'   => date('d'),
				'start_time' => date('H:i:s'),
				'start_mem' => $memUsage['current'],
				'start_load' => $serverLoad[0]
			);
			unset($memUsage, $serverLoad);

			$this->create();
			$alreadyRunning = $this->_isRunning();
			if($this->save($data)){
				$this->_currentProcess = $this->id;
				return $alreadyRunning === false;
			}
		}

		/**
		 * @brief updates the cron row to show the process as complete
		 *
		 * When the cron run is done this method is called to mark the end of the
		 * process, along with recording some stats on the system that can
		 * later be used for analysys.
		 *
		 * @param int $tasksRan the number of events that did something
		 * @param string $memAverage average memory usage for the run
		 * @param string $loadAverage system load average for the run
		 * @access public
		 *
		 * @return AppModel::save()
		 */
		public function end($tasksRan = 0, $memAverage = 0, $loadAverage = 0){
			if(!$this->_currentProcess){
				trigger_error(__('Cron not yet started', true), E_USER_WARNING);
				return false;
			}
			
			$data = null;
			$memUsage = memoryUsage(false, false);
			$serverLoad = serverLoad(false);
			$serverLoad[0] = ($serverLoad[0] >= 0) ? $serverLoad[0] : 0;

			$data['Cron'] = array(
				'id' => $this->_currentProcess,
				'end_time' => date('H:i:s'),
				'end_mem' => $memUsage['current'],
				'end_load' => $serverLoad[0],
				'mem_ave' => $memAverage,
				'load_ave' => $loadAverage,
				'tasks_ran' => $tasksRan,
				'done' => 1
			);
			unset($memUsage, $serverLoad);

			$this->_currentProcess = null;

			return $this->save($data);
		}

		/**
		 * @brief check if a cron is already running
		 *
		 * This does a simple check against the database to see if any jobs are
		 * open (not marked done). If there are there could be something still
		 * running.
		 *
		 * @todo check using the process_id to see if the process is still active
		 *
		 * @return <type>
		 */
		protected function _isRunning(){
			return (bool)$this->find(
				'count',
				array(
					'conditions' => array(
						$this->alias . '.done' => 0
					)
				)
			);
		}

		/**
		 * @brief check if enough time has elapsed since the last run
		 *
		 * the query checks if there are any jobs between the desired date and the
		 * last run. If there are that means there was a job that ran more recently
		 * than the time span required.
		 *
		 * @param string $date the datetime since the last cron should have run
		 * 
		 * @return bool are there jobs recently
		 */
		public function countJobsAfter($date){
			$data = $this->find(
				'count',
				array(
					'conditions' => array(
						'Cron.created > ' => $date
					)
				)
			);

			return $data;
		}

		/**
		 * @brief get the last run job
		 *
		 * This gets the last time a cron ran, and can be used for checking if
		 * the crons are setup or if they are running.
		 *
		 * @return string|bool false if not running, or datetime string of last run
		 */
		public function getLastRun(){
			$last = $this->find(
				'first',
				array(
					'fields' => array(
						$this->alias . '.id',
						'created'
					),
					'order' => array(
						'created' => 'desc'
					)
				)
			);

			return (isset($last['Cron']['created']) && !empty($last['Cron']['created'])) ? $last['Cron']['created'] : false;
		}

		/**
		 * @brief clear out old data
		 *
		 * This method is used to clear out old data, normally it is called via
		 * crons to happen automatically, but could be used in other places.
		 */
		public function clearOldLogs($date = null){
			if(!$date){
				$date = Configure::read('Cron.clear_logs');
			}

			$date = date('Y-m-d H:i:s', strtotime('- ' . $date));

			return $this->deleteAll(
				array(
					'Cron.created <= ' => $date
				),
					false
			);
		}
	}