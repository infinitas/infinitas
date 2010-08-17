<?php
/* ShopSpecial Fixture generated on: 2010-08-17 14:08:08 : 1282055228 */
class ShopSpecialFixture extends CakeTestFixture {
	var $name = 'ShopSpecial';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'image_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'discount' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'start_date' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'end_date' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'start_time' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'end_time' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 2,
			'product_id' => 2,
			'image_id' => 13,
			'discount' => 30.67,
			'amount' => 46.01,
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-05-04 01:14:34',
			'modified' => '2010-05-04 01:14:34',
			'start_date' => '2010-05-01',
			'end_date' => '2010-05-13',
			'start_time' => '2001-00-00',
			'end_time' => '2001-00-00',
			'active' => 1
		),
		array(
			'id' => 3,
			'product_id' => 1,
			'image_id' => 0,
			'discount' => 0,
			'amount' => 50,
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-05-11 16:30:06',
			'modified' => '2010-05-11 16:30:06',
			'start_date' => '2010-05-10',
			'end_date' => '2010-05-17',
			'start_time' => '0000-00-00',
			'end_time' => '0000-00-00',
			'active' => 1
		),
		array(
			'id' => 5,
			'product_id' => 3,
			'image_id' => 0,
			'discount' => 10,
			'amount' => 20,
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-05-11 16:33:55',
			'modified' => '2010-05-11 16:33:55',
			'start_date' => '2010-05-10',
			'end_date' => '2010-05-14',
			'start_time' => '0000-00-00',
			'end_time' => '0000-00-00',
			'active' => 1
		),
	);
}
?>