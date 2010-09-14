<?php
class R4c8e68c012404e78b49538ba6318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Cms version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Cms';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'layouts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'content_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'css' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'html' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'php' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'content_count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'contents' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'body' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'start' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'end' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'rating' => array('type' => 'float', 'null' => false, 'default' => '0'),
					'rating_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'layout_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'modified_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'comment_count' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'idx_access' => array('column' => 'group_id', 'unique' => 0),
						'idx_checkout' => array('column' => 'locked', 'unique' => 0),
						'category_id' => array('column' => 'category_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'features' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'frontpages' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'modified_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'content_layouts', 'contents', 'features', 'frontpages'
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
		'Layout' => array(
			array(
				'id' => 1,
				'content_id' => 1,
				'name' => 'default',
				'css' => '	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}',
				'html' => '<div class=\"cms-content\">\r\n<div class=\"heading\">\r\n<h2>{{Content.title}}</h2>\r\n<div class=\"stats\">\r\n<div class=\"views\">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class=\"introduction quote\"><blockquote> 			<span class=\"bqstart\">&ldquo;</span> 			[[Content.introduction]] 			<span class=\"bqend\">&rdquo;</span> 		</blockquote></div>\r\n<div class=\"body\">[[Content.body]]\r\n<div class=\"stats\">\r\n<div class=\"modified\">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>',
				'php' => '',
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => '0000-00-00 00:00:00',
				'content_count' => 3,
				'active' => 1,
				'created' => '2010-01-15 00:46:16',
				'modified' => '2010-01-18 02:38:34'
			),
			array(
				'id' => 2,
				'content_id' => 2,
				'name' => 'no introduction',
				'css' => '	.quote blockquote{\r\n		line-height:180%;\r\n		margin:45px;\r\n		font-size:130%;\r\n		background-color:#EEEEEE;\r\n	}\r\n	.quote .bqstart,\r\n	.quote .bqend{\r\n		font-family:\'Lucida Grande\',Verdana,helvetica,sans-serif;\r\n		font-size:700%;\r\n		font-style:normal;\r\n		color:#FF0000;\r\n	}\r\n	.quote .bqstart{\r\n		padding-top:45px;\r\n		float:left;\r\n		height:45px;\r\n		margin-bottom:-50px;\r\n		margin-top:-20px;\r\n	}\r\n	.quote .bqend{\r\n		padding-top:5px;\r\n		float:right;\r\n		height:25px;\r\n		margin-top:0;\r\n	}\r\n\r\n	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}',
				'html' => '<div class=\"cms-content\">\r\n<div class=\"heading\">\r\n<h2>{{Content.title}}</h2>\r\n<div class=\"stats\">\r\n<div class=\"views\">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class=\"body\">[[Content.body]]\r\n<div class=\"stats\">\r\n<div class=\"modified\">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>',
				'php' => '',
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => '0000-00-00 00:00:00',
				'content_count' => 1,
				'active' => 1,
				'created' => '2010-01-15 01:44:10',
				'modified' => '2010-01-15 01:45:33'
			),
		),
		'Frontpage' => array(
			array(
				'id' => 6,
				'content_id' => 4,
				'ordering' => 0,
				'order_id' => 1,
				'created' => '2010-02-25 12:15:11',
				'modified' => '2010-02-25 12:15:11',
				'created_by' => 0,
				'modified_by' => 0
			),
			array(
				'id' => 3,
				'content_id' => 3,
				'ordering' => 2,
				'order_id' => 1,
				'created' => '2010-01-18 03:49:33',
				'modified' => '2010-01-18 03:49:33',
				'created_by' => 0,
				'modified_by' => 0
			),
			array(
				'id' => 5,
				'content_id' => 5,
				'ordering' => 3,
				'order_id' => 1,
				'created' => '2010-01-18 09:58:10',
				'modified' => '2010-02-25 12:15:19',
				'created_by' => 0,
				'modified_by' => 0
			),
		),
		'Content' => array(
			array(
				'id' => 3,
				'title' => 'What is infinitas',
				'slug' => 'what-is-infinitas',
				'body' => '<p>\r\n	Over and above the core of infinitus is an easy to use api so anything that is not included in the core can be added through easy to develop plugins.&nbsp; With infinitas being built using the ever popular CakePHP&nbsp;framework there is countless plugins already developed that can be integrated with little or no modification.</p>\r\n<p>\r\n	The core of infinitas has been developed using the MVC standard of object orintated design.&nbsp; If you are an amature php deveeloper or a veteran you will find Infinitas easy to follow and even easier to expand on.&nbsp;</p>\r\n<p>\r\n	Now that you have Infinitas running your web site, you will have time to run your business.</p>\r\n',
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'ordering' => 1,
				'group_id' => 2,
				'views' => 1,
				'active' => 1,
				'start' => '0000-00-00 00:00:00',
				'end' => '0000-00-00 00:00:00',
				'rating' => 0,
				'rating_count' => 0,
				'created' => '2010-01-18 03:37:17',
				'modified' => '2010-04-12 11:28:57',
				'layout_id' => 1,
				'created_by' => 0,
				'modified_by' => 0,
				'category_id' => 1,
				'comment_count' => 0
			),
			array(
				'id' => 4,
				'title' => 'Extending Infinitas',
				'slug' => 'extending-infinitas',
				'body' => '<p>\r\n	With infinitas built using the CakePHP&nbsp;framework with the MVC design pattern, adding functionality to your site could not be easier. Even if you are developing a plugin from scratch you have the Infinitas API&nbsp;at your disposal allowing you to create admin pages with copy / delete functionality with out even one line of code for example. Other functionalty like locking records, deleting traking creators, editors and dates content was last updated is all handled for you.</p>\r\n<p>\r\n	Full logging of create and update actions is done automaticaly and there is also full revisions of all models available.&nbsp; For more information see the development guide.</p>\r\n<p>\r\n	Future versions of Infinitas have a full plugin installer planed meaning you will not even need to use your ftp program to add plugins. The installer will work in two ways, the first being a normal installer like the one found in other popular cms&#39;s, and the second is a online installer that will display a list of trusted plugins that you can just select from.</p>\r\n',
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'ordering' => 3,
				'group_id' => 2,
				'views' => 2,
				'active' => 1,
				'start' => '0000-00-00 00:00:00',
				'end' => '0000-00-00 00:00:00',
				'rating' => 4.5,
				'rating_count' => 2,
				'created' => '2010-01-18 04:05:26',
				'modified' => '2010-04-16 21:14:40',
				'layout_id' => 1,
				'created_by' => 0,
				'modified_by' => 0,
				'category_id' => 1,
				'comment_count' => 0
			),
			array(
				'id' => 5,
				'title' => 'Contributing to Infinitas',
				'slug' => 'contributing-to-infinitas',
				'body' => '<p>\r\n	Open source software is all about the community around the application, and Infinitas is no different. With out users and developers contributing Infinitas would not get anywere. To help make it as easy as possible, we have the code hosted on <a href=\"http://github.com/infinitas\" target=\"_blank\">git</a> and the issues are being tracked on <a href=\"http://infinitas.lighthouseapp.com/dashboard\">lighthouse</a>.&nbsp; There is a lot of information for developers that are interested in helping with Infinitas on lighthouse.</p>\r\n<p>\r\n	We have a channel on irc where you can come and chat to us about issues you are having, or if you need some help integrating code / developing an application with Infinitas. We will be more than happy to help you were we can.</p>\r\n<p>\r\n	If you find an issues and would like to fix it all you need to do is have a look at the details on <a href=\"http://infinitas.lighthouseapp.com/contributor-guidelines\" target=\"_blank\">lighthouse</a>.&nbsp; Once you have submitted a patch or pushed your code fixes, dont forget to send us a pull request or let us know in the irc channel that there is code we need to pull.</p>',
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'ordering' => 2,
				'group_id' => 2,
				'views' => 1,
				'active' => 1,
				'start' => '0000-00-00 00:00:00',
				'end' => '0000-00-00 00:00:00',
				'rating' => 0,
				'rating_count' => 0,
				'created' => '2010-01-18 04:17:50',
				'modified' => '2010-04-12 11:29:07',
				'layout_id' => 1,
				'created_by' => 0,
				'modified_by' => 0,
				'category_id' => 1,
				'comment_count' => 0
			),
		),
		'Feature' => array(
			array(
				'id' => 1,
				'content_id' => 1,
				'ordering' => 1,
				'order_id' => 1,
				'created' => '2010-01-04 21:49:03',
				'created_by' => 0
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