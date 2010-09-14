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
		public function getToalViews($class = null){
			$conditions = array();
			
			if($class){
				$conditions = array('ViewCount.model' => $class);
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
	}