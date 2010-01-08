-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306

-- Generation Time: Jan 08, 2010 at 12:21 PM
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` VALUES(1, 'making cake show the primary key field.', 'making-cake-show-the-primary-key-field', '<p>By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.</p>', '<p>I have the following setup for the cms in this app</p>\r\n<pre lang="sql">\r\nCREATE TABLE `cms_content_frontpages` (   \r\n`content_id` int(11) NOT NULL DEFAULT ''0'',  \r\n`created` datetime DEFAULT NULL,   \r\n`modified` datetime DEFAULT NULL,    \r\nPRIMARY KEY (`content_id`) ) \r\nENGINE=MyISAM DEFAULT CHARSET=utf8;\r\n</pre>\r\n<p>All this is for is to select items to show as the home page so as you can guess the form is simple. shows a list of the content itmes and saves them so we get a list of the items in the controller and show them like this:</p>\r\n<pre lang="php">\r\n$contents = $this-&gt;ContentFrontpage-&gt;Content-&gt;find( ''list'' ); \r\n$this-&gt;set( compact( ''contents'' ) );\r\n</pre>\r\n<p>Now in the form all wee need is one input, a select list of the content_id''s:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'' ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>And you would think you have this simple form done.  But cake is hiding it.  What you need to do is specify the type like this</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'', array( ''type'' =&gt; ''select'' ) ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>But now there is nothing in the select list.  I then just specified the options and all was cool.  Final form looked something like below:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' ); \r\n    echo $this-&gt;Form-&gt;input( \r\n        ''content_id'', \r\n        array( \r\n            ''type'' =&gt; ''select'', \r\n            ''options'' =&gt; $contents \r\n        ) \r\n    ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>&nbsp;</p>', 0, 1, 224, 4, 3, 0, NULL, NULL, '2009-11-30 10:54:16', '2010-01-07 10:28:34');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `blog_posts_tags`
--

INSERT INTO `blog_posts_tags` VALUES(25, 1, 2);
INSERT INTO `blog_posts_tags` VALUES(24, 1, 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cms_categories`
--

INSERT INTO `cms_categories` VALUES(1, 'test', 'test', '<p>test</p>', 0, 0, NULL, NULL, 1, 0, 3, 3, 6, 0, '2010-01-02 08:11:04', '2010-01-02 08:12:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_categories` VALUES(2, 'test2', 'test2', '<p>test</p>', 0, 0, NULL, NULL, 1, 6, 0, 1, 8, 0, '2010-01-02 08:11:27', '2010-01-02 08:11:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_categories` VALUES(3, 'test2.1', 'test2-1', '', 0, 1, '2010-01-04 10:28:27', 2, 1, 0, 2, 2, 7, 0, '2010-01-02 08:11:43', '2010-01-04 10:28:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_categories` VALUES(4, '123', '123', '<p>123</p>', 0, 0, NULL, NULL, 1, 0, 1, 0, 0, 0, '2010-01-04 10:27:40', '2010-01-04 10:27:40', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`group_id`),
  KEY `idx_checkout` (`locked`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cms_contents`
--

INSERT INTO `cms_contents` VALUES(1, 'test cat content', 'test-cat-content', '<p>test</p>', '<p>test</p>', 0, NULL, NULL, 1, 1, 0, 0, NULL, NULL, '2010-01-02 08:02:58', '2010-01-02 08:02:58', 0, 0, 2);
INSERT INTO `cms_contents` VALUES(2, 'asdfasd', 'asdf', '<p>asdf</p>', '<p>sadf</p>', 0, NULL, NULL, 2, 1, 0, 1, NULL, NULL, '2010-01-02 08:20:33', '2010-01-02 08:21:03', 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cms_content_frontpages`
--

DROP TABLE IF EXISTS `cms_content_frontpages`;
CREATE TABLE `cms_content_frontpages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_content_frontpages`
--

INSERT INTO `cms_content_frontpages` VALUES(2, 2, 1, 1, '2010-01-04 22:46:15', '2010-01-04 22:46:15', 0, 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cms_features`
--

INSERT INTO `cms_features` VALUES(1, 1, 1, 1, '2010-01-04 21:49:03', 0);

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `core_comments`
--

INSERT INTO `core_comments` VALUES(1, 'Post', 1, 'bob something', 'bob@gmail.com', 'http://www.something.com', '&lt;p&gt;Redistributions of files must reributions of files s must retain the above copyright notice.Redistribtain the above copyright notice.Redistributions of files mmust retain the above copyright notice.Redistutions leust retain the above copyright notice.Redistributions of fiof files must retain the above copyright notice.Redistributions of files must retain the above copyright notice.&lt;/p&gt;', 1, 0, 3, 'approved', '2010-01-07 07:20:42');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `core_logs`
--

INSERT INTO `core_logs` VALUES(1, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'active (1) => (0)', NULL, '2010-01-07 17:44:51');
INSERT INTO `core_logs` VALUES(2, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'active (0) => (1)', NULL, '2010-01-07 17:45:06');
INSERT INTO `core_logs` VALUES(3, 'Config (50)', 'Config (50) added by Core.User "1" (1).', 'Config', 50, 'add', 1, 'key () => (Global.pagination_limit), value () => (100), type () => (integer), core () => (0), description () => (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database.</p>)', NULL, '2010-01-07 21:00:38');
INSERT INTO `core_logs` VALUES(4, 'Config (50)', 'Config (50) updated by Core.User "1" (1).', 'Config', 50, 'edit', 1, 'value (100) => (0), description (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database.</p>) => (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database. Setting this value to 0 will disable and alow any nomber of records to be requested. The default for this setting is 100.</p>)', NULL, '2010-01-07 21:15:35');
INSERT INTO `core_logs` VALUES(5, 'Config (50)', 'Config (50) updated by Core.User "1" (1).', 'Config', 50, 'edit', 1, 'value (0) => (5)', NULL, '2010-01-07 21:16:22');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `core_ratings`
--

INSERT INTO `core_ratings` VALUES(1, 'Blog.Post', 1, 3, 2, '127.0.0.1', '2010-01-07 07:04:37');
INSERT INTO `core_ratings` VALUES(2, 'Blog.Post', 1, 5, 3, '127.0.0.1', '2010-01-07 07:06:14');
INSERT INTO `core_ratings` VALUES(3, 'Blog.Post', 1, 4, 1, '127.0.0.1', '2010-01-07 07:06:45');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `licence` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `core_themes`
--

INSERT INTO `core_themes` VALUES(2, 'terrafirma', NULL, '', '', 0);
INSERT INTO `core_themes` VALUES(3, 'aqueous', 'A blue 3 col layout', 'Six Shooter Media\r\n', '', 0);
INSERT INTO `core_themes` VALUES(4, 'aqueous_light', 'aqueous_light', '', '', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `newsletter_campaigns`
--

INSERT INTO `newsletter_campaigns` VALUES(3, '436', '34563456546', 0, 2, 1, 1, 1, '2009-12-21 16:28:38', '2009-12-12 12:47:53', '2009-12-21 16:28:38');
INSERT INTO `newsletter_campaigns` VALUES(6, '23423', '23423', 1, 0, 1, 0, NULL, NULL, '2010-01-04 09:23:38', '2010-01-04 09:23:57');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `newsletter_newsletters`
--

INSERT INTO `newsletter_newsletters` VALUES(7, 3, 0, 'dogmatic69@gmail.com', 'dogmatic69@gmail.com', 'asdf', '<p>asd</p>', '<p>asd</p>', 0, 1, 0, 0, NULL, 0, NULL, NULL, '2010-01-04 03:14:15', '2010-01-04 03:14:15', NULL, NULL);
INSERT INTO `newsletter_newsletters` VALUES(9, 3, 0, 'dogmatic69@gmail.com', 'dogmatic69@gmail.com', 'asdf- copy ( 2010-01-04 )', '<p>asd</p>', '<p>asd</p>', 0, 1, 0, 0, NULL, 0, NULL, NULL, '2010-01-04 03:14:15', '2010-01-04 03:14:15', NULL, NULL);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

