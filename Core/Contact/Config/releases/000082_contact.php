<?php
class R4e2d75a0d6c042e6bd7439aa6318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Contact version 0.8.2';

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
			'alter_field' => array(
				'countries' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1', 'key' => 'primary'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'countries' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => '`UUID()`', 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1', 'key' => 'primary'),
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