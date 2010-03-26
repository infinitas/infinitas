<?php
/* Content Fixture generated on: 2009-12-13 19:12:31 : 1260726631 */
class ContentFixture extends CakeTestFixture {
	var $name = 'Content';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false),
		'introduction' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'body' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'locked_since' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'locked_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_access' => array('column' => 'group_id', 'unique' => 0), 'idx_checkout' => array('column' => 'locked', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'introduction' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'locked' => 1,
			'locked_since' => '2009-12-13 19:50:31',
			'locked_by' => 1,
			'ordering' => 1,
			'group_id' => 1,
			'views' => 1,
			'created' => '2009-12-13 19:50:31',
			'modified' => '2009-12-13 19:50:31',
			'created_by' => 1,
			'modified_by' => 1,
			'category_id' => 1
		),
	);
}
?>