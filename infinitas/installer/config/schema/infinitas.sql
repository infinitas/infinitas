--    /**
--     * Comment Template.
--     *
--     * @todo -c Implement .this needs to be sorted out.
--     *
--     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
--     *
--     * Licensed under The MIT License
--     * Redistributions of files must retain the above copyright notice.
--     *
--     * @filesource
--     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
--     * @link          http://www.dogmatic.co.za
--     * @package       sort
--     * @subpackage    sort.comments
--     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
--     * @since         0.5a
--     */

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `infinitas`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_configs`
--

CREATE TABLE IF NOT EXISTS `blog_configs` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `core` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `body` text,
  `comment_count` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `locked_by` int(11) DEFAULT NULL,
  `locked_since` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts_tags`
--

CREATE TABLE IF NOT EXISTS `blog_posts_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_categories`
--

CREATE TABLE IF NOT EXISTS `cms_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locked_since` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content_count` int(11) NOT NULL DEFAULT '0',
  `section_id` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` datetime NOT NULL,
  `modified_by` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`active`,`group_id`),
  KEY `idx_access` (`group_id`),
  KEY `idx_checkout` (`locked`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_contents`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_content_frontpages`
--

CREATE TABLE IF NOT EXISTS `cms_content_frontpages` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_sections`
--

CREATE TABLE IF NOT EXISTS `cms_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locked_since` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '1',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category_count` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` datetime NOT NULL,
  `modified_by` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `core_backups`
--

CREATE TABLE IF NOT EXISTS `core_backups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` varchar(50) DEFAULT NULL,
  `model` varchar(50) NOT NULL,
  `last_id` int(11) NOT NULL,
  `data` longtext NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_comments`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_configs`
--

CREATE TABLE IF NOT EXISTS `core_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `options` text NOT NULL,
  `description` text,
  `core` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_feeds`
--

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_groups`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_sessions`
--

CREATE TABLE IF NOT EXISTS `core_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_users`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_campaigns`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_newsletters`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_newsletters_users`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_templates`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_configs`
--

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `landline` varchar(15) NOT NULL,
  `company` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;