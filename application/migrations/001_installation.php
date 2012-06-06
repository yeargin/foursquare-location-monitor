<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_installation extends CI_Migration {

	public function up() {

		// If a user table already exists, safe to bet that
		// installation has already run. Fix the missing migrations table.
		$query = $this->db->query('SHOW TABLES LIKE \'users\' ');
		if ($query->row()):
			$this->db->query('CREATE TABLE `migrations` (`version` int(3) NOT NULL)');
			$this->db->query('UPDATE TABLE `migrations` SET `version` = 1');
			return;
		endif;
		

		// Beta Keys
		$this->db->query("CREATE TABLE `beta_keys` (
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
		)");
		
		// Foursquare Check Log
		$this->db->query("CREATE TABLE `foursquare_check_log` (
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
		)");

		// Foursquare Check Log Live
		$this->db->query("CREATE TABLE `foursquare_check_log_live` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `check_id` int(11) NOT NULL,
		  `herenow_total` int(11) NOT NULL DEFAULT '0',
		  `herenow_friends` int(11) NOT NULL DEFAULT '0',
		  `herenow_other` int(11) NOT NULL DEFAULT '0',
		  `insert_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`),
		  KEY `check_id` (`check_id`)
		)");

		// Foursquare Check Tags
		$this->db->query("CREATE TABLE `foursquare_check_tags` (
		  `check_id` int(11) NOT NULL,
		  `tag` varchar(255) NOT NULL DEFAULT '',
		  UNIQUE KEY `tag` (`tag`,`check_id`),
		  KEY `tag_2` (`tag`)
		)");

		// Foursquare Checks Table
		$this->db->query("CREATE TABLE `foursquare_checks` (
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
		)");

		// Foursquare Tokens
		$this->db->query("CREATE TABLE `foursquare_tokens` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `token` varchar(255) NOT NULL DEFAULT '',
		  `insert_ts` datetime NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `user_id` (`user_id`)
		)");

		// Packages Table
		$this->db->query("CREATE TABLE `packages` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(250) NOT NULL DEFAULT '',
		  `description` text NOT NULL,
		  `check_limit` int(11) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `check_limit` (`check_limit`)
		)");
		$this->db->query("INSERT INTO `packages` (`id`, `name`, `description`, `check_limit`)
		VALUES
			(1,'Starter','',10),
			(2,'Professional','',50),
			(3,'Enterprise','',200)");

		// Users Table
		$this->db->query("CREATE TABLE `users` (
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
		)");
		
	}

	public function down() {
		$this->dbforge->drop_table('beta_keys');
		$this->dbforge->drop_table('foursquare_check_log');
		$this->dbforge->drop_table('foursquare_check_log_live');
		$this->dbforge->drop_table('foursquare_check_tags');
		$this->dbforge->drop_table('foursquare_checks');
		$this->dbforge->drop_table('foursquare_tokens');
		$this->dbforge->drop_table('packages');
		$this->dbforge->drop_table('users');
		$this->dbforge->drop_table('migrations');
	}
	
}