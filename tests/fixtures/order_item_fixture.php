<?php
/* OrderItem Fixture generated on: 2010-08-17 14:08:25 : 1282055185 */
class OrderItemFixture extends CakeTestFixture {
	var $name = 'OrderItem';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>