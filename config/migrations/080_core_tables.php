<?php
class M4c6a5cedc6bc413bb79212386318cd70 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'acos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'mptt_alias' => array('column' => array('alias', 'lft', 'rght'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'api_api_classes' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'api_package_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'key' => 'index'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'file_name' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'method_index' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'property_index' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'flags' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
					'coverage_cache' => array('type' => 'float', 'null' => false, 'default' => NULL, 'length' => '4,4'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'api_package_id' => array('column' => 'api_package_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'api_api_packages' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'key' => 'index'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'parent_id' => array('column' => 'parent_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'aros' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'alias' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'aros_acos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'aro_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
					'aco_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
					'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'sessions' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'key' => 'primary'),
					'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'id_unique' => array('column' => 'id', 'unique' => 1),
						'expires_index' => array('column' => 'expires', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'user_configs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'value' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
					'options' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'config_key' => array('column' => 'key', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'user_details' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'surname' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'mobile' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
					'landline' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
					'company' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
					'indexes' => array(
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'acos', 'api_api_classes', 'api_api_packages', 'aros', 'aros_acos', 'sessions', 'user_configs', 'user_details'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>