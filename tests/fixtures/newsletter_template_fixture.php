<?php
/* NewsletterTemplate Fixture generated on: 2010-08-17 14:08:21 : 1282055181 */
class NewsletterTemplateFixture extends CakeTestFixture {
	var $name = 'NewsletterTemplate';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique'),
		'header' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'footer' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'delete' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'first template',
			'header' => '<p style=\"color: red;\">this is the head</p>',
			'footer' => '<p>this is the foot</p>',
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2009-12-12 17:04:07',
			'modified' => '2009-12-21 16:26:14',
			'delete' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 2,
			'name' => 'User - Registration',
			'header' => '<p>\r\n	Thank-you for joining us, your account is active and you may login at www.site.com</p>',
			'footer' => '<p>\r\n	&nbsp;</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2010-05-14 16:32:42',
			'modified' => '2010-05-15 13:33:45',
			'delete' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 3,
			'name' => 'User - Activate',
			'header' => '<p>\r\n	Thank you for registering, please click the link below to activate your account.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'footer' => '<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Once your account is activated you will be able to login.</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2010-05-15 13:30:46',
			'modified' => '2010-05-15 13:30:46',
			'delete' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
	);
}
?>