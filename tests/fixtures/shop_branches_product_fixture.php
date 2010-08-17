<?php
/* ShopBranchesProduct Fixture generated on: 2010-08-17 14:08:44 : 1282055204 */
class ShopBranchesProductFixture extends CakeTestFixture {
	var $name = 'ShopBranchesProduct';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 8,
			'branch_id' => 1,
			'product_id' => 3
		),
		array(
			'id' => 9,
			'branch_id' => 2,
			'product_id' => 3
		),
		array(
			'id' => 10,
			'branch_id' => 3,
			'product_id' => 3
		),
	);
}
?>