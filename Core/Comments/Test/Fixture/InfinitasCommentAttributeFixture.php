<?php
/* GlobalCommentAttribute Fixture generated on: 2010-12-13 17:12:02 : 1292259722 */
class InfinitasCommentAttributeFixture extends CakeTestFixture {
	var $name = 'InfinitasCommentAttribute';

	var $table = 'infinitas_comment_attributes';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'comment_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'val' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 5,
			'comment_id' => 3,
			'key' => 'username',
			'val' => 'user1'
		),
		array(
			'id' => 6,
			'comment_id' => 3,
			'key' => 'website',
			'val' => 'http://user1.com'
		),
		array(
			'id' => 89,
			'comment_id' => 332,
			'key' => 'username',
			'val' => 'user2'
		),
		array(
			'id' => 90,
			'comment_id' => 332,
			'key' => 'website',
			'val' => 'http://user2.com'
		),
	);
}
?>