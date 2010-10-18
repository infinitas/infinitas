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
				'sub_total' => 'COUNT(ViewCount.id)',
				'month'     => 'CONCAT_WS("-", YEAR(ViewCount.created), MONTH(ViewCount.created))'
			);

			$viewCountsByMonth = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'month',
						'sub_total'
					),
					'conditions' => $conditions,
					'group' => array(
						'month'
					),
					'limit' => (int)$limit
				)
			);

			$byMonth = array();
			if(!empty($viewCountsByMonth)){
				$byMonth['model'] = $viewCountsByMonth[0]['ViewCount']['model'];
				$byMonth['totals'] = Set::extract('/ViewCount/sub_total', $viewCountsByMonth);
				$byMonth['months'] = Set::extract('/ViewCount/month', $viewCountsByMonth);
				unset($viewCountsByMonth);
			}

			return $byMonth;
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
				'week'      => 'WEEK(ViewCount.created)',
				'month'     => 'MONTH(ViewCount.created)'
			);

			$viewCountsByWeek = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'week',
						'month',
						'sub_total'
					),
					'conditions' => $conditions,
					'group' => array(
						'week'
					),
					'limit' => (int)$limit
				)
			);

			$byWeek = array();
			if(!empty($viewCountsByWeek)){
				$byWeek['model'] = $viewCountsByWeek[0]['ViewCount']['model'];
				$byWeek['totals'] = Set::extract('/ViewCount/sub_total', $viewCountsByWeek);
				$byWeek['weeks'] = Set::extract('/ViewCount/week', $viewCountsByWeek);
				unset($viewCountsByWeek);
			}

			return $byWeek;
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
				'sub_total' => 'COUNT(ViewCount.id)',
				'day'       => 'DAYOFYEAR(ViewCount.created)',
				'month'     => 'MONTH(ViewCount.created)'
			);

			$viewCountsByDay = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'day',
						'month',
						'sub_total'
					),
					'conditions' => $conditions,
					'group' => array(
						'day'
					),
					'limit' => (int)$limit
				)
			);

			$byDay = array();
			if(!empty($viewCountsByDay)){
				$byDay['model'] = $viewCountsByDay[0]['ViewCount']['model'];
				$byDay['totals'] = Set::extract('/ViewCount/sub_total', $viewCountsByDay);
				$byDay['days'] = Set::extract('/ViewCount/day', $viewCountsByDay);
				unset($viewCountsByDay);
			}

			return $byDay;
		}

		private function __bindRelation($model){
			$Model = ClassRegistry::init($model);

			$fields[str_replace('.', '', $model)] = array(
				$Model->primaryKey,
				$Model->displayField
			);
			unset($Model);

			$this->bindModel(
				array(
					'belongsTo' => array(
						current(array_keys($fields)) => array(
							'className' => $model,
							'foreignKey' => 'foreign_key',
							'fields' => current($fields)
						)
					)
				)
			);

			return $fields;
		}

		public function reportPopularRows($conditions = array(), $model, $limit = 20){
			$this->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)',
				'month'     => 'MONTH(ViewCount.created)'
			);

			$model = $this->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'ViewCount.foreign_key',
						'month',
						'sub_total'
					),
					'contain' => array(
						current(array_keys($this->__bindRelation($model)))
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
				'sub_total' => 'COUNT(ViewCount.id)',
				'month'     => 'CONCAT_WS("-", YEAR(ViewCount.created), MONTH(ViewCount.created))'
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
						'month >= ' => date('Y-m', mktime(0, 0, 0, date('m') - 12))
					),
					'group' => array(
						'ViewCount.model'
					)
				)
			);

			return $models;
		}
	}