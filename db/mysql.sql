# This file contains a complete database schema for all the 
# tables used by this module, written in SQL

CREATE TABLE `prefix_certificate` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `course` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `type` int(10) unsigned NOT NULL default'0',
  `border_style` varchar(30) NOT NULL default'0',
  `border_color` varchar(30) NOT NULL default'0',
  `print_wmark` varchar(30) NOT NULL default'0',
  `date_fmt` int(10) unsigned NOT NULL default'0',
  `print_number` int(10) unsigned NOT NULL default'0',
  `print_grade` int(10) unsigned NOT NULL default'0',
  `print_teacher` int(10) unsigned NOT NULL default'0',
  `print_signature` varchar(30) NOT NULL default'0',
  `print_seal` varchar(30) NOT NULL default'0',
  PRIMARY KEY  (`id`)
) COMMENT='Defines certificates';

CREATE TABLE `prefix_certificate_viewed` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `courseid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `studentname` varchar(40) NOT NULL default '',
  `code` varchar(30) NOT NULL default '',
  `classname` varchar(40) NOT NULL default '',
  `cert_date` int(10) unsigned NOT NULL default '0',
  `timemodified` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) COMMENT='Defines issued certificates';
