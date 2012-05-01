# Dump of table beta_keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `beta_keys`;

CREATE TABLE `beta_keys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `beta_key` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `insert_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beta_key` (`beta_key`),
  KEY `name` (`name`),
  KEY `email` (`email`)
);



# Dump of table foursquare_check_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `foursquare_check_log`;

CREATE TABLE `foursquare_check_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `check_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `total_checkins` int(11) NOT NULL DEFAULT '0',
  `unique_visitors` int(11) NOT NULL DEFAULT '0',
  `tips_left` int(11) NOT NULL DEFAULT '0',
  `photo_count` int(11) NOT NULL DEFAULT '0',
  `insert_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `check_id` (`check_id`)
);



# Dump of table foursquare_check_log_live
# ------------------------------------------------------------

DROP TABLE IF EXISTS `foursquare_check_log_live`;

CREATE TABLE `foursquare_check_log_live` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `check_id` int(11) NOT NULL,
  `herenow_total` int(11) NOT NULL DEFAULT '0',
  `herenow_friends` int(11) NOT NULL DEFAULT '0',
  `herenow_other` int(11) NOT NULL DEFAULT '0',
  `insert_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `check_id` (`check_id`)
);



# Dump of table foursquare_check_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `foursquare_check_tags`;

CREATE TABLE `foursquare_check_tags` (
  `check_id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `tag` (`tag`,`check_id`),
  KEY `tag_2` (`tag`)
);



# Dump of table foursquare_checks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `foursquare_checks`;

CREATE TABLE `foursquare_checks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `check_title` varchar(255) NOT NULL DEFAULT '',
  `venue_id` varchar(25) NOT NULL DEFAULT '',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `insert_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_daily_check_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_live_check_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `venue_id` (`venue_id`),
  KEY `active` (`active`)
);



# Dump of table foursquare_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `foursquare_tokens`;

CREATE TABLE `foursquare_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL DEFAULT '',
  `insert_ts` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
);



# Dump of table packages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `check_limit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `check_limit` (`check_limit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `packages` (`id`, `name`, `description`, `check_limit`)
VALUES
	(1,'Starter','',10),
	(2,'Professional','',50),
	(3,'Enterprise','',200);


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `display_name` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `package_id` int(11) NOT NULL DEFAULT '0',
  `level` enum('user','admin') NOT NULL DEFAULT 'user',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `insert_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);

