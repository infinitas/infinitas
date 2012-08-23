<?php
/**
 * @brief fixture file for Newsletter tests.
 *
 * @package Newsletter.Fixture
 * @since 0.9b1
 */
class NewsletterFixture extends CakeTestFixture {
	public $name = 'Newsletter';
	public $table = 'newsletter_newsletters';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'campaign_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'template_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'from' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'reply_to' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'html' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'text' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'sends' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'last_sent' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => null),
		'modified_by' => array('type' => 'integer', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'campaign_id' => array('column' => 'campaign_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 7,
			'campaign_id' => 3,
			'template_id' => 0,
			'from' => 'dogmatic69@gmail.com',
			'reply_to' => 'dogmatic69@gmail.com',
			'subject' => 'asdf',
			'html' => '<p>asd</p>',
			'text' => '<p>asd</p>',
			'active' => 0,
			'sent' => 1,
			'views' => 0,
			'sends' => 0,
			'last_sent' => '0000-00-00 00:00:00',
			'created' => '2010-01-04 03:14:15',
			'modified' => '2010-01-04 03:14:15',
			'created_by' => 0,
			'modified_by' => 0
		),
		array(
			'id' => 9,
			'campaign_id' => 3,
			'template_id' => 0,
			'from' => 'dogmatic69@gmail.com',
			'reply_to' => 'dogmatic69@gmail.com',
			'subject' => 'asdf- copy ( 2010-01-04 )',
			'html' => '<p>asd</p>',
			'text' => '<p>asd</p>',
			'active' => 0,
			'sent' => 1,
			'views' => 0,
			'sends' => 0,
			'last_sent' => '0000-00-00 00:00:00',
			'created' => '2010-01-04 03:14:15',
			'modified' => '2010-01-04 03:14:15',
			'created_by' => 0,
			'modified_by' => 0
		),
		array(
			'id' => 10,
			'campaign_id' => 6,
			'template_id' => 0,
			'from' => 'gsdfgd@dssd.com',
			'reply_to' => 'gsdfgd@dssd.com',
			'subject' => 'dsfgsdf',
			'html' => '<p>dfgdsfgsd</p>',
			'text' => '<p>sdfgdsfsfsfsfsfsf</p>',
			'active' => 0,
			'sent' => 0,
			'views' => 0,
			'sends' => 0,
			'last_sent' => '0000-00-00 00:00:00',
			'created' => '2010-01-12 14:19:31',
			'modified' => '2010-01-12 14:19:31',
			'created_by' => 0,
			'modified_by' => 0
		),
	);
}