<?php	
	class ServerStatus extends CoreAppModel{
		public $name = 'ServerStatus';

		public $useTable = 'crons';

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->virtualFields['sub_total'] = 'COUNT(' . $this->alias . '.id)';
			$this->virtualFields['created']  = 'CONCAT(' . $this->alias . '.year, "-", '. $this->alias.'.month, "-", `'. $this->alias.'.day, " ", '. $this->alias.'.start_time)';
			$this->virtualFields['hour']  = 'HOUR(' . $this->alias . '.start_time)';

			$this->ChartDataManipulation = new ChartDataManipulation();
		}

		/**
		 * @brief get some average overall stats
		 */
		public function reportAllTime(){
			$this->virtualFields['average_memory'] = 'ROUND(AVG(' . $this->alias . '.start_mem), 3)';
			$this->virtualFields['average_load']   = 'ROUND(AVG(' . $this->alias . '.load_ave), 3)';
			$this->virtualFields['max_memory'] = 'ROUND(MAX(' . $this->alias . '.start_mem), 3)';
			$this->virtualFields['max_load']   = 'ROUND(MAX(' . $this->alias . '.load_ave), 3)';

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'average_memory',
						'average_load',
						'max_memory',
						'max_load'
					)
				)
			);
			
			if(empty($return)){
				return $return;
			}

			return array(
				'average_memory' => $return[0]['ServerStatus']['average_memory'],
				'average_load' => $return[0]['ServerStatus']['average_load'],
				'max_memory' => $return[0]['ServerStatus']['max_memory'],
				'max_load' => $return[0]['ServerStatus']['max_load'],
			);
		}

		/**
		 * Generate a report on the last two weeks
		 *
		 * @param array $conditions normal conditions for the find
		 * @return array array of data with model, totals and days
		 */
		public function reportLastTwoWeeks($conditions = array()){
			$viewCountsByDay = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						$this->alias . '.day',
						'sub_total',
						'created'
					),
					'conditions' => array(
						'created > ' => date('Y-m-d H:i:s', strtotime('- 2 weeks'))
					),
					'group' => array(
						$this->alias . '.day'
					)
				)
			);

			foreach($viewCountsByDay as $k => $v){
				$viewCountsByDay[$k][$this->alias]['day'] = (int)$viewCountsByDay[$k][$this->alias]['day'];
			}

			$viewCountsByDay = $this->ChartDataManipulation->formatData($this->alias, $viewCountsByDay, 'day');
			return $this->ChartDataManipulation->fillBlanks($viewCountsByDay, range(1, 14), 'days');
		}

		/**
		 * Generate a report on the last six months
		 *
		 * @param array $conditions normal conditions for the find
		 * @return array array of data with model, totals and days
		 */
		public function reportLastSixMonths($conditions = array()){
			$lastSixMonths = array();
			
			$this->virtualFields['sub_total']   = 'ROUND(AVG(' . $this->alias . '.load_ave), 3)';
			$lastSixMonths['average'] = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						$this->alias . '.month',
						'sub_total',
						'created'
					),
					'conditions' => array(
						'created > ' => date('Y-m-d H:i:s', strtotime('- 6 weeks'))
					),
					'group' => array(
						$this->alias . '.month'
					)
				)
			);

			$this->virtualFields['sub_total']   = 'ROUND(MAX(' . $this->alias . '.load_ave), 3)';
			$lastSixMonths['max'] = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						$this->alias . '.month',
						'sub_total',
						'created'
					),
					'conditions' => array(
						'created > ' => date('Y-m-d H:i:s', strtotime('- 6 weeks'))
					),
					'group' => array(
						$this->alias . '.month'
					)
				)
			);

			foreach($lastSixMonths['average'] as $k => $v){
				$lastSixMonths['average'][$k][$this->alias]['month'] = (int)$lastSixMonths['average'][$k][$this->alias]['month'];
			}

			foreach($lastSixMonths['max'] as $k => $v){
				$lastSixMonths['max'][$k][$this->alias]['month'] = (int)$lastSixMonths['max'][$k][$this->alias]['month'];
			}

			$lastSixMonths['average'] = $this->ChartDataManipulation->formatData($this->alias, $lastSixMonths['average'], 'month');
			$lastSixMonths['average'] = $this->ChartDataManipulation->fillBlanks($lastSixMonths['average'], range(1, 6), 'months');
			
			$lastSixMonths['max']     = $this->ChartDataManipulation->formatData($this->alias, $lastSixMonths['max'], 'month');
			$lastSixMonths['max']     = $this->ChartDataManipulation->fillBlanks($lastSixMonths['max'], range(1, 6), 'months');

			return $lastSixMonths;
		}

		/**
		 * Generate a report on the daily loads
		 *
		 * @param array $conditions normal conditions for the find
		 * @return array array of data with model, totals and days
		 */
		public function reportByDay($conditions = array()){
			$viewCountsByDay = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						$this->alias . '.day',
						'sub_total',
						'created'
					),
					'conditions' => $conditions,
					'group' => array(
						$this->alias . '.day'
					)
				)
			);
			foreach($viewCountsByDay as $k => $v){
				$viewCountsByDay[$k][$this->alias]['day'] = (int)$viewCountsByDay[$k][$this->alias]['day'];
			}

			$viewCountsByDay = $this->ChartDataManipulation->formatData($this->alias, $viewCountsByDay, 'day');
			return $this->ChartDataManipulation->fillBlanks($viewCountsByDay, range(1, 31), 'days');
		}

		/**
		 * Generate a report on the hourly loads
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 * @return array array of data with model, totals and days
		 */
		public function reportByHour($conditions = array()){
			$this->virtualFields['sub_total'] = 'ROUND(AVG(' . $this->alias . '.start_load), 3)';
			$viewCountsByHour = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						'hour',
						'sub_total',
						'created'
					),
					'conditions' => $conditions,
					'group' => array(
						'hour'
					)
				)
			);

			foreach($viewCountsByHour as $k => $v){
				$viewCountsByHour[$k][$this->alias]['hour'] = (int)$viewCountsByHour[$k][$this->alias]['hour'];
			}

			$viewCountsByHour = $this->ChartDataManipulation->formatData($this->alias, $viewCountsByHour, 'hour');
			return $this->ChartDataManipulation->fillBlanks($viewCountsByHour, range(1, 24), 'hours');
		}
	}