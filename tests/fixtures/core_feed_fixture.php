<?php
/* CoreFeed Fixture generated on: 2010-08-17 14:08:22 : 1282055122 */
class CoreFeedFixture extends CakeTestFixture {
	var $name = 'CoreFeed';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'fields' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'conditions' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'order' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'limit' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'plugin' => 'blog',
			'model' => '',
			'controller' => 'Posts',
			'action' => 'view',
			'created' => '2010-04-03 12:54:28',
			'name' => 'Them main feed',
			'description' => '<p>\r\n	All the stuff</p>\r\n',
			'fields' => '{\r\n\"0\":\"Post.id\",\r\n\"1\":\"Post.title\",\r\n\"2\":\"Post.slug\",\r\n\"3\":\"Post.intro AS body\",\r\n\"4\":\"Post.created AS date\"\r\n}',
			'conditions' => '{\r\n\"Post.active\":\"1\",\r\n\"Post.parent_id < \":\"1\"\r\n}',
			'order' => '{\"date\":\"DESC\"}',
			'limit' => 10,
			'active' => 1,
			'group_id' => 2,
			'modified' => '2010-04-06 17:36:29'
		),
	);
}
?>