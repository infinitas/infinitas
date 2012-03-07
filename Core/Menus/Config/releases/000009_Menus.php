<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f56349702f44c3d978343556318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Menus version 0.9';

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
					'core_menu_items' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
						'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'name'),
						'link' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'slug'),
						'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'link'),
						'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'prefix'),
						'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'plugin'),
						'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'controller'),
						'params' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'action'),
						'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'params'),
						'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'force_backend'),
						'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'force_frontend'),
						'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'class'),
						'menu_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index', 'after' => 'active'),
						'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index', 'after' => 'menu_id'),
						'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0', 'after' => 'group_id'),
						'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'parent_id'),
						'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'lft'),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'rght'),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'menuItems_groupIndex' => array('column' => 'group_id', 'unique' => 0),
							'menuItems_menuIndex' => array('column' => 'menu_id', 'unique' => 0),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'core_menus' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
						'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'name'),
						'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'after' => 'type'),
						'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'item_count'),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'active'),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'menu_index' => array('column' => array('type', 'active'), 'unique' => 0),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
				)
			),
			'down' => array(
				'drop_table' => array(
					'core_menu_items', 'core_menus'
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