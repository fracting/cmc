CREATE TABLE IF NOT EXISTS `#__cmc_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mc_id` varchar(10) NOT NULL,
  `web_id` int(11) NOT NULL,
  `list_name` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `email_type_option` tinyint(1) NOT NULL DEFAULT '0',
  `use_awesomebar` tinyint(1) NOT NULL DEFAULT '1',
  `default_from_name` varchar(255) NOT NULL,
  `default_from_email` varchar(255) NOT NULL,
  `default_subject` varchar(255) DEFAULT NULL,
  `default_language` varchar(10) NOT NULL DEFAULT 'en',
  `list_rating` float(5,4) NOT NULL DEFAULT '0.0000',
  `subscribe_url_short` varchar(255) NOT NULL,
  `subscribe_url_long` varchar(255) NOT NULL,
  `beamer_address` varchar(255) NOT NULL,
  `visibility` varchar(255) NOT NULL DEFAULT 'pub',
  `created_user_id` int(11) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(11) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) NOT NULL DEFAULT '0',
  `query_data` text,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__cmc_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mc_id` varchar(255) DEFAULT NULL,
  `list_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(500) DEFAULT NULL,
  `email` varchar(500) NOT NULL,
  `email_type` varchar(500) NOT NULL DEFAULT 'html',
  `interests` text,
  `merges` text,
  `status` varchar(255) NOT NULL DEFAULT 'subscribed',
  `ip_signup` varchar(255) DEFAULT NULL,
  `timestamp_signup` datetime NOT NULL,
  `ip_opt` varchar(255) DEFAULT NULL,
  `timestamp_opt` datetime NOT NULL,
  `member_rating` tinyint(2) NOT NULL DEFAULT '2',
  `campaign_id` int(11) NOT NULL,
  `lists` varchar(255) DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `info_changed` datetime NOT NULL,
  `web_id` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT 'en',
  `is_gmonkey` tinyint(1) NOT NULL DEFAULT '0',
  `geo` text COMMENT 'json',
  `clients` text COMMENT 'json',
  `static_segments` text COMMENT 'json',
  `created_user_id` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `modified_user_id` int(11) NOT NULL,
  `modified_time` datetime NOT NULL,
  `query_data` text COMMENT 'json',
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `#__cmc_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `params` text NOT NULL,
  `plg` tinyint(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);