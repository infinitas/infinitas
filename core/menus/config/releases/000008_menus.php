<?php
class R4c94edcd346445abb4c278d86318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Menus version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Menus';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'menu_items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'link' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'params' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'menu_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'menuItems_groupIndex' => array('column' => 'group_id', 'unique' => 0),
						'menuItems_menuIndex' => array('column' => 'menu_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'menus' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index'),
					'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'menu_index' => array('column' => array('type', 'active'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'menu_items', 'menus'
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