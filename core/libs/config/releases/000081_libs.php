<?php
class R4e274386ffe846f88c1b49266318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Libs version 0.8.1';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Libs';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'ratings' => array(
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'ratings' => array(
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
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