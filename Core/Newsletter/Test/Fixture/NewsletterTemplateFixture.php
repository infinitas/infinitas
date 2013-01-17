<?php
/**
 * fixture file for Template tests.
 *
 * @package Newsletter.Fixture
 * @since 0.9b1
 */
class NewsletterTemplateFixture extends CakeTestFixture {

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'header' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'footer' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'newsletter_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'name' => array('column' => 'name', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 'template-1',
			'name' => 'first template',
			'header' => '<p style=\\"color: red;\\">this is the head</p>',
			'footer' => '<p>this is the foot</p>',
			'newsletter_count' => 2,
			'created' => '2009-12-12 17:04:07',
			'modified' => '2009-12-21 16:26:14',
		),
		array(
			'id' => 'template-2',
			'name' => 'User - Registration',
			'header' => '<p>\\r\\n	Thank-you for joining us, your account is active and you may login at www.site.com</p>',
			'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			'newsletter_count' => 0,
			'created' => '2010-05-14 16:32:42',
			'modified' => '2010-05-15 13:33:45',
		),
		array(
			'id' => 'template-3',
			'name' => 'User - Activate',
			'header' => '<p>\\r\\n	Thank you for registering, please click the link below to activate your account.</p>\\r\\n<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<p>\\r\\n	Once your account is activated you will be able to login.</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			'newsletter_count' => 0,
			'created' => '2010-05-15 13:30:46',
			'modified' => '2010-05-15 13:30:46',
		),
	);
}