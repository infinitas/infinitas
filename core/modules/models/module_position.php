<?php
	class ModulePosition extends CoreAppModel{
		public $name = 'ModulePosition';

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'validName' => array(
						'rule' => '/[a-z0-9_]{1,50}/',
						'message' => __('Please enter a valid name, lowercase letters, numbers and underscores only', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a position with that name', true)
					)
				)
			);
		}

		public function isPosition($position = null){
			if(!$position){
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