<?php
App::uses('ClearCache', 'Data.Lib');

class ServerStatusController extends ServerStatusAppController {

/**
 * Models to load
 * 
 * @var array
 */
	public $uses = array(
		'ServerStatus.ServerStatus'
	);

/**
 * Constructor
 * 
 * @param [type] $request  [description]
 * @param [type] $response [description]
 */
	public function __construct(CakeRequest $request = null, CakeResponse $response = null) {
		$this->serverLoad = serverLoad(false);
		$this->memoryUsage = memoryUsage(false, false);
		parent::__construct($request, $response);
	}

	public function admin_dashboard() {

	}

	public function admin_cache_status() {
		if (isset($this->request->params['named']['clear']) && $this->request->params['named']['clear']) {
			$this->set('clearedCache', ClearCache::run());
			apc_clear_cache();
  			apc_clear_cache('user');
			apc_clear_cache('opcode');
			$this->notice(__d('server_status', 'Cache cleared'), array(
				'redirect' => false
			));
		}

		$this->set('cacheStatus', ClearCache::status());
		$this->set('cacheGroups', ClearCache::getGroups());
	}

	public function admin_status() {
		$current = $current['Load'] = $allTime = array();

		if (count($this->serverLoad) >= 3) {
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

		$data = $this->Event->trigger('Crons.areCronsSetup');
		if (!current($data['areCronsSetup'])) {
			$this->notice(__d('server_status', 'Crons are not currently running, reporting is disabled'), array(
				'level' => 'error',
			));

			$allTime = $lastTwoWeeks = $lastSixMonths = $byHour = $byDay = array();
		} else{
			$allTime = $this->ServerStatus->reportAllTime();
			$lastTwoWeeks = $this->ServerStatus->reportLastTwoWeeks();
			$lastSixMonths = $this->ServerStatus->reportLastSixMonths();
			$byHour = $this->ServerStatus->reportByHour();
			$byDay = $this->ServerStatus->reportByDay();
		}

		$this->set(compact('allTime', 'lastTwoWeeks', 'lastSixMonths', 'byHour', 'byDay'));

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$this->notice(__d('server_status', 'This information may not be accurate on windows based systems'), array(
				'level' => 'warning'
			));
		}
	}
}