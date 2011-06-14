<?php	
	class ServerStatus extends CoreAppModel{
		public $name = 'ServerStatus';

		public $useTable = 'crons';

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->virtualFields['sub_total'] = 'COUNT(' . $this->alias . '.id)';
			$this->virtualFields['created']   = 'CONCAT(' . $this->alias . '.year, "-", ' . 
				$this->alias . '.month, "-", `' . $this->alias.'.day, " ", '. $this->alias.'.start_time)';
			$this->virtualFields['hour']      = 'HOUR(' . $this->alias . '.start_time)';
			$this->virtualFields['average_load'] = 'ROUND(AVG(' . $this->alias . '.load_ave), 3)';
			$this->virtualFields['max_load']     = 'ROUND(MAX(' . $this->alias . '.load_ave), 3)';

			$this->ChartDataManipulation = new ChartDataManipulation();
		}

		/**
		 * @brief get some average overall stats
		 */
		public function reportAllTime(){
			$this->virtualFields['average_memory'] = 'ROUND(AVG(' . $this->alias . '.start_mem), 3)';
			$this->virtualFields['max_memory'] = 'ROUND(MAX(' . $this->alias . '.start_mem), 3)';

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
						'average_load',
						'max_load',
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
			
			$options = array(
				'alias' => $this->alias,
				'range' => range(1, 14),
				'blanks' => false,
				'blank_field' => 'day',
				'insert' => 'before',
				'fields' => array('max_load', 'average_load')
			);
			
			$viewCountsByDay = $this->ChartDataManipulation->getFormatted($viewCountsByDay, $options);
			return $viewCountsByDay;
		}

		/**
		 * Generate a report on the last six months
		 *
		 * @param array $conditions normal conditions for the find
		 * @return array array of data with model, totals and days
		 */
		public function reportLastSixMonths($conditions = array()){
			$lastSixMonths = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						$this->alias . '.month',
						'average_load',
						'max_load',
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

			$options = array(
				'alias' => $this->alias,
				'range' => range(1, 6),
				'blank_field' => 'month',
				'insert' => 'before',
				'fields' => array('max_load', 'average_load')
			);
			
			$lastSixMonths = $this->ChartDataManipulation->getFormatted($lastSixMonths, $options);
			return $lastSixMonths;
		}

		/**
		 * Generate a report on the hourly loads
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 * @return array array of data with model, totals and days
		 */
		public function reportByHour($conditions = array()){
			$viewCountsByHour = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						'hour',
						'average_load',
						'max_load',
						'created'
					),
					'conditions' => $conditions,
					'group' => array(
						'hour'
					)
				)
			);

			$options = array(
				'alias' => $this->alias,
				'range' => range(0, 23),
				'blanks' => false,
				'blank_field' => 'hour',
				'fields' => array('max_load', 'average_load')
			);

			$viewCountsByHour = $this->ChartDataManipulation->getFormatted($viewCountsByHour, $options);
			return $viewCountsByHour;
		}

		/**
		 * Generate a report on the daily loads
		 *
		 * @param array $conditions normal conditions for the find
		 * @return array array of data with model, totals and days
		 */
		public function reportByDay($conditions = array()){
			$this->virtualFields['sub_total']   = 'ROUND(AVG(' . $this->alias . '.load_ave), 3)';
			$viewCountsByDay = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.id',
						$this->alias . '.day',
						'average_load',
						'max_load',
						'created'
					),
					'conditions' => $conditions,
					'group' => array(
						$this->alias . '.day'
					)
				)
			);

			$options = array(
				'alias' => $this->alias,
				'range' => range(0, 23),
				'blanks' => false,
				'blank_field' => 'day',
				'fields' => array('max_load', 'average_load')
			);

			$viewCountsByDay = $this->ChartDataManipulation->getFormatted($viewCountsByDay, $options);
			return $viewCountsByDay;
		}
	}