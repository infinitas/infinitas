<?php
class R4c94edceb66c49d38c8678d86318cd70 extends CakeRelease {

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
				'newsletters_users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'newsletter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'newsletter_sent' => array('column' => 'sent', 'unique' => 0),
						'newsletter_newsletter_id' => array('column' => 'newsletter_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'templates' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique'),
					'header' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'footer' => array('type' => 'text', 'null' => true, 'default' => NULL),
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
				'campaigns', 'newsletters', 'newsletters_users', 'templates'
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
		'Template' => array(
			array(
				'id' => 3,
				'name' => 'User - Activate',
				'header' => '<p>\\r\\n	Thank you for registering, please click the link below to activate your account.</p>\\r\\n<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\\"1.5.4\\\" id=\\\"_firebugConsole\\\" style=\\\"display: none;\\\">\\r\\n	&nbsp;</div>',
				'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<p>\\r\\n	Once your account is activated you will be able to login.</p>\\r\\n<div firebugversion=\\\"1.5.4\\\" id=\\\"_firebugConsole\\\" style=\\\"display: none;\\\">\\r\\n	&nbsp;</div>',
				'delete' => 0,
				'deleted_date' => '0000-00-00 00:00:00',
				'created' => '2010-05-15 13:30:46',
				'modified' => '2010-05-15 13:30:46'
			),
			array(
				'id' => 2,
				'name' => 'User - Registration',
				'header' => '<p>\\r\\n	Thank-you for joining us, your account is active and you may login at www.site.com</p>',
				'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\\"1.5.4\\\" id=\\\"_firebugConsole\\\" style=\\\"display: none;\\\">\\r\\n	&nbsp;</div>',
				'delete' => 0,
				'deleted_date' => '0000-00-00 00:00:00',
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