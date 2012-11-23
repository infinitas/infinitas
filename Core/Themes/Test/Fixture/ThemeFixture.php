<?php
/**
 * fixture file for Theme tests.
 *
 * @package Themes.Fixture
 * @since 0.9b1
 */
class ThemeFixture extends CakeTestFixture {
	public $table = 'core_themes';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'author' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'update_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'licence' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'default_layout' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'active' => array('column' => 'admin', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

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
			'admin' => 0,
			'frontend' => 1,
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
			'admin' => 0,
			'frontend' => 0,
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 3,
			'name' => 'aqueous',
			'description' => 'A blue 3 col layout',
			'author' => 'Six Shooter Media\\r\\n',
			'url' => '',
			'update_url' => '',
			'licence' => '',
			'default_layout' => null,
			'admin' => 0,
			'frontend' => 0,
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
			'admin' => 1,
			'frontend' => 0,
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00'
		),
	);
}