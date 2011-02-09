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
		 * @property ChartDataManipulation
		 */
		public $ChartDataManipulation;

		/**
		 * @copydoc AppModel::__construct()
		 */
		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);
			
			$this->ChartDataManipulation = new ChartDataManipulation();
		}

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
		 * Get the average views in total or per model
		 *
		 * @param string $model the model to check against
		 *
		 * @return int the average views of the selected range.
		 */
		public function getAverage($model = null){
			$this->virtualFields['views'] = 'COUNT(ViewCount.id)';
			$conditions = $group = array();
			if($model){
				$conditions[$this->alias . '.model'] = (string)$model;
				$group[] = $this->alias . '.foreign_key';
			}

			$cacheName = cacheName('view_average', array($conditions, $group));
			$data = Cache::read($cacheName, 'view_counts');
			if($data !== false){
				return $data;
			}

			$data = $this->find(
				'all',
				array(
					'fields' => array(
						'views'
					),
					'conditions' => $conditions,
					'group' => $group
				)
			);
			
			if(!empty($data)){
				$data = Set::extract('/ViewCount/views', $data);
				$data = round(array_sum($data) / count($data));
				Cache::write($cacheName, $data, 'view_counts');
			}
			return $data;
		}

		/**
		 * Get a list of unique models that are being tracked by the ViewCounter.
		 *
		 * @param string $plugin the name of a plugin to check for
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
		 * Overview of data displayed
		 *
		 * @param array $conditions the conditions to limit the data
		 *
		 * @return array an overview of the data
		 */
		public function reportOverview($conditions){
			$this->virtualFields['unique_visits'] = 'CONCAT_WS(\'-\', `' .$this->alias . '`.`ip_address`, `' . $this->alias . '`.`user_id`)';
			$this->virtualFields['sub_total'] = 'COUNT(ViewCount.id)';
			$data['total_views'] = $this->find('count', array('conditions' => $conditions));
			if(empty($data['total_views'])){
				return false;
			}
			$data['global_total_views'] = $this->find('count');
			$data['total_ratio'] = ($data['total_views'] / $data['global_total_views']) * 100;
			
			$data['unique_views']['visits'] = $this->find(
				'all',
				array(
					'conditions' => $conditions,
					'fields' => array(
						$this->alias . '.ip_address',
						$this->alias . '.user_id',
						$this->alias . '.country_code',
						$this->alias . '.country',
						$this->alias . '.city',
						$this->alias . '.created',
						'sub_total'
					),
					'group'=> array(
						'unique_visits'
					)
				)
			);
			
			/**
			 * unique visitors
			 */
			$data['unique_views']['total_views'] = count($data['unique_views']['visits']);
			$data['unique_views']['country_codes'] = Set::extract('/' . $this->alias . '/country_code', $data['unique_views']['visits']);
			$data['unique_views']['ratio'] = ($data['unique_views']['total_views'] / $data['total_views']) * 100;
			$data['unique_views']['country_codes'] = array_flip(array_flip($data['unique_views']['country_codes']));
			sort($data['unique_views']['country_codes']);
			$dates = Set::extract('/' . $this->alias . '/created', $data['unique_views']['visits']);
			$data['unique_views']['start_date'] = $data['start_date'] = min($dates);
			$data['unique_views']['end_date'] = $data['end_date'] = max($dates);
			$data['unique_views']['views_per_visit'] = $data['total_views'] / $data['unique_views']['total_views'];
			unset($dates);


			/**
			 * new visitors
			 */
			$newVisitors = Set::extract('/' . $this->alias . '[sub_total<2]', $data['unique_views']['visits']);
			$data['new_visitors']['country_codes'] = Set::extract('/' . $this->alias . '/country_code', $newVisitors);
			$data['new_visitors']['country_codes'] = array_flip(array_flip($data['new_visitors']['country_codes']));
			sort($data['new_visitors']['country_codes']);
			$data['new_visitors']['total_views'] = count($newVisitors);
			$data['new_visitors']['ratio'] = ($data['new_visitors']['total_views'] / $data['unique_views']['total_views']) * 100;
			$dates = Set::extract('/' . $this->alias . '/created', $newVisitors);
			$data['new_visitors']['start_date'] = min($dates);
			$data['new_visitors']['end_date'] = max($dates);
			$data['new_visitors']['views_per_visit'] = $data['total_views'] / $data['new_visitors']['total_views'];
			unset($data['unique_views']['visits'], $newVisitors, $dates);

			$conditions[$this->alias . '.user_id'] = 0;
			$data['visitor_type']['public'] = $this->find('count', array('conditions' => $conditions));
			unset($conditions[$this->alias . '.user_id']);
			$conditions[$this->alias . '.user_id > '] = 0;
			$data['visitor_type']['registered'] = $this->find('count', array('conditions' => $conditions));

			$data['visitor_type']['public_percentage'] = ($data['visitor_type']['public'] / $data['total_views']) * 100;
			$data['visitor_type']['registered_percentage'] = ($data['visitor_type']['registered'] / $data['total_views']) * 100;

			return $data;
		}

		/**
		 * Report showing averages accross years
		 * 
		 * @param array $conditions the conditions to limit the data
		 *
		 * @return array the data for selected range
		 */
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

			return $this->ChartDataManipulation->formatData($this->alias, $viewCountsByYear, 'year');
		}

		/**
		 * Generate a report on the monthly visit counts
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 *
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

			$options = array(
				'alias' => $this->alias,
				'range' => range(1, 12),
				'insert' => 'before',
				'stats' => true,
				'fields' => array('sub_total', 'month')
			);

			$viewCountsByMonth = $this->ChartDataManipulation->getFormatted($viewCountsByMonth, $options);

			$viewCountsByMonth['month'] = isset($viewCountsByMonth['month']) ? $viewCountsByMonth['month'] : array();
			foreach($viewCountsByMonth['month'] as $k => $v){
				switch($v){
					case  1: $viewCountsByMonth['month'][$k] = __('Jan', true); break;
					case  2: $viewCountsByMonth['month'][$k] = __('Feb', true); break;
					case  3: $viewCountsByMonth['month'][$k] = __('Mar', true); break;
					case  4: $viewCountsByMonth['month'][$k] = __('Apr', true); break;
					case  5: $viewCountsByMonth['month'][$k] = __('May', true); break;
					case  6: $viewCountsByMonth['month'][$k] = __('Jun', true); break;
					case  7: $viewCountsByMonth['month'][$k] = __('Jul', true); break;
					case  8: $viewCountsByMonth['month'][$k] = __('Aug', true); break;
					case  9: $viewCountsByMonth['month'][$k] = __('Sep', true); break;
					case 10: $viewCountsByMonth['month'][$k] = __('Oct', true); break;
					case 11: $viewCountsByMonth['month'][$k] = __('Nov', true); break;
					case 12: $viewCountsByMonth['month'][$k] = __('Dev', true); break;
				}
			}

			return $viewCountsByMonth;
		}

		/**
		 * Report comparing the weeks of data
		 *
		 * @param array $conditions the conditions to limit the data
		 *
		 * @return array the data found for the conditions passed in
		 */
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

			$options = array(
				'alias' => $this->alias,
				'range' => range(1, 52),
				'insert' => 'before',
				'stats' => true,
				'fields' => array('sub_total', 'week_of_year')
			);

			$return = $this->ChartDataManipulation->getFormatted($return, $options);
			return $return;
		}

		/**
		 * Generate a report on the daily visit counts
		 *
		 * @param array $conditions normal conditions for the find
		 * @param int $limit the maximum number of rows to return
		 *
		 * @return array array of data with model, totals and days
		 */
		public function reportByDayOfMonth($conditions = array()){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.day',
						'sub_total',
						'ViewCount.created'
					),
					'conditions' => $conditions,
					'group' => array(
						'ViewCount.day'
					)
				)
			);

			$options = array(
				'alias' => $this->alias,
				'range' => range(1, 31),
				'insert' => 'before',
				'stats' => true,
				'fields' => array('sub_total', 'day')
			);

			$return = $this->ChartDataManipulation->getFormatted($return, $options);
			return $return;
		}

		/**
		 * report of data broken up into the 7 days of the week for selected
		 * range.
		 *
		 * @param array $conditions the conditions to limit the data
		 *
		 * @return array the data by week
		 */
		public function reportDayOfWeek($conditions = array()){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
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

			$options = array(
				'alias' => $this->alias,
				'range' => range(1, 7),
				'insert' => 'before',
				'stats' => true,
				'fields' => array('sub_total', 'day_of_week')
			);

			$return = $this->ChartDataManipulation->getFormatted($return, $options);

			$return['day_of_week'] = isset($return['day_of_week']) ? $return['day_of_week'] : array();
			foreach($return['day_of_week'] as $k => $v){
				switch($v){
					case 1: $return['day_of_week'][$k] = __('Sun', true); break;
					case 2: $return['day_of_week'][$k] = __('Mon', true); break;
					case 3: $return['day_of_week'][$k] = __('Tue', true); break;
					case 4: $return['day_of_week'][$k] = __('Wen', true); break;
					case 5: $return['day_of_week'][$k] = __('Thu', true); break;
					case 6: $return['day_of_week'][$k] = __('Fri', true); break;
					case 7: $return['day_of_week'][$k] = __('Sat', true); break;
				}
			}

			return $return;
		}

		/**
		 * report on the data per hour for the dates selected
		 *
		 * @param array $conditions the conditions to limit the data
		 *
		 * @return array the data per hour
		 */
		public function reportHourOnHour($conditions = array()){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)',
			);

			$return = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
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

			$options = array(
				'alias' => $this->alias,
				'range' => range(0, 23),
				'insert' => 'before',
				'stats' => true,
				'fields' => array('sub_total', 'hour')
			);

			$return = $this->ChartDataManipulation->getFormatted($return, $options);
			return $return;
		}

		/**
		 * this method auto binds the data from the rows its displaying data for
		 * title etc.
		 *
		 * @param string $model the model to join
		 *
		 * @return array the fields to be used in the find/join
		 */
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

		/**
		 * get a list of rows that are popular for the particular conditions passed in
		 *
		 * @param array $conditions conditions to check for
		 * @param string $model the model being checked
		 * @param int $limit the number of rows to limit the find to
		 *
		 * @return array the $limit most popular rows
		 */
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

		/**
		 * simmilar to the popular rows, this returns the most popular models overal
		 *
		 * @return array of popular models
		 */
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

		/**
		 * Generate a set of data shoing view by region
		 *
		 * @param array $conditions the conditions to limit the find
		 * @param int $limit the max number of regions to find for
		 * 
		 * @return array of regions sorted by views
		 */
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

			return $this->ChartDataManipulation->formatData($this->alias, $return, array('country_code', 'country'));
		}


		/**
		 * Some methods for updating when there is only ip address data saved /
		 * available. Could be used to make saves faster and then run on a cron
		 * before viewing the reports
		 *
		 * below is not generally needed.
		 */

		public function getCountryForUnknown(){
			$rows = $this->find(
				'all',
				array(
					'conditions' => array(
						'ViewCount.country' => 'Unknown'
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

			foreach($rows as $row){
				$temp = EventCore::trigger($this, 'getLocation', $row[$this->alias]['ip_address']);
				$temp = current($temp['getLocation']);

				if($temp['country_code'] == 'Unknown'){
					$temp['country_code'] = '-';
				}
				if(empty($temp['city'])){
					unset($temp['city']);
				}
				if(empty($temp['continent_code'])){
					$temp['continent_code'] = '-';
				}

				$row['ViewCount'] = array_merge(array_filter($row['ViewCount']), $temp);
				$this->save($row);
			}
		}

		public function getDatePartsFromCreated(){
			/*
			 UPDATE `global_view_counts` set
			 `year` = YEAR(`created`),
			 `month` = MONTH(`created`),
			 `day` = DAYOFMONTH(`created`),
			 `day_of_year` = DAYOFYEAR(`created`),
			 `week_of_year` = WEEKOFYEAR(`created`),
			 `hour` = HOUR(`created`),
			 `day_of_week` = DAYOFWEEK(`created`)
			 */
		}

		public function clearLocalhost(){
			return $this->deleteAll(
				array(
					$this->alias . '.ip_address' => '127.0.0.1'
				)
			);
		}
	}
