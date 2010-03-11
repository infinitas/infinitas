<?php
/* Aro Fixture generated on: 2010-03-11 21:03:35 : 1268336075 */
class AroFixture extends CakeTestFixture {
	var $name = 'Aro';
	var $table = 'aros';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => 0,
			'model' => 'Group',
			'foreign_key' => 1,
			'alias' => '',
			'lft' => 1,
			'rght' => 2
		),
		array(
			'id' => 2,
			'parent_id' => NULL,
			'model' => 'User',
			'foreign_key' => 1,
			'alias' => '',
			'lft' => 3,
			'rght' => 4
		),
	);
}
?>