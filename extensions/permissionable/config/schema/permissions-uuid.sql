CREATE TABLE `permissions` (
  `id` char(36) NOT NULL,
  `model` varchar(32) NOT NULL,
  `foreign_id` char(36) NOT NULL,
  `uid` char(36) NOT NULL,
  `gid` char(36) NOT NULL,
  `perms` int(3) unsigned zerofill NOT NULL DEFAULT '000',
  PRIMARY KEY (`id`),
  KEY `polymorphic_idx` (`model`,`foreign_id`),
  KEY `uid_idx` (`uid`),
  KEY `gid_idx` (`gid`)
);