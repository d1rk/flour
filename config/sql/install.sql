CREATE TABLE IF NOT EXISTS `flour_activities` (
  `id` CHAR(36) NOT NULL,
  `user_id` char(36) NULL,
  `type` VARCHAR(255) NOT NULL,
  `status` VARCHAR(40) NOT NULL,
  `message` text NOT NULL,
  `tags` VARCHAR(255) NOT NULL,
  `data` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_users` (
  `id` CHAR(36) NOT NULL DEFAULT '',
  `name` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `tags` VARCHAR(255) NOT NULL,
  `created` DATETIME DEFAULT NULL,
  `modified` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_usergroups` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_login_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` CHAR(36) NOT NULL,
  `token` CHAR(32) NOT NULL,
  `duration` VARCHAR(32) NOT NULL,
  `used` TINYINT(1) NOT NULL DEFAULT '0',
  `created` DATETIME NOT NULL,
  `expires` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_custom_lists` (
  `id` char(36) NOT NULL,
  `status` int(3) default '0',
  `slug` varchar(255) NOT NULL default '',
  `description` tinytext,
  `name` varchar(255) NOT NULL COMMENT 'internal name',
  `title` varchar(255) NOT NULL,
  `tags` VARCHAR(255) NOT NULL,
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_custom_list_items` (
  `id` char(36) NOT NULL,
  `custom_list_id` char(36) NOT NULL,
  `status` int(3) default '0',
  `slug` varchar(255) NOT NULL default '',
  `description` tinytext,
  `name` varchar(255) NOT NULL COMMENT 'internal name',
  `val` TEXT NULL,
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_nodes` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL default 'page',
  `host_id` char(36) default NULL,
  `parent_id` char(36) default NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `status` int(3) default '0',
  `slug` varchar(255) NOT NULL default '',
  `fullslug` varchar(255) default NULL,
  `description` tinytext,
  `name` varchar(255) NOT NULL COMMENT 'internal name',
  `title` varchar(255) NOT NULL,
  `excerpt` text,
  `body` text,
  `template` varchar(120) NOT NULL default 'default',
  `layout` varchar(120) NOT NULL default 'default',
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_node_fields` (
  `id` char(36) NOT NULL,
  `node_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `val` text NOT NULL,
  `status` tinyint(3) NOT NULL default '0',
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_contents` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL default 'content',
  `status` int(3) default '0',
  `slug` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL COMMENT 'internal name',
  `description` tinytext,
  `title` varchar(255) NOT NULL,
  `body` text,
  `tags` VARCHAR(255) NOT NULL,
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_content_fields` (
  `id` char(36) NOT NULL,
  `content_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `val` text NOT NULL,
  `status` tinyint(3) NOT NULL default '0',
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_widgets` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL default 'content',
  `status` int(3) default '0',
  `slug` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL COMMENT 'internal name',
  `description` tinytext,
  `title` varchar(255) NOT NULL,
  `data` text,
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_widget_fields` (
  `id` char(36) NOT NULL,
  `widget_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `val` text NOT NULL,
  `status` tinyint(3) NOT NULL default '0',
  `valid_from` datetime default NULL,
  `valid_to` datetime default NULL,
  `created` datetime NOT NULL,
  `created_by` char(36) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` char(36) default NULL,
  `deleted` datetime default NULL,
  `deleted_by` char(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_tags` (
  `id` varchar(36) NOT NULL,
  `identifier` varchar(30) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `keyname` varchar(30) NOT NULL,
  `weight` int(2) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_TAG` (`identifier`,`keyname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_tagged` (
  `id` varchar(36) NOT NULL,
  `foreign_key` varchar(36) NOT NULL,
  `tag_id` varchar(36) NOT NULL,
  `model` varchar(255) NOT NULL,
  `language` varchar(6) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_TAGGING` (`model`,`foreign_key`,`tag_id`,`language`),
  KEY `INDEX_TAGGED` (`model`),
  KEY `INDEX_LANGUAGE` (`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;