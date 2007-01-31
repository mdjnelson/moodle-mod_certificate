---- postgres7.sql ---
# This file contains a complete database schema for all the
# tables used by this module, written in SQL

CREATE TABLE prefix_certificate (
  id SERIAL PRIMARY KEY,
  course integer NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  type integer  NOT NULL default '0',
  border_style varchar(30) NOT NULL default '0',
  border_color varchar(30) NOT NULL default '0',
  print_wmark varchar(30) NOT NULL default '0',
  date_fmt integer  NOT NULL default '0',
  print_number integer  NOT NULL default '0',
  print_grade integer  NOT NULL default '0',
  print_teacher integer  NOT NULL default '0',
  print_signature varchar(30) NOT NULL default '0',
  print_seal varchar(30) NOT NULL default '0'
);

CREATE TABLE prefix_certificate_viewed (
  id SERIAL PRIMARY KEY,
  courseid integer  NOT NULL default '0',
  userid integer  NOT NULL default '0',
  studentname varchar(40) NOT NULL default '',
  code varchar(30) NOT NULL default '',
  classname varchar(40) NOT NULL default '',
  cert_date integer  NOT NULL default '0',
  timemodified integer  NOT NULL default '0'
);

INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'view', 'certificate', 'name');
INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'update', 'certificate', 'name');
INSERT INTO prefix_log_display (module, action, mtable, field) VALUES ('certificate', 'add', 'certificate', 'name');
--- end of file ----