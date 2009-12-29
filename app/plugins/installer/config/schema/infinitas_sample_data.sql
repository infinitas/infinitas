-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306

-- Generation Time: Dec 29, 2009 at 06:49 PM
-- Server version: 5.1.34
-- PHP Version: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpdev`
--

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` VALUES(1, 'making cake show the primary key field.', 'making-cake-show-the-primary-key-field', '<p>By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.</p>', '<p>I have the following setup for the cms in this app</p>\r\n<pre lang="sql">\r\nCREATE TABLE `cms_content_frontpages` (   \r\n`content_id` int(11) NOT NULL DEFAULT ''0'',  \r\n`created` datetime DEFAULT NULL,   \r\n`modified` datetime DEFAULT NULL,    \r\nPRIMARY KEY (`content_id`) ) \r\nENGINE=MyISAM DEFAULT CHARSET=utf8;\r\n</pre>\r\n<p>All this is for is to select items to show as the home page so as you can guess the form is simple. shows a list of the content itmes and saves them so we get a list of the items in the controller and show them like this:</p>\r\n<pre lang="php">\r\n$contents = $this-&gt;ContentFrontpage-&gt;Content-&gt;find( ''list'' ); \r\n$this-&gt;set( compact( ''contents'' ) );\r\n</pre>\r\n<p>Now in the form all wee need is one input, a select list of the content_id''s:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'' ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>And you would think you have this simple form done.  But cake is hiding it.  What you need to do is specify the type like this</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' );\r\n    echo $this-&gt;Form-&gt;input( ''content_id'', array( ''type'' =&gt; ''select'' ) ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>But now there is nothing in the select list.  I then just specified the options and all was cool.  Final form looked something like below:</p>\r\n<pre lang="php">\r\necho $this-&gt;Form-&gt;create( ''ContentFrontpage'' ); \r\n    echo $this-&gt;Form-&gt;input( \r\n        ''content_id'', \r\n        array( \r\n            ''type'' =&gt; ''select'', \r\n            ''options'' =&gt; $contents \r\n        ) \r\n    ); \r\necho $this-&gt;Form-&gt;end( __( ''Submit'', true ) );\r\n</pre>\r\n<p>&nbsp;</p>', 5, 1, 184, 1, 1, '2009-12-28 22:05:37', '2009-11-30 10:54:16', '2009-12-29 01:28:34');
INSERT INTO `blog_posts` VALUES(2, 'pending', 'pending', '<p>pending&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>', '<p>pending</p>', 0, 0, 0, 0, NULL, NULL, '2009-12-23 13:58:47', '2009-12-23 13:58:47');

--
-- Dumping data for table `blog_posts_tags`
--

INSERT INTO `blog_posts_tags` VALUES(21, 1, 2);
INSERT INTO `blog_posts_tags` VALUES(20, 1, 1);
INSERT INTO `blog_posts_tags` VALUES(15, 2, 2);

--
-- Dumping data for table `blog_tags`
--

INSERT INTO `blog_tags` VALUES(1, 'Cakephp', '2009-12-20 13:39:20', '2009-12-20 13:39:20');
INSERT INTO `blog_tags` VALUES(2, 'Forms', '2009-12-20 13:39:20', '2009-12-20 13:39:20');

--
-- Dumping data for table `cms_categories`
--

INSERT INTO `cms_categories` VALUES(7, 'this is other stuff', 'this-is-other-stuff', '<p>other stuff goes here</p>', 1, 0, NULL, NULL, 1, 1, 2, 6, 5, '2009-12-20 15:05:38', '2009-12-28 11:20:18', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_categories` VALUES(3, 'this is locked', 'this-is-locked', '<p>this is locked so only user 1 should be able to edit it</p>', 0, 0, NULL, NULL, 1, 1, 0, 1, 5, '2009-12-16 18:31:19', '2009-12-28 10:01:57', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Dumping data for table `cms_contents`
--

INSERT INTO `cms_contents` VALUES(3, 'test cat content', '', '<p>test cat content</p>', '<p>test cat content</p>', 0, NULL, NULL, 1, 1, 1, 1, NULL, NULL, '2009-12-20 12:34:09', '2009-12-21 15:22:35', 0, 0, 7);
INSERT INTO `cms_contents` VALUES(4, 'check the slug', 'check-the-slug', '<p>asdf</p>', '<p>asdf</p>', 0, NULL, NULL, 1, 1, 1, 1, NULL, NULL, '2009-12-20 12:47:06', '2009-12-21 15:22:47', 0, 0, 7);

--
-- Dumping data for table `cms_content_frontpages`
--

INSERT INTO `cms_content_frontpages` VALUES(3, 0, 1, '2009-12-21 16:39:31', '2009-12-21 16:39:31', 0, 0);
INSERT INTO `cms_content_frontpages` VALUES(4, 0, 1, '2009-12-20 14:55:01', '2009-12-20 14:55:51', 0, 0);

--
-- Dumping data for table `cms_sections`
--

INSERT INTO `cms_sections` VALUES(1, 'The first section for random stuff.', 'the-first-section-for-random-stuff', '<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>\r\n<p>blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa blaa</p>', 1, 1, '2009-12-28 16:50:47', 1, 1, 1, 1, 1, 97, '2009-12-16 15:34:27', '2009-12-28 16:50:47', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_sections` VALUES(6, 'another section for other stuff', 'another-section-for-other-stuff', '<p>this is other stuff</p>', 1, 0, NULL, NULL, 2, 1, 1, 1, 0, '2009-12-20 15:04:55', '2009-12-20 15:04:55', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Dumping data for table `core_comments`
--

INSERT INTO `core_comments` VALUES(7, 'Blog.Post', 1, 'bob', 'bob@bob.com', 'http://www.bob.com', '<p>bob</p>', 1, 0, 0, NULL, '2009-12-23 13:48:27');
INSERT INTO `core_comments` VALUES(10, 'Blog.Post', 1, 'carl', 'dogmatic69@gmail.com', 'http://www.site.com', 'testing', 1, 0, 0, NULL, '2009-12-28 17:42:28');
INSERT INTO `core_comments` VALUES(30, 'Post', 1, 'sam something', 'someplace@somewhere.com', 'http://www.site.com', '&lt;p&gt;By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.&lt;br /&gt;\\n&amp;nbsp;&lt;/p&gt;', 1, 0, 0, NULL, '2009-12-29 01:14:17');
INSERT INTO `core_comments` VALUES(29, 'Post', 1, 'bob dmith', 'someplace@somewhere.com', 'http://www.site.com', '&lt;p&gt;By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.&lt;/p&gt;', 1, 0, 1, NULL, '2009-12-29 01:13:17');
INSERT INTO `core_comments` VALUES(28, 'Post', 1, 'asdf', 'asdf@sad.com', 'http://www.co.za', '&lt;p&gt;asdf blaa blaa blaa this is cool&lt;/p&gt;', 1, 0, 2, NULL, '2009-12-29 01:08:02');
INSERT INTO `core_comments` VALUES(31, 'Post', 1, 'sam something', 'someplace@somewhere.com', 'http://www.site.com', '&lt;p&gt;By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.&lt;/p&gt;', 0, 0, 1, 'approved', '2009-12-29 01:16:08');
INSERT INTO `core_comments` VALUES(32, 'Post', 1, 'sam something', 'someplace@somewhere.com', 'http://www.site.com', '&lt;p&gt;By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.&lt;/p&gt;', 0, 0, 2, 'approved', '2009-12-29 01:21:19');
INSERT INTO `core_comments` VALUES(33, 'Post', 1, 'sam something', 'someplace@somewhere.com', 'http://www.site.com', '&lt;p&gt;By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.&lt;/p&gt;', 0, 0, 3, 'approved', '2009-12-29 01:22:40');
INSERT INTO `core_comments` VALUES(34, 'Post', 1, 'sam something', 'someplace@somewhere.com', 'http://www.site.com', '&lt;p&gt;By default cake will automaticaly hide the primary key of a table in a form. This is normally cool, but sometimes you need the primary key field to display. Here ill show you how.&lt;/p&gt;', 0, 0, 4, 'approved', '2009-12-29 01:28:33');

--
-- Dumping data for table `core_feeds`
--


--
-- Dumping data for table `newsletter_campaigns`
--

INSERT INTO `newsletter_campaigns` VALUES(3, '436', '34563456546', 0, 3, 1, 1, 1, '2009-12-21 16:28:38', '2009-12-12 12:47:53', '2009-12-21 16:28:38');
INSERT INTO `newsletter_campaigns` VALUES(4, 'abc', 'blaa blaa', 0, 1, 1, 0, NULL, NULL, '2009-12-12 19:33:55', '2009-12-12 19:33:55');

--
-- Dumping data for table `newsletter_newsletters`
--

INSERT INTO `newsletter_newsletters` VALUES(1, 2, 0, 'dogmatic69@gmail.com', 'dogmatic69@gmail.com', 'test', '<p>sdaf</p>', '<p>sdf</p>', 1, 1, 0, 0, NULL, 0, NULL, NULL, '2009-12-12 12:56:40', '2009-12-12 14:45:01', NULL, NULL);
INSERT INTO `newsletter_newsletters` VALUES(2, 3, 1, 'sent@sent.com', 'sent@sent.com', 'sent', '<p>this is sent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>', '<p>sent</p>', 1, 1, 1, 0, NULL, 0, NULL, NULL, '2009-12-12 14:45:37', '2009-12-12 14:45:37', NULL, NULL);
INSERT INTO `newsletter_newsletters` VALUES(3, 3, 0, 'pending@pending.com', 'pending@pending.com', 'pending', '<p>pending&nbsp;&nbsp;&nbsp;&nbsp;</p>', '<p>pending</p>', 1, 0, 3, 1, NULL, 0, NULL, NULL, '2009-12-12 14:49:05', '2009-12-13 02:11:36', NULL, NULL);
INSERT INTO `newsletter_newsletters` VALUES(4, 4, 0, 'sadf@gam.com', 'sadf@gam.com', 'sdf', '<p>sdaf</p>', '<p>sdf</p>', 0, 0, 0, 0, NULL, 0, NULL, NULL, '2009-12-21 14:54:54', '2009-12-21 14:54:54', NULL, NULL);
INSERT INTO `newsletter_newsletters` VALUES(5, 3, 0, 'sadf@gam.com', 'sadf@gam.com', 'sad', '<p>asd</p>', '<p>sda</p>', 0, 0, 0, 0, NULL, 0, NULL, NULL, '2009-12-21 14:55:41', '2009-12-21 14:55:41', NULL, NULL);

--
-- Dumping data for table `newsletter_newsletters_users`
--

INSERT INTO `newsletter_newsletters_users` VALUES(1, 1, 3, 1, '2009-12-13 01:51:32', '2009-12-13 02:11:36');

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` VALUES(1, 1, 1, '2009-12-13 01:49:32', '2009-12-13 01:49:32');

--
-- Dumping data for table `newsletter_templates`
--

INSERT INTO `newsletter_templates` VALUES(1, 'first template', '<p style="color: red;">this is the head</p>', '<p>this is the foot</p>', 1, 1, '2009-12-21 16:26:14', '2009-12-12 17:04:07', '2009-12-21 16:26:14');
INSERT INTO `newsletter_templates` VALUES(3, 'second template', '', '', 0, NULL, NULL, '2009-12-12 20:17:21', '2009-12-12 20:17:21');
INSERT INTO `newsletter_templates` VALUES(4, 'test', '<p>test</p>', '<p>test</p>', 0, NULL, NULL, '2009-12-21 16:20:58', '2009-12-21 16:20:58');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;