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
--     * @link          http://infinitas-cms.org
--     * @package       sort
--     * @subpackage    sort.comments
--     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
--     * @since         0.5a
--     */

-- Generation Time: Dec 29, 2009 at 06:47 PM
-- Server version: 5.1.34
-- PHP Version: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `phpdev`
--

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
INSERT INTO `core_configs` VALUES(14, 'Currency.name', 'Rand', 'string', '', 'The name of the default currency', 0);
INSERT INTO `core_configs` VALUES(15, 'Currency.unit', 'R', 'string', '', 'The unit of the default currency', 0);
INSERT INTO `core_configs` VALUES(16, 'Language.name', 'English', 'string', '', 'The default language of the site', 0);
INSERT INTO `core_configs` VALUES(17, 'Language.code', 'En', 'string', '', 'The iso code of the default site language.', 0);
INSERT INTO `core_configs` VALUES(18, 'Blog.allow_comments', 'true', 'bool', 'true,false', 'Whether to allow comments on the blog or not. If disabled historical comments will not be displayed but will not be deleted.', 0);
INSERT INTO `core_configs` VALUES(19, 'Cms.allow_comments', 'true', 'bool', 'true,false', 'Whether to allow comments on the cms Content items or not. If disabled historical comments will not be displayed but will not be deleted.', 0);
INSERT INTO `core_configs` VALUES(20, 'Newsletter.send_count', '200', 'integer', '', 'The number of newsletters to send at a time.', 0);
INSERT INTO `core_configs` VALUES(21, 'Newsletter.send_interval', '300', 'integer', '', 'The time interval between sending emails in seconds', 0);
INSERT INTO `core_configs` VALUES(22, 'Newsletter.track_views', 'true', 'bool', 'true,false', 'Attempt to track the number of views a newsletter creates.  works with  a call back to the server.  Needs html to work', 0);
INSERT INTO `core_configs` VALUES(23, 'Newsletter.send_as', 'both', 'dropdown', 'both,html,text', 'What format to send the newsletter out as. Both is the best option as its nut uncommon for people to only accept text mails.', 0);
INSERT INTO `core_configs` VALUES(24, 'Website.name', 'Some Site', 'string', '', 'This is the name of the site that will be used in emails and on the website its self', 1);
INSERT INTO `core_configs` VALUES(25, 'Website.description', 'Some Seo information about the site', 'string', '', 'This is the main description about the site', 0);
INSERT INTO `core_configs` VALUES(26, 'Cms.auto_redirect', 'true', 'bool', 'true,false', 'When a category has only one content itme should the site automaticaly redirect to that one item of first display the category.\r\n\r\nThis will also work for sections.', 0);
INSERT INTO `core_configs` VALUES(27, 'Comments.time_limit', '4 weeks', 'string', '', 'the date the comments will stop being available. if it is set to 0 users will always be able to comment on a record.\r\n\r\nit uses strtotime() and will expire after the amount of time you specify. eg: 4 weeks - comments will be disabled 4 weeks after the post was last edited.', 0);
INSERT INTO `core_configs` VALUES(28, 'Blog.depreciate', '6 months', 'string', '', 'Uses strtotime, after this time the post will be marked as depreciated.  set to 0 to never show this message.', 1);
INSERT INTO `core_configs` VALUES(29, 'Comments.purge', '4 weeks', 'string', '', 'If set to 0 purge is disabled.  You can also enter a time string used in strtotime() like "4 weeks" and purge will remove comments that pending older than 4 weeks.', 1);
INSERT INTO `core_configs` VALUES(30, 'Comments.auto_moderate', 'false', 'bool', 'true,false', 'Set this to true for comments to be automaticaly set to active so you do not need to manually moderate them in admin.\r\n\r\nif set to false, comments will need to be activated before they are displayed on the site.', 1);

--
-- Dumping data for table `core_groups`
--

INSERT INTO `core_groups` VALUES(1, 'admin', 'admin', '2009-12-16 00:06:53', '2009-12-16 00:06:53', 0, 1, 2);

--
-- Dumping data for table `core_users`
--

INSERT INTO `core_users` VALUES(1, 'dogmatic', 'dogmatic69@gmail.com', 'def267b31a9443f297b593b42992da19c0e72fec', 1, '2009-12-13 01:53:54', '2009-12-13 01:53:54');
INSERT INTO `core_users` VALUES(2, 'bob', 'bob@bob.com', 'def267b31a9443f297b593b42992da19c0e72fec', 1, '2009-12-16 16:24:33', '2009-12-16 16:24:33');

--
-- Dumping data for table `user_configs`
--


--
-- Dumping data for table `user_details`
--