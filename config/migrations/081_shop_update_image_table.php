<?php
class M4bcf87eee70c4d7eb8e822386318cd70 extends CakeMigration {

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
				'shop_images' => array(
					'image' => array('type' => 'string', 'null' => false, 'default' => NULL),
				),
			),
			'drop_field' => array(
				'shop_images' => array('name',),
			),
		),
		'down' => array(
			'drop_field' => array(
				'shop_images' => array('image',),
			),
			'create_field' => array(
				'shop_images' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
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