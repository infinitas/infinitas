-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 13. Januar 2010 um 12:30
-- Server Version: 5.1.37
-- PHP-Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `infinitas`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `intro`, `body`, `comment_count`, `active`, `views`, `rating`, `rating_count`, `locked`, `locked_by`, `locked_since`, `created`, `modified`) VALUES
(1, 'making cake show the primary key field.', 'making-cake-show-the-primary-key-field', '<p>By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.</p>', '<p>I have the following setup for the cms in this app</p>\r\n<pre lang="sql">\r\nCREATE TABLE `cms_content_frontpages` (   \r\n`content_id` int(11) NOT NULL DEFAULT ''0'',  \r\n`created` datetime DEFAULT NULL,   \r\n`modified` datetime DEFAULT NULL,    \r\nPRIMARY KEY (`content_id`) ) \r\nENGINE=InnoDB DEFAULT CHARSET=utf8;\r\n</pre>\r\n<p>All this is for is to select items to show as the home page so as you can guess the form is simple. shows a list of the content itmes and saves them so we get a list of the items in the controller and show them like this:</p>\r\n<pre lang="php">\r\n$contents = $this-&gt;ContentFrontpage-&gt;Content-&gt;find( ''list'' ); \r\n$this-&gt;set( compact( ''contents'' ) );\r\n</pre>\r\n<p>Now in the form all wee need is one input, a select list of the content_id''s:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'' ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>And you would think you have this simple form done.  But cake is hiding it.  What you need to do is specify the type like this</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'', array( ''type'' =&gt; ''select'' ) ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>But now there is nothing in the select list.  I then just specified the options and all was cool.  Final form looked something like below:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' ); \r\n    echo $this-&gt;Form-&gt;input( \r\n        ''content_id'', \r\n        array( \r\n            ''type'' =&gt; ''select'', \r\n            ''options'' =&gt; $contents \r\n        ) \r\n    ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>&nbsp;</p>', 1, 1, 312, 4, 3, 0, NULL, NULL, '2009-11-30 10:54:16', '2010-01-12 14:24:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog_posts_tags`
--

DROP TABLE IF EXISTS `blog_posts_tags`;
CREATE TABLE IF NOT EXISTS `blog_posts_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Daten für Tabelle `blog_posts_tags`
--

INSERT INTO `blog_posts_tags` (`id`, `post_id`, `tag_id`) VALUES
(26, 1, 1),
(27, 1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog_tags`
--

DROP TABLE IF EXISTS `blog_tags`;
CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `blog_tags`
--

INSERT INTO `blog_tags` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Cakephp', '2009-12-20 13:39:20', '2009-12-20 13:39:20'),
(2, 'Forms', '2009-12-20 13:39:20', '2009-12-20 13:39:20');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_categories`
--

DROP TABLE IF EXISTS `cms_categories`;
CREATE TABLE IF NOT EXISTS `cms_categories` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `cms_categories`
--

INSERT INTO `cms_categories` (`id`, `title`, `slug`, `description`, `active`, `locked`, `locked_since`, `locked_by`, `group_id`, `content_count`, `parent_id`, `lft`, `rght`, `views`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(1, 'test', 'test', '<p>test</p>', 0, 0, NULL, NULL, 1, 0, 3, 3, 6, 0, '2010-01-02 08:11:04', '2010-01-02 08:12:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'test2', 'test2', '<p>test</p>', 0, 0, NULL, NULL, 1, 6, 0, 1, 8, 0, '2010-01-02 08:11:27', '2010-01-02 08:11:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'test2.1', 'test2-1', '', 0, 1, '2010-01-04 10:28:27', 2, 1, 0, 2, 2, 7, 0, '2010-01-02 08:11:43', '2010-01-04 10:28:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '123', '123', '<p>123</p>', 0, 0, NULL, NULL, 1, 0, 1, 0, 0, 0, '2010-01-04 10:27:40', '2010-01-04 10:27:40', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_contents`
--

DROP TABLE IF EXISTS `cms_contents`;
CREATE TABLE IF NOT EXISTS `cms_contents` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `cms_contents`
--

INSERT INTO `cms_contents` (`id`, `title`, `slug`, `introduction`, `body`, `locked`, `locked_since`, `locked_by`, `ordering`, `group_id`, `views`, `active`, `start`, `end`, `created`, `modified`, `created_by`, `modified_by`, `category_id`) VALUES
(1, 'test cat content', 'test-cat-content', '<p>test</p>', '<p>test</p>', 0, NULL, NULL, 1, 1, 0, 0, NULL, NULL, '2010-01-02 08:02:58', '2010-01-02 08:02:58', 0, 0, 2),
(2, 'asdfasd', 'asdf', '<p>asdf</p>', '<p>sadf</p>', 0, NULL, NULL, 2, 1, 0, 1, NULL, NULL, '2010-01-02 08:20:33', '2010-01-02 08:21:03', 0, 0, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_content_frontpages`
--

DROP TABLE IF EXISTS `cms_content_frontpages`;
CREATE TABLE IF NOT EXISTS `cms_content_frontpages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `cms_content_frontpages`
--

INSERT INTO `cms_content_frontpages` (`id`, `content_id`, `ordering`, `order_id`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(2, 2, 1, 1, '2010-01-04 22:46:15', '2010-01-04 22:46:15', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_features`
--

DROP TABLE IF EXISTS `cms_features`;
CREATE TABLE IF NOT EXISTS `cms_features` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `cms_features`
--

INSERT INTO `cms_features` (`id`, `content_id`, `ordering`, `order_id`, `created`, `created_by`) VALUES
(1, 1, 1, 1, '2010-01-04 21:49:03', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_backups`
--

DROP TABLE IF EXISTS `core_backups`;
CREATE TABLE IF NOT EXISTS `core_backups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` varchar(50) DEFAULT NULL,
  `model` varchar(50) NOT NULL,
  `last_id` int(11) NOT NULL,
  `data` longtext NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `core_backups`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_comments`
--

DROP TABLE IF EXISTS `core_comments`;
CREATE TABLE IF NOT EXISTS `core_comments` (
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
-- Daten für Tabelle `core_comments`
--

INSERT INTO `core_comments` (`id`, `class`, `foreign_id`, `name`, `email`, `website`, `comment`, `active`, `rating`, `points`, `status`, `created`) VALUES
(1, 'Post', 1, 'bob something', 'bob@gmail.com', 'http://www.something.com', '&lt;p&gt;Redistributions of files must reributions of files s must retain the above copyright notice.Redistribtain the above copyright notice.Redistributions of files mmust retain the above copyright notice.Redistutions leust retain the above copyright notice.Redistributions of fiof files must retain the above copyright notice.Redistributions of files must retain the above copyright notice.&lt;/p&gt;', 1, 0, 3, 'approved', '2010-01-07 07:20:42'),
(2, 'Post', 1, 'bob smith', 'dogmatic69@gmail.com', 'www.google.com', '&lt;p&gt;Our expert says:  &amp;quot;Attractive reward card, particularly for AA members. Members receive 2 points for every &amp;pound;1.00 spent on motoring costs and 1 point per &amp;pound;1.00 for other spending. Non&#45;members receive 1 point for every &amp;pound;2.00 spent. Balance transfers are interest&#45;free until Jan 2011. Spend on motoring products and services, fuel and AA products are interest free until Jan 2011&amp;quot;&lt;/p&gt;', 1, 0, -4, 'spam', '2010-01-12 14:23:27');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_configs`
--

DROP TABLE IF EXISTS `core_configs`;
CREATE TABLE IF NOT EXISTS `core_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `options` text NOT NULL,
  `description` text,
  `core` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Daten für Tabelle `core_configs`
--

INSERT INTO `core_configs` (`id`, `key`, `value`, `type`, `options`, `description`, `core`) VALUES
(1, 'debug', '2', 'dropdown', '0,1,2,3', 'Production Mode:\r\n0: No error messages, errors, or warnings shown. Flash messages redirect.\r\n\r\nDevelopment Mode:\r\n1: Errors and warnings shown, model caches refreshed, flash messages halted.\r\n2: As in 1, but also with full debug messages and SQL output.\r\n', 1),
(2, 'log', '1', 'bool', 'true,false', 'In case of Production Mode CakePHP gives you the possibility to continue logging errors.\r\n\r\nThe following parameters can be used:\r\nBoolean: Set true/false to activate/deactivate logging', 1),
(3, 'Session.save', 'php', 'dropdown', 'php,cake,database', 'The preferred session handling method.\r\n\r\n''php'' -> Uses settings defined in your php.ini.\r\n''cake'' -> Saves session files in CakePHP''s /tmp directory.\r\n''database'' -> Uses CakePHP''s database sessions.', 1),
(4, 'App.encoding', 'utf8', 'string', '', 'Application wide charset encoding', 1),
(5, 'Cache.disable', 'false', 'bool', 'true,false', 'Turn off all caching application-wide.', 1),
(6, 'Session.model', 'Session', 'string', '', 'The model name to be used for the session model.\r\n\r\n''Session.save'' must be set to ''database'' in order to utilize this constant.\r\n\r\nThe model name set here should *not* be used elsewhere in your application.', 1),
(7, 'Session.database', 'default', 'string', '', 'The DATABASE_CONFIG::$var to use for database session handling.\r\n\r\n''Session.save'' must be set to ''database'' in order to utilize this constant.', 1),
(8, 'Session.timeout', '120', 'integer', '', 'Session time out time (in seconds).\r\nActual value depends on ''Security.level'' setting.', 1),
(9, 'Session.start', 'true', 'bool', 'true,false', 'If set to false, sessions are not automatically started.', 1),
(10, 'Session.checkAgent', 'true', 'bool', 'true,false', 'When set to false, HTTP_USER_AGENT will not be checked in the session', 1),
(11, 'Security.level', 'medium', 'dropdown', 'high,medium,low', 'The level of CakePHP security. The session timeout time defined in ''Session.timeout'' is multiplied according to the settings here.\r\n\r\n''high'' -> Session timeout in ''Session.timeout'' x 10\r\n''medium'' -> Session timeout in ''session.timeout'' x 100\r\n''low'' -> Session timeout in ''Session.timeout'' x 300\r\n\r\nsession IDs are also regenerated between requests if set to high', 1),
(12, 'Session.cookie', 'CAKEPHP', 'string', '', 'The name of the session cookie', 1),
(13, 'Wysiwyg.editor', 'fck', 'dropdown', 'text,fck', 'Select the wysiwyg editor that you would like to use.', 0),
(14, 'Currency.name', 'Rand', 'string', '', '<p>The name of the default currency</p>', 0),
(15, 'Currency.unit', 'R', 'string', '', 'The unit of the default currency', 0),
(16, 'Language.name', 'English', 'string', '', 'The default language of the site', 0),
(17, 'Language.code', 'En', 'string', '', 'The iso code of the default site language.', 0),
(18, 'Blog.allow_comments', 'true', 'bool', 'true,false', 'Whether to allow comments on the blog or not. If disabled historical comments will not be displayed but will not be deleted.', 0),
(19, 'Cms.allow_comments', 'true', 'bool', 'true,false', 'Whether to allow comments on the cms Content items or not. If disabled historical comments will not be displayed but will not be deleted.', 0),
(20, 'Newsletter.send_count', '200', 'integer', '', 'The number of newsletters to send at a time.', 0),
(21, 'Newsletter.send_interval', '300', 'integer', '', 'The time interval between sending emails in seconds', 0),
(22, 'Newsletter.track_views', 'true', 'bool', 'true,false', 'Attempt to track the number of views a newsletter creates.  works with  a call back to the server.  Needs html to work', 0),
(23, 'Newsletter.send_as', 'both', 'dropdown', 'both,html,text', 'What format to send the newsletter out as. Both is the best option as its nut uncommon for people to only accept text mails.', 0),
(24, 'Website.name', 'Some Site', 'string', '', 'This is the name of the site that will be used in emails and on the website its self', 0),
(25, 'Website.description', 'Some Seo information about the site', 'string', '', 'This is the main description about the site', 0),
(26, 'Cms.auto_redirect', 'true', 'bool', 'true,false', 'When a category has only one content itme should the site automaticaly redirect to that one item of first display the category.\r\n\r\nThis will also work for sections.', 0),
(27, 'Comments.time_limit', '4 weeks', 'string', '', 'the date the comments will stop being available. if it is set to 0 users will always be able to comment on a record.\r\n\r\nit uses strtotime() and will expire after the amount of time you specify. eg: 4 weeks - comments will be disabled 4 weeks after the post was last edited.', 0),
(28, 'Blog.depreciate', '6 months', 'string', '', 'Uses strtotime, after this time the post will be marked as depreciated.  set to 0 to never show this message.', 0),
(29, 'Comments.purge', '4 weeks', 'string', '', 'If set to 0 purge is disabled.  You can also enter a time string used in strtotime() like "4 weeks" and purge will remove comments that pending older than 4 weeks.', 0),
(30, 'Comments.auto_moderate', 'false', 'bool', 'true,false', 'Set this to true for comments to be automaticaly set to active so you do not need to manually moderate them in admin.\r\n\r\nif set to false, comments will need to be activated before they are displayed on the site.', 0),
(31, 'FileManager.base_path', 'z:/www/webroot', 'string', '', '<p>The base path for access to manage files.</p>', 0),
(32, 'Newsletter.send_method', 'smtp', 'dropdown', 'smtp,mail,debug', '<p>This is the method that you would like to send emails with.&nbsp; Smtp requres that you have the correct ports and login details (for servers that require sending authentication ).</p>', 0),
(33, 'Newsletter.smtp_out_going_port', '25', 'integer', '', '<p>The default port is 25 for smtp sending (outgoing mails). If you are having problems sending try findout from your host if there is another port to use.</p>', 0),
(34, 'Newsletter.smtp_timeout', '30', 'integer', '', '<p>Smtp timeout in seconds. If you are getting timeout errors try and up this ammount a bit. The default time is 30 seconds</p>', 0),
(35, 'Newsletter.smtp_host', 'mail.php-dev.co.za', 'string', '', '<p>This is the host address of your smtp server. There is no default. It is normaly something like mail.server.com but can be an ip address.</p>', 0),
(36, 'Newsletter.smtp_username', 'test@php-dev.co.za', 'string', '', '<p>This is your smtp username for authenticating. It is usualy in the form of username@domain.com. If your server does not require outgoing authentication you must leave this blank.</p>', 0),
(37, 'Newsletter.smtp_password', 'test', 'string', '', '<p>This is your password for smtp authentication. It should be left blank if there is no authentication for outgoing mails on your server.</p>', 0),
(38, 'Newsletter.from_name', 'Dogmatic', 'string', '', '<p>This is the name you would like to have as the sender of your mails.. will default to the site name if it is empty.</p>', 0),
(39, 'Newsletter.from_email', 'test@php-dev.co.za', 'string', '', '<p>The email address where your mails come from. This is used as the default when generating mails.</p>', 0),
(40, 'Newsletter.template', 'default', 'string', '', '<p>This is the internal template that is used by the Newsletter plugin to send mails. If you do not know what this is do not edit it.&nbsp; The default template used is &quot;default&quot;.</p>', 0),
(41, 'Global.pagination_select', '5,10,20,50,100', 'string', '', '<p>This is for the options in the pagiantion drop down. Any comma seperated list of integers will be generated in the pagination.</p>\r\n<p>The default is "5,10,20,50,100"</p>', 0),
(42, 'Pagination.nothing_found_message', 'Nothing was found, try a more generic search.', 'string', '', '<p>This is the message that will show at the bottom of a page when there is no resaults.</p>', 0),
(43, 'Blog.allow_ratings', 'true', 'bool', 'true,false', '<p>If you would like people to be able to rate your blog posts enable this option.</p>', 0),
(44, 'Rating.time_limit', '4 weeks', 'string', '', '<p>the date the ratings will stop being available. if it is set to 0 users will always be able to comment on a record. it uses strtotime() and will expire after the amount of time you specify. eg: 4 weeks - ratings will be disabled 4 weeks after the post was last edited.</p>', 0),
(45, 'Comment.fields', 'name,email,website,comment', 'string', '', '<p>A comma seperated list of the fields you should have in your comments. the defaut is &quot;name,email,website,comment&quot;. if you are adding other fields to the comments make sure that the fields are available in the database or the information will not be saved.</p>', 0),
(46, 'Rating.require_auth', 'true', 'bool', 'true,false', '<p>Set to true if you would like only logged in users to be able to rate items.&nbsp; If set to false anybody will be able to rate items. The default setting is true.</p>', 0),
(47, 'Website.blacklist_keywords', 'levitra,viagra,casino,sex,loan,finance,slots,debt,free,interesting,sorry,cool', 'string', '', '<p>A list of comma separated keywords that are used for automatic moderation of comments and reviews.</p>', 0),
(48, 'Website.blacklist_words', '.html,.info,?,&,.de,.pl,.cn', 'string', '', '<p>A list of comma seperated words used to automaticaly moderate comments and reviews on the site.</p>', 0),
(49, 'Reviews.auto_moderate', 'true', 'bool', 'true,false', '<p>Set this to true to alow the reviews to be automaticaly moderated for spam. If set to true the reviews will be cross checked with the data in the blacklisted keywordsconfiguration setting.</p>', 0),
(50, 'Global.pagination_limit', '100', 'integer', '', '<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database. Setting this value to 0 will disable and alow any nomber of records to be requested. The default for this setting is 100.</p>', 0),
(51, 'Website.home_page', 'blog', 'dropdown', 'blog,cms,shop', '<p>this is the page visitors to your site will land on when entering your domain directly</p>', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_feeds`
--

DROP TABLE IF EXISTS `core_feeds`;
CREATE TABLE IF NOT EXISTS `core_feeds` (
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
-- Daten für Tabelle `core_feeds`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_groups`
--

DROP TABLE IF EXISTS `core_groups`;
CREATE TABLE IF NOT EXISTS `core_groups` (
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
-- Daten für Tabelle `core_groups`
--

INSERT INTO `core_groups` (`id`, `name`, `description`, `created`, `modified`, `parent_id`, `lft`, `rght`) VALUES
(1, 'admin', 'admin', '2009-12-16 00:06:53', '2009-12-16 00:06:53', 0, 1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_logs`
--

DROP TABLE IF EXISTS `core_logs`;
CREATE TABLE IF NOT EXISTS `core_logs` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `core_logs`
--

INSERT INTO `core_logs` (`id`, `title`, `description`, `model`, `model_id`, `action`, `user_id`, `change`, `version_id`, `created`) VALUES
(1, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'active (1) => (0)', NULL, '2010-01-07 17:44:51'),
(2, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'active (0) => (1)', NULL, '2010-01-07 17:45:06'),
(3, 'Config (50)', 'Config (50) added by Core.User "1" (1).', 'Config', 50, 'add', 1, 'key () => (Global.pagination_limit), value () => (100), type () => (integer), core () => (0), description () => (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database.</p>)', NULL, '2010-01-07 21:00:38'),
(4, 'Config (50)', 'Config (50) updated by Core.User "1" (1).', 'Config', 50, 'edit', 1, 'value (100) => (0), description (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database.</p>) => (<p>This is the maximum number of rows a query will ever return. only used where limits are set. This should stop people from passing params in urls to pull the entire database. Setting this value to 0 will disable and alow any nomber of records to be requested. The default for this setting is 100.</p>)', NULL, '2010-01-07 21:15:35'),
(5, 'Config (50)', 'Config (50) updated by Core.User "1" (1).', 'Config', 50, 'edit', 1, 'value (0) => (5)', NULL, '2010-01-07 21:16:22'),
(6, 'making cake show the primary key field.', 'Post "making cake show the primary key field." (1) updated by Core.User "1" (1).', 'Post', 1, 'edit', 1, 'locked (1) => (0), locked_by (1) => (), locked_since (2010-01-10 07:48:34) => ()', NULL, '2010-01-10 07:48:45'),
(7, 'dsfgsdf', 'Newsletter "dsfgsdf" (10) added by Core.User "1" (1).', 'Newsletter', 10, 'add', 1, 'sent () => (0), views () => (0), sends () => (0), campaign_id () => (6), from () => (gsdfgd@dssd.com), reply_to () => (gsdfgd@dssd.com), subject () => (dsfgsdf), html () => (<p>dfgdsfgsd</p>), text () => (<p>sdfgdsfsfsfsfsfsf</p>), created () => (2010-01-12 14:19:31)', NULL, '2010-01-12 14:19:31'),
(8, 'bob smith', 'Comment "bob smith" (2) added by Core.User "1" (1).', 'Comment', 2, 'add', 1, 'name () => (bob smith), email () => (dogmatic69@gmail.com), website () => (www.google.com), comment () => (&lt;p&gt;Our expert says:  &amp;quot;Attractive reward card, particularly for AA members. Members receive 2 points for every &amp;pound;1.00 spent on motoring costs and 1 point per &amp;pound;1.00 for other spending. Non&#45;members receive 1 point for every &amp;pound;2.00 spent. Balance transfers are interest&#45;free until Jan 2011. Spend on motoring products and services, fuel and AA products are interest free until Jan 2011&amp;quot;&lt;/p&gt;), class () => (Post), foreign_id () => (1), points () => (-4), status () => (spam), created () => (2010-01-12 14:23:27)', NULL, '2010-01-12 14:23:27');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_ratings`
--

DROP TABLE IF EXISTS `core_ratings`;
CREATE TABLE IF NOT EXISTS `core_ratings` (
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
-- Daten für Tabelle `core_ratings`
--

INSERT INTO `core_ratings` (`id`, `class`, `foreign_id`, `rating`, `user_id`, `ip`, `created`) VALUES
(1, 'Blog.Post', 1, 3, 2, '127.0.0.1', '2010-01-07 07:04:37'),
(2, 'Blog.Post', 1, 5, 3, '127.0.0.1', '2010-01-07 07:06:14'),
(3, 'Blog.Post', 1, 4, 1, '127.0.0.1', '2010-01-07 07:06:45');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_routes`
--

DROP TABLE IF EXISTS `core_routes`;
CREATE TABLE IF NOT EXISTS `core_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `core` tinyint(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `plugin` varchar(50) DEFAULT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `match_all` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `core_routes`
--

INSERT INTO `core_routes` (`id`, `core`, `name`, `url`, `plugin`, `controller`, `action`, `match_all`, `created`, `modified`) VALUES
(1, 0, 'Home Page', '/', '', '', '', 0, '2010-01-13 12:37:18', '2010-01-13 12:37:18');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_sessions`
--

DROP TABLE IF EXISTS `core_sessions`;
CREATE TABLE IF NOT EXISTS `core_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `core_sessions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_themes`
--

DROP TABLE IF EXISTS `core_themes`;
CREATE TABLE IF NOT EXISTS `core_themes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `author` varchar(150) NOT NULL,
  `licence` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `core_themes`
--

INSERT INTO `core_themes` (`id`, `name`, `description`, `author`, `licence`, `active`) VALUES
(2, 'terrafirma', NULL, '', '', 0),
(3, 'aqueous', 'A blue 3 col layout', 'Six Shooter Media\r\n', '', 0),
(4, 'aqueous_light', 'aqueous_light', '', '', 0),
(5, 'think', NULL, 'Carl Sutton', '(c) ', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_users`
--

DROP TABLE IF EXISTS `core_users`;
CREATE TABLE IF NOT EXISTS `core_users` (
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
-- Daten für Tabelle `core_users`
--

INSERT INTO `core_users` (`id`, `username`, `email`, `password`, `active`, `created`, `modified`) VALUES
(1, 'dogmatic', 'dogmatic69@gmail.com', 'def267b31a9443f297b593b42992da19c0e72fec', 1, '2009-12-13 01:53:54', '2009-12-13 01:53:54'),
(2, 'bob', 'bob@bob.com', 'def267b31a9443f297b593b42992da19c0e72fec', 1, '2009-12-16 16:24:33', '2009-12-16 16:24:33');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_campaigns`
--

DROP TABLE IF EXISTS `newsletter_campaigns`;
CREATE TABLE IF NOT EXISTS `newsletter_campaigns` (
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
-- Daten für Tabelle `newsletter_campaigns`
--

INSERT INTO `newsletter_campaigns` (`id`, `name`, `description`, `active`, `newsletter_count`, `template_id`, `locked`, `locked_by`, `locked_since`, `created`, `modified`) VALUES
(3, '436', '34563456546', 0, 2, 1, 1, 1, '2009-12-21 16:28:38', '2009-12-12 12:47:53', '2009-12-21 16:28:38'),
(6, '23423', '23423', 1, 1, 1, 0, NULL, NULL, '2010-01-04 09:23:38', '2010-01-04 09:23:57');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_newsletters`
--

DROP TABLE IF EXISTS `newsletter_newsletters`;
CREATE TABLE IF NOT EXISTS `newsletter_newsletters` (
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
-- Daten für Tabelle `newsletter_newsletters`
--

INSERT INTO `newsletter_newsletters` (`id`, `campaign_id`, `template_id`, `from`, `reply_to`, `subject`, `html`, `text`, `active`, `sent`, `views`, `sends`, `last_sent`, `locked`, `locked_by`, `locked_since`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(7, 3, 0, 'dogmatic69@gmail.com', 'dogmatic69@gmail.com', 'asdf', '<p>asd</p>', '<p>asd</p>', 0, 1, 0, 0, NULL, 0, NULL, NULL, '2010-01-04 03:14:15', '2010-01-04 03:14:15', NULL, NULL),
(9, 3, 0, 'dogmatic69@gmail.com', 'dogmatic69@gmail.com', 'asdf- copy ( 2010-01-04 )', '<p>asd</p>', '<p>asd</p>', 0, 1, 0, 0, NULL, 0, NULL, NULL, '2010-01-04 03:14:15', '2010-01-04 03:14:15', NULL, NULL),
(10, 6, 0, 'gsdfgd@dssd.com', 'gsdfgd@dssd.com', 'dsfgsdf', '<p>dfgdsfgsd</p>', '<p>sdfgdsfsfsfsfsfsf</p>', 0, 0, 0, 0, NULL, 0, NULL, NULL, '2010-01-12 14:19:31', '2010-01-12 14:19:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_newsletters_users`
--

DROP TABLE IF EXISTS `newsletter_newsletters_users`;
CREATE TABLE IF NOT EXISTS `newsletter_newsletters_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `newsletter_id` int(11) NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `newsletter_sent` (`sent`),
  KEY `newsletter_newsletter_id` (`newsletter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `newsletter_newsletters_users`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `user_id`, `active`, `created`, `modified`) VALUES
(1, 1, 1, '2009-12-13 01:49:32', '2009-12-13 01:49:32');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter_templates`
--

DROP TABLE IF EXISTS `newsletter_templates`;
CREATE TABLE IF NOT EXISTS `newsletter_templates` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `newsletter_templates`
--

INSERT INTO `newsletter_templates` (`id`, `name`, `header`, `footer`, `locked`, `locked_by`, `locked_since`, `created`, `modified`) VALUES
(1, 'first template', '<p style="color: red;">this is the head</p>', '<p>this is the foot</p>', 1, 1, '2009-12-21 16:26:14', '2009-12-12 17:04:07', '2009-12-21 16:26:14');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_configs`
--

DROP TABLE IF EXISTS `user_configs`;
CREATE TABLE IF NOT EXISTS `user_configs` (
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
-- Daten für Tabelle `user_configs`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_details`
--

DROP TABLE IF EXISTS `user_details`;
CREATE TABLE IF NOT EXISTS `user_details` (
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `landline` varchar(15) NOT NULL,
  `company` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `user_details`
--

