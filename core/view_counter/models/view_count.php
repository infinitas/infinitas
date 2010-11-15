<?php
	/**
	 * Model responsible for saving page view data.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.view_counter
	 * @subpackage Infinitas.view_counter.model.view_count
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ViewCount extends ViewCounterAppModel{
		 public $name = 'ViewCount';

		/**
		 * Get the total number of views for a selected model
		 *
		 * @param string $class the class to count
		 *
		 * @return int the number of rows found
		 */
		public function getToalViews($class = null, $foreignKey = 0){
			$conditions = array();
			
			if($class){
				$conditions = array('ViewCount.model' => $class);
			}
			if((int)$foreignKey > 0){
				$conditions['ViewCount.foreign_key'] = $foreignKey;
			}
			
			return $this->find(
				'count',
				array(
					'conditions' => $conditions
				)
			);
		}

		/**
		 * Get some view stats.
		 *
		 * builds an array of all models that are being tracked for use in creating
		 * some pretty graphs and stats for the user.
		 *
		 * @return array of data
		 */
		public function getGlobalStats($limit = 5, $plugin = null){
			$models = $this->getUniqueModels($plugin);

			$return = array();
			foreach($models as $model){
				$Model = ClassRegistry::init($model);
				$return[] = array(
					'class' => $model,
					'model' => $Model->alias,
					'plugin' => $Model->plugin,
					'displayField' => $Model->displayField,
					'total_views' => $Model->getToalViews(),
					'top_rows' => $Model->getMostViewed($limit)
				);
			}
			unset($Model);

			return $return;
		}

		/**
		 * Get totals per model and overall
		 *
		 * @return array list of models and counts
		 */
		public function getGlobalTotalCount(){
			$models = $this->getUniqueModels();
			
			$return = array();
			foreach($models as $model){
				ClassRegistry::init($model)->Behaviors->attach('ViewCounter.Viewable');
				$return[$model] = ClassRegistry::init($model)->getToalViews();
			}

			return $return;
		}

		/**
		 * Get a list of unique models that are being tracked by the ViewCounter.
		 *
		 * @return array list of id -> models that are being tracked
		 */
		public function getUniqueModels($plugin = null){
			$this->displayField = 'model';

			$conditions = array();
			if($plugin){
				$conditions = array(
					'ViewCount.model LIKE' => Inflector::camelize($plugin).'%'
				);
			}
			
			$models = $this->find(
				'list',
				array(
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.model'
					)
				)
			);

			return $models;
		}

		/**
		 * all stats per conditions and grouped by year so you can compare
		 * previous years to each other.
		 *
		 * @param <type> $conditions
		 * @return <type>
		 */
		public function reportByYear($conditions){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$viewCountsByYear = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						isset($conditions['ViewCount.model']) ? 'ViewCount.model' : '',
						isset($conditions['ViewCount.foreign_key']) ? 'ViewCount.foreign_key' : '',
						'ViewCount.year',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.year'
					)
				)
			);

			return $this->__formatData($viewCountsByYear, 'year');
		}

		public function reportYearOnYear($conditions){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$viewCountsByYear = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						isset($conditions['ViewCount.model']) ? 'ViewCount.model' : '',
						isset($conditions['ViewCount.foreign_key']) ? 'ViewCount.foreign_key' : '',
						'ViewCount.year',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.year'
					)
				)
			);

			return $this->__formatData($viewCountsByYear, 'year');
		}

		/**
		 * Reporting methods
		 */
		/**
		 * Generate a report on the monthly visit counts
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 * @return array array of data with model, totals and months
		 */
		public function reportByMonth($conditions = array(), $limit = 200){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$viewCountsByMonth = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.month',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.month'
					),
					'limit' => (int)$limit
				)
			);

			return $this->__formatData($viewCountsByMonth, 'month');
		}
		/**
		 * Generate a report on the monthly visit counts
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 * @return array array of data with model, totals and months
		 */
		public function reportMonthOnMonth($conditions = array()){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$viewCountsByMonth = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.month',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.month'
					)
				)
			);

			$return = $this->__formatData($viewCountsByMonth, 'month');
			$return = $this->__fillTheBlanks($return, range(1, 12), 'months');

			foreach($return['months'] as $k => $v){
				switch($v){
					case  1: $return['months'][$k] = __('Jan', true); break;
					case  2: $return['months'][$k] = __('Feb', true); break;
					case  3: $return['months'][$k] = __('Mar', true); break;
					case  4: $return['months'][$k] = __('Apr', true); break;
					case  5: $return['months'][$k] = __('May', true); break;
					case  6: $return['months'][$k] = __('Jun', true); break;
					case  7: $return['months'][$k] = __('Jul', true); break;
					case  8: $return['months'][$k] = __('Aug', true); break;
					case  9: $return['months'][$k] = __('Sep', true); break;
					case 10: $return['months'][$k] = __('Oct', true); break;
					case 11: $return['months'][$k] = __('Nov', true); break;
					case 12: $return['months'][$k] = __('Dev', true); break;
				}
			}

			return $return;
		}

		/**
		 * Generate a report on the weekly visit counts
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 * @return array array of data with model, totals and weeks
		 */
		public function reportByWeek($conditions = array(), $limit = 200){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)',
			);

			$viewCountsByWeek = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.week_of_year',
						'ViewCount.month',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.week_of_year'
					),
					'limit' => (int)$limit
				)
			);

			return $this->__formatData($viewCountsByWeek, 'week_of_year');
		}

		public function reportWeekOnWeek($conditions = array()){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)',
			);

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.week_of_year',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.week_of_year'
					)
				)
			);

			$return = $this->__formatData($return, 'week_of_year');
			$return = $this->__fillTheBlanks($return, range(1, 52), 'week_of_years');
			return $return;
		}

		/**
		 * Generate a report on the daily visit counts
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 * @return array array of data with model, totals and days
		 */
		public function reportByDay($conditions = array(), $limit = 200){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$viewCountsByDay = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.day',
						'ViewCount.month',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.day'
					),
					'limit' => (int)$limit
				)
			);
			
			return $this->__formatData($viewCountsByDay, 'day');
		}

		public function reportDayOfWeek($conditions = array()){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.day_of_week',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.day_of_week'
					)
				)
			);
			
			$return = $this->__formatData($return, 'day_of_week');
			$return = $this->__fillTheBlanks($return, range(1, 7), 'day_of_weeks');
			
			foreach($return['day_of_weeks'] as $k => $v){
				switch($v){
					case 1: $return['day_of_weeks'][$k] = __('Sun', true); break;
					case 2: $return['day_of_weeks'][$k] = __('Mon', true); break;
					case 3: $return['day_of_weeks'][$k] = __('Tue', true); break;
					case 4: $return['day_of_weeks'][$k] = __('Wen', true); break;
					case 5: $return['day_of_weeks'][$k] = __('Thu', true); break;
					case 6: $return['day_of_weeks'][$k] = __('Fri', true); break;
					case 7: $return['day_of_weeks'][$k] = __('Sat', true); break;
				}
			}

			return $return;
		}

		public function reportHourOnHour($conditions = array()){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)',
			);

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.hour',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.hour'
					)
				)
			);

			$return = $this->__formatData($return, 'hour');
			$return = $this->__fillTheBlanks($return, range(1, 24), 'hours');

			return $return;
		}

		private function __fillTheBlanks($data, $range, $field){
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

		/**
		 * Format the data ready for the google charts helper by extracting the
		 * sub_totals into one array and the 'value' field into another (for lables)
		 *
		 * Some other info is also added to the return array like totals and what
		 * model its for.
		 *
		 * @param <type> $data
		 * @param <type> $field
		 * @return <type>
		 */
		private function __formatData($data, $fields){
			if(!is_array($fields)){
				$fields = array($fields);
			}
			
			$return = array();
			$return['totals'] = array();
			$fieldName = isset($fields[0]) ? $fields[0] : '';
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

		private function __bindRelation($model){
			$Model = ClassRegistry::init($model);
			$fields = array();
			
			$fields[str_replace('.', '', $model)] = array(
				$Model->primaryKey,
				$Model->displayField
			);
			unset($Model);

			$_fields = array();
			foreach(current($fields) as $field){
				$_fields[] = current(array_keys($fields)) . '.' . $field;
			}

			$this->bindModel(
				array(
					'belongsTo' => array(
						current(array_keys($fields)) => array(
							'className' => $model,
							'foreignKey' => 'foreign_key',
							'fields' => $_fields
						)
					)
				)
			);

			return $fields;
		}

		public function reportPopularRows($conditions = array(), $model, $limit = 20){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$model = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.foreign_key',
						'ViewCount.month',
						'sub_total'
					),
					'contain' => array(
						current(array_keys($this->__bindRelation($model))) => array(
							'fields' => $this->belongsTo[current(array_keys($this->__bindRelation($model)))]['fields']
						)
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.foreign_key'
					),
					'order' => array(
						'sub_total' => 'desc'
					),
					'limit' => 20
				)
			);

			return $model;
		}

		public function reportPopularModels(){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$models = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'sub_total'
					),
					'conditions' => array(
						'ViewCount.year BETWEEN ? AND ?' => array(
							date('y', mktime(0, 0, 0, date('m') - 12)),
							date('y', time()),
						),
						'ViewCount.month >= ' => date('m', mktime(0, 0, 0, date('m') - 12))
					),
					'group' => array(
						'ViewCount.model'
					)
				)
			);

			return $models;
		}

		public function reportByRegion($conditions = array(), $limit = 24){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)',
			);

			$conditions['ViewCount.country_code != \'-\''] = '';

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.ip_address',
						'ViewCount.country_code',
						'ViewCount.country',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'order' => array(
						'sub_total' => 'desc'
					),
					'group' => array(
						'ViewCount.country_code'
					)
				)
			);
			
			return $this->__formatData($return, array('country_code', 'country'));
		}


		/**
		 * Some methods for updating when there is only ip address data saved /
		 * available. Could be used to make saves faster and then run on a cron
		 * before viewing the reports
		 */
		
		public function getCountryForUnknown(){
			$rows = $this->find(
				'all',
				array(
					'conditions' => array(
						//'ViewCount.country' => 'Unknown'
					),
					'limit' => 500
				)
			);

			App::import('Lib', 'Libs.IpLocation');
			$this->IpLocation = new IpLocation();
			foreach($rows as $row){
				$temp = $this->IpLocation->getCountryData($row['ViewCount']['ip_address'], true);
				if($temp['country_code'] == 'Unknown'){
					$temp['country_code'] = '-';
				}
				$row['ViewCount'] = array_merge($row['ViewCount'], $temp);
				$this->save($row);
			}
		}

		public function getCityForUnknown(){
			$rows = $this->find(
				'all',
				array(
					'conditions' => array(
						'ViewCount.city' => 'Unknown',
						'ViewCount.country_code != ' => '-'
					)
				)
			);

			App::import('Lib', 'Libs.IpLocation');
			$this->IpLocation = new IpLocation();
			foreach($rows as $row){
				$temp = $this->IpLocation->getCityData($row['ViewCount']['ip_address'], true);

				if($temp['country_code'] == 'Unknown'){
					$temp['country_code'] = '-';
				}
				if(empty($temp['city'])){
					unset($temp['city']);
				}
				if(empty($temp['continent_code'])){
					$temp['continent_code'] = '-';
				}

				
				$row['ViewCount'] = array_merge($row['ViewCount'], $temp);
				$this->save($row);
			}
		}

		public function getDatePartsFromCreated(){
			/*
			 * UPDATE `global_view_counts` set
			 * `year` = YEAR(`created`),
			 * `month` = MONTH(`created`),
			 * `day` = DAYOFMONTH(`created`),
			 * `day_of_year` = DAYOFYEAR(`created`),
			 * `week_of_year` = WEEKOFYEAR(`created`),
			 * `hour` = HOUR(`created`),
			 * `day_of_week` = DAYOFWEEK(`created`)
			 */
		}
	}