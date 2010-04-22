<?php
class M4bd0bd9fbb4441c89c941fb86318cd70 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'shop_spotlights' => array(
					'start_date' => array('type' => 'date', 'null' => false, 'default' => NULL),
					'end_date' => array('type' => 'date', 'null' => false, 'default' => NULL),
					'start_time' => array('type' => 'date', 'null' => false, 'default' => NULL),
					'end_time' => array('type' => 'date', 'null' => false, 'default' => NULL),
				),
			),
			'drop_field' => array(
				'shop_spotlights' => array('start', 'end',),
			),
		),
		'down' => array(
			'drop_field' => array(
				'shop_spotlights' => array('start_date', 'end_date', 'start_time', 'end_time',),
			),
			'create_field' => array(
				'shop_spotlights' => array(
					'start' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'end' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
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