<?php
class R4e865d1dadf048e69d643fce6318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Installer version 0.8.2';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Installer';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'plugins' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
				),
			),
			'drop_field' => array(
				'plugins' => array('enabled',),
			),
		),
		'down' => array(
			'drop_field' => array(
				'plugins' => array('active',),
			),
			'create_field' => array(
				'plugins' => array(
					'enabled' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
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