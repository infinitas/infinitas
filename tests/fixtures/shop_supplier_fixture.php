<?php
/* ShopSupplier Fixture generated on: 2010-08-17 14:08:16 : 1282055236 */
class ShopSupplierFixture extends CakeTestFixture {
	var $name = 'ShopSupplier';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'slug' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'address_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'phone' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
		'fax' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
		'logo' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'product_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'terms' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'CakePhp',
			'slug' => 'cakephp',
			'address_id' => 1,
			'email' => 'cakephp@cakephp.org',
			'phone' => '123456789',
			'fax' => '123456789',
			'logo' => 'cakephp_logo_250_trans.png',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00',
			'created' => '2010-04-20 23:37:10',
			'modified' => '2010-04-29 15:16:12',
			'product_count' => 3,
			'terms' => 'Cash',
			'active' => 1
		),
	);
}
?>