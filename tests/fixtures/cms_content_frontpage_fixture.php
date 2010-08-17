<?php
/* CmsContentFrontpage Fixture generated on: 2010-08-17 14:08:52 : 1282055092 */
class CmsContentFrontpageFixture extends CakeTestFixture {
	var $name = 'CmsContentFrontpage';

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
			'id' => 2,
			'content_id' => 2,
			'ordering' => 1,
			'order_id' => 1,
			'created' => '2010-01-04 22:46:15',
			'modified' => '2010-01-04 22:46:15',
			'created_by' => 0,
			'modified_by' => 0
		),
	);
}
?>