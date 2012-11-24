<?php
/**
 * fixture file for InfinitasComment tests.
 *
 * @package Comments.Fixture
 * @since 0.9b1
 */
class InfinitasCommentFixture extends CakeTestFixture {

	public $name = 'InfinitasComment';

	public $table = 'infinitas_comments';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'class' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => null, 'key' => 'index'),
		'rating' => array('type' => 'integer', 'null' => false, 'default' => null),
		'points' => array('type' => 'integer', 'null' => false, 'default' => null),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'mx_record' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'ip_address' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'active' => array('column' => 'active', 'unique' => 0),
			'status' => array('column' => 'status', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 3,
			'class' => 'Blog.TestPost',
			'foreign_id' => 1,
			'user_id' => 0,
			'email' => 'user1@user1.com',
			'comment' => ' blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa some long comment user1 blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa',
			'active' => 1,
			'rating' => 0,
			'points' => 4,
			'status' => 'approved',
			'created' => '2010-10-04 01:19:32',
			'mx_record' => 1,
			'ip_address' => '127.0.0.1'
		),
		array(
			'id' => 332,
			'class' => 'Blog.TestPost',
			'foreign_id' => 1,
			'user_id' => 0,
			'email' => 'user2@user2.com',
			'comment' => ' blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa another long comment by user2 blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa',
			'active' => 1,
			'rating' => 0,
			'points' => 4,
			'status' => 'approved',
			'created' => '2010-12-08 16:43:09',
			'mx_record' => 1,
			'ip_address' => '127.0.0.1'
		),
	);
}