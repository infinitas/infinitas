<?php
/* CoreConfig Fixture generated on: 2010-08-17 14:08:15 : 1282055115 */
class CoreConfigFixture extends CakeTestFixture {
	var $name = 'CoreConfig';

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
			'description' => '<p>Production Mode: 0: No error messages, errors, or warnings shown. Flash messages redirect. Development Mode: 1: Errors and warnings shown, model caches refreshed, flash messages halted. 2: As in 1, but also with full debug messages and SQL output.</p>',
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
			'description' => 'Application wide charset encoding',
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
		array(
			'id' => 26,
			'key' => 'Cms.auto_redirect',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'When a category has only one content itme should the site automaticaly redirect to that one item of first display the category.\r\n\r\nThis will also work for sections.',
			'core' => 0
		),
		array(
			'id' => 27,
			'key' => 'Comments.time_limit',
			'value' => '4 weeks',
			'type' => 'string',
			'options' => '',
			'description' => 'the date the comments will stop being available. if it is set to 0 users will always be able to comment on a record.\r\n\r\nit uses strtotime() and will expire after the amount of time you specify. eg: 4 weeks - comments will be disabled 4 weeks after the post was last edited.',
			'core' => 0
		),
		array(
			'id' => 28,
			'key' => 'Blog.depreciate',
			'value' => '6 months',
			'type' => 'string',
			'options' => '',
			'description' => 'Uses strtotime, after this time the post will be marked as depreciated.  set to 0 to never show this message.',
			'core' => 0
		),
		array(
			'id' => 29,
			'key' => 'Comments.purge',
			'value' => '4 weeks',
			'type' => 'string',
			'options' => '',
			'description' => 'If set to 0 purge is disabled.  You can also enter a time string used in strtotime() like \"4 weeks\" and purge will remove comments that pending older than 4 weeks.',
			'core' => 0
		),
		array(
			'id' => 30,
			'key' => 'Comments.auto_moderate',
			'value' => 'false',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => 'Set this to true for comments to be automaticaly set to active so you do not need to manually moderate them in admin.\r\n\r\nif set to false, comments will need to be activated before they are displayed on the site.',
			'core' => 0
		),
		array(
			'id' => 31,
			'key' => 'FileManager.base_path',
			'value' => 'z:/www/webroot',
			'type' => 'string',
			'options' => '',
			'description' => '<p>The base path for access to manage files.</p>',
			'core' => 0
		),
		array(
			'id' => 32,
			'key' => 'Newsletter.send_method',
			'value' => 'smtp',
			'type' => 'dropdown',
			'options' => 'smtp,mail,debug',
			'description' => '<p>This is the method that you would like to send emails with.&nbsp; Smtp requres that you have the correct ports and login details (for servers that require sending authentication ).</p>',
			'core' => 0
		),
		array(
			'id' => 33,
			'key' => 'Newsletter.smtp_out_going_port',
			'value' => '25',
			'type' => 'integer',
			'options' => '',
			'description' => '<p>The default port is 25 for smtp sending (outgoing mails). If you are having problems sending try findout from your host if there is another port to use.</p>',
			'core' => 0
		),
		array(
			'id' => 34,
			'key' => 'Newsletter.smtp_timeout',
			'value' => '30',
			'type' => 'integer',
			'options' => '',
			'description' => '<p>Smtp timeout in seconds. If you are getting timeout errors try and up this ammount a bit. The default time is 30 seconds</p>',
			'core' => 0
		),
		array(
			'id' => 35,
			'key' => 'Newsletter.smtp_host',
			'value' => 'mail.php-dev.co.za',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the host address of your smtp server. There is no default. It is normaly something like mail.server.com but can be an ip address.</p>',
			'core' => 0
		),
		array(
			'id' => 36,
			'key' => 'Newsletter.smtp_username',
			'value' => 'test@php-dev.co.za',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is your smtp username for authenticating. It is usualy in the form of username@domain.com. If your server does not require outgoing authentication you must leave this blank.</p>',
			'core' => 0
		),
		array(
			'id' => 37,
			'key' => 'Newsletter.smtp_password',
			'value' => 'test',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is your password for smtp authentication. It should be left blank if there is no authentication for outgoing mails on your server.</p>',
			'core' => 0
		),
		array(
			'id' => 38,
			'key' => 'Newsletter.from_name',
			'value' => 'Dogmatic',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the name you would like to have as the sender of your mails.. will default to the site name if it is empty.</p>',
			'core' => 0
		),
		array(
			'id' => 39,
			'key' => 'Newsletter.from_email',
			'value' => 'test@php-dev.co.za',
			'type' => 'string',
			'options' => '',
			'description' => '<p>The email address where your mails come from. This is used as the default when generating mails.</p>',
			'core' => 0
		),
		array(
			'id' => 40,
			'key' => 'Newsletter.template',
			'value' => 'default',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the internal template that is used by the Newsletter plugin to send mails. If you do not know what this is do not edit it.&nbsp; The default template used is &quot;default&quot;.</p>',
			'core' => 0
		),
		array(
			'id' => 41,
			'key' => 'Global.pagination_select',
			'value' => '5,10,20,50,100',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is for the options in the pagiantion drop down. Any comma seperated list of integers will be generated in the pagination.</p>\r\n<p>The default is \"5,10,20,50,100\"</p>',
			'core' => 0
		),
		array(
			'id' => 42,
			'key' => 'Pagination.nothing_found_message',
			'value' => 'Nothing was found, try a more generic search.',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the message that will show at the bottom of a page when there is no resaults.</p>',
			'core' => 0
		),
		array(
			'id' => 43,
			'key' => 'Blog.allow_ratings',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>If you would like people to be able to rate your blog posts enable this option.</p>',
			'core' => 0
		),
		array(
			'id' => 44,
			'key' => 'Rating.time_limit',
			'value' => '4 weeks',
			'type' => 'string',
			'options' => '',
			'description' => '<p>the date the ratings will stop being available. if it is set to 0 users will always be able to comment on a record. it uses strtotime() and will expire after the amount of time you specify. eg: 4 weeks - ratings will be disabled 4 weeks after the post was last edited.</p>',
			'core' => 0
		),
		array(
			'id' => 45,
			'key' => 'Comment.fields',
			'value' => 'name,email,website,comment',
			'type' => 'string',
			'options' => '',
			'description' => '<p>A comma seperated list of the fields you should have in your comments. the defaut is &quot;name,email,website,comment&quot;. if you are adding other fields to the comments make sure that the fields are available in the database or the information will not be saved.</p>',
			'core' => 0
		),
		array(
			'id' => 46,
			'key' => 'Rating.require_auth',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>Set to true if you would like only logged in users to be able to rate items.&nbsp; If set to false anybody will be able to rate items. The default setting is true.</p>',
			'core' => 0
		),
		array(
			'id' => 47,
			'key' => 'Website.blacklist_keywords',
			'value' => 'levitra,viagra,casino,sex,loan,finance,slots,debt,free,interesting,sorry,cool',
			'type' => 'string',
			'options' => '',
			'description' => '<p>A list of comma separated keywords that are used for automatic moderation of comments and reviews.</p>',
			'core' => 0
		),
		array(
			'id' => 48,
			'key' => 'Website.blacklist_words',
			'value' => '.html,.info,?,&,.de,.pl,.cn',
			'type' => 'string',
			'options' => '',
			'description' => '<p>A list of comma seperated words used to automaticaly moderate comments and reviews on the site.</p>',
			'core' => 0
		),
		array(
			'id' => 49,
			'key' => 'Reviews.auto_moderate',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>Set this to true to alow the reviews to be automaticaly moderated for spam. If set to true the reviews will be cross checked with the data in the blacklisted keywordsconfiguration setting.</p>',
			'core' => 0
		),
		array(
			'id' => 50,
			'key' => 'Global.pagination_limit',
			'value' => '100',
			'type' => 'integer',
			'options' => '',
			'description' => '<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database. Setting this value to 0 will disable and alow any nomber of records to be requested. The default for this setting is 100.</p>',
			'core' => 0
		),
		array(
			'id' => 51,
			'key' => 'Website.home_page',
			'value' => 'cms',
			'type' => 'dropdown',
			'options' => 'blog,cms,shop',
			'description' => '<p>this is the page visitors to your site will land on when entering your domain directly</p>',
			'core' => 0
		),
		array(
			'id' => 52,
			'key' => 'Cms.content_layout',
			'value' => 'default',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the default layout of your content pages for the cms.&nbsp; Have a look when editing content pages for what is available, you can set any one of the values in the dropdown as the default here.&nbsp; All values must be like &quot;my_layout&quot; and not &quot;My Layout&quot;</p>',
			'core' => 0
		),
		array(
			'id' => 53,
			'key' => 'Cms.content_title',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>This sets if the heading is displayed in the content pages of your cms</p>',
			'core' => 0
		),
		array(
			'id' => 54,
			'key' => 'Cms.content_title_link',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>Set this to true to make the headings links in your content itmes pages</p>',
			'core' => 0
		),
		array(
			'id' => 55,
			'key' => 'Cms.content_introduction_text',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>Display the introduction text when viewing the content pages in your cms</p>',
			'core' => 0
		),
		array(
			'id' => 56,
			'key' => 'Cms.content_category_title',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>This sets if the category name should be displayed in the content items page</p>',
			'core' => 0
		),
		array(
			'id' => 57,
			'key' => 'Cms.content_category_title_link',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>If you have category headings displayed on the content pages this will set if they should be links</p>',
			'core' => 0
		),
		array(
			'id' => 58,
			'key' => 'Cms.content_rateable',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>If this is enabled content will be rateable by users and will display the overall rating for that content item.</p>',
			'core' => 0
		),
		array(
			'id' => 59,
			'key' => 'Cms.content_commentable',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>This sets if users my comment on the content items displayed in the site.</p>',
			'core' => 0
		),
		array(
			'id' => 60,
			'key' => 'Cms.content_show_created',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>If this is set to true the date the article will be displayed on the content items</p>',
			'core' => 0
		),
		array(
			'id' => 61,
			'key' => 'Cms.content_show_author',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>When set to true this will display the author of the article</p>',
			'core' => 0
		),
		array(
			'id' => 62,
			'key' => 'Cms.content_share',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>If this is set to true some social networking links will be available for your users to share your content</p>',
			'core' => 0
		),
		array(
			'id' => 63,
			'key' => 'Website.read_more',
			'value' => 'Read more...',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the text you want to be displayed in read more text.</p>',
			'core' => 0
		),
		array(
			'id' => 64,
			'key' => 'Website.password_regex',
			'value' => '^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\\s).{4,8}$',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the regex for password validation.&nbsp; the default value is ^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\\s).{4,8}$ and requires one lower case letter, one upper case letter, one digit, 6-13 length, and no spaces. If you would like to change this, remember to update the Website.password_validation to match or the users will get the wrong message.</p>',
			'core' => 0
		),
		array(
			'id' => 65,
			'key' => 'Website.password_validation',
			'value' => 'Please enter a password with one lower case letter, one upper case letter, one digit, 6-13 length, and no spaces',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the message that is displayed to the user for password validation and is use in conjunction with the Website.password_regex configuration value. Remember to change the regex if you would like a different password type. Also you will need to update the language files if you are using different languages on the site.</p>',
			'core' => 0
		),
		array(
			'id' => 66,
			'key' => 'Website.empty_select',
			'value' => 'Please select',
			'type' => 'string',
			'options' => '',
			'description' => '<p>This is the default option that you will see on dropdown\'s. The default is &quot;Please Select&quot;</p>',
			'core' => 0
		),
		array(
			'id' => 67,
			'key' => 'CORE.active_options',
			'value' => '{\"\":\"Please Select\",\"0\":\"Inactive\",\"1\":\"Active\"}',
			'type' => 'array',
			'options' => '',
			'description' => '<p>\r\n	This is just to make selects for active/inactive more consistent.</p>',
			'core' => 1
		),
		array(
			'id' => 70,
			'key' => 'CORE.core_options',
			'value' => '{\"\":\"Please Select\",\"0\":\"Extention\",\"1\":\"Core\"} ',
			'type' => 'array',
			'options' => '',
			'description' => '<p>\r\n	This is to egnerate dropdowns for core filter options</p>\r\n',
			'core' => 1
		),
		array(
			'id' => 71,
			'key' => 'Security.login_attempts',
			'value' => '3',
			'type' => 'integer',
			'options' => '',
			'description' => '<p>\r\n	This is the number of times a user can attempt to login to the system before they are blocked. Once the user is blocked they will not be able to access anything on the site untill the time limit has passed.&nbsp; The time limit is dependent on the number of times the user has been blocked before.</p>\r\n<p>\r\n	<br />\r\n	The blocking is done per ip address so others on the same ip address will be blocked.</p>\r\n',
			'core' => 1
		),
		array(
			'id' => 72,
			'key' => 'Website.admin_quick_post',
			'value' => 'blog',
			'type' => 'dropdown',
			'options' => 'cms,blog',
			'description' => '<p>\r\n	This is the quick post form you will see on the admin dashboard. Select the one that is better for your needs.</p>',
			'core' => 0
		),
		array(
			'id' => 73,
			'key' => 'Cms.allow_ratings',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>\r\n	If you would like to not allow rating of items, set this value to false. Default is true.</p>\r\n',
			'core' => 0
		),
		array(
			'id' => 74,
			'key' => 'Shop.allow_ratings',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>\r\n	Select true to alow users to rate products in your shop, If set to false the rating option will not show.</p>\r\n<div firebugversion=\"1.5.3\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'core' => 0
		),
		array(
			'id' => 75,
			'key' => 'Website.allow_login',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>\r\n	Should user be able to login to the site? this can be used to take down the logged in area of your site while doing mainanece etc.</p>\r\n',
			'core' => 0
		),
		array(
			'id' => 76,
			'key' => 'Website.allow_registration',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>\r\n	If you would like to dissable user registrations set this to false, can be useful when running an intra-net and you want to control users from the backed.</p>\r\n',
			'core' => 0
		),
		array(
			'id' => 77,
			'key' => 'Newsletter.greeting',
			'value' => 'ehlo',
			'type' => 'dropdown',
			'options' => 'ehlo,HELO',
			'description' => '<p>\r\n	the greeting type for the emails</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'core' => 0
		),
		array(
			'id' => 78,
			'key' => 'Website.email_validation',
			'value' => 'true',
			'type' => 'bool',
			'options' => 'true,false',
			'description' => '<p>\r\n	Set this to true if you would like users to validate their email address. When true a email will be sent to the email address with a link they must click to activate the account. when false, they will be activated automaticaly after registration.</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'core' => 0
		),
		array(
			'id' => 79,
			'key' => 'Currency.code',
			'value' => 'ZAR',
			'type' => 'string',
			'options' => '',
			'description' => '<p>\r\n	Valid currency code of your shop</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
			'core' => 0
		),
	);
}
?>