<?php
/**
 * @brief fixture file for GlobalTag tests.
 *
 * @package Contents.Fixture
 * @since 0.9b1
 */
class GlobalTagFixture extends CakeTestFixture {
	public $table = 'global_tags';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'identifier' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'keyname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'weight' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'meta_keywords' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'meta_description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '1',
			'identifier' => null,
			'name' => 'CakePHP',
			'keyname' => 'cakephp',
			'weight' => '2',
			'created' => '2008-06-02 18:18:11',
			'modified' => '2008-06-02 18:18:37',
			'description' => 'nice',
			'meta_keywords' => 'cakephp',
			'meta_description' => 'cakephp'
		),
		array(
			'id' => '2',
			'identifier' => null,
			'name' => 'CakeDC',
			'keyname' => 'cakedc',
			'weight' => '2',
			'created' => '2008-06-01 18:18:15',
			'modified' => '2008-06-01 18:18:15',
			'description' => 'as',
			'meta_keywords' => 'cakedc',
			'meta_description' => 'cakedc'
		),
		array(
			'id' => '3',
			'identifier' => null,
			'name' => 'CakeDC',
			'keyname' => 'cakedc',
			'weight' => '2',
			'created' => '2008-06-01 18:18:15',
			'modified' => '2008-06-01 18:18:15',
			'description' => 'well',
			'meta_keywords' => 'cakedc',
			'meta_description' => 'cakedc'
		),
	);
}