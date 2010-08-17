<?php
/* ShopStock Fixture generated on: 2010-08-17 14:08:13 : 1282055233 */
class ShopStockFixture extends CakeTestFixture {
	var $name = 'ShopStock';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'stock' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'branch_id' => 1,
			'product_id' => 1,
			'stock' => 120
		),
		array(
			'id' => 2,
			'branch_id' => 1,
			'product_id' => 2,
			'stock' => 15
		),
	);
}
?>