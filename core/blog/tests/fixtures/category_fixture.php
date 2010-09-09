<?php
/* BlogCategory Fixture generated on: 2010-03-13 15:03:53 : 1268487233 */
class CategoryFixture extends CakeTestFixture {
	var $name = 'Category';

	var $table = 'blog_category';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Category 1',
			'slug' => 'category-1',
			'description' => 'this is the first category',
			'active' => 1,
			'group_id' => 1,
			'created' => '2010-03-13 15:33:52',
			'modified' => '2010-03-13 15:33:52',
			'deleted' => 0,
			'deleted_date' => null
		),
		array(
			'id' => 2,
			'name' => 'Category 2',
			'slug' => 'category-2',
			'description' => 'this is the second category',
			'active' => 1,
			'group_id' => 1,
			'created' => '2010-03-13 15:33:52',
			'modified' => '2010-03-13 15:33:52',
			'deleted' => 0,
			'deleted_date' => null
		),
	);
}
?>