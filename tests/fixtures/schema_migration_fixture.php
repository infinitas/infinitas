<?php
/* SchemaMigration Fixture generated on: 2010-08-17 14:08:37 : 1282055197 */
class SchemaMigrationFixture extends CakeTestFixture {
	var $name = 'SchemaMigration';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'version' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'version' => 1,
			'type' => 'migrations',
			'created' => '2010-08-17 09:55:00'
		),
		array(
			'id' => 2,
			'version' => 1,
			'type' => 'app',
			'created' => '2010-08-17 09:57:01'
		),
		array(
			'id' => 3,
			'version' => 2,
			'type' => 'app',
			'created' => '2010-08-17 09:57:46'
		),
		array(
			'id' => 4,
			'version' => 1,
			'type' => 'blog',
			'created' => '2010-08-17 09:58:32'
		),
		array(
			'id' => 5,
			'version' => 1,
			'type' => 'cms',
			'created' => '2010-08-17 09:59:26'
		),
		array(
			'id' => 6,
			'version' => 1,
			'type' => 'contact',
			'created' => '2010-08-17 09:59:38'
		),
		array(
			'id' => 7,
			'version' => 1,
			'type' => 'feed',
			'created' => '2010-08-17 09:59:51'
		),
		array(
			'id' => 8,
			'version' => 2,
			'type' => 'blog',
			'created' => '2010-08-17 10:06:51'
		),
		array(
			'id' => 9,
			'version' => 1,
			'type' => 'management',
			'created' => '2010-08-17 10:08:49'
		),
		array(
			'id' => 10,
			'version' => 1,
			'type' => 'newsletter',
			'created' => '2010-08-17 10:33:10'
		),
		array(
			'id' => 11,
			'version' => 1,
			'type' => 'shop',
			'created' => '2010-08-17 10:33:33'
		),
		array(
			'id' => 12,
			'version' => 1,
			'type' => 'blog',
			'created' => '2010-08-17 13:47:17'
		),
		array(
			'id' => 13,
			'version' => 1,
			'type' => 'cms',
			'created' => '2010-08-17 13:48:14'
		),
		array(
			'id' => 14,
			'version' => 1,
			'type' => 'contact',
			'created' => '2010-08-17 13:48:28'
		),
		array(
			'id' => 15,
			'version' => 1,
			'type' => 'feed',
			'created' => '2010-08-17 13:49:34'
		),
		array(
			'id' => 16,
			'version' => 1,
			'type' => 'management',
			'created' => '2010-08-17 13:49:46'
		),
		array(
			'id' => 17,
			'version' => 1,
			'type' => 'newsletter',
			'created' => '2010-08-17 13:49:58'
		),
		array(
			'id' => 18,
			'version' => 1,
			'type' => 'shop',
			'created' => '2010-08-17 13:50:09'
		),
		array(
			'id' => 19,
			'version' => 1,
			'type' => 'categories',
			'created' => '2010-08-17 13:51:26'
		),
		array(
			'id' => 20,
			'version' => 2,
			'type' => 'app',
			'created' => '2010-08-17 13:52:43'
		),
		array(
			'id' => 21,
			'version' => 1,
			'type' => 'order',
			'created' => '2010-08-17 14:09:03'
		),
	);
}
?>