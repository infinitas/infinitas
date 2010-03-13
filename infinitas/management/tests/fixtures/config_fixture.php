<?php
/* CoreConfig Fixture generated on: 2010-03-13 11:03:59 : 1268471759 */
class ConfigFixture extends CakeTestFixture {
	var $name = 'Config';

	var $table = 'core_configs';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
		'value' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'options' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'config_key' => array('column' => 'key', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'key' => 'debug',
			'value' => '2',
			'type' => 'dropdown',
			'options' => '0,1,2,3',
			'description' => '<p>Production Mode: 0: No error messages, errors, or warnings shown. Flash messages redirect.  Development Mode: 1: Errors and warnings shown, model caches refreshed, flash messages halted. 2: As in 1, but also with full debug messages and SQL output.</p>',
			'core' => 1
		),
		array(
			'id' => 2,
			'key' => 'log',
			'value' => '1',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'In case of Production Mode CakePHP gives you the possibility to continue logging errors.\r\n\r\nThe following parameters can be used:\r\nBoolean: Set true/false to activate/deactivate logging',
			'core' => 1
		),
		array(
			'id' => 3,
			'key' => 'Session.save',
			'value' => 'database',
			'type' => 'dropdown',
			'options' => 'php,cake,database',
			'description' => '<p>\r\n	The preferred session handling method. &#39;php&#39; -&gt; Uses settings defined in your php.ini. &#39;cake&#39; -&gt; Saves session files in CakePHP&#39;s /tmp directory. &#39;database&#39; -&gt; Uses CakePHP&#39;s database sessions.</p>\r\n',
			'core' => 1
		),
		array(
			'id' => 4,
			'key' => 'App.encoding',
			'value' => 'UTF-8',
			'type' => 'string',
			'options' => '',
			'description' => '<p>\r\n	Application wide charset encoding</p>',
			'core' => 1
		),
		array(
			'id' => 5,
			'key' => 'Cache.disable',
			'value' => 'false',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'Turn off all caching application-wide.',
			'core' => 1
		),
		array(
			'id' => 6,
			'key' => 'Session.model',
			'value' => 'Management.Session',
			'type' => 'string',
			'options' => '',
			'description' => 'The model name to be used for the session model.\r\n\r\n\'Session.save\' must be set to \'database\' in order to utilize this constant.\r\n\r\nThe model name set here should *not* be used elsewhere in your application.',
			'core' => 1
		),
		array(
			'id' => 7,
			'key' => 'Session.database',
			'value' => 'default',
			'type' => 'string',
			'options' => '',
			'description' => 'The DATABASE_CONFIG::$var to use for database session handling.\r\n\r\n\'Session.save\' must be set to \'database\' in order to utilize this constant.',
			'core' => 1
		),
		array(
			'id' => 8,
			'key' => 'Session.timeout',
			'value' => '120',
			'type' => 'integer',
			'options' => '',
			'description' => 'Session time out time (in seconds).\r\nActual value depends on \'Security.level\' setting.',
			'core' => 1
		),
		array(
			'id' => 9,
			'key' => 'Session.start',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'If set to false, sessions are not automatically started.',
			'core' => 1
		),
		array(
			'id' => 10,
			'key' => 'Session.checkAgent',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'When set to false, HTTP_USER_AGENT will not be checked in the session',
			'core' => 1
		),
		array(
			'id' => 11,
			'key' => 'Security.level',
			'value' => 'medium',
			'type' => 'dropdown',
			'options' => 'high,medium,low',
			'description' => 'The level of CakePHP security. The session timeout time defined in \'Session.timeout\' is multiplied according to the settings here.\r\n\r\n\'high\' -> Session timeout in \'Session.timeout\' x 10\r\n\'medium\' -> Session timeout in \'session.timeout\' x 100\r\n\'low\' -> Session timeout in \'Session.timeout\' x 300\r\n\r\nsession IDs are also regenerated between requests if set to high',
			'core' => 1
		),
		array(
			'id' => 12,
			'key' => 'Session.cookie',
			'value' => 'CAKEPHP',
			'type' => 'string',
			'options' => '',
			'description' => 'The name of the session cookie',
			'core' => 1
		),
		array(
			'id' => 13,
			'key' => 'Wysiwyg.editor',
			'value' => 'ck_editor',
			'type' => 'dropdown',
			'options' => 'text,ck_editor,tiny_mce',
			'description' => '<p>Select the wysiwyg editor that you would like to use.</p>',
			'core' => 0
		),
		array(
			'id' => 14,
			'key' => 'Currency.name',
			'value' => 'Rand',
			'type' => 'string',
			'options' => '',
			'description' => '<p>The name of the default currency</p>',
			'core' => 0
		),
		array(
			'id' => 15,
			'key' => 'Currency.unit',
			'value' => 'R',
			'type' => 'string',
			'options' => '',
			'description' => 'The unit of the default currency',
			'core' => 0
		),
		array(
			'id' => 16,
			'key' => 'Language.name',
			'value' => 'English',
			'type' => 'string',
			'options' => '',
			'description' => 'The default language of the site',
			'core' => 0
		),
		array(
			'id' => 17,
			'key' => 'Language.code',
			'value' => 'En',
			'type' => 'string',
			'options' => '',
			'description' => 'The iso code of the default site language.',
			'core' => 0
		),
		array(
			'id' => 18,
			'key' => 'Blog.allow_comments',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'Whether to allow comments on the blog or not. If disabled historical comments will not be displayed but will not be deleted.',
			'core' => 0
		),
		array(
			'id' => 19,
			'key' => 'Cms.allow_comments',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'Whether to allow comments on the cms Content items or not. If disabled historical comments will not be displayed but will not be deleted.',
			'core' => 0
		),
		array(
			'id' => 20,
			'key' => 'Newsletter.send_count',
			'value' => '200',
			'type' => 'integer',
			'options' => '',
			'description' => 'The number of newsletters to send at a time.',
			'core' => 0
		),
		array(
			'id' => 21,
			'key' => 'Newsletter.send_interval',
			'value' => '300',
			'type' => 'integer',
			'options' => '',
			'description' => 'The time interval between sending emails in seconds',
			'core' => 0
		),
		array(
			'id' => 22,
			'key' => 'Newsletter.track_views',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'Attempt to track the number of views a newsletter creates.  works with  a call back to the server.  Needs html to work',
			'core' => 0
		),
		array(
			'id' => 23,
			'key' => 'Newsletter.send_as',
			'value' => 'both',
			'type' => 'dropdown',
			'options' => 'both,html,text',
			'description' => 'What format to send the newsletter out as. Both is the best option as its nut uncommon for people to only accept text mails.',
			'core' => 0
		),
		array(
			'id' => 24,
			'key' => 'Website.name',
			'value' => 'Infinitas Cms',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the name of the site that will be used in emails and on the website its self</p>',
			'core' => 0
		),
		array(
			'id' => 25,
			'key' => 'Website.description',
			'value' => 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.',
			'type' => 'string',
			'options' => '',
			'description' => 'This is the main description about the site',
			'core' => 0
		),
	);
}
?>