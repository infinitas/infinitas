<?php
/* CoreFeedItem Fixture generated on: 2010-08-17 14:08:19 : 1282055119 */
class CoreFeedItemFixture extends CakeTestFixture {
	var $name = 'CoreFeedItem';

	var $fields = array(
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
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>