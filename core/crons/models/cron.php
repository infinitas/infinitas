<?php
	class Cron extends CoreAppModel{
		public $name = 'Cron';

		protected $_currentProcess;

		public function start(){			
			$data = null;
			$memUsage = memoryUsage(false, false);
			$serverLoad = serverLoad(false);

			$data['Cron'] = array(
				'process_id' => @getmypid(),
				'year'	=> date('Y'),
				'month' => date('m'),
				'day'   => date('d'),
				'start_time' => date('H:m:s'),
				'start_mem' => $memUsage['current'],
				'start_load' => $serverLoad[0] >= 0 ? $serverLoad[0] : 0
			);
			unset($memUsage, $serverLoad);

			$this->create();
			$alreadyRunning = $this->_isRunning();
			if($this->save($data)){
				$this->_currentProcess = $this->id;
				return $alreadyRunning === false;
			}

			return false;
		}

		public function end($tasksRan = 0, $memAverage = 0, $loadAverage = 0){
			$data = null;
			$memUsage = memoryUsage(false, false);
			$serverLoad = serverLoad(false);

			$data['Cron'] = array(
				'id' => $this->_currentProcess,
				'end_time' => date('H:m:s'),
				'end_mem' => $memUsage['current'],
				'end_load' => $serverLoad[0] >= 0 ? $serverLoad[0] : 0,
				'mem_ave' => $memAverage,
				'load_ave' => $loadAverage,
				'tasks_ran' => $tasksRan,
				'done' => 1
			);
			unset($memUsage, $serverLoad);

			return $this->save($data);
		}

		public function _isRunning(){
			return (bool)$this->find(
				'count',
				array(
					'conditions' => array(
						$this->alias . '.done' => 0
					)
				)
			);
		}
	}