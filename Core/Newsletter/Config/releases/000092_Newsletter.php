<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */

	class R5103e49e7b444252aac80d7f6318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Newsletter version 0.9.2';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Newsletter';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
				'create_field' => array(
					'newsletter_newsletters' => array(
						'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					),
				),
				'alter_field' => array(
					'newsletter_newsletters' => array(
						'from' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'reply_to' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					),
					'newsletter_subscribers' => array(
						'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
					),
				),
			),
			'down' => array(
				'alter_field' => array(
					'newsletter_newsletters' => array(
						'from' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'reply_to' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					),
					'newsletter_subscribers' => array(
						'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
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