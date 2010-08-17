<?php
class M4c6a936e33d448e7aabd12246318cd70 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'core_feed_items' => array(
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
				'core_feeds' => array(
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
				'core_feeds_feed_items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'feed_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'feed_item_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'core_feed_items', 'core_feeds', 'core_feeds_feed_items'
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