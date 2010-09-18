<?php
/* ShopSpotlight Fixture generated on: 2010-08-17 14:08:11 : 1282055231 */
class ShopSpotlightFixture extends CakeTestFixture {
	var $name = 'ShopSpotlight';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'image_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'start_date' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'end_date' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'start_time' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'end_time' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'product_id' => 1,
			'image_id' => 11,
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-04-23 18:50:26',
			'modified' => '2010-05-17 16:15:39',
			'start_date' => '2010-05-02',
			'end_date' => '2010-05-23',
			'start_time' => '0000-00-00',
			'end_time' => '0000-00-00',
			'active' => 1
		),
		array(
			'id' => 2,
			'product_id' => 3,
			'image_id' => 6,
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-05-17 16:14:59',
			'modified' => '2010-05-17 16:14:59',
			'start_date' => '2010-05-16',
			'end_date' => '2010-05-23',
			'start_time' => '0000-00-00',
			'end_time' => '0000-00-00',
			'active' => 1
		),
		array(
			'id' => 3,
			'product_id' => 2,
			'image_id' => 0,
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-05-17 16:47:25',
			'modified' => '2010-05-17 16:47:25',
			'start_date' => '2010-05-16',
			'end_date' => '2010-06-20',
			'start_time' => '0000-00-00',
			'end_time' => '0000-00-00',
			'active' => 1
		),
	);
}
?>