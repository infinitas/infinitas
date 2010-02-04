<?php
	/**
	 *
	 *
	 */
	class Module extends ManagementAppModel{
		var $name = 'Module';

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
				'className' => 'Management.ModulePosition',
				'foreignKey' => 'position_id'
			),
			'Management.Group',
			'Theme' => array(
				'className' => 'Management.Theme',
				'foreignKey' => 'theme_id'
			),
		);

		var $hasAndBelongsToMany = array(
			'Route' => array(
				'className' => 'Management.Route',
				'joinTable' => 'modules_routes',
				'foreignKey' => 'module_id',
				'associationForeignKey' => 'route_id',
				'unique' => true
			)
		);

		function getModules($position = null, $admin){
			if (!$position) {
				return array();
			}

			$positions = $this->find(
				'all',
				array(
					'fields' => array(
						'Module.id',
						'Module.name',
						'Module.content',
						'Module.module',
						'Module.config',
						'Module.show_heading'
					),
					'conditions' => array(
						'Position.name' => $position,
						'Module.admin' => $admin,
						'Module.active' => 1
					),
					'contain' => array(
						'Position' => array(
							'fields' => array(
								'Position.id',
								'Position.name'
							)
						),
						'Group' => array(
							'fields' => array(
								'Group.id',
								'Group.name'
							)
						),
						'Route' => array(
							'fields' => array(
								'Route.id',
								'Route.name',
								'Route.url'
							)
						),
						'Theme' => array(
							'fields' => array(
								'Theme.id',
								'Theme.name'
							)
						)
					)
				)
			);

			return $positions;
		}
	}
?>