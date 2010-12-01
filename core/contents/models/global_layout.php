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

	class GlobalLayout extends ContentsAppModel{
		/**
		 * the model name
		 *
		 * @var string
		 * @access public
		 */
		public $name = 'GlobalLayout';

		/**
		 * enable the row locking for layouts
		 *
		 * @ref LockableBehavior
		 *
		 * @var bool
		 * @access public
		 */
		public $lockable = true;

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
		public $belongsTo = array(
			'GlobalContent' => array(
				'className' => 'Contents.GlobalContent',
				'counterCache' => true
			)
		);

		/**
		 * The GlobalContent model
		 *
		 * @var GlobalContent
		 * @access public
		 */
		public $GlobalContent;

		/**
		 * @copydoc AppModel::__construct()
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of this template', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a template with that name', true)
					)
				),
				'html' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please create the html for your template', true)
					)
				),
				'plugin' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the plugin that this layout is for', true)
					)
				),
				'model' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the model that this layout is for', true)
					)
				)
			);
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
	}