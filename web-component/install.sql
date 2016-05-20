DROP TABLE IF EXISTS `turtleduck_allowed_users`;
CREATE TABLE `turtleduck_allowed_users` (
  `username` varchar(255) NOT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TRIGGER `turtleduck_allowed_users_date` 
BEFORE INSERT ON `turtleduck_allowed_users` FOR EACH ROW
SET new.date_added = NOW();

DROP TABLE IF EXISTS `turtleduck_chats`;
CREATE TABLE `turtleduck_chats` (
  `chat_id` int(64) NOT NULL,
  `registered_by` varchar(255) NOT NULL,
  `registration_time` datetime NOT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `turtleduck_spigot_log`;
CREATE TABLE `turtleduck_spigot_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_match` tinyint(1) NOT NULL,
  `remote_hmac` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `salt` int(64) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
