<?php
	/**
	 * View Counter reporting behavior.
	 *
	 * This is used to allow models easy access to some simple reporting methods.
	 * It is seperate to the actual View counter as that is only attached on view
	 * pages on the frontend, however this is needed all over the app
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package ViewCounter
	 * @subpackage ViewCounter.models.behaviors.viewable_reports
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 */

	class ViewableReportingBehavior extends ModelBehavior {
		/**
		 * Contain settings indexed by model name.
		 *
		 * @var array
		 * @access private
		 */
		public $__settings = array();

		public $__session = array();

		/**
		 * Initiate behavior for the model using specified settings.
		 * Available settings:
		 *
		 * - view_counter: string :: the field in the table that has the count
		 * - session_tracking false to disable, int for number of views to keep track of
		 * 	views are tracked by displayField and will do funny things if displayField is not a string.
		 *
		 * @param object $Model Model using the behaviour
		 * @param array $settings Settings to override for model.
		 * @access public
		 */
		public function setup(&$Model, $settings = array()) {
			$Model->bindModel(
				array(
					'hasMany' => array(
						'ViewCount' => array(
							'className' => 'ViewCounter.ViewCount',
							'foreignKey' => 'foreign_key',
							'conditions' => array(
								'model' => $Model->plugin.'.'.$Model->alias
							),
							'limit' => 0
						)
					)
				)
			);

			$Model->ViewCount->bindModel(
				array(
					'belongsTo' => array(
						$Model->alias => array(
							'className' => $Model->alias,
							'foreignKey' => 'foreign_key',
							'counterCache' => 'views'
						)
					)
				)
			);
		}

		/**
		 * Get the most viewed records for the table
		 *
		 * @param object $Model the model that is being used.
		 * @param int $limit the number or records to return
		 *
		 * @return array the most viewed records
		 */
		public function getMostViewed(&$Model, $limit = 10){
			$fields = array(
				$Model->alias.'.id',
				$Model->alias.'.'.$Model->displayField,
				$Model->alias.'.views'
			);

			if($Model->hasField('slug')){
				$fields[] = $Model->alias.'.slug';
			}

			$rows = $Model->find(
				'all',
				array(
					'fields' => $fields,
					'conditions' => array(
						$Model->alias.'.views > ' => 0
					),
					'order' => array(
						$Model->alias.'.views' => 'DESC'
					),
					'limit' => (int)$limit
				)
			);

			return $rows;
		}

		/**
		 * Get the total number of views for a selected model
		 *
		 * Short cut method for models using the viewable behavior to get the
		 * total number of views for that model.
		 *
		 * @return int the number of rows found
		 */
		public function getToalViews(&$Model, $foreignKey = 0){
			return $Model->ViewCount->getToalViews($Model->plugin.'.'.$Model->alias, $foreignKey);
		}
	}