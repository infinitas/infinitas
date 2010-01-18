<?php
	/**
	 *
	 *
	 */
	class Module extends ManagementAppModel{
		var $name = 'Module';

		var $tablePrefix = 'core_';

		var $actsAs = array(
			'Libs.Ordered' => array(
				'foreign_key' => 'position_id'
			)
		);

		var $order = array(
			'Module.position_id' => 'ASC',
			'Module.ordering' => 'ASC'
		);

		var $belongsTo = array(
			'Position' => array(
				'className' => 'Core.ModulePosition',
				'foreignKey' => 'position_id'
			),
			'Core.Group'
		);

		var $hasAndBelongsToMany = array(
			'ModulesRoute' => array(
				'className' => 'Core.ModulesRoute',
				'joinTable' => 'modules_routes',
				'foreignKey' => 'module_id',
				'associationForeignKey' => 'route_id',
				'unique' => true
			)
		);
	}
?>