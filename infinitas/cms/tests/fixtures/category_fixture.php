<?php
/* Category Fixture generated on: 2009-12-13 19:12:46 : 1260726106 */
class CategoryFixture extends CakeTestFixture {
	var $name = 'Category';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false),
		'image' => array('type' => 'string', 'null' => false),
		'image_position' => array('type' => 'string', 'null' => false, 'length' => 30),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'locked_since' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'locked_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'key' => 'index'),
		'content_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'section_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'hits' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'created_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0), 'idx_access' => array('column' => 'group_id', 'unique' => 0), 'idx_checkout' => array('column' => 'locked', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'image' => 'Lorem ipsum dolor sit amet',
			'image_position' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'active' => 1,
			'locked' => 1,
			'locked_since' => '2009-12-13 19:41:46',
			'locked_by' => 1,
			'ordering' => 1,
			'group_id' => 1,
			'content_count' => 1,
			'section_id' => 1,
			'hits' => 1,
			'created' => '2009-12-13 19:41:46',
			'modified' => '2009-12-13 19:41:46',
			'created_by' => '2009-12-13 19:41:46',
			'modified_by' => '2009-12-13 19:41:46'
		),
	);
}
?>