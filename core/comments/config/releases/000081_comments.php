<?php
class R4e27425ae13447e1961a49266318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Comments version 0.8.1';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Comments';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'comment_attributes' => array(
					'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'val' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'comments' => array(
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'comment' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'comment_attributes' => array(
					'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'val' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
				'comments' => array(
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'comment' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'key' => 'index'),
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