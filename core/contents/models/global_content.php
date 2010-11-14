<?php
	class GlobalContent extends ContentsAppModel{
		public $name = 'GlobalContent';

		public $useTable = 'contents';

		public $belongsTo = array(
			'GlobalLayout' => array(
				'className' => 'Contents.GlobalLayout',
				'foreignKey' => 'layout_id'
			),
			'Group' => array(
				'className' => 'Users.Group',
				'foreignKey' => 'group_id'
			)
		);

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'title' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for this content item', true)
					)
				),
				'layout_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the layout for this content item', true)
					)
				),
				'group_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the lowest group that will have access', true)
					)
				),
				'body' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the body of this content item', true)
					)
				)
			);
		}

		public function moveContent($model = null, $limit = 500){
			if(!$model){
				trigger_error(__('No model selected to move', true), E_USER_WARNING);
				return false;
			}

			if(!is_int($limit)){
				$limit = 500;
			}

			$return = array();
			$return['moved'] = 0;

			$Model = ClassRegistry::init($model);			
			$return['total'] = $Model->find(
				'count',
				array(					
					'conditions' => array(
						$Model->alias . '.' . $Model->displayField . ' IS NOT NULL'
					)
				)
			);

			if($Model->displayField == $Model->primaryKey){
				trigger_error(sprintf(__('Display field and primary key are the same for %s, cant move', true), $model), E_USER_WARNING);
				return false;
			}

			$rows = $Model->find(
				'all',
				array(
					'conditions' => array(
						$Model->alias . '.' . $Model->displayField . ' IS NOT NULL'
					),
					'contain' => false,
					'limit' => $limit
				)
			);
			
			foreach($rows as $row){
				$newContent = array();
				$newContent[$this->alias] = $row[$Model->alias];
				$newContent[$this->alias]['foreign_key'] = $row[$Model->alias][$Model->primaryKey];
				$newContent[$this->alias]['model'] = $Model->plugin . '.' . $Model->alias;
				
				unset($newContent[$this->alias][$Model->primaryKey]);
				$this->create();
				if($this->save($newContent)){
					$Model->id = $row[$Model->alias][$Model->primaryKey];
					$Model->saveField($Model->displayField, '', false);
					$return['moved']++;
				}
			}

			return $return;
		}
	}