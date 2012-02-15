<?php
	/**
	 * @brief GlobalLayout handles the CRUD for layouts within infinitas
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contents.models
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class GlobalLayout extends ContentsAppModel {
		/**
		 * enable the row locking for layouts
		 *
		 * @ref LockableBehavior
		 *
		 * @var bool
		 * @access public
		 */
		public $lockable = true;
		public $contentable = true;

		/**
		 * The table to use for layouts
		 *
		 * @bug this could be causing the installer to not include the prefix when
		 * installing infinitas
		 *
		 * @var string
		 * @access public
		 */
		public $useTable = 'global_layouts';

		/**
		 * belongs to relations for the GlobalLayout model
		 *
		 * @var array
		 * @access public
		 */
		public $hasMany = array(
			'GlobalContent' => array(
				'className' => 'Contents.GlobalContent',
				'counterCache' => true
			)
		);

		/**
		 * @copydoc AppModel::__construct()
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of this template')
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a template with that name')
					)
				),
				'html' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please create the html for your template')
					)
				),
				'plugin' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the plugin that this layout is for')
					)
				),
				'model' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the model that this layout is for')
					)
				)
			);

			$this->findMethods['autoLoadLayout'] = true;
		}

		/**
		 * @copydoc AppModel::beforeSave()
		 *
		 * Before saving the record make sure that the model is set to the correct
		 * value so that it will be linked up properly to the related rows.
		 */
		public function beforeSave($options){
			$this->data['GlobalLayout']['model'] =
				$this->data['GlobalLayout']['plugin'] . '.' . $this->data['GlobalLayout']['model'];
			return true;
		}

		/**
		 * @copydoc AppModel::afterFind()
		 *
		 * after getting the data split the model into its plugin / model parts
		 * for the ajax selects to work properly
		 */
		public function afterFind($results, $primary = false) {
			parent::afterFind($results, $primary);

			if(isset($results[0][$this->alias]['model'])){
				foreach($results as $k => $result){
					$parts = pluginSplit($results[$k][$this->alias]['model']);
					$results[$k][$this->alias]['model_class'] = $results[$k][$this->alias]['model'];
					$results[$k][$this->alias]['plugin'] = $parts[0];
					$results[$k][$this->alias]['model'] = $parts[1];
				}
			}
			
			return $results;
		}

		public function _findAutoLoadLayout($state, $query, $results = array()) {
			if ($state === 'before') {
				if(empty($query['plugin']) || empty($query['model']) || empty($query['action'])) {
					return $query;
				}

				$query['conditions'] = array();
				$query['conditions'][$this->alias . '.model'] = $query['plugin'];
				$query['conditions'][$this->alias . '.model'] .= '.' . $query['model'];
				$query['conditions'][$this->alias . '.auto_load'] = $query['action'];

				$query['fields'] = array(
					'GlobalLayout.*'
				);
				
				unset($query['model'], $query['plugin'], $query['action']);
				return $query;
			}

			if (!empty($query['operation'])) {
				return $this->findPaginatecount($state, $query, $results);
			}

			return current($results);
		}

		public function hasLayouts($model) {
			return $this->find(
				'count',
				array(
					'conditions' => array(
						$this->alias . '.model' => $model
					)
				)
			) > 0;
		}
	}