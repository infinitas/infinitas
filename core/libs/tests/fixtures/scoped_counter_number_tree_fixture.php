<?php
class ScopedCounterNumberTreeFixture extends CakeTestFixture {
	var $name = 'ScopedCounterNumberTree';
	var $table = 'scoped_counter_number_trees';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name'	=> array('type' => 'string','null' => false),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'category_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft'	=> array('type' => 'integer','null' => false),
		'rght'	=> array('type' => 'integer','null' => false),
		'children_count' => array('type' => 'integer', 'null' => false, 'default' => 0)
	);
	
	public $records = array(
		
	);
}