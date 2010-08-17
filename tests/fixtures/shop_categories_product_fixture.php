<?php
/* ShopCategoriesProduct Fixture generated on: 2010-08-17 14:08:53 : 1282055213 */
class ShopCategoriesProductFixture extends CakeTestFixture {
	var $name = 'ShopCategoriesProduct';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 12,
			'category_id' => 1,
			'product_id' => 10
		),
		array(
			'id' => 13,
			'category_id' => 2,
			'product_id' => 3
		),
		array(
			'id' => 14,
			'category_id' => 3,
			'product_id' => 3
		),
	);
}
?>