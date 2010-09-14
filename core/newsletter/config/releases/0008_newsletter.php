<?php
class R4c8fccf58f9c4e2485b158946318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Newsletter version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Newsletter';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'campaigns' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'newsletter_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 1),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'newsletters' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'campaign_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'from' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'reply_to' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'subject' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'html' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'text' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'sends' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'last_sent' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'modified_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'campaign_id' => array('column' => 'campaign_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'templates' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique'),
					'header' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'footer' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'delete' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'name' => array('column' => 'name', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'campaigns', 'newsletters', 'templates'
			),
		),
	);

/**
 * Fixtures for data
 *
 * @var array $fixtures
 * @access public
 */
	public $fixtures = array(
	'core' => array(
		'Campaign' => array(
			array(
				'id' => 6,
				'name' => '23423',
				'description' => '23423',
				'active' => 1,
				'newsletter_count' => 1,
				'template_id' => 1,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'deleted' => 0,
				'deleted_date' => NULL,
				'created' => '2010-01-04 09:23:38',
				'modified' => '2010-01-04 09:23:57'
			),
			array(
				'id' => 3,
				'name' => '436',
				'description' => '34563456546',
				'active' => 0,
				'newsletter_count' => 2,
				'template_id' => 1,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'deleted' => 0,
				'deleted_date' => NULL,
				'created' => '2009-12-12 12:47:53',
				'modified' => '2009-12-21 16:28:38'
			),
			array(
				'id' => 7,
				'name' => 'asdf',
				'description' => '<p>\r\n	sadfsdaf</p>\r\n',
				'active' => 1,
				'newsletter_count' => 0,
				'template_id' => 1,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'deleted' => 0,
				'deleted_date' => NULL,
				'created' => '2010-05-14 15:39:18',
				'modified' => '2010-05-14 15:39:18'
			),
		),
		'Newsletter' => array(
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
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
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
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
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
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-01-12 14:19:31',
				'modified' => '2010-01-12 14:19:31',
				'created_by' => 0,
				'modified_by' => 0
			),
		),
		'Template' => array(
			array(
				'id' => 1,
				'name' => 'first template',
				'header' => '<p style=\"color: red;\">this is the head</p>',
				'footer' => '<p>this is the foot</p>',
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'delete' => 0,
				'deleted_date' => NULL,
				'created' => '2009-12-12 17:04:07',
				'modified' => '2009-12-21 16:26:14'
			),
			array(
				'id' => 3,
				'name' => 'User - Activate',
				'header' => '<p>\r\n	Thank you for registering, please click the link below to activate your account.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
				'footer' => '<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Once your account is activated you will be able to login.</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'delete' => 0,
				'deleted_date' => NULL,
				'created' => '2010-05-15 13:30:46',
				'modified' => '2010-05-15 13:30:46'
			),
			array(
				'id' => 2,
				'name' => 'User - Registration',
				'header' => '<p>\r\n	Thank-you for joining us, your account is active and you may login at www.site.com</p>',
				'footer' => '<p>\r\n	&nbsp;</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'delete' => 0,
				'deleted_date' => NULL,
				'created' => '2010-05-14 16:32:42',
				'modified' => '2010-05-15 13:33:45'
			),
		),
		),
	);
	
/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>