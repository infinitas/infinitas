<?php	
	class ServerStatus extends CoreAppModel{
		public $name = 'ServerStatus';

		public $useTable = 'crons';

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->virtualFields['sub_total'] = 'COUNT(' . $this->alias . '.id)';
			$this->virtualFields['created']  = 'CONCAT(' . $this->alias . '.year, "-", '. $this->alias.'.month, "-", `'. $this->alias.'.day, " ", '. $this->alias.'.start_time)';
			$this->virtualFields['hour']  = 'HOUR(' . $this->alias . '.start_time)';
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

			return $this->__fillTheBlanks($this->__formatData($viewCountsByDay, 'day'), range(1, 14), 'days');
		}

		/**
		 * Generate a report on the last six months
		 *
		 * @param array $conditions normal conditions for the find
		 * @return array array of data with model, totals and days
		 */
		public function reportLastSixMonths($conditions = array()){
			$viewCountsByDay = $this->find(
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

			foreach($viewCountsByDay as $k => $v){
				$viewCountsByDay[$k][$this->alias]['month'] = (int)$viewCountsByDay[$k][$this->alias]['month'];
			}

			return $this->__fillTheBlanks($this->__formatData($viewCountsByDay, 'month'), range(1, 6), 'months');
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

			return $this->__fillTheBlanks($this->__formatData($viewCountsByDay, 'day'), range(1, 31), 'days');
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

			return $this->__fillTheBlanks($this->__formatData($viewCountsByHour, 'hour'), range(1, 24), 'hours');
		}

		/**
		 * @brief this is copied from ViewCounter
		 */
		private function __formatData($data, $fields){
			if(!is_array($fields)){
				$fields = array($fields);
			}

			$return = array();
			$return['totals'] = array();
			foreach($fields as $field){
				$fieldName = $field;
				$return[$field] = array();
			}

			if(!empty($data)){
				$return['model'] = __('All', true);
				if(isset($data[0][$this->alias]['model'])){
					$return['model'] = $data[0][$this->alias]['model'];
				}
				$return['totals'] = Set::extract('/' . $this->alias . '/sub_total', $data);

				foreach($fields as $field){
					$fieldName = Inflector::pluralize($field);
					$return[$fieldName] = Set::extract('/' . $this->alias . '/' . $field, $data);
				}
			}


			$dates = Set::extract('/' . $this->alias . '/created', $data);
			$return['start_date'] = !empty($dates) ? min($dates) : array();
			$return['end_date'] = !empty($dates) ? max($dates) : array();

			$return['total_views'] = array_sum((array)$return['totals']);
			$return['total_rows']  = count($return['totals']);

			if(isset($return[$fieldName]) && $return['total_rows'] != count($return[$fieldName])){
				trigger_error(sprintf(__('data mismach for model: %s fields: (%s)', true), $return['model'], implode(', ', $fields)), E_USER_WARNING);
			}

			unset($data);
			return $return;
		}

		/**
		 * @brief this is copied from ViewCounter
		 */
		private function __fillTheBlanks($data, $range, $field){
			if(empty($data['totals'])){
				return $data;
			}

			$data['totals'] = array_combine($data[$field], $data['totals']);
			foreach($range as $v){
				$data[$field][$v - 1] = $v;
				if(!isset($data['totals'][$v - 1])){
					$data['totals'][$v - 1] = 0;
				}
			}
			ksort($data['totals']);

			return $data;
		}
	}