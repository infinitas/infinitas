<?php
/**
 * CoreThemeFixture
 *
 */
class ThemeFixture extends CakeTestFixture {
	public $table = 'core_themes';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'author' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'default_layout' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'active' => array('column' => 'active', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
			array(
				'id' => 1,
				'name' => 'default',
				'description' => 'This is the default infinitas theme',
				'author' => 'Infinitas',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'default_layout' => null,
				'active' => 0,
				'core' => 1,
				'created' => '2010-01-14 01:39:54',
				'modified' => '2010-01-14 01:39:57'
			),
			array(
				'id' => 2,
				'name' => 'terrafirma',
				'description' => '',
				'author' => '',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'default_layout' => null,
				'active' => 0,
				'core' => 0,
				'created' => '0000-00-00 00:00:00',
				'modified' => '0000-00-00 00:00:00'
			),
			array(
				'id' => 3,
				'name' => 'aqueous',
				'description' => 'A blue 3 col layout',
				'author' => 'Six Shooter Media\r\n',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'default_layout' => null,
				'active' => 0,
				'core' => 0,
				'created' => '0000-00-00 00:00:00',
				'modified' => '0000-00-00 00:00:00'
			),
			array(
				'id' => 4,
				'name' => 'aqueous_light',
				'description' => 'aqueous_light',
				'author' => '',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'default_layout' => null,
				'active' => 1,
				'core' => 0,
				'created' => '0000-00-00 00:00:00',
				'modified' => '0000-00-00 00:00:00'
			),
	);
}
