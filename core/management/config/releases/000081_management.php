<?php
class R4e2744fa04e44659942b49266318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Management version 0.8.1';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Management';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'backups' => array(
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'ip_addresses' => array(
					'ip_address' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'logs' => array(
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'change' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'relation_types' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'relations' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'conditions' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'fields' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'order' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'counter_scope' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'join_table' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'with' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'association_foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'finder_query' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'delete_query' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'insert_query' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'tickets' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'drop_table' => array(
				'core_trash'
			),
		),
		'down' => array(
			'alter_field' => array(
				'backups' => array(
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
				'ip_addresses' => array(
					'ip_address' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
				'logs' => array(
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'change' => array('type' => 'text', 'null' => true, 'default' => NULL),
				),
				'relation_types' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
				'relations' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'conditions' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'fields' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'order' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'counter_scope' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'join_table' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'with' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150),
					'association_foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'finder_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'delete_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'insert_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
				'tickets' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
			),
			'create_table' => array(
				'core_trash' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'deleted' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'deleted_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
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