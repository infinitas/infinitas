<?php
	class ArosAco extends ManagementAppModel {
		var $name = 'ArosAco';
		//The Associations below have been created with all possible keys, those that are not needed can be removed

		var $belongsTo = array(
			'Aro' => array(
				'className' => 'Management.Aro',
				'foreignKey' => 'aro_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Aco' => array(
				'className' => 'Management.Aco',
				'foreignKey' => 'aco_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}