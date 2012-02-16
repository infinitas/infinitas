<?php
	class ModulesRouteFixture extends CakeTestFixture {		
		public $table = 'core_modules_routes';
		
		public $fields = 	array(
			'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
			'module_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
			'route_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'habtm' => array('column' => array('route_id', 'module_id'), 'unique' => 1), 'route' => array('column' => 'route_id', 'unique' => 0), 'module' => array('column' => 'module_id', 'unique' => 0)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
		);
	
		public $records = 	array(
			array(
				'id' => 65,
				'module_id' => 'module-login',
				'route_id' => 0
			),
			array(
				'id' => 56,
				'module_id' => 'module-popular-posts',
				'route_id' => 0
			),
			array(
				'id' => 66,
				'module_id' => 'module-search',
				'route_id' => 0
			),
			array(
				'id' => 39,
				'module_id' => 'module-admin-menu',
				'route_id' => 0
			),
			array(
				'id' => 60,
				'module_id' => 'module-frontend-menu',
				'route_id' => 0
			),
			array(
				'id' => 40,
				'module_id' => 'module-tags',
				'route_id' => 0
			),
			array(
				'id' => 41,
				'module_id' => 'module-post-dates',
				'route_id' => 0
			),
			array(
				'id' => 77,
				'module_id' => 'module-google-analytics',
				'route_id' => 0
			),
			array(
				'id' => 68,
				'module_id' => 'module-ratings',
				'route_id' => 0
			),
		);
	}