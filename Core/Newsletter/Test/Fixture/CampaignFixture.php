<?php
/**
 * @brief fixture file for Campaign tests.
 *
 * @package Newsletter.Fixture
 * @since 0.9b1
 */
class CampaignFixture extends CakeTestFixture {
	public $name = 'Campaign';
	public $table = 'newsletter_campaigns';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'newsletter_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'template_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 3,
			'name' => '436',
			'description' => '34563456546',
			'active' => 0,
			'newsletter_count' => 2,
			'template_id' => 1,
			'created' => '2009-12-12 12:47:53',
			'modified' => '2009-12-21 16:28:38',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 6,
			'name' => '23423',
			'description' => '23423',
			'active' => 1,
			'newsletter_count' => 1,
			'template_id' => 1,
			'created' => '2010-01-04 09:23:38',
			'modified' => '2010-01-04 09:23:57',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 7,
			'name' => 'asdf',
			'description' => '<p>\\r\\n	sadfsdaf</p>\\r\\n',
			'active' => 1,
			'newsletter_count' => 0,
			'template_id' => 1,
			'created' => '2010-05-14 15:39:18',
			'modified' => '2010-05-14 15:39:18',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
	);
}