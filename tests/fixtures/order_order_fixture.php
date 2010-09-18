<?php
/* OrderOrder Fixture generated on: 2010-08-17 14:08:28 : 1282055188 */
class OrderOrderFixture extends CakeTestFixture {
	var $name = 'OrderOrder';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'address_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'special_instructions' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'payment_method' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'shipping_method' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 50),
		'tracking_number' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'total' => array('type' => 'float', 'null' => false, 'default' => NULL),
		'shipping' => array('type' => 'float', 'null' => false, 'default' => NULL),
		'status_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>