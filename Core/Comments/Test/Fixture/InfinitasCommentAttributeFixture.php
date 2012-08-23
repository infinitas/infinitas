<?php
/**
 * @brief fixture file for InfinitasCommentAttribute tests.
 *
 * @package Comments.Fixture
 * @since 0.9b1
 */
class InfinitasCommentAttributeFixture extends CakeTestFixture {
	public $name = 'InfinitasCommentAttribute';
	public $table = 'infinitas_comment_attributes';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'infinitas_comment_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'val' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 5,
			'infinitas_comment_id' => 3,
			'key' => 'username',
			'val' => 'user1'
		),
		array(
			'id' => 6,
			'infinitas_comment_id' => 3,
			'key' => 'website',
			'val' => 'http://user1.com'
		),
		array(
			'id' => 89,
			'infinitas_comment_id' => 332,
			'key' => 'username',
			'val' => 'user2'
		),
		array(
			'id' => 90,
			'infinitas_comment_id' => 332,
			'key' => 'website',
			'val' => 'http://user2.com'
		),
	);
}