<?php
	/* CoreModulesRoute Fixture generated on: 2010-08-17 12:08:51 : 1282046151 */
	class ModulesRouteFixture extends CakeTestFixture {
		var $name = 'ModulesRoute';

		var $table = 'core_modules_routes';

		var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'module_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
			'route_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'habtm' => array('column' => array('route_id', 'module_id'), 'unique' => 1), 'route' => array('column' => 'route_id', 'unique' => 0), 'module' => array('column' => 'module_id', 'unique' => 0)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
		);

		var $records = array(
			array(
				'id' => 65,
				'module_id' => 2,
				'route_id' => 0
			),
			array(
				'id' => 56,
				'module_id' => 4,
				'route_id' => 0
			),
			array(
				'id' => 66,
				'module_id' => 5,
				'route_id' => 0
			),
			array(
				'id' => 39,
				'module_id' => 12,
				'route_id' => 0
			),
			array(
				'id' => 60,
				'module_id' => 13,
				'route_id' => 0
			),
			array(
				'id' => 40,
				'module_id' => 14,
				'route_id' => 0
			),
			array(
				'id' => 41,
				'module_id' => 15,
				'route_id' => 0
			),
			array(
				'id' => 77,
				'module_id' => 16,
				'route_id' => 0
			),
			array(
				'id' => 68,
				'module_id' => 25,
				'route_id' => 0
			),
			array(
				'id' => 95,
				'module_id' => 26,
				'route_id' => 0
			),
		);
	}