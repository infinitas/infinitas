<?php
/* CmsContentConfig Fixture generated on: 2010-08-17 14:08:49 : 1282055089 */
class CmsContentConfigFixture extends CakeTestFixture {
	var $name = 'CmsContentConfig';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'content_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'author_alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'keywords' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'content_id' => 1,
			'author_alias' => '',
			'keywords' => '',
			'description' => ''
		),
		array(
			'id' => 2,
			'content_id' => 2,
			'author_alias' => 'bob',
			'keywords' => '',
			'description' => ''
		),
		array(
			'id' => 5,
			'content_id' => 3,
			'author_alias' => '',
			'keywords' => 'infinitas,core,cms',
			'description' => 'Infinitas is a powerful content management system'
		),
		array(
			'id' => 6,
			'content_id' => 4,
			'author_alias' => '',
			'keywords' => '',
			'description' => ''
		),
		array(
			'id' => 7,
			'content_id' => 5,
			'author_alias' => '',
			'keywords' => '',
			'description' => ''
		),
		array(
			'id' => 8,
			'content_id' => 6,
			'author_alias' => '',
			'keywords' => '',
			'description' => ''
		),
	);
}
?>