<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f5634a4512448c28cb043556318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Migrations version 0.9';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Migrations';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
				'create_table' => array(
					'schema_migrations' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'version' => array('type' => 'integer', 'null' => false, 'default' => NULL),
						'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					)
				)
			),
			'down' => array(
				'drop_table' => array(
					'schema_migrations'
				)
			)
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