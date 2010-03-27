<?php
class Aco extends ManagementAppModel {
	var $name = 'Aco';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	//var $actsAs = array('Tree');

	var $order = array(
		'Aco.lft' => 'ASC'
	);

	var $displayField = 'Aco.alias';


	var $belongsTo = array(
		'ParentAco' => array(
			'className' => 'Management.Aco',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => array(
				'ParentAco.id',
				'ParentAco.alias'
			),
			'order' => ''
		)
	);

	var $hasMany = array(
		'ChildAco' => array(
			'className' => 'Management.Aco',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array(
				'ChildAco.id',
				'ChildAco.alias'
			),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	var $hasAndBelongsToMany = array(
		'Aro' => array(
			'className' => 'Management.Aro',
			'joinTable' => 'aros_acos',
			'with' => 'Management.ArosAco',
			'foreignKey' => 'aco_id',
			'associationForeignKey' => 'aro_id',
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
?>