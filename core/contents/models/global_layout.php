<?php
	/**
	 *
	 *
	 */
	class GlobalLayout extends ContentsAppModel{
		public $name = 'GlobalLayout';

		public $lockable = true;

		public $useTable = 'layouts';

		public $belongsTo = array(
			'GlobalContent' => array(
				'className' => 'Contents.GlobalContent',
				'counterCache' => true
			)
		);

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

		public function beforeSave($options){
			$this->data['GlobalLayout']['model'] =
				$this->data['GlobalLayout']['plugin'] . '.' . $this->data['GlobalLayout']['model'];
			return true;
		}

		public function  afterFind($results, $primary = false) {
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