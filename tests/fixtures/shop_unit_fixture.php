<?php
/* ShopUnit Fixture generated on: 2010-08-17 14:08:19 : 1282055239 */
class ShopUnitFixture extends CakeTestFixture {
	var $name = 'ShopUnit';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'symbol' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 5),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'product_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Killogram',
			'slug' => '',
			'description' => '<p>\r\n	unit of weight</p>\r\n<div firebugversion=\"1.5.3\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-04-23 18:55:07',
			'modified' => '2010-04-23 18:55:07',
			'symbol' => 'Kg',
			'active' => 1,
			'product_count' => 1
		),
		array(
			'id' => 2,
			'name' => 'Each',
			'slug' => '',
			'description' => '<p>\r\n	quantity</p>\r\n<div firebugversion=\"1.5.3\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-04-23 19:06:26',
			'modified' => '2010-04-23 19:06:26',
			'symbol' => 'ea',
			'active' => 1,
			'product_count' => 2
		),
	);
}
?>