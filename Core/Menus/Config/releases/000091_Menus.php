<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R50ae4128066c48e8853a11b06318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Menus version 0.9.1';

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
			'alter_field' => array(
				'core_menu_items' => array(
					'menu_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'group_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'core_menu_items' => array(
					'menu_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
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