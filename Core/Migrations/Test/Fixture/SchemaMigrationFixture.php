<?php
/**
 * @brief fixture file for SchemaMigration tests.
 *
 * @package Migrations.Fixture
 * @since 0.9b1
 */
class SchemaMigrationFixture extends CakeTestFixture {
	public $name = 'SchemaMigration';
	public $table = 'schema_migrations';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'version' => array('type' => 'integer', 'null' => false, 'default' => null),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'version' => 1,
			'type' => 'migrations',
			'created' => '2011-09-06 16:53:39'
		),
		array(
			'id' => 2,
			'version' => 1,
			'type' => 'app',
			'created' => '2011-09-06 16:54:08'
		),
		array(
			'id' => 3,
			'version' => 1,
			'type' => 'Installer',
			'created' => '2011-09-06 16:54:09'
		),
		array(
			'id' => 4,
			'version' => 2,
			'type' => 'Installer',
			'created' => '2011-09-06 16:54:09'
		),
	);
}