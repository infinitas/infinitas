<?php
/* NewsletterCampaign Fixture generated on: 2010-08-17 14:08:11 : 1282055171 */
class NewsletterCampaignFixture extends CakeTestFixture {
	var $name = 'NewsletterCampaign';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'newsletter_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'deleted' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 1),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 3,
			'name' => '436',
			'description' => '34563456546',
			'active' => 0,
			'newsletter_count' => 2,
			'template_id' => 1,
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
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
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2010-01-04 09:23:38',
			'modified' => '2010-01-04 09:23:57',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 7,
			'name' => 'asdf',
			'description' => '<p>\r\n	sadfsdaf</p>\r\n',
			'active' => 1,
			'newsletter_count' => 0,
			'template_id' => 1,
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2010-05-14 15:39:18',
			'modified' => '2010-05-14 15:39:18',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
	);
}
?>