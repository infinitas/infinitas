<?php
/* CmsFrontpage Fixture generated on: 2010-08-17 14:08:01 : 1282055101 */
class CmsFrontpageFixture extends CakeTestFixture {
	var $name = 'CmsFrontpage';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 3,
			'content_id' => 3,
			'ordering' => 2,
			'order_id' => 1,
			'created' => '2010-01-18 03:49:33',
			'modified' => '2010-01-18 03:49:33',
			'created_by' => 0,
			'modified_by' => 0
		),
		array(
			'id' => 5,
			'content_id' => 5,
			'ordering' => 3,
			'order_id' => 1,
			'created' => '2010-01-18 09:58:10',
			'modified' => '2010-02-25 12:15:19',
			'created_by' => 0,
			'modified_by' => 0
		),
		array(
			'id' => 6,
			'content_id' => 4,
			'ordering' => 0,
			'order_id' => 1,
			'created' => '2010-02-25 12:15:11',
			'modified' => '2010-02-25 12:15:11',
			'created_by' => 0,
			'modified_by' => 0
		),
	);
}
?>