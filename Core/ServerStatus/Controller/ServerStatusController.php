<?php
	class ServerStatusController extends ServerStatusAppController {
		public $name = 'ServerStatus';

		public $helpers = array(
			'ViewCounter.ViewCounter',
			'Charts.Charts' => array(
				'Google.GoogleStatic'
			)
		);

		public $uses = array(
			'ServerStatus.ServerStatus'
		);

		public function __construct() {
			$this->serverLoad = serverLoad(false);
			$this->memoryUsage = memoryUsage(false, false);
			parent::__construct();
		}

		public function admin_dashboard() {
			
		}

		public function admin_status() {
			$current = $current['Load'] = $allTime = array();
			
			if(count($this->serverLoad) >= 3){
				$current['Load'] = array(
					'1 min' => $this->serverLoad[0],
					'5 min' => $this->serverLoad[1],
					'15 min' => $this->serverLoad[2],
				);
			}
			
			$current['Memory'] = array(
				'current' => $this->memoryUsage['current'],
				'limit' => str_replace('M', '', $this->memoryUsage['limit']) * 1024 * 1024,
			);
			
			unset($this->serverLoad, $this->memoryUsage);
			$this->set('current', array_merge(systemInfo(), $current));

			$data = $this->Event->trigger('crons.areCronsSetup');			
			if(!current($data['areCronsSetup'])){
				$this->notice(
					__('Crons are not currently running, reporting is disabled'),
					array(
						'level' => 'error'
					)
				);
			}
			else{
				$this->set('allTime', $this->ServerStatus->reportAllTime());
				$this->set('lastTwoWeeks', $this->ServerStatus->reportLastTwoWeeks());
				$this->set('lastSixMonths', $this->ServerStatus->reportLastSixMonths());
				$this->set('byHour', $this->ServerStatus->reportByHour());
				$this->set('byDay', $this->ServerStatus->reportByDay());
			}
			
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$this->notice(
					__('This information may not be accurate on windows based systems'),
					array(
						'level' => 'warning'
					)
				);
			}
		}
	}