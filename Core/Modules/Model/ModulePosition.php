<?php
	class ModulePosition extends ModulesAppModel {
		public $useTable = 'module_positions';
		
		public $hasMany = array(
			'Module' => array(
				'className' => 'Modules.Module',
				'foreignKey' => 'position_id',
			)
		);

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'validName' => array(
						'rule' => '/[a-z0-9_]{1,50}/',
						'message' => __('Please enter a valid name, lowercase letters, numbers and underscores only')
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a position with that name')
					)
				)
			);
		}

		public function isPosition($position = null) {
			if(!$position) {
				return false;
			}

			return (bool)$this->find(
				'count',
				array(
					'conditions' => array(
						'ModulePosition.name' => $position
					)
				)
			);
		}
	}