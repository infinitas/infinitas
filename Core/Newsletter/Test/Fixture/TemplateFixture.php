<?php
/**
 * @brief fixture file for Template tests.
 *
 * @package Newsletter.Fixture
 * @since 0.9b1
 */
class TemplateFixture extends CakeTestFixture {
	public $name = 'Template';
	public $table = 'newsletter_templates';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'header' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'footer' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'delete' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'name' => array('column' => 'name', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'name' => 'first template',
			'header' => '<p style=\\"color: red;\\">this is the head</p>',
			'footer' => '<p>this is the foot</p>',
			'created' => '2009-12-12 17:04:07',
			'modified' => '2009-12-21 16:26:14',
			'delete' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 2,
			'name' => 'User - Registration',
			'header' => '<p>\\r\\n	Thank-you for joining us, your account is active and you may login at www.site.com</p>',
			'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			'created' => '2010-05-14 16:32:42',
			'modified' => '2010-05-15 13:33:45',
			'delete' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 3,
			'name' => 'User - Activate',
			'header' => '<p>\\r\\n	Thank you for registering, please click the link below to activate your account.</p>\\r\\n<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<p>\\r\\n	Once your account is activated you will be able to login.</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			'created' => '2010-05-15 13:30:46',
			'modified' => '2010-05-15 13:30:46',
			'delete' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
	);
}