<?php
/* GlobalComment Fixture generated on: 2010-12-13 17:12:34 : 1292259754 */
class InfinitasCommentFixture extends CakeTestFixture {
	var $name = 'InfinitasComment';

	var $table = 'infinitas_comments';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'comment' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'rating' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'points' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'active' => array('column' => 'active', 'unique' => 0), 'status' => array('column' => 'status', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
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
			'created' => '2010-10-04 01:19:32'
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
			'created' => '2010-12-08 16:43:09'
		),
	);
}
?>