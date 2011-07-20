<?php
class R4e274566454840d58a9c49266318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Menus version 0.8.1';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Menus';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'menu_items' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'link' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'params' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'menus' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'menu_items' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'link' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'params' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL),
				),
				'menus' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index'),
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