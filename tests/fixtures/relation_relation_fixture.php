<?php
/* RelationRelation Fixture generated on: 2010-08-17 14:08:35 : 1282055195 */
class RelationRelationFixture extends CakeTestFixture {
	var $name = 'RelationRelation';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'conditions' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'fields' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'order' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'dependent' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'limit' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'offset' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'counter_cache' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'counter_scope' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'join_table' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'with' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150),
		'association_foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'unique' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'finder_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'delete_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'insert_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'bind' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'reverse_association' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'class_name' => array('column' => 'model', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>