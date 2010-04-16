<?php
class M4bc8bedae4fc4a4b8bc214806318cd70 extends CakeMigration {

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
				'cms_contents' => array(
					'rating' => array('type' => 'float', 'null' => false, 'default' => 0),
					'rating_count' => array('type' => 'integer', 'null' => false, 'default' => 0),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'cms_contents' => array('rating', 'rating_count'),
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