<?php
class R4c951b48bac4438d84417e246318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Feed version 0.81';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Feed';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'feeds' => array(
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'feeds' => array('slug',),
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