<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R509b147597004cbeb25209466318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Contact version 0.9.2';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Contact';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_field' => array(
				'contact_branches' => array(
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'time_zone_id'),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'model'),
					'indexes' => array(
						'model' => array('column' => array('model', 'ordering'), 'unique' => 0),
						'ordering' => array('column' => 'ordering', 'unique' => 0),
					),
				),
			),
			'alter_field' => array(
				'contact_branches' => array(
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
				),
				'contact_contacts' => array(
					'user_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'branch_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'contact_branches' => array('model', 'foreign_key', 'indexes' => array('model', 'ordering')),
			),
			'alter_field' => array(
				'contact_branches' => array(
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
				'contact_contacts' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
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