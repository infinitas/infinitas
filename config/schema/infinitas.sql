-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306

-- Generation Time: Jan 18, 2010 at 11:42 PM
-- Server version: 5.1.34
-- PHP Version: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `infinitas`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `body` text,
  `comment_count` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  `rating_count` int(11) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `locked_by` int(11) DEFAULT NULL,
  `locked_since` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` VALUES(1, 'making cake show the primary key field.', 'making-cake-show-the-primary-key-field', '<p>By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.</p>', '<p>I have the following setup for the cms in this app</p>\r\n<pre lang="sql">\r\nCREATE TABLE `cms_content_frontpages` (   \r\n`content_id` int(11) NOT NULL DEFAULT ''0'',  \r\n`created` datetime DEFAULT NULL,   \r\n`modified` datetime DEFAULT NULL,    \r\nPRIMARY KEY (`content_id`) ) \r\nENGINE=InnoDB DEFAULT CHARSET=utf8;\r\n</pre>\r\n<p>All this is for is to select items to show as the home page so as you can guess the form is simple. shows a list of the content itmes and saves them so we get a list of the items in the controller and show them like this:</p>\r\n<pre lang="php">\r\n$contents = $this-&gt;ContentFrontpage-&gt;Content-&gt;find( ''list'' ); \r\n$this-&gt;set( compact( ''contents'' ) );\r\n</pre>\r\n<p>Now in the form all wee need is one input, a select list of the content_id''s:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'' ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>And you would think you have this simple form done.  But cake is hiding it.  What you need to do is specify the type like this</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'', array( ''type'' =&gt; ''select'' ) ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>But now there is nothing in the select list.  I then just specified the options and all was cool.  Final form looked something like below:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' ); \r\n    echo $this-&gt;Form-&gt;input( \r\n        ''content_id'', \r\n        array( \r\n            ''type'' =&gt; ''select'', \r\n            ''options'' =&gt; $contents \r\n        ) \r\n    ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>&nbsp;</p>', 1, 1, 312, 4, 3, 0, NULL, NULL, '2009-11-30 10:54:16', '2010-01-12 14:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts_tags`
--

DROP TABLE IF EXISTS `blog_posts_tags`;
CREATE TABLE `blog_posts_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `blog_posts_tags`
--

INSERT INTO `blog_posts_tags` VALUES(26, 1, 1);
INSERT INTO `blog_posts_tags` VALUES(27, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

DROP TABLE IF EXISTS `blog_tags`;
CREATE TABLE `blog_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `blog_tags`
--

INSERT INTO `blog_tags` VALUES(1, 'Cakephp', '2009-12-20 13:39:20', '2009-12-20 13:39:20');
INSERT INTO `blog_tags` VALUES(2, 'Forms', '2009-12-20 13:39:20', '2009-12-20 13:39:20');

-- --------------------------------------------------------

--
-- Table structure for table `cms_categories`
--

DROP TABLE IF EXISTS `cms_categories`;
CREATE TABLE `cms_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locked_since` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content_count` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` datetime NOT NULL,
  `modified_by` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`active`,`group_id`),
  KEY `idx_access` (`group_id`),
  KEY `idx_checkout` (`locked`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cms_categories`
--

INSERT INTO `cms_categories` VALUES(5, 'Infinitas Pages', 'infinitas-pages', '<p>This category contains some information about infinitus, and what you can do when you have infinitus running your website.</p>', 1, 0, NULL, NULL, 1, 3, 0, 1, 2, 0, '2010-01-18 02:47:12', '2010-01-18 03:03:10', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cms_category_configs`
--

DROP TABLE IF EXISTS `cms_category_configs`;
CREATE TABLE `cms_category_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `main_articles` int(11) NOT NULL DEFAULT '0',
  `columns` int(11) NOT NULL,
  `limit` int(11) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cms_category_configs`
--


-- --------------------------------------------------------

--
-- Table structure for table `cms_contents`
--

DROP TABLE IF EXISTS `cms_contents`;
CREATE TABLE `cms_contents` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL,
  `introduction` mediumtext NOT NULL,
  `body` mediumtext NOT NULL,
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locked_since` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0',
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `layout_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`group_id`),
  KEY `idx_checkout` (`locked`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cms_contents`
--

INSERT INTO `cms_contents` VALUES(3, 'What is infinitas', 'what-is-infinitas', '<p>Infinitas is the cumulation of all the great web application rolled into one easy to manage system. All the features that you would expect from top class web based content management systems, with a powerfull e-commerce platform are at your disposal.&nbsp; Infinitas has been designed to be easy enough to use as a small personal blog site, but also powerful enough to be used as corporate level intranet or e-commerce platform.</p>', '<p>Over and above the core of infinitus is an easy to use api so anything that is not included in the core can be added through easy to develop plugins.&nbsp; With infinitas being built using the ever popular CakePHP&nbsp;framework there is countless plugins already developed that can be integrated with little or no modification.</p>\r\n<p>The core of infinitas has been developed using the MVC standard of object orintated design.&nbsp; If you are an amature php deveeloper or a veteran you will find Infinitas easy to follow and even easier to expand on.&nbsp;</p>\r\n<p>Now that you have Infinitas running your web site, you will have time to run your business.</p>', 0, NULL, NULL, 0, 0, 0, 1, NULL, NULL, '2010-01-18 03:37:17', '2010-01-18 03:39:03', 1, 0, 0, 5);
INSERT INTO `cms_contents` VALUES(4, 'Extending Infinitus', 'extending-infinitus', '<p>Its never been easier to extend a web system. With the power of CakePHP''s helpers, components, behaviors, elements and plugins you can have new functionality up and running on your site in no time.&nbsp;&nbsp;&nbsp;&nbsp;</p>', '<p>With infinitas built using the CakePHP&nbsp;framework with the MVC design pattern, adding functionality to your site could not be easier. Even if you are developing a plugin from scratch you have the Infinitas API&nbsp;at your disposal allowing you to create admin pages with copy / delete functionality with out even one line of code for example. Other functionalty like locking records, deleting traking creators, editors and dates content was last updated is all handled for you.</p>\r\n<p>Full logging of create and modifing actions is logged and there is also full revisions of all models available.&nbsp; For more information see the development guide.</p>\r\n<p>Future versions of Infinitas have a full plugin installer planed meaning you will not even need to use your ftp program to add plugins. The installer will work in two ways, the first being a normal installer like the one found in other popular cms''s, and the second is a online installer that will display a list of trusted plugins that you can just select from.</p>', 0, NULL, NULL, 0, 0, 0, 1, NULL, NULL, '2010-01-18 04:05:26', '2010-01-18 09:50:14', 1, 0, 0, 5);
INSERT INTO `cms_contents` VALUES(5, 'Contributing to Infinitas', 'contributing-to-infinitas', '<p>Contributing to Infinitus is important as there is only so many hours in the day to get code into the repo. All help is welcome by the core developers and is greatly appreciated.</p>', '<p>Open source software is all about the community around the application, and Infinitas is no different. With out users and developers contributing Infinitas would not get anywere. To help make it as easy as possible, we have the code hosted on <a target="_blank" href="http://github.com/infinitas">git</a> and the issues are being tracked on <a href="http://infinitas.lighthouseapp.com/dashboard">lighthouse</a>.&nbsp; There is a lot of information for developers that are interested in helping with Infinitas on lighthouse.</p>\r\n<p>We have a channel on irc where you can come and chat to us about issues you are having, or if you need some help integrating code / developing an application with Infinitas. We will be more than happy to help you were we can.</p>\r\n<p>If you find an issues and would like to fix it all you need to do is have a look at the details on <a target="_blank" href="http://infinitas.lighthouseapp.com/contributor-guidelines">lighthouse</a>.&nbsp; Once you have submitted a patch or pushed your code fixes, dont forget to send us a pull request or let us know in the irc channel that there is code we need to pull.</p>\r\n<p>&nbsp;</p>', 0, NULL, NULL, 0, 0, 0, 1, NULL, NULL, '2010-01-18 04:17:50', '2010-01-18 09:49:46', 1, 0, 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cms_content_configs`
--

DROP TABLE IF EXISTS `cms_content_configs`;
CREATE TABLE `cms_content_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `author_alias` varchar(50) DEFAULT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cms_content_configs`
--

INSERT INTO `cms_content_configs` VALUES(1, 1, '', '', '');
INSERT INTO `cms_content_configs` VALUES(2, 2, 'bob', '', '');
INSERT INTO `cms_content_configs` VALUES(5, 3, '', 'infinitas,core,cms', 'Infinitas is a powerful content management system');
INSERT INTO `cms_content_configs` VALUES(6, 4, '', '', '');
INSERT INTO `cms_content_configs` VALUES(7, 5, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cms_content_layouts`
--

DROP TABLE IF EXISTS `cms_content_layouts`;
CREATE TABLE `cms_content_layouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `css` text NOT NULL,
  `html` text NOT NULL,
  `php` text NOT NULL,
  `locked` tinyint(4) NOT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `locked_since` datetime DEFAULT NULL,
  `content_count` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_content_layouts`
--

INSERT INTO `cms_content_layouts` VALUES(1, 1, 'default', '	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}', '<div class="cms-content">\r\n<div class="heading">\r\n<h2>{{Content.title}}</h2>\r\n<div class="stats">\r\n<div class="views">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class="introduction quote"><blockquote> 			<span class="bqstart">&ldquo;</span> 			[[Content.introduction]] 			<span class="bqend">&rdquo;</span> 		</blockquote></div>\r\n<div class="body">[[Content.body]]\r\n<div class="stats">\r\n<div class="modified">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>', '', 0, NULL, NULL, 3, 1, '2010-01-15 00:46:16', '2010-01-18 02:38:34');
INSERT INTO `cms_content_layouts` VALUES(2, 2, 'no introduction', '	.quote blockquote{\r\n		line-height:180%;\r\n		margin:45px;\r\n		font-size:130%;\r\n		background-color:#EEEEEE;\r\n	}\r\n	.quote .bqstart,\r\n	.quote .bqend{\r\n		font-family:''Lucida Grande'',Verdana,helvetica,sans-serif;\r\n		font-size:700%;\r\n		font-style:normal;\r\n		color:#FF0000;\r\n	}\r\n	.quote .bqstart{\r\n		padding-top:45px;\r\n		float:left;\r\n		height:45px;\r\n		margin-bottom:-50px;\r\n		margin-top:-20px;\r\n	}\r\n	.quote .bqend{\r\n		padding-top:5px;\r\n		float:right;\r\n		height:25px;\r\n		margin-top:0;\r\n	}\r\n\r\n	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}', '<div class="cms-content">\r\n<div class="heading">\r\n<h2>{{Content.title}}</h2>\r\n<div class="stats">\r\n<div class="views">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class="body">[[Content.body]]\r\n<div class="stats">\r\n<div class="modified">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>', '', 0, NULL, NULL, 1, 1, '2010-01-15 01:44:10', '2010-01-15 01:45:33');

-- --------------------------------------------------------

--
-- Table structure for table `cms_features`
--

DROP TABLE IF EXISTS `cms_features`;
CREATE TABLE `cms_features` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cms_features`
--

INSERT INTO `cms_features` VALUES(1, 1, 1, 1, '2010-01-04 21:49:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_frontpages`
--

DROP TABLE IF EXISTS `cms_frontpages`;
CREATE TABLE `cms_frontpages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cms_frontpages`
--

INSERT INTO `cms_frontpages` VALUES(3, 3, 1, 1, '2010-01-18 03:49:33', '2010-01-18 03:49:33', 0, 0);
INSERT INTO `cms_frontpages` VALUES(4, 4, 2, 1, '2010-01-18 09:50:56', '2010-01-18 09:50:56', 0, 0);
INSERT INTO `cms_frontpages` VALUES(5, 5, 3, 1, '2010-01-18 09:58:10', '2010-01-18 09:58:10', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `core_backups`
--

DROP TABLE IF EXISTS `core_backups`;
CREATE TABLE `core_backups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` varchar(50) DEFAULT NULL,
  `model` varchar(50) NOT NULL,
  `last_id` int(11) NOT NULL,
  `data` longtext NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `core_backups`
--


-- --------------------------------------------------------

--
-- Table structure for table `core_comments`
--

DROP TABLE IF EXISTS `core_comments`;
CREATE TABLE `core_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(128) NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `rating` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `core_comments`
--

INSERT INTO `core_comments` VALUES(1, 'Post', 1, 'bob something', 'bob@gmail.com', 'http://www.something.com', '&lt;p&gt;Redistributions of files must reributions of files s must retain the above copyright notice.Redistribtain the above copyright notice.Redistributions of files mmust retain the above copyright notice.Redistutions leust retain the above copyright notice.Redistributions of fiof files must retain the above copyright notice.Redistributions of files must retain the above copyright notice.&lt;/p&gt;', 1, 0, 3, 'approved', '2010-01-07 07:20:42');
INSERT INTO `core_comments` VALUES(2, 'Post', 1, 'bob smith', 'dogmatic69@gmail.com', 'www.google.com', '&lt;p&gt;Our expert says:  &amp;quot;Attractive reward card, particularly for AA members. Members receive 2 points for every &amp;pound;1.00 spent on motoring costs and 1 point per &amp;pound;1.00 for other spending. Non&#45;members receive 1 point for every &amp;pound;2.00 spent. Balance transfers are interest&#45;free until Jan 2011. Spend on motoring products and services, fuel and AA products are interest free until Jan 2011&amp;quot;&lt;/p&gt;', 1, 0, -4, 'spam', '2010-01-12 14:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `core_configs`
--

DROP TABLE IF EXISTS `core_configs`;
CREATE TABLE `core_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `options` text NOT NULL,
  `description` text,
  `core` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `core_configs`
--

INSERT INTO `core_configs` VALUES(1, 'debug', '2', 'dropdown', '0,1,2,3', 'Production Mode:\r\n0: No error messages, errors, or warnings shown. Flash messages redirect.\r\n\r\nDevelopment Mode:\r\n1: Errors and warnings shown, model caches refreshed, flash messages halted.\r\n2: As in 1, but also with full debug messages and SQL output.\r\n', 1);
INSERT INTO `core_configs` VALUES(2, 'log', '1', 'bool', 'true,false', 'In case of Production Mode CakePHP gives you the possibility to continue logging errors.\r\n\r\nThe following parameters can be used:\r\nBoolean: Set true/false to activate/deactivate logging', 1);
INSERT INTO `core_configs` VALUES(3, 'Session.save', 'php', 'dropdown', 'php,cake,database', 'The preferred session handling method.\r\n\r\n''php'' -> Uses settings defined in your php.ini.\r\n''cake'' -> Saves session files in CakePHP''s /tmp directory.\r\n''database'' -> Uses CakePHP''s database sessions.', 1);
INSERT INTO `core_configs` VALUES(4, 'App.encoding', 'UTF-8', 'string', '', 'Application wide charset encoding', 1);
INSERT INTO `core_configs` VALUES(5, 'Cache.disable', 'false', 'bool', 'true,false', 'Turn off all caching application-wide.', 1);
INSERT INTO `core_configs` VALUES(6, 'Session.model', 'Session', 'string', '', 'The model name to be used for the session model.\r\n\r\n''Session.save'' must be set to ''database'' in order to utilize this constant.\r\n\r\nThe model name set here should *not* be used elsewhere in your application.', 1);
INSERT INTO `core_configs` VALUES(7, 'Session.database', 'default', 'string', '', 'The DATABASE_CONFIG::$var to use for database session handling.\r\n\r\n''Session.save'' must be set to ''database'' in order to utilize this constant.', 1);
INSERT INTO `core_configs` VALUES(8, 'Session.timeout', '120', 'integer', '', 'Session time out time (in seconds).\r\nActual value depends on ''Security.level'' setting.', 1);
INSERT INTO `core_configs` VALUES(9, 'Session.start', 'true', 'bool', 'true,false', 'If set to false, sessions are not automatically started.', 1);
INSERT INTO `core_configs` VALUES(10, 'Session.checkAgent', 'true', 'bool', 'true,false', 'When set to false, HTTP_USER_AGENT will not be checked in the session', 1);
INSERT INTO `core_configs` VALUES(11, 'Security.level', 'medium', 'dropdown', 'high,medium,low', 'The level of CakePHP security. The session timeout time defined in ''Session.timeout'' is multiplied according to the settings here.\r\n\r\n''high'' -> Session timeout in ''Session.timeout'' x 10\r\n''medium'' -> Session timeout in ''session.timeout'' x 100\r\n''low'' -> Session timeout in ''Session.timeout'' x 300\r\n\r\nsession IDs are also regenerated between requests if set to high', 1);
INSERT INTO `core_configs` VALUES(12, 'Session.cookie', 'CAKEPHP', 'string', '', 'The name of the session cookie', 1);
INSERT INTO `core_configs` VALUES(13, 'Wysiwyg.editor', 'fck', 'dropdown', 'text,fck', 'Select the wysiwyg editor that you would like to use.', 0);
INSERT INTO `core_configs` VALUES(14, 'Currency.name', 'Rand', 'string', '', '<p>The name of the default currency</p>', 0);
INSERT INTO `core_configs` VALUES(15, 'Currency.unit', 'R', 'string', '', 'The unit of the default currency', 0);
INSERT INTO `core_configs` VALUES(16, 'Language.name', 'English', 'string', '', 'The default language of the site', 0);
INSERT INTO `core_configs` VALUES(17, 'Language.code', 'En', 'string', '', 'The iso code of the default site language.', 0);
INSERT INTO `core_configs` VALUES(18, 'Blog.allow_comments', 'true', 'bool', 'true,false', 'Whether to allow comments on the blog or not. If disabled historical comments will not be displayed but will not be deleted.', 0);
INSERT INTO `core_configs` VALUES(19, 'Cms.allow_comments', 'true', 'bool', 'true,false', 'Whether to allow comments on the cms Content items or not. If disabled historical comments will not be displayed but will not be deleted.', 0);
INSERT INTO `core_configs` VALUES(20, 'Newsletter.send_count', '200', 'integer', '', 'The number of newsletters to send at a time.', 0);
INSERT INTO `core_configs` VALUES(21, 'Newsletter.send_interval', '300', 'integer', '', 'The time interval between sending emails in seconds', 0);
INSERT INTO `core_configs` VALUES(22, 'Newsletter.track_views', 'true', 'bool', 'true,false', 'Attempt to track the number of views a newsletter creates.  works with  a call back to the server.  Needs html to work', 0);
INSERT INTO `core_configs` VALUES(23, 'Newsletter.send_as', 'both', 'dropdown', 'both,html,text', 'What format to send the newsletter out as. Both is the best option as its nut uncommon for people to only accept text mails.', 0);
INSERT INTO `core_configs` VALUES(24, 'Website.name', 'Some Site', 'string', '', 'This is the name of the site that will be used in emails and on the website its self', 0);
INSERT INTO `core_configs` VALUES(25, 'Website.description', 'Some Seo information about the site', 'string', '', 'This is the main description about the site', 0);
INSERT INTO `core_configs` VALUES(26, 'Cms.auto_redirect', 'true', 'bool', 'true,false', 'When a category has only one content itme should the site automaticaly redirect to that one item of first display the category.\r\n\r\nThis will also work for sections.', 0);
INSERT INTO `core_configs` VALUES(27, 'Comments.time_limit', '4 weeks', 'string', '', 'the date the comments will stop being available. if it is set to 0 users will always be able to comment on a record.\r\n\r\nit uses strtotime() and will expire after the amount of time you specify. eg: 4 weeks - comments will be disabled 4 weeks after the post was last edited.', 0);
INSERT INTO `core_configs` VALUES(28, 'Blog.depreciate', '6 months', 'string', '', 'Uses strtotime, after this time the post will be marked as depreciated.  set to 0 to never show this message.', 0);
INSERT INTO `core_configs` VALUES(29, 'Comments.purge', '4 weeks', 'string', '', 'If set to 0 purge is disabled.  You can also enter a time string used in strtotime() like "4 weeks" and purge will remove comments that pending older than 4 weeks.', 0);
INSERT INTO `core_configs` VALUES(30, 'Comments.auto_moderate', 'false', 'bool', 'true,false', 'Set this to true for comments to be automaticaly set to active so you do not need to manually moderate them in admin.\r\n\r\nif set to false, comments will need to be activated before they are displayed on the site.', 0);
INSERT INTO `core_configs` VALUES(31, 'FileManager.base_path', 'z:/www/webroot', 'string', '', '<p>The base path for access to manage files.</p>', 0);
INSERT INTO `core_configs` VALUES(32, 'Newsletter.send_method', 'smtp', 'dropdown', 'smtp,mail,debug', '<p>This is the method that you would like to send emails with.&nbsp; Smtp requres that you have the correct ports and login details (for servers that require sending authentication ).</p>', 0);
INSERT INTO `core_configs` VALUES(33, 'Newsletter.smtp_out_going_port', '25', 'integer', '', '<p>The default port is 25 for smtp sending (outgoing mails). If you are having problems sending try findout from your host if there is another port to use.</p>', 0);
INSERT INTO `core_configs` VALUES(34, 'Newsletter.smtp_timeout', '30', 'integer', '', '<p>Smtp timeout in seconds. If you are getting timeout errors try and up this ammount a bit. The default time is 30 seconds</p>', 0);
INSERT INTO `core_configs` VALUES(35, 'Newsletter.smtp_host', 'mail.php-dev.co.za', 'string', '', '<p>This is the host address of your smtp server. There is no default. It is normaly something like mail.server.com but can be an ip address.</p>', 0);
INSERT INTO `core_configs` VALUES(36, 'Newsletter.smtp_username', 'test@php-dev.co.za', 'string', '', '<p>This is your smtp username for authenticating. It is usualy in the form of username@domain.com. If your server does not require outgoing authentication you must leave this blank.</p>', 0);
INSERT INTO `core_configs` VALUES(37, 'Newsletter.smtp_password', 'test', 'string', '', '<p>This is your password for smtp authentication. It should be left blank if there is no authentication for outgoing mails on your server.</p>', 0);
INSERT INTO `core_configs` VALUES(38, 'Newsletter.from_name', 'Dogmatic', 'string', '', '<p>This is the name you would like to have as the sender of your mails.. will default to the site name if it is empty.</p>', 0);
INSERT INTO `core_configs` VALUES(39, 'Newsletter.from_email', 'test@php-dev.co.za', 'string', '', '<p>The email address where your mails come from. This is used as the default when generating mails.</p>', 0);
INSERT INTO `core_configs` VALUES(40, 'Newsletter.template', 'default', 'string', '', '<p>This is the internal template that is used by the Newsletter plugin to send mails. If you do not know what this is do not edit it.&nbsp; The default template used is &quot;default&quot;.</p>', 0);
INSERT INTO `core_configs` VALUES(41, 'Global.pagination_select', '5,10,20,50,100', 'string', '', '<p>This is for the options in the pagiantion drop down. Any comma seperated list of integers will be generated in the pagination.</p>\r\n<p>The default is "5,10,20,50,100"</p>', 0);
INSERT INTO `core_configs` VALUES(42, 'Pagination.nothing_found_message', 'Nothing was found, try a more generic search.', 'string', '', '<p>This is the message that will show at the bottom of a page when there is no resaults.</p>', 0);
INSERT INTO `core_configs` VALUES(43, 'Blog.allow_ratings', 'true', 'bool', 'true,false', '<p>If you would like people to be able to rate your blog posts enable this option.</p>', 0);
INSERT INTO `core_configs` VALUES(44, 'Rating.time_limit', '4 weeks', 'string', '', '<p>the date the ratings will stop being available. if it is set to 0 users will always be able to comment on a record. it uses strtotime() and will expire after the amount of time you specify. eg: 4 weeks - ratings will be disabled 4 weeks after the post was last edited.</p>', 0);
INSERT INTO `core_configs` VALUES(45, 'Comment.fields', 'name,email,website,comment', 'string', '', '<p>A comma seperated list of the fields you should have in your comments. the defaut is &quot;name,email,website,comment&quot;. if you are adding other fields to the comments make sure that the fields are available in the database or the information will not be saved.</p>', 0);
INSERT INTO `core_configs` VALUES(46, 'Rating.require_auth', 'true', 'bool', 'true,false', '<p>Set to true if you would like only logged in users to be able to rate items.&nbsp; If set to false anybody will be able to rate items. The default setting is true.</p>', 0);
INSERT INTO `core_configs` VALUES(47, 'Website.blacklist_keywords', 'levitra,viagra,casino,sex,loan,finance,slots,debt,free,interesting,sorry,cool', 'string', '', '<p>A list of comma separated keywords that are used for automatic moderation of comments and reviews.</p>', 0);
INSERT INTO `core_configs` VALUES(48, 'Website.blacklist_words', '.html,.info,?,&,.de,.pl,.cn', 'string', '', '<p>A list of comma seperated words used to automaticaly moderate comments and reviews on the site.</p>', 0);
INSERT INTO `core_configs` VALUES(49, 'Reviews.auto_moderate', 'true', 'bool', 'true,false', '<p>Set this to true to alow the reviews to be automaticaly moderated for spam. If set to true the reviews will be cross checked with the data in the blacklisted keywordsconfiguration setting.</p>', 0);
INSERT INTO `core_configs` VALUES(50, 'Global.pagination_limit', '100', 'integer', '', '<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database. Setting this value to 0 will disable and alow any nomber of records to be requested. The default for this setting is 100.</p>', 0);
INSERT INTO `core_configs` VALUES(51, 'Website.home_page', 'blog', 'dropdown', 'blog,cms,shop', '<p>this is the page visitors to your site will land on when entering your domain directly</p>', 0);
INSERT INTO `core_configs` VALUES(52, 'Cms.content_layout', 'default', 'string', '', '<p>This is the default layout of your content pages for the cms.&nbsp; Have a look when editing content pages for what is available, you can set any one of the values in the dropdown as the default here.&nbsp; All values must be like &quot;my_layout&quot; and not &quot;My Layout&quot;</p>', 0);
INSERT INTO `core_configs` VALUES(53, 'Cms.content_title', 'true', 'bool', 'true,false', '<p>This sets if the heading is displayed in the content pages of your cms</p>', 0);
INSERT INTO `core_configs` VALUES(54, 'Cms.content_title_link', 'true', 'bool', 'true,false', '<p>Set this to true to make the headings links in your content itmes pages</p>', 0);
INSERT INTO `core_configs` VALUES(55, 'Cms.content_introduction_text', 'true', 'bool', 'true,false', '<p>Display the introduction text when viewing the content pages in your cms</p>', 0);
INSERT INTO `core_configs` VALUES(56, 'Cms.content_category_title', 'true', 'bool', 'true,false', '<p>This sets if the category name should be displayed in the content items page</p>', 0);
INSERT INTO `core_configs` VALUES(57, 'Cms.content_category_title_link', 'true', 'bool', 'true,false', '<p>If you have category headings displayed on the content pages this will set if they should be links</p>', 0);
INSERT INTO `core_configs` VALUES(58, 'Cms.content_rateable', 'true', 'bool', 'true,false', '<p>If this is enabled content will be rateable by users and will display the overall rating for that content item.</p>', 0);
INSERT INTO `core_configs` VALUES(59, 'Cms.content_commentable', 'true', 'bool', 'true,false', '<p>This sets if users my comment on the content items displayed in the site.</p>', 0);
INSERT INTO `core_configs` VALUES(60, 'Cms.content_show_created', 'true', 'bool', 'true,false', '<p>If this is set to true the date the article will be displayed on the content items</p>', 0);
INSERT INTO `core_configs` VALUES(61, 'Cms.content_show_author', 'true', 'bool', 'true,false', '<p>When set to true this will display the author of the article</p>', 0);
INSERT INTO `core_configs` VALUES(62, 'Cms.content_share', 'true', 'bool', 'true,false', '<p>If this is set to true some social networking links will be available for your users to share your content</p>', 0);
INSERT INTO `core_configs` VALUES(63, 'Website.read_more', 'Read more...', 'string', '', '<p>This is the text you want to be displayed in read more text.</p>', 0);

-- --------------------------------------------------------

--
-- Table structure for table `core_feeds`
--

DROP TABLE IF EXISTS `core_feeds`;
CREATE TABLE `core_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` int(11) DEFAULT NULL,
  `model` varchar(50) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `primary_key` int(11) NOT NULL,
  `body` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `core_feeds`
--


-- --------------------------------------------------------

--
-- Table structure for table `core_groups`
--

DROP TABLE IF EXISTS `core_groups`;
CREATE TABLE `core_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `core_groups`
--

INSERT INTO `core_groups` VALUES(1, 'admin', 'admin', '2009-12-16 00:06:53', '2009-12-16 00:06:53', 0, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `core_logs`
--

DROP TABLE IF EXISTS `core_logs`;
CREATE TABLE `core_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `model` varchar(100) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `change` text,
  `version_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=119 ;

--
-- Dumping data for table `core_logs`
--

INSERT INTO `core_logs` VALUES(1, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'active (1) => (0)', NULL, '2010-01-07 17:44:51');
INSERT INTO `core_logs` VALUES(2, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'active (0) => (1)', NULL, '2010-01-07 17:45:06');
INSERT INTO `core_logs` VALUES(3, 'Config (50)', 'Config (50) added by Core.User "1" (1).', 'Config', 50, 'add', 1, 'key () => (Global.pagination_limit), value () => (100), type () => (integer), core () => (0), description () => (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database.</p>)', NULL, '2010-01-07 21:00:38');
INSERT INTO `core_logs` VALUES(4, 'Config (50)', 'Config (50) updated by Core.User "1" (1).', 'Config', 50, 'edit', 1, 'value (100) => (0), description (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database.</p>) => (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database. Setting this value to 0 will disable and alow any nomber of records to be requested. The default for this setting is 100.</p>)', NULL, '2010-01-07 21:15:35');
INSERT INTO `core_logs` VALUES(5, 'Config (50)', 'Config (50) updated by Core.User "1" (1).', 'Config', 50, 'edit', 1, 'value (0) => (5)', NULL, '2010-01-07 21:16:22');
INSERT INTO `core_logs` VALUES(6, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-10 07:48:34) => ()', NULL, '2010-01-10 07:48:45');
INSERT INTO `core_logs` VALUES(7, 'dsfgsdf', 'Newsletter "dsfgsdf" (10) added by Core.User "1" (1).', 'Newsletter', 10, 'add', 1, 'sent () => (0), views () => (0), sends () => (0), campaign_id () => (6), from () => (gsdfgd@dssd.com), reply_to () => (gsdfgd@dssd.com), subject () => (dsfgsdf), html () => (<p>dfgdsfgsd</p>), text () => (<p>sdfgdsfsfsfsfsfsf</p>), created () => (2010-01-12 14:19:31)', NULL, '2010-01-12 14:19:31');
INSERT INTO `core_logs` VALUES(8, 'bob smith', 'Comment "bob smith" (2) added by Core.User "1" (1).', 'Comment', 2, 'add', 1, 'name () => (bob smith), email () => (dogmatic69@gmail.com), website () => (www.google.com), comment () => (&lt;p&gt;Our expert says:  &amp;quot;Attractive reward card, particularly for AA members. Members receive 2 points for every &amp;pound;1.00 spent on motoring costs and 1 point per &amp;pound;1.00 for other spending. Non&#45;members receive 1 point for every &amp;pound;2.00 spent. Balance transfers are interest&#45;free until Jan 2011. Spend on motoring products and services, fuel and AA products are interest free until Jan 2011&amp;quot;&lt;/p&gt;), class () => (Post), foreign_id () => (1), points () => (-4), status () => (spam), created () => (2010-01-12 14:23:27)', NULL, '2010-01-12 14:23:27');
INSERT INTO `core_logs` VALUES(9, '', 'Route "" (2) added by Core.User "1" (1).', 'Route', 2, 'add', 1, 'url () => (/), plugin () => (0), match_all () => (0), created () => (2010-01-13 14:58:29), ordering () => (1)', NULL, '2010-01-13 14:58:29');
INSERT INTO `core_logs` VALUES(10, '', 'Route "" (3) added by Core.User "1" (1).', 'Route', 3, 'add', 1, 'url () => (/), plugin () => (0), match_all () => (0), created () => (2010-01-13 14:59:37), ordering () => (1)', NULL, '2010-01-13 14:59:37');
INSERT INTO `core_logs` VALUES(11, 'dsf', 'Route "dsf" (4) added by Core.User "1" (1).', 'Route', 4, 'add', 1, 'name () => (dsf), url () => (/sdf), plugin () => (filemanager), controller () => (filemanager), action () => (index), match_all () => (0), created () => (2010-01-13 15:02:25), ordering () => (2)', NULL, '2010-01-13 15:02:25');
INSERT INTO `core_logs` VALUES(12, 'dsf', 'Route "dsf" (5) added by Core.User "1" (1).', 'Route', 5, 'add', 1, 'name () => (dsf), url () => (/sdf), plugin () => (filemanager), controller () => (filemanager), action () => (index), match_all () => (0), created () => (2010-01-13 15:02:45), ordering () => (3)', NULL, '2010-01-13 15:02:45');
INSERT INTO `core_logs` VALUES(13, 'dsf', 'Route "dsf" (6) added by Core.User "1" (1).', 'Route', 6, 'add', 1, 'name () => (dsf), url () => (/sdf), plugin () => (cms), controller () => (content), action () => (view), match_all () => (1), order_id () => (1), created () => (2010-01-13 15:14:14), ordering () => (4)', NULL, '2010-01-13 15:14:14');
INSERT INTO `core_logs` VALUES(14, 'dsf', 'Route (4) updated by Core.User "1" (1).', 'Route', 4, 'edit', 1, 'ordering (2) => (1)', NULL, '2010-01-13 15:46:23');
INSERT INTO `core_logs` VALUES(15, '', 'Route (3) updated by Core.User "1" (1).', 'Route', 3, 'edit', 1, 'ordering (1) => (2)', NULL, '2010-01-13 15:46:23');
INSERT INTO `core_logs` VALUES(16, 'dsf', 'Route (4) updated by Core.User "1" (1).', 'Route', 4, 'edit', 1, 'ordering (1) => (2)', NULL, '2010-01-13 15:46:35');
INSERT INTO `core_logs` VALUES(17, '', 'Route (3) updated by Core.User "1" (1).', 'Route', 3, 'edit', 1, 'ordering (2) => (1)', NULL, '2010-01-13 15:46:35');
INSERT INTO `core_logs` VALUES(18, 'Home Page', 'Route "Home Page" (7) added by Core.User "1" (1).', 'Route', 7, 'add', 1, 'name () => (Home Page), url () => (/), plugin () => (blog), controller () => (posts), match_all () => (0), force_backend () => (0), force_frontend () => (0), active () => (1), order_id () => (1), created () => (2010-01-13 16:50:39), ordering () => (2)', NULL, '2010-01-13 16:50:39');
INSERT INTO `core_logs` VALUES(19, 'Pages', 'Route "Pages" (8) added by Core.User "1" (1).', 'Route', 8, 'add', 1, 'name () => (Pages), url () => (/pages/*), plugin () => (0), controller () => (pages), action () => (display), match_all () => (0), force_backend () => (0), force_frontend () => (0), active () => (1), order_id () => (1), created () => (2010-01-13 18:26:36), ordering () => (3)', NULL, '2010-01-13 18:26:36');
INSERT INTO `core_logs` VALUES(20, 'Admin Home', 'Route "Admin Home" (9) added by Core.User "1" (1).', 'Route', 9, 'add', 1, 'name () => (Admin Home), url () => (/admin), prefix () => (admin), plugin () => (management), controller () => (management), action () => (dashboard), force_backend () => (1), force_frontend () => (0), active () => (1), order_id () => (1), created () => (2010-01-13 18:36:50), ordering () => (4)', NULL, '2010-01-13 18:36:50');
INSERT INTO `core_logs` VALUES(21, 'Admin Home- copy ( 2010-01-13 )', 'Route "Admin Home- copy ( 2010-01-13 )" (10) added by Core.User "1" (1).', 'Route', 10, 'add', 1, 'force_backend () => (1), force_frontend () => (0), order_id () => (1), core () => (0), name () => (Admin Home- copy ( 2010-01-13 )), url () => (/admin), prefix () => (admin), plugin () => (management), controller () => (management), action () => (dashboard), ordering () => (5), created () => (2010-01-13 18:39:13)', NULL, '2010-01-13 18:39:13');
INSERT INTO `core_logs` VALUES(22, 'Management Home', 'Route "Management Home" (11) added by Core.User "1" (1).', 'Route', 11, 'add', 1, 'name () => (Management Home), url () => (/), plugin () => (management), controller () => (management), action () => (dashboard), order_id () => (1), created () => (2010-01-13 18:40:23), ordering () => (6)', NULL, '2010-01-13 18:40:23');
INSERT INTO `core_logs` VALUES(23, 'Management Home', 'Route "Management Home" (11) updated by Core.User "1" (1).', 'Route', 11, 'edit', 1, 'url (/) => (/admin/management), prefix () => (admin), force_backend (0) => (1)', NULL, '2010-01-13 18:41:46');
INSERT INTO `core_logs` VALUES(24, 'Management Home', 'Route "Management Home" (11) updated by Core.User "1" (1).', 'Route', 11, 'edit', 1, 'url (/admin/management) => (/), active (0) => (1)', NULL, '2010-01-13 18:42:04');
INSERT INTO `core_logs` VALUES(25, 'Management Home', 'Route "Management Home" (11) updated by Core.User "1" (1).', 'Route', 11, 'edit', 1, 'url (/) => (/admin/management)', NULL, '2010-01-13 18:42:53');
INSERT INTO `core_logs` VALUES(26, 'Blog Home - Admin', 'Route "Blog Home - Admin" (12) added by Core.User "1" (1).', 'Route', 12, 'add', 1, 'name () => (Blog Home - Admin), url () => (/admin/blog), prefix () => (admin), plugin () => (blog), controller () => (posts), action () => (dashboard), force_backend () => (1), force_frontend () => (0), active () => (0), order_id () => (1), created () => (2010-01-13 18:45:23), ordering () => (7)', NULL, '2010-01-13 18:45:23');
INSERT INTO `core_logs` VALUES(27, 'Blog Home - Admin- copy ( 2010-01-13 )', 'Route "Blog Home - Admin- copy ( 2010-01-13 )" (13) added by Core.User "1" (1).', 'Route', 13, 'add', 1, 'force_backend () => (1), force_frontend () => (0), order_id () => (1), core () => (0), name () => (Blog Home - Admin- copy ( 2010-01-13 )), url () => (/admin/blog), prefix () => (admin), plugin () => (blog), controller () => (posts), action () => (dashboard), ordering () => (8), created () => (2010-01-13 18:47:07)', NULL, '2010-01-13 18:47:07');
INSERT INTO `core_logs` VALUES(28, 'Blog Home - frontend', 'Route "Blog Home - frontend" (13) updated by Core.User "1" (1).', 'Route', 13, 'edit', 1, 'name (Blog Home - Admin- copy ( 2010-01-13 )) => (Blog Home - frontend), url (/admin/blog) => (/blog), prefix (admin) => (), action (dashboard) => (), force_backend (1) => (0), force_frontend (0) => (1), active (0) => (1)', NULL, '2010-01-13 18:47:45');
INSERT INTO `core_logs` VALUES(29, 'Blog Home - Backend', 'Route "Blog Home - Backend" (14) added by Core.User "1" (1).', 'Route', 14, 'add', 1, 'name () => (Blog Home - Backend), url () => (/admin/blog), prefix () => (admin), plugin () => (blog), controller () => (posts), action () => (dashboard), force_backend () => (1), force_frontend () => (0), active () => (0), order_id () => (1), created () => (2010-01-13 19:01:14), ordering () => (9)', NULL, '2010-01-13 19:01:14');
INSERT INTO `core_logs` VALUES(30, 'Blog Home - Backend', 'Route "Blog Home - Backend" (12) updated by Core.User "1" (1).', 'Route', 12, 'edit', 1, 'name (Blog Home - Admin) => (Blog Home - Backend)', NULL, '2010-01-13 19:02:17');
INSERT INTO `core_logs` VALUES(31, 'Cms Home - Backend', 'Route "Cms Home - Backend" (14) updated by Core.User "1" (1).', 'Route', 14, 'edit', 1, 'name (Blog Home - Backend) => (Cms Home - Backend), url (/admin/blog) => (/cms/admin), plugin (blog) => (cms), controller (posts) => (categories), active (0) => (1)', NULL, '2010-01-13 19:03:23');
INSERT INTO `core_logs` VALUES(32, 'Cms Home - Backend', 'Route "Cms Home - Backend" (14) updated by Core.User "1" (1).', 'Route', 14, 'edit', 1, 'url (/cms/admin) => (/admin/cms)', NULL, '2010-01-13 19:04:59');
INSERT INTO `core_logs` VALUES(33, 'Cms Home - Backend- copy ( 2010-01-13 )', 'Route "Cms Home - Backend- copy ( 2010-01-13 )" (15) added by Core.User "1" (1).', 'Route', 15, 'add', 1, 'force_backend () => (1), force_frontend () => (0), order_id () => (1), core () => (0), name () => (Cms Home - Backend- copy ( 2010-01-13 )), url () => (/admin/cms), prefix () => (admin), plugin () => (cms), controller () => (categories), action () => (dashboard), ordering () => (10), created () => (2010-01-13 19:05:28)', NULL, '2010-01-13 19:05:28');
INSERT INTO `core_logs` VALUES(34, 'Cms Home - Frontend', 'Route "Cms Home - Frontend" (15) updated by Core.User "1" (1).', 'Route', 15, 'edit', 1, 'name (Cms Home - Backend- copy ( 2010-01-13 )) => (Cms Home - Frontend), url (/admin/cms) => (/cms), prefix (admin) => (), action (dashboard) => (), force_backend (1) => (0), force_frontend (0) => (1), active (0) => (1)', NULL, '2010-01-13 19:09:11');
INSERT INTO `core_logs` VALUES(35, 'Blog Home - Frontend', 'Route "Blog Home - Frontend" (13) updated by Core.User "1" (1).', 'Route', 13, 'edit', 1, 'name (Blog Home - frontend) => (Blog Home - Frontend)', NULL, '2010-01-13 19:10:00');
INSERT INTO `core_logs` VALUES(36, 'Cms Home - Frontend', 'Route "Cms Home - Frontend" (15) updated by Core.User "1" (1).', 'Route', 15, 'edit', 1, 'controller (categories) => (contentFrontpages)', NULL, '2010-01-13 19:11:14');
INSERT INTO `core_logs` VALUES(37, 'Newsletter Home - Backend', 'Route "Newsletter Home - Backend" (16) added by Core.User "1" (1).', 'Route', 16, 'add', 1, 'name () => (Newsletter Home - Backend), url () => (/admin/newsletter), prefix () => (admin), plugin () => (newsletter), controller () => (newsletters), action () => (dashboard), force_backend () => (1), force_frontend () => (0), active () => (1), order_id () => (1), created () => (2010-01-13 19:18:16), ordering () => (11)', NULL, '2010-01-13 19:18:16');
INSERT INTO `core_logs` VALUES(38, 'Newsletter Home - Backend- copy ( 2010-01-13 )', 'Route "Newsletter Home - Backend- copy ( 2010-01-13 )" (17) added by Core.User "1" (1).', 'Route', 17, 'add', 1, 'force_backend () => (1), force_frontend () => (0), order_id () => (1), core () => (0), name () => (Newsletter Home - Backend- copy ( 2010-01-13 )), url () => (/admin/newsletter), prefix () => (admin), plugin () => (newsletter), controller () => (newsletters), action () => (dashboard), ordering () => (12), created () => (2010-01-13 19:19:03)', NULL, '2010-01-13 19:19:03');
INSERT INTO `core_logs` VALUES(39, 'Blog Test', 'Route "Blog Test" (18) added by Core.User "1" (1).', 'Route', 18, 'add', 1, 'name () => (Blog Test), url () => (/:controller/:year/:month/:day), plugin () => (blog), controller () => (/posts), action () => (index), values () => (day:null\r\nhour:null), rules () => (year:[12][0-9]{3}\r\nmonth:0[1-9]|1[012]\r\nday:0[1-9]|[12][0-9]|3[01]\r\n), force_backend () => (0), force_frontend () => (1), active () => (1), order_id () => (1), created () => (2010-01-13 19:36:31), ordering () => (12)', NULL, '2010-01-13 19:36:31');
INSERT INTO `core_logs` VALUES(40, 'Blog Test', 'Route "Blog Test" (18) updated by Core.User "1" (1).', 'Route', 18, 'edit', 1, 'url (/:controller/:year/:month/:day) => (/p/:year/:month/:day)', NULL, '2010-01-13 19:38:12');
INSERT INTO `core_logs` VALUES(41, 'Blog Test', 'Route "Blog Test" (18) updated by Core.User "1" (1).', 'Route', 18, 'edit', 1, 'controller (/posts) => (posts)', NULL, '2010-01-13 19:38:38');
INSERT INTO `core_logs` VALUES(42, 'Blog Test', 'Route "Blog Test" (19) added by Core.User "1" (1).', 'Route', 19, 'add', 1, 'name () => (Blog Test), url () => (/), plugin () => (blog), controller () => (posts), action () => (index), values () => (day:null), rules () => (year:[12][0-9]{3}\r\nmonth:0[1-9]|1[012]\r\nday:0[1-9]|[12][0-9]|3[01]\r\n), force_backend () => (0), force_frontend () => (1), active () => (0), order_id () => (1), created () => (2010-01-13 20:08:29), ordering () => (13)', NULL, '2010-01-13 20:08:29');
INSERT INTO `core_logs` VALUES(43, 'Blog Test', 'Route "Blog Test" (18) updated by Core.User "1" (1).', 'Route', 18, 'edit', 1, 'url (/p/:year/:month/:day) => (/), values (day:null\r\nhour:null) => (day:null)', NULL, '2010-01-13 20:09:29');
INSERT INTO `core_logs` VALUES(44, 'Blog Test', 'Route "Blog Test" (18) updated by Core.User "1" (1).', 'Route', 18, 'edit', 1, 'url (/) => (/p/:year/:month/:day)', NULL, '2010-01-13 20:09:55');
INSERT INTO `core_logs` VALUES(45, 'Blog Test', 'Route "Blog Test" (18) updated by Core.User "1" (1).', 'Route', 18, 'edit', 1, 'url (/p/:year/:month/:day) => (/:controller/:year/:month/:day), controller (posts) => ()', NULL, '2010-01-13 20:26:17');
INSERT INTO `core_logs` VALUES(46, '', 'Route "" (20) added by Core.User "1" (1).', 'Route', 20, 'add', 1, 'plugin () => (0), force_backend () => (0), force_frontend () => (0), active () => (0), theme_id () => (0), order_id () => (1), created () => (2010-01-14 00:38:19), ordering () => (13)', NULL, '2010-01-14 00:38:19');
INSERT INTO `core_logs` VALUES(47, 'Pages', 'Route "Pages" (8) updated by Core.User "1" (1).', 'Route', 8, 'edit', 1, 'theme_id () => (4)', NULL, '2010-01-14 00:38:53');
INSERT INTO `core_logs` VALUES(48, 'sdfg', 'Theme "sdfg" (6) added by Core.User "1" (1).', 'Theme', 6, 'add', 1, 'name () => (sdfg), author () => (dsfg), url () => (dfsg), update_url () => (dfg), licence () => (dsfg), active () => (0), core () => (1), description () => (<p>dfg</p>), created () => (2010-01-14 01:17:11)', NULL, '2010-01-14 01:17:11');
INSERT INTO `core_logs` VALUES(49, '234', 'Theme "234" (6) updated by Core.User "1" (1).', 'Theme', 6, 'edit', 1, 'name (sdfg) => (234)', NULL, '2010-01-14 01:17:34');
INSERT INTO `core_logs` VALUES(50, 'Config (52)', 'Config (52) added by Core.User "1" (1).', 'Config', 52, 'add', 1, 'key () => (content_layout), value () => (default), type () => (string), core () => (0), description () => (<p>This is the default layout of your content pages for the cms.&nbsp; Have a look when editing content pages for what is available, you can set any one of the values in the dropdown as the default here.&nbsp; All values must be like &quot;my_layout&quot; and not &quot;My Layout&quot;</p>)', NULL, '2010-01-14 19:08:49');
INSERT INTO `core_logs` VALUES(51, 'Config (53)', 'Config (53) added by Core.User "1" (1).', 'Config', 53, 'add', 1, 'key () => (Cms.content_title), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>This sets if the heading is displayed in the content pages of your cms</p>)', NULL, '2010-01-14 19:13:35');
INSERT INTO `core_logs` VALUES(52, 'Config (54)', 'Config (54) added by Core.User "1" (1).', 'Config', 54, 'add', 1, 'key () => (Cms.content_title_link), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>Set this to true to make the headings links in your content itmes pages</p>)', NULL, '2010-01-14 19:14:28');
INSERT INTO `core_logs` VALUES(53, 'Config (55)', 'Config (55) added by Core.User "1" (1).', 'Config', 55, 'add', 1, 'key () => (Cms.content_introduction_text), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>Display the introduction text when viewing the content pages in your cms</p>)', NULL, '2010-01-14 19:15:22');
INSERT INTO `core_logs` VALUES(54, 'Config (56)', 'Config (56) added by Core.User "1" (1).', 'Config', 56, 'add', 1, 'key () => (Cms.content_category_title), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>This sets if the category name should be displayed in the content items page</p>)', NULL, '2010-01-14 19:16:30');
INSERT INTO `core_logs` VALUES(55, 'Config (57)', 'Config (57) added by Core.User "1" (1).', 'Config', 57, 'add', 1, 'key () => (Cms.content_category_title_link), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>If you have category headings displayed on the content pages this will set if they should be links</p>)', NULL, '2010-01-14 19:17:18');
INSERT INTO `core_logs` VALUES(56, 'Config (58)', 'Config (58) added by Core.User "1" (1).', 'Config', 58, 'add', 1, 'key () => (Cms.content_rateable), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>If this is enabled content will be rateable by users and will display the overall rating for that content item.</p>)', NULL, '2010-01-14 19:18:17');
INSERT INTO `core_logs` VALUES(57, 'Config (59)', 'Config (59) added by Core.User "1" (1).', 'Config', 59, 'add', 1, 'key () => (Cms.content_commentable), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>This sets if users my comment on the content items displayed in the site.</p>)', NULL, '2010-01-14 19:19:54');
INSERT INTO `core_logs` VALUES(58, 'Config (60)', 'Config (60) added by Core.User "1" (1).', 'Config', 60, 'add', 1, 'key () => (Cms.content_show_created), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>If this is set to true the date the article will be displayed on the content items</p>)', NULL, '2010-01-14 19:20:51');
INSERT INTO `core_logs` VALUES(59, 'Config (61)', 'Config (61) added by Core.User "1" (1).', 'Config', 61, 'add', 1, 'key () => (Cms.content_show_author), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>When set to true this will display the author of the article</p>)', NULL, '2010-01-14 19:22:05');
INSERT INTO `core_logs` VALUES(60, 'Config (62)', 'Config (62) added by Core.User "1" (1).', 'Config', 62, 'add', 1, 'key () => (Cms.content_share), value () => (true), type () => (bool), options () => (true,false), core () => (0), description () => (<p>If this is set to true some social networking links will be available for your users to share your content</p>)', NULL, '2010-01-14 19:23:07');
INSERT INTO `core_logs` VALUES(61, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:07:29) => ()', NULL, '2010-01-14 20:09:59');
INSERT INTO `core_logs` VALUES(62, '2', 'ContentConfig "2" (1) added by Core.User "1" (1).', 'ContentConfig', 1, 'add', 1, 'layout () => (0), title () => (2), title_link () => (2), introduction_text () => (2), category_title () => (2), category_title_link () => (2), rateable () => (2), commentable () => (2), show_created () => (2), show_author () => (2), share () => (2), content_id () => (1)', NULL, '2010-01-14 20:10:43');
INSERT INTO `core_logs` VALUES(63, 'asdfasd', 'Content "asdfasd" (2) updated by Core.User "1" (1).', 'Content', 2, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:11:51) => ()', NULL, '2010-01-14 20:12:43');
INSERT INTO `core_logs` VALUES(64, '0', 'ContentConfig "0" (2) added by Core.User "1" (1).', 'ContentConfig', 2, 'add', 1, 'layout () => (0), author_alias () => (bob), title () => (0), title_link () => (2), introduction_text () => (0), category_title () => (2), category_title_link () => (2), rateable () => (0), commentable () => (2), show_created () => (2), show_author () => (2), share () => (2), content_id () => (2)', NULL, '2010-01-14 20:12:43');
INSERT INTO `core_logs` VALUES(65, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:23:46) => ()', NULL, '2010-01-14 20:24:05');
INSERT INTO `core_logs` VALUES(66, '2', 'ContentConfig "2" (3) added by Core.User "1" (1).', 'ContentConfig', 3, 'add', 1, 'layout () => (2), title () => (2), title_link () => (2), introduction_text () => (2), category_title () => (2), category_title_link () => (2), rateable () => (2), commentable () => (2), show_created () => (2), show_author () => (2), share () => (2), content_id () => (1)', NULL, '2010-01-14 20:24:05');
INSERT INTO `core_logs` VALUES(67, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:25:28) => ()', NULL, '2010-01-14 20:25:39');
INSERT INTO `core_logs` VALUES(68, '2', 'ContentConfig "2" (4) added by Core.User "1" (1).', 'ContentConfig', 4, 'add', 1, 'content_id () => (1), layout () => (2), title () => (2), title_link () => (2), introduction_text () => (2), category_title () => (2), category_title_link () => (2), rateable () => (2), commentable () => (2), show_created () => (2), show_author () => (2), share () => (2)', NULL, '2010-01-14 20:25:39');
INSERT INTO `core_logs` VALUES(69, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:29:18) => ()', NULL, '2010-01-14 20:29:26');
INSERT INTO `core_logs` VALUES(70, '2', 'ContentConfig "2" (1) updated by Core.User "1" (1).', 'ContentConfig', 1, 'edit', 1, 'layout (0) => (2)', NULL, '2010-01-14 20:29:26');
INSERT INTO `core_logs` VALUES(71, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:30:17) => ()', NULL, '2010-01-14 20:30:30');
INSERT INTO `core_logs` VALUES(72, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:31:54) => ()', NULL, '2010-01-14 20:32:02');
INSERT INTO `core_logs` VALUES(73, '2', 'ContentConfig "2" (1) updated by Core.User "1" (1).', 'ContentConfig', 1, 'edit', 1, 'layout (2) => (default)', NULL, '2010-01-14 20:32:02');
INSERT INTO `core_logs` VALUES(74, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 20:54:18) => ()', NULL, '2010-01-14 20:54:30');
INSERT INTO `core_logs` VALUES(75, '2', 'ContentConfig "2" (1) updated by Core.User "1" (1).', 'ContentConfig', 1, 'edit', 1, 'category_title (2) => (0), category_title_link (2) => (0), rateable (2) => (1)', NULL, '2010-01-14 20:54:30');
INSERT INTO `core_logs` VALUES(76, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-14 21:02:16) => ()', NULL, '2010-01-14 21:03:33');
INSERT INTO `core_logs` VALUES(77, 'default', 'Layout "default" (1) added by Core.User "1" (1).', 'Layout', 1, 'add', 1, 'name () => (default), css () => (.test{\r\nwidth:100px;\r\n}), html () => (<p>[[Content.title]]</p>\r\n<p>&nbsp;</p>\r\n<p>{{Category.title}}</p>), created () => (2010-01-15 00:46:16)', NULL, '2010-01-15 00:46:16');
INSERT INTO `core_logs` VALUES(78, 'default', 'Layout "default" (1) updated by Core.User "1" (1).', 'Layout', 1, 'edit', 1, 'name (0) => (default), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-15 00:59:48) => ()', NULL, '2010-01-15 01:00:46');
INSERT INTO `core_logs` VALUES(79, 'default', 'Layout "default" (1) updated by Core.User "1" (1).', 'Layout', 1, 'edit', 1, 'css (.test{\r\nwidth:100px;\r\n}) => (\r\n	.quote blockquote{\r\n		line-height:180%;\r\n		margin:45px;\r\n		font-size:130%;\r\n		background-color:#EEEEEE;\r\n	}\r\n	.quote .bqstart,\r\n	.quote .bqend{\r\n		font-family:''Lucida Grande'',Verdana,helvetica,sans-serif;\r\n		font-size:700%;\r\n		font-style:normal;\r\n		color:#FF0000;\r\n	}\r\n	.quote .bqstart{\r\n		padding-top:45px;\r\n		float:left;\r\n		height:45px;\r\n		margin-bottom:-50px;\r\n		margin-top:-20px;\r\n	}\r\n	.quote .bqend{\r\n		padding-top:5px;\r\n		float:right;\r\n		height:25px;\r\n		margin-top:0;\r\n	}\r\n\r\n	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}), html (<p>[[Content.title]]</p>\r\n<p>&nbsp;</p>\r\n<p>{{Category.title}}</p>) => (<div class="cms-content">\r\n<div class="heading">\r\n<h2>{{Content.title}}</h2>\r\n<div class="stats">\r\n<div class="views">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class="introduction quote"><blockquote> 			<span class="bqstart">&ldquo;</span> 			[[Content.introduction]] 			<span class="bqend">&rdquo;</span> 		</blockquote></div>\r\n<div class="body">[[Content.body]]\r\n<div class="stats">\r\n<div class="modified">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-15 01:02:26) => ()', NULL, '2010-01-15 01:09:54');
INSERT INTO `core_logs` VALUES(80, 'default- copy ( 2010-01-15 )', 'Layout "default- copy ( 2010-01-15 )" (2) added by Core.User "1" (1).', 'Layout', 2, 'add', 1, 'content_id () => (1), name () => (default- copy ( 2010-01-15 )), css () => (\r\n	.quote blockquote{\r\n		line-height:180%;\r\n		margin:45px;\r\n		font-size:130%;\r\n		background-color:#EEEEEE;\r\n	}\r\n	.quote .bqstart,\r\n	.quote .bqend{\r\n		font-family:''Lucida Grande'',Verdana,helvetica,sans-serif;\r\n		font-size:700%;\r\n		font-style:normal;\r\n		color:#FF0000;\r\n	}\r\n	.quote .bqstart{\r\n		padding-top:45px;\r\n		float:left;\r\n		height:45px;\r\n		margin-bottom:-50px;\r\n		margin-top:-20px;\r\n	}\r\n	.quote .bqend{\r\n		padding-top:5px;\r\n		float:right;\r\n		height:25px;\r\n		margin-top:0;\r\n	}\r\n\r\n	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}), html () => (<div class="cms-content">\r\n<div class="heading">\r\n<h2>{{Content.title}}</h2>\r\n<div class="stats">\r\n<div class="views">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class="introduction quote"><blockquote> 			<span class="bqstart">&ldquo;</span> 			[[Content.introduction]] 			<span class="bqend">&rdquo;</span> 		</blockquote></div>\r\n<div class="body">[[Content.body]]\r\n<div class="stats">\r\n<div class="modified">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>), created () => (2010-01-15 01:44:10)', NULL, '2010-01-15 01:44:10');
INSERT INTO `core_logs` VALUES(81, 'no introduction', 'Layout "no introduction" (2) updated by Core.User "1" (1).', 'Layout', 2, 'edit', 1, 'name (default- copy ( 2010-01-15 )) => (no introduction), css (\r\n	.quote blockquote{\r\n		line-height:180%;\r\n		margin:45px;\r\n		font-size:130%;\r\n		background-color:#EEEEEE;\r\n	}\r\n	.quote .bqstart,\r\n	.quote .bqend{\r\n		font-family:''Lucida Grande'',Verdana,helvetica,sans-serif;\r\n		font-size:700%;\r\n		font-style:normal;\r\n		color:#FF0000;\r\n	}\r\n	.quote .bqstart{\r\n		padding-top:45px;\r\n		float:left;\r\n		height:45px;\r\n		margin-bottom:-50px;\r\n		margin-top:-20px;\r\n	}\r\n	.quote .bqend{\r\n		padding-top:5px;\r\n		float:right;\r\n		height:25px;\r\n		margin-top:0;\r\n	}\r\n\r\n	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}) => (	.quote blockquote{\r\n		line-height:180%;\r\n		margin:45px;\r\n		font-size:130%;\r\n		background-color:#EEEEEE;\r\n	}\r\n	.quote .bqstart,\r\n	.quote .bqend{\r\n		font-family:''Lucida Grande'',Verdana,helvetica,sans-serif;\r\n		font-size:700%;\r\n		font-style:normal;\r\n		color:#FF0000;\r\n	}\r\n	.quote .bqstart{\r\n		padding-top:45px;\r\n		float:left;\r\n		height:45px;\r\n		margin-bottom:-50px;\r\n		margin-top:-20px;\r\n	}\r\n	.quote .bqend{\r\n		padding-top:5px;\r\n		float:right;\r\n		height:25px;\r\n		margin-top:0;\r\n	}\r\n\r\n	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}), html (<div class="cms-content">\r\n<div class="heading">\r\n<h2>{{Content.title}}</h2>\r\n<div class="stats">\r\n<div class="views">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class="introduction quote"><blockquote> 			<span class="bqstart">&ldquo;</span> 			[[Content.introduction]] 			<span class="bqend">&rdquo;</span> 		</blockquote></div>\r\n<div class="body">[[Content.body]]\r\n<div class="stats">\r\n<div class="modified">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>) => (<div class="cms-content">\r\n<div class="heading">\r\n<h2>{{Content.title}}</h2>\r\n<div class="stats">\r\n<div class="views">||Viewed|| [[Content.views]] ||times||</div>\r\n</div>\r\n</div>\r\n<div class="body">[[Content.body]]\r\n<div class="stats">\r\n<div class="modified">||Last updated||: [[Content.modified]]</div>\r\n</div>\r\n</div>\r\n</div>), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-15 01:44:21) => ()', NULL, '2010-01-15 01:45:33');
INSERT INTO `core_logs` VALUES(82, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'introduction (<p>test</p>) => (<p>This uses a layout with a introduction</p>), body (<p>test</p>) => (<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa </p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa </p>), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-15 01:46:43) => ()', NULL, '2010-01-15 01:48:15');
INSERT INTO `core_logs` VALUES(83, 'asdfasd', 'Content "asdfasd" (2) updated by Core.User "1" (1).', 'Content', 2, 'edit', 1, 'body (<p>sadf</p>) => (<p>this does not use a introduction because its a different layout</p>\r\n<p>&nbsp;</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa b</p>\r\n<p>laa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa bla</p>\r\n<p>a blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa </p>\r\n<p>blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa<br />\r\n&nbsp;</p>), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-15 01:46:46) => ()', NULL, '2010-01-15 01:49:09');
INSERT INTO `core_logs` VALUES(84, 'asdfasd', 'Content "asdfasd" (2) updated by Core.User "1" (1).', 'Content', 2, 'edit', 1, 'body (<p>this does not use a introduction because its a different layout</p>\r\n<p>&nbsp;</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa b</p>\r\n<p>laa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa bla</p>\r\n<p>a blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa </p>\r\n<p>blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa<br />\r\n&nbsp;</p>) => (<p>this does not use a introduction because it has a different layout</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa b</p>\r\n<p>laa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa bla</p>\r\n<p>a blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa<br />\r\n&nbsp;</p>), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-15 01:50:19) => ()', NULL, '2010-01-15 01:50:58');
INSERT INTO `core_logs` VALUES(85, 'test cat content', 'Content "test cat content" (1) updated by Core.User "1" (1).', 'Content', 1, 'edit', 1, 'body (<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa </p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa </p>) => (<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-17 21:14:15) => ()', NULL, '2010-01-17 21:14:52');
INSERT INTO `core_logs` VALUES(86, 'asdfasd', 'Content "asdfasd" (2) updated by Core.User "1" (1).', 'Content', 2, 'edit', 1, 'body (<p>this does not use a introduction because it has a different layout</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa b</p>\r\n<p>laa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa bla</p>\r\n<p>a blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa<br />\r\n&nbsp;</p>) => (<p><img height="16" width="16" src="/img/content/img/hr.gif" alt="" />this does not use a introduction because it has a different layout</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa b</p>\r\n<p>laa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa bla</p>\r\n<p>a blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa<br />\r\n&nbsp;</p>), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-17 21:15:11) => ()', NULL, '2010-01-17 21:15:44');
INSERT INTO `core_logs` VALUES(87, 'Cms Home - Frontend', 'Route "Cms Home - Frontend" (15) updated by Core.User "1" (1).', 'Route', 15, 'edit', 1, 'controller (contentFrontpages) => (frontpages), theme_id () => (0)', NULL, '2010-01-17 23:38:36');
INSERT INTO `core_logs` VALUES(88, 'Config (63)', 'Config (63) added by Core.User "1" (1).', 'Config', 63, 'add', 1, 'key () => (Website.read_more), value () => (Read more...), type () => (string), core () => (0), description () => (<p>This is the text you want to be displayed in read more text.</p>)', NULL, '2010-01-18 01:20:03');
INSERT INTO `core_logs` VALUES(89, 'Cms Home - Frontend', 'Route "Cms Home - Frontend" (15) updated by Core.User "1" (1).', 'Route', 15, 'edit', 1, 'url (/cms) => (/cms/:category/:slug), controller (frontpages) => (contents), action () => (view), pass () => (:slug), active (1) => (0)', NULL, '2010-01-18 01:25:52');
INSERT INTO `core_logs` VALUES(90, 'Cms Home - Frontend', 'Route "Cms Home - Frontend" (15) updated by Core.User "1" (1).', 'Route', 15, 'edit', 1, 'url (/cms/:category/:slug) => (/cms), controller (contents) => (fontpages), action (view) => (), pass (:slug) => ()', NULL, '2010-01-18 01:34:30');
INSERT INTO `core_logs` VALUES(91, 'Blog Test- copy ( 2010-01-18 )', 'Route "Blog Test- copy ( 2010-01-18 )" (19) added by Core.User "1" (1).', 'Route', 19, 'add', 1, 'force_backend () => (0), force_frontend () => (1), order_id () => (1), core () => (0), name () => (Blog Test- copy ( 2010-01-18 )), url () => (/p/:year/:month/:day), plugin () => (blog), controller () => (posts), values () => (day:null), rules () => (year:[12][0-9]{3}\r\nmonth:0[1-9]|1[012]\r\nday:0[1-9]|[12][0-9]|3[01]\r\n), ordering () => (13), theme_id () => (1), created () => (2010-01-18 01:35:21)', NULL, '2010-01-18 01:35:21');
INSERT INTO `core_logs` VALUES(92, 'Blog Test- copy ( 2010-01-18 )', 'Route (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'ordering (13) => (12)', NULL, '2010-01-18 01:35:41');
INSERT INTO `core_logs` VALUES(93, 'Blog Test', 'Route (18) updated by Core.User "1" (1).', 'Route', 18, 'edit', 1, 'ordering (12) => (13)', NULL, '2010-01-18 01:35:41');
INSERT INTO `core_logs` VALUES(94, 'Blog Test- copy ( 2010-01-18 )', 'Route (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'ordering (12) => (11)', NULL, '2010-01-18 01:35:56');
INSERT INTO `core_logs` VALUES(95, 'Newsletter Home - Backend', 'Route (16) updated by Core.User "1" (1).', 'Route', 16, 'edit', 1, 'ordering (11) => (12)', NULL, '2010-01-18 01:35:56');
INSERT INTO `core_logs` VALUES(96, 'Cms SEO', 'Route "Cms SEO" (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'name (Blog Test- copy ( 2010-01-18 )) => (Cms SEO), url (/p/:year/:month/:day) => (/cms/:category/:slug), plugin (blog) => (cms), controller (posts) => (contents), action () => (view), values (day:null) => (category:null), rules (year:[12][0-9]{3}\r\nmonth:0[1-9]|1[012]\r\nday:0[1-9]|[12][0-9]|3[01]\r\n) => (slug:\r\n)', NULL, '2010-01-18 01:37:55');
INSERT INTO `core_logs` VALUES(97, 'Cms SEO', 'Route "Cms SEO" (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'values (category:null) => (), rules (slug:\r\n) => (\r\n)', NULL, '2010-01-18 01:38:38');
INSERT INTO `core_logs` VALUES(98, 'Cms Home - Frontend', 'Route "Cms Home - Frontend" (15) updated by Core.User "1" (1).', 'Route', 15, 'edit', 1, 'controller (fontpages) => (frontpages)', NULL, '2010-01-18 01:40:23');
INSERT INTO `core_logs` VALUES(99, 'Cms SEO', 'Route "Cms SEO" (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'values () => (category:all), rules (\r\n) => (), active (0) => (1)', NULL, '2010-01-18 01:55:47');
INSERT INTO `core_logs` VALUES(100, 'Cms SEO', 'Route "Cms SEO" (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'url (/cms/:category/:slug) => (/cms/:id-:slug), pass () => (id,slug), values (category:all) => (), rules () => (id:[0-9]+)', NULL, '2010-01-18 02:04:51');
INSERT INTO `core_logs` VALUES(101, 'Cms SEO', 'Route "Cms SEO" (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'url (/cms/:id-:slug) => (/cms/:category/:id-:slug)', NULL, '2010-01-18 02:05:59');
INSERT INTO `core_logs` VALUES(102, 'Cms SEO', 'Route "Cms SEO" (19) updated by Core.User "1" (1).', 'Route', 19, 'edit', 1, 'theme_id (1) => (0)', NULL, '2010-01-18 02:09:17');
INSERT INTO `core_logs` VALUES(103, 'default', 'Layout "default" (1) updated by Core.User "1" (1).', 'Layout', 1, 'edit', 1, 'css (\r\n	.quote blockquote{\r\n		line-height:180%;\r\n		margin:45px;\r\n		font-size:130%;\r\n		background-color:#EEEEEE;\r\n	}\r\n	.quote .bqstart,\r\n	.quote .bqend{\r\n		font-family:''Lucida Grande'',Verdana,helvetica,sans-serif;\r\n		font-size:700%;\r\n		font-style:normal;\r\n		color:#FF0000;\r\n	}\r\n	.quote .bqstart{\r\n		padding-top:45px;\r\n		float:left;\r\n		height:45px;\r\n		margin-bottom:-50px;\r\n		margin-top:-20px;\r\n	}\r\n	.quote .bqend{\r\n		padding-top:5px;\r\n		float:right;\r\n		height:25px;\r\n		margin-top:0;\r\n	}\r\n\r\n	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}) => (	.cms-content big{\r\n		font-size:120%;\r\n	}\r\n	.cms-content ol,\r\n	.cms-content ul {\r\n		list-style:lower-greek outside none;\r\n	}\r\n\r\n	.cms-content .heading{\r\n		margin-bottom:20px;\r\n	}\r\n\r\n	.cms-content .heading h2{\r\n		font-size:130%;\r\n		color:#1E379C;\r\n		padding-bottom:5px;\r\n	}\r\n\r\n	.cms-content .stats{\r\n		border-top:1px dotted #E4E4E4;\r\n	}\r\n\r\n	.cms-content .stats div{\r\n		float:left;\r\n		padding-right:20px;\r\n		font-size:80%;\r\n		padding-top:3px;\r\n	}\r\n\r\n	.cms-content .introduction{\r\n		font-style: italic;\r\n		color: #8F8F8F;\r\n	}\r\n\r\n	.cms-content p{\r\n		margin-bottom:10px;\r\n	}\r\n\r\n	.cms-content .body{\r\n		color:#535D6F;\r\n		line-height:110%;\r\n	}\r\n		.cms-content .body .stats div{\r\n			float:right;\r\n		}), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-18 02:37:22) => ()', NULL, '2010-01-18 02:38:34');
INSERT INTO `core_logs` VALUES(104, 'Infinitas Pager', 'Category "Infinitas Pager" (5) added by Core.User "1" (1).', 'Category', 5, 'add', 1, 'active () => (1), group_id () => (1), content_count () => (0), title () => (Infinitas Pager), parent_id () => (0), description () => (<p>This category contains some information about infinitus, and what you can do when you have infinitus running your website.</p>), created () => (2010-01-18 02:47:12), slug () => (infinitas-pager), lft () => (1), rght () => (2)', NULL, '2010-01-18 02:47:12');
INSERT INTO `core_logs` VALUES(105, 'Infinitas Pages', 'Category "Infinitas Pages" (5) updated by Core.User "1" (1).', 'Category', 5, 'edit', 1, 'title (Infinitas Pager) => (Infinitas Pages), parent_id (0) => (), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-18 03:02:58) => ()', NULL, '2010-01-18 03:03:10');
INSERT INTO `core_logs` VALUES(106, 'What is infinitas', 'Content "What is infinitas" (3) added by Core.User "1" (1).', 'Content', 3, 'add', 1, 'title () => (What is infinitas), introduction () => (<p>Infinitas is the cumulation of all the great web application rolled into one easy to manage system. All the features that you would expect from top class web based content management systems, with a powerfull e-commerce platform are at your disposal.&nbsp; Infinitas has been designed to be easy enough to use as a small personal blog site, but also powerful enough to be used as corporate level intranet or e-commerce platform.</p>), body () => (<p>Over and above the core of infinitus is an easy to use api so anything that is not included in the core can be added through easy to develop plugins.&nbsp; With infinitas being built using the ever popular CakePHP&nbsp;framework there is countless plugins already developed that can be integrated with little or no modification.</p>\r\n<p>The core of infinitas has been developed using the MVC standard of object orintated design.&nbsp; If you are an amature php deveeloper or a veteran you will find Infinitas easy to follow and even easier to expand on.&nbsp; </p>\r\n<p>Now that you have Infinitas running your web site, you will have time to run your business.</p>), active () => (0), layout_id () => (1), category_id () => (0), group_id () => (0), created () => (2010-01-18 03:37:17), slug () => (what-is-infinitas)', NULL, '2010-01-18 03:37:17');
INSERT INTO `core_logs` VALUES(107, 'ContentConfig (5)', 'ContentConfig (5) added by Core.User "1" (1).', 'ContentConfig', 5, 'add', 1, 'content_id () => (3)', NULL, '2010-01-18 03:37:17');
INSERT INTO `core_logs` VALUES(108, 'What is infinitas', 'Content "What is infinitas" (3) updated by Core.User "1" (1).', 'Content', 3, 'edit', 1, 'body (<p>Over and above the core of infinitus is an easy to use api so anything that is not included in the core can be added through easy to develop plugins.&nbsp; With infinitas being built using the ever popular CakePHP&nbsp;framework there is countless plugins already developed that can be integrated with little or no modification.</p>\r\n<p>The core of infinitas has been developed using the MVC standard of object orintated design.&nbsp; If you are an amature php deveeloper or a veteran you will find Infinitas easy to follow and even easier to expand on.&nbsp; </p>\r\n<p>Now that you have Infinitas running your web site, you will have time to run your business.</p>) => (<p>Over and above the core of infinitus is an easy to use api so anything that is not included in the core can be added through easy to develop plugins.&nbsp; With infinitas being built using the ever popular CakePHP&nbsp;framework there is countless plugins already developed that can be integrated with little or no modification.</p>\r\n<p>The core of infinitas has been developed using the MVC standard of object orintated design.&nbsp; If you are an amature php deveeloper or a veteran you will find Infinitas easy to follow and even easier to expand on.&nbsp;</p>\r\n<p>Now that you have Infinitas running your web site, you will have time to run your business.</p>), category_id (0) => (5), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-18 03:37:28) => ()', NULL, '2010-01-18 03:39:03');
INSERT INTO `core_logs` VALUES(109, 'ContentConfig (5)', 'ContentConfig (5) updated by Core.User "1" (1).', 'ContentConfig', 5, 'edit', 1, 'keywords () => (infinitas,core,cms), description () => (Infinitas is a powerful content management system)', NULL, '2010-01-18 03:39:03');
INSERT INTO `core_logs` VALUES(110, 'Frontpage (3)', 'Frontpage (3) added by Core.User "1" (1).', 'Frontpage', 3, 'add', 1, 'content_id () => (3), ordering () => (1), order_id () => (1), created () => (2010-01-18 03:49:33)', NULL, '2010-01-18 03:49:33');
INSERT INTO `core_logs` VALUES(111, 'Extending Infinitus', 'Content "Extending Infinitus" (4) added by Core.User "1" (1).', 'Content', 4, 'add', 1, 'title () => (Extending Infinitus), introduction () => (<p>Its never been easier to extend a web system. With the power of CakePHP''s helpers, components, behaviors, elements and plugins you can have new functionality up and running on your site in no time.&nbsp;&nbsp;&nbsp;&nbsp;</p>), body () => (<p>With infinitas built using the CakePHP&nbsp;framework with the MVC design pattern, adding functionality to your site could not be easier. Even if you are developing a plugin from scratch you have the Infinitas API&nbsp;at your disposal allowing you to create admin pages with copy / delete functionality with out even one line of code for example. Other functionalty like locking records, deleting traking creators, editors and dates content was last updated is all handled for you.</p>\r\n<p>Full logging of create and modifing actions is logged and there is also full revisions of all models available.&nbsp; For more information see the development guide.</p>\r\n<p>Future versions of Infinitas have a full plugin installer planed meaning you will not even need to use your ftp program to add plugins. The installer will work in two ways, the first being a normal installer like the one found in other popular cms''s, and the second is a online installer that will display a list of trusted plugins that you can just select from.</p>), active () => (0), layout_id () => (1), category_id () => (0), group_id () => (0), created () => (2010-01-18 04:05:26), slug () => (extending-infinitus)', NULL, '2010-01-18 04:05:26');
INSERT INTO `core_logs` VALUES(112, 'ContentConfig (6)', 'ContentConfig (6) added by Core.User "1" (1).', 'ContentConfig', 6, 'add', 1, 'content_id () => (4)', NULL, '2010-01-18 04:05:26');
INSERT INTO `core_logs` VALUES(113, 'Contributing to Infinitas', 'Content "Contributing to Infinitas" (5) added by Core.User "1" (1).', 'Content', 5, 'add', 1, 'title () => (Contributing to Infinitas), introduction () => (<p>Contributing to Infinitus is important as there is only so many hours in the day to get code into the repo. All help is welcome by the core developers and is greatly appreciated. </p>), body () => (<p>Open source software is all about the community around the application, and Infinitas is no different. With out users and developers contributing Infinitas would not get anywere. To help make it as easy as possible, we have the code hosted on <a href="http://github.com/infinitas" target="_blank">git</a> and the issues are being tracked on <a href="http://infinitas.lighthouseapp.com/dashboard">lighthouse</a>.&nbsp; There is a lot of information for developers that are interested in helping with Infinitas on lighthouse.</p>\r\n<p>We have a channel on irc where you can come and chat to us about issues you are having, or if you need some help integrating code / developing an application with Infinitas. We will be more than happy to help you were we can.</p>\r\n<p>If you find an issues and would like to fix it all you need to do is have a look at the details on <a href="http://infinitas.lighthouseapp.com/contributor-guidelines" target="_blank">lighthouse</a>.&nbsp; Once you have submitted a patch or pushed your code fixes, dont forget to send us a pull request or let us know in the irc channel that there is code we need to pull.</p>\r\n<p>&nbsp;</p>), active () => (0), layout_id () => (1), category_id () => (0), group_id () => (0), created () => (2010-01-18 04:17:50), slug () => (contributing-to-infinitas)', NULL, '2010-01-18 04:17:50');
INSERT INTO `core_logs` VALUES(114, 'ContentConfig (7)', 'ContentConfig (7) added by Core.User "1" (1).', 'ContentConfig', 7, 'add', 1, 'content_id () => (5)', NULL, '2010-01-18 04:17:50');
INSERT INTO `core_logs` VALUES(115, 'Contributing to Infinitas', 'Content "Contributing to Infinitas" (5) updated by Core.User "1" (1).', 'Content', 5, 'edit', 1, 'introduction (<p>Contributing to Infinitus is important as there is only so many hours in the day to get code into the repo. All help is welcome by the core developers and is greatly appreciated. </p>) => (<p>Contributing to Infinitus is important as there is only so many hours in the day to get code into the repo. All help is welcome by the core developers and is greatly appreciated.</p>), body (<p>Open source software is all about the community around the application, and Infinitas is no different. With out users and developers contributing Infinitas would not get anywere. To help make it as easy as possible, we have the code hosted on <a href="http://github.com/infinitas" target="_blank">git</a> and the issues are being tracked on <a href="http://infinitas.lighthouseapp.com/dashboard">lighthouse</a>.&nbsp; There is a lot of information for developers that are interested in helping with Infinitas on lighthouse.</p>\r\n<p>We have a channel on irc where you can come and chat to us about issues you are having, or if you need some help integrating code / developing an application with Infinitas. We will be more than happy to help you were we can.</p>\r\n<p>If you find an issues and would like to fix it all you need to do is have a look at the details on <a href="http://infinitas.lighthouseapp.com/contributor-guidelines" target="_blank">lighthouse</a>.&nbsp; Once you have submitted a patch or pushed your code fixes, dont forget to send us a pull request or let us know in the irc channel that there is code we need to pull.</p>\r\n<p>&nbsp;</p>) => (<p>Open source software is all about the community around the application, and Infinitas is no different. With out users and developers contributing Infinitas would not get anywere. To help make it as easy as possible, we have the code hosted on <a target="_blank" href="http://github.com/infinitas">git</a> and the issues are being tracked on <a href="http://infinitas.lighthouseapp.com/dashboard">lighthouse</a>.&nbsp; There is a lot of information for developers that are interested in helping with Infinitas on lighthouse.</p>\r\n<p>We have a channel on irc where you can come and chat to us about issues you are having, or if you need some help integrating code / developing an application with Infinitas. We will be more than happy to help you were we can.</p>\r\n<p>If you find an issues and would like to fix it all you need to do is have a look at the details on <a target="_blank" href="http://infinitas.lighthouseapp.com/contributor-guidelines">lighthouse</a>.&nbsp; Once you have submitted a patch or pushed your code fixes, dont forget to send us a pull request or let us know in the irc channel that there is code we need to pull.</p>\r\n<p>&nbsp;</p>), category_id (0) => (5), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-18 09:49:20) => ()', NULL, '2010-01-18 09:49:46');
INSERT INTO `core_logs` VALUES(116, 'Extending Infinitus', 'Content "Extending Infinitus" (4) updated by Core.User "1" (1).', 'Content', 4, 'edit', 1, 'category_id (0) => (5), locked (1) => (0), locked_by (1) => (), locked_since (2010-01-18 09:50:01) => ()', NULL, '2010-01-18 09:50:14');
INSERT INTO `core_logs` VALUES(117, 'Frontpage (4)', 'Frontpage (4) added by Core.User "1" (1).', 'Frontpage', 4, 'add', 1, 'content_id () => (4), ordering () => (2), order_id () => (1), created () => (2010-01-18 09:50:56)', NULL, '2010-01-18 09:50:56');
INSERT INTO `core_logs` VALUES(118, 'Frontpage (5)', 'Frontpage (5) added by Core.User "1" (1).', 'Frontpage', 5, 'add', 1, 'content_id () => (5), ordering () => (3), order_id () => (1), created () => (2010-01-18 09:58:10)', NULL, '2010-01-18 09:58:10');

-- --------------------------------------------------------

--
-- Table structure for table `core_modules`
--

DROP TABLE IF EXISTS `core_modules`;
CREATE TABLE `core_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `module` varchar(100) NOT NULL,
  `config` text,
  `position_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `locked_since` int(11) DEFAULT NULL,
  `show_heading` tinyint(1) NOT NULL DEFAULT '1',
  `core` tinyint(1) NOT NULL DEFAULT '0',
  `author` varchar(50) DEFAULT NULL,
  `licence` varchar(75) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `update_url` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `core_modules`
--


-- --------------------------------------------------------

--
-- Table structure for table `core_modules_routes`
--

DROP TABLE IF EXISTS `core_modules_routes`;
CREATE TABLE `core_modules_routes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `core_modules_routes`
--


-- --------------------------------------------------------

--
-- Table structure for table `core_module_positions`
--

DROP TABLE IF EXISTS `core_module_positions`;
CREATE TABLE `core_module_positions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `core_module_positions`
--

INSERT INTO `core_module_positions` VALUES(1, 'top', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(2, 'bottom', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(3, 'left', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(4, 'right', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(5, 'custom1', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(6, 'custom2', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(7, 'custom3', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(8, 'custom4', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(9, 'bread_crumbs', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(10, 'debug', '2010-01-18 21:45:23', '2010-01-18 21:45:23');
INSERT INTO `core_module_positions` VALUES(11, 'feeds', '2010-01-18 21:45:23', '2010-01-18 21:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `core_ratings`
--

DROP TABLE IF EXISTS `core_ratings`;
CREATE TABLE `core_ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(100) NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `rating` int(3) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `core_ratings`
--

INSERT INTO `core_ratings` VALUES(1, 'Blog.Post', 1, 3, 2, '127.0.0.1', '2010-01-07 07:04:37');
INSERT INTO `core_ratings` VALUES(2, 'Blog.Post', 1, 5, 3, '127.0.0.1', '2010-01-07 07:06:14');
INSERT INTO `core_ratings` VALUES(3, 'Blog.Post', 1, 4, 1, '127.0.0.1', '2010-01-07 07:06:45');

-- --------------------------------------------------------

--
-- Table structure for table `core_routes`
--

DROP TABLE IF EXISTS `core_routes`;
CREATE TABLE `core_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `core` tinyint(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `prefix` varchar(100) DEFAULT NULL,
  `plugin` varchar(50) DEFAULT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `values` text NOT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `rules` text NOT NULL,
  `force_backend` tinyint(1) NOT NULL DEFAULT '0',
  `force_frontend` tinyint(1) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `core_routes`
--

INSERT INTO `core_routes` VALUES(7, 0, 'Home Page', '/', '', 'blog', 'posts', '', '', NULL, '', 0, 0, 1, 2, NULL, 1, '2010-01-13 16:50:39', '2010-01-13 16:50:39');
INSERT INTO `core_routes` VALUES(8, 0, 'Pages', '/pages/*', '', '0', 'pages', 'display', '', '', '', 0, 0, 1, 3, 4, 1, '2010-01-13 18:26:36', '2010-01-14 00:38:53');
INSERT INTO `core_routes` VALUES(9, 0, 'Admin Home', '/admin', 'admin', 'management', 'management', 'dashboard', '', NULL, '', 1, 0, 1, 4, NULL, 1, '2010-01-13 18:36:50', '2010-01-13 18:36:50');
INSERT INTO `core_routes` VALUES(11, 0, 'Management Home', '/admin/management', 'admin', 'management', 'management', 'dashboard', '', NULL, '', 1, 0, 1, 6, NULL, 1, '2010-01-13 18:40:23', '2010-01-13 18:42:53');
INSERT INTO `core_routes` VALUES(12, 0, 'Blog Home - Backend', '/admin/blog', 'admin', 'blog', 'posts', 'dashboard', '', NULL, '', 1, 0, 1, 7, NULL, 1, '2010-01-13 18:45:23', '2010-01-13 19:02:17');
INSERT INTO `core_routes` VALUES(13, 0, 'Blog Home - Frontend', '/blog', '', 'blog', 'posts', '', '', NULL, '', 0, 1, 1, 8, NULL, 1, '2010-01-13 18:47:07', '2010-01-13 19:10:00');
INSERT INTO `core_routes` VALUES(14, 0, 'Cms Home - Backend', '/admin/cms', 'admin', 'cms', 'categories', 'dashboard', '', NULL, '', 1, 0, 1, 9, NULL, 1, '2010-01-13 19:01:14', '2010-01-13 19:04:59');
INSERT INTO `core_routes` VALUES(15, 0, 'Cms Home - Frontend', '/cms', '', 'cms', 'frontpages', '', '', '', '', 0, 1, 1, 10, 0, 1, '2010-01-13 19:05:28', '2010-01-18 01:40:23');
INSERT INTO `core_routes` VALUES(16, 0, 'Newsletter Home - Backend', '/admin/newsletter', 'admin', 'newsletter', 'newsletters', 'dashboard', '', NULL, '', 1, 0, 1, 12, NULL, 1, '2010-01-13 19:18:16', '2010-01-18 01:35:56');
INSERT INTO `core_routes` VALUES(18, 0, 'Blog Test', '/p/:year/:month/:day', '', 'blog', 'posts', '', 'day:null', NULL, 'year:[12][0-9]{3}\r\nmonth:0[1-9]|1[012]\r\nday:0[1-9]|[12][0-9]|3[01]\r\n', 0, 1, 1, 13, 1, 1, '2010-01-13 19:36:31', '2010-01-18 01:35:41');
INSERT INTO `core_routes` VALUES(19, 0, 'Cms SEO', '/cms/:category/:id-:slug', '', 'cms', 'contents', 'view', '', 'id,slug', 'id:[0-9]+', 0, 1, 1, 11, 0, 1, '2010-01-18 01:35:21', '2010-01-18 02:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `core_sessions`
--

DROP TABLE IF EXISTS `core_sessions`;
CREATE TABLE `core_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `core_sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `core_themes`
--

DROP TABLE IF EXISTS `core_themes`;
CREATE TABLE `core_themes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `author` varchar(150) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `update_url` varchar(255) DEFAULT NULL,
  `licence` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `core` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `core_themes`
--

INSERT INTO `core_themes` VALUES(1, 'default', 'This is the default infinitas theme', 'Infinitas', NULL, NULL, '', 0, 1, '2010-01-14 01:39:54', '2010-01-14 01:39:57');
INSERT INTO `core_themes` VALUES(2, 'terrafirma', NULL, '', NULL, NULL, '', 0, 0, NULL, NULL);
INSERT INTO `core_themes` VALUES(3, 'aqueous', 'A blue 3 col layout', 'Six Shooter Media\r\n', NULL, NULL, '', 0, 0, NULL, NULL);
INSERT INTO `core_themes` VALUES(4, 'aqueous_light', 'aqueous_light', '', NULL, NULL, '', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `core_users`
--

DROP TABLE IF EXISTS `core_users`;
CREATE TABLE `core_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `core_users`
--

INSERT INTO `core_users` VALUES(1, 'dogmatic', 'dogmatic69@gmail.com', 'def267b31a9443f297b593b42992da19c0e72fec', 1, '2009-12-13 01:53:54', '2009-12-13 01:53:54');
INSERT INTO `core_users` VALUES(2, 'bob', 'bob@bob.com', 'def267b31a9443f297b593b42992da19c0e72fec', 1, '2009-12-16 16:24:33', '2009-12-16 16:24:33');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_campaigns`
--

DROP TABLE IF EXISTS `newsletter_campaigns`;
CREATE TABLE `newsletter_campaigns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `newsletter_count` int(11) NOT NULL DEFAULT '0',
  `template_id` int(11) NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `locked_by` int(11) DEFAULT NULL,
  `locked_since` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `newsletter_campaigns`
--

INSERT INTO `newsletter_campaigns` VALUES(3, '436', '34563456546', 0, 2, 1, 1, 1, '2009-12-21 16:28:38', '2009-12-12 12:47:53', '2009-12-21 16:28:38');
INSERT INTO `newsletter_campaigns` VALUES(6, '23423', '23423', 1, 1, 1, 0, NULL, NULL, '2010-01-04 09:23:38', '2010-01-04 09:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_newsletters`
--

DROP TABLE IF EXISTS `newsletter_newsletters`;
CREATE TABLE `newsletter_newsletters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) DEFAULT NULL,
  `template_id` int(11) NOT NULL,
  `from` varchar(150) NOT NULL,
  `reply_to` varchar(150) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `html` text NOT NULL,
  `text` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `sends` int(11) NOT NULL DEFAULT '0',
  `last_sent` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `locked_by` int(11) DEFAULT NULL,
  `locked_since` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `newsletter_newsletters`
--

INSERT INTO `newsletter_newsletters` VALUES(7, 3, 0, 'dogmatic69@gmail.com', 'dogmatic69@gmail.com', 'asdf', '<p>asd</p>', '<p>asd</p>', 0, 1, 0, 0, NULL, 0, NULL, NULL, '2010-01-04 03:14:15', '2010-01-04 03:14:15', NULL, NULL);
INSERT INTO `newsletter_newsletters` VALUES(9, 3, 0, 'dogmatic69@gmail.com', 'dogmatic69@gmail.com', 'asdf- copy ( 2010-01-04 )', '<p>asd</p>', '<p>asd</p>', 0, 1, 0, 0, NULL, 0, NULL, NULL, '2010-01-04 03:14:15', '2010-01-04 03:14:15', NULL, NULL);
INSERT INTO `newsletter_newsletters` VALUES(10, 6, 0, 'gsdfgd@dssd.com', 'gsdfgd@dssd.com', 'dsfgsdf', '<p>dfgdsfgsd</p>', '<p>sdfgdsfsfsfsfsfsf</p>', 0, 0, 0, 0, NULL, 0, NULL, NULL, '2010-01-12 14:19:31', '2010-01-12 14:19:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_newsletters_users`
--

DROP TABLE IF EXISTS `newsletter_newsletters_users`;
CREATE TABLE `newsletter_newsletters_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `newsletter_id` int(11) NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `newsletter_sent` (`sent`),
  KEY `newsletter_newsletter_id` (`newsletter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `newsletter_newsletters_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE `newsletter_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` VALUES(1, 1, 1, '2009-12-13 01:49:32', '2009-12-13 01:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_templates`
--

DROP TABLE IF EXISTS `newsletter_templates`;
CREATE TABLE `newsletter_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `header` text,
  `footer` text,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `locked_by` int(11) DEFAULT NULL,
  `locked_since` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `newsletter_templates`
--

INSERT INTO `newsletter_templates` VALUES(1, 'first template', '<p style="color: red;">this is the head</p>', '<p>this is the foot</p>', 1, 1, '2009-12-21 16:26:14', '2009-12-12 17:04:07', '2009-12-21 16:26:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_configs`
--

DROP TABLE IF EXISTS `user_configs`;
CREATE TABLE `user_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `options` text NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_configs`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

DROP TABLE IF EXISTS `user_details`;
CREATE TABLE `user_details` (
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `landline` varchar(15) NOT NULL,
  `company` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_details`
--

