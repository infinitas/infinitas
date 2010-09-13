<?php
class R4c8e68c23f884b73b1fc38ba6318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Feed version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Feed';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'feed_items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'fields' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'conditions' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'feeds' => array(
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
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'feed_items', 'feeds'
			),
		),
	);

/**
 * Fixtures for data
 *
 * @var array $fixtures
 * @access public
 */
	public $fixtures = array(
	'core' => array(
		'Feed.FeedItem' => array(
		),
		'Feed.Feed' => array(
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
		),
		),
	);
	
/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>