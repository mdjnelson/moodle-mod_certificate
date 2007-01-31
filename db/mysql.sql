-- --------------------------------------------------------

-- 
-- Table structure for table 'prefix_certificate'
-- 

CREATE TABLE prefix_certificate (
  id int(10) unsigned NOT NULL auto_increment,
  course int(10) unsigned NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  emailteachers tinyint(2) unsigned NOT NULL default '0',
  save tinyint(2) unsigned NOT NULL default '0',
  delivery tinyint(3) unsigned NOT NULL default '0',
  certificatetype varchar(50) NOT NULL default '',
  borderstyle varchar(30) NOT NULL default '0',
  bordercolor varchar(30) NOT NULL default '0',
  printwmark varchar(30) NOT NULL default '0',
  printdate int(10) unsigned NOT NULL default '0',
  datefmt int(10) unsigned NOT NULL default '0',
  printnumber int(10) unsigned NOT NULL default '0',
  printgrade int(10) unsigned NOT NULL default '0',
  gradefmt int(10) unsigned NOT NULL default '0',
  printteacher int(10) unsigned NOT NULL default '0',
  printsignature varchar(30) NOT NULL default '0',
  printseal varchar(30) NOT NULL default '0',
  timemodified int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Defines options for creating certificates';

CREATE TABLE prefix_certificate_issues (
  id int(10) unsigned NOT NULL auto_increment,
  certificateid int(10) unsigned NOT NULL default '0',
  userid int(10) unsigned NOT NULL default '0',
  timecreated int(10) unsigned NOT NULL default '0',
  studentname varchar(40) NOT NULL default '',
  code varchar(40) default '',
  classname varchar(254) NOT NULL default '',
  certdate int(10) unsigned default '0',
  sent tinyint(1) unsigned NOT NULL default '0',
  mailed tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Info about issued certificates';

INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'view', 'certificate', 'name');
INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'update', 'certificate', 'name');
INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'add', 'certificate', 'name');
INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'report', 'certificate', 'name');
INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'received', 'certificate', 'name');