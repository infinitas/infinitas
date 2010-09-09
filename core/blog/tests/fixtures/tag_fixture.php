<?php
/* BlogTag Fixture generated on: 2010-03-13 15:03:08 : 1268487128 */
class TagFixture extends CakeTestFixture {
	var $name = 'Tag';

	var $table = 'blog_tags';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Test',
			'created' => '2010-03-01 00:00:00',
			'modified' => '2010-03-01 00:00:00'
		),
		array(
			'id' => 2,
			'name' => 'Good Tag',
			'created' => '2010-03-01 00:00:00',
			'modified' => '2010-03-01 00:00:00'
		),
		array(
			'id' => 3,
			'name' => 'Bad Tag',
			'created' => '2010-03-01 00:00:00',
			'modified' => '2010-03-01 00:00:00'
		),
		array(
			'id' => 4,
			'name' => 'Cool Tag',
			'created' => '2010-03-01 00:00:00',
			'modified' => '2010-03-01 00:00:00'
		),
		array(
			'id' => 5,
			'name' => 'Multi',
			'created' => '2010-03-01 00:00:00',
			'modified' => '2010-03-01 00:00:00'
		),
	);
}
?>