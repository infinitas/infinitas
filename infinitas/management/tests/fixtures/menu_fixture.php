<?php
/* CoreMenu Fixture generated on: 2010-03-13 11:03:36 : 1268472216 */
class MenuFixture extends CakeTestFixture {
	var $name = 'Menu';

	var $table = 'core_menus';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index'),
		'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'menu_index' => array('column' => array('type', 'active'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Admin Menu',
			'type' => 'core_admin',
			'item_count' => 0,
			'active' => 1,
			'created' => '2010-01-27 18:07:51',
			'modified' => '2010-01-27 18:07:51'
		),
		array(
			'id' => 2,
			'name' => 'Main User Menu',
			'type' => 'main_menu',
			'item_count' => 0,
			'active' => 1,
			'created' => '2010-02-01 00:35:47',
			'modified' => '2010-02-01 00:35:47'
		),
	);
}
?>