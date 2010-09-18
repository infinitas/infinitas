<?php
/* CmsContentLayout Fixture generated on: 2010-08-17 14:08:54 : 1282055094 */
class CmsContentLayoutFixture extends CakeTestFixture {
	var $name = 'CmsContentLayout';

	var $fields = array(
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
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
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
	);
}
?>