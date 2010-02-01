CREATE TABLE `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(32) NOT NULL,
  `foreign_id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `gid` int(11) unsigned NOT NULL,
  `perms` int(3) unsigned zerofill NOT NULL DEFAULT '000',
  PRIMARY KEY (`id`),
  KEY `polymorphic_idx` (`model`,`foreign_id`),
  KEY `uid_idx` (`uid`),
  KEY `gid_idx` (`gid`)
);