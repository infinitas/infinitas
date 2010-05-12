<?php
	class Aro extends ManagementAppModel {
		var $name = 'Aro';
		//The Associations below have been created with all possible keys, those that are not needed can be removed

		var $belongsTo = array(
			'ParentAro' => array(
				'className' => 'Management.Aro',
				'foreignKey' => 'parent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Group' => array(
				'className' => 'Management.Group',
				'foreignKey' => 'foreign_key',
				'conditions' => array(),
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'Management.User',
				'foreignKey' => 'foreign_key',
				'conditions' => array(
				),
				'fields' => '',
				'order' => ''
			)
		);

		var $hasMany = array(
			'ChildAro' => array(
				'className' => 'Management.Aro',
				'foreignKey' => 'parent_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		var $hasAndBelongsToMany = array(
			'Aco' => array(
				'className' => 'Management.Aco',
				'joinTable' => 'aros_acos',
				'with' => 'Management.ArosAco',
				'foreignKey' => 'aro_id',
				'associationForeignKey' => 'aco_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			)
		);
	}