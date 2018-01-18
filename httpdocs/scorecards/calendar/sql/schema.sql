#
# Table structure for table EXT_groups
#

CREATE TABLE EXT_groups (
  group_id int(11) NOT NULL auto_increment,
  group_name varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  has_admin_access tinyint(1) NOT NULL default '0',
  can_manage_accounts tinyint(1) NOT NULL default '0',
  can_change_settings tinyint(1) NOT NULL default '0',
  can_manage_cats tinyint(1) NOT NULL default '0',
  upl_need_approval tinyint(1) NOT NULL default '1',
  locked tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (group_id)
) TYPE=MyISAM COMMENT = 'Table holding user groups';
# ----------------------------------------------------

#
# Table structure for table EXT_users
#

CREATE TABLE EXT_users (
  user_id int(11) NOT NULL auto_increment,
  username varchar(30) NOT NULL default '',
  password varchar(64) NOT NULL default '',
  firstname varchar(64) default '',
  lastname varchar(64) default '',
  group_id int(11) NOT NULL default 2,
  lastvisit datetime NOT NULL default '0000-00-00 00:00:00',
  email varchar(255) NOT NULL default '',
  user_regdate datetime NOT NULL default '0000-00-00 00:00:00',
  user_lang varchar(255) NOT NULL default '',
  user_website varchar(255) NOT NULL default '',
  user_location varchar(255) NOT NULL default '',
  user_occupation varchar(255) NOT NULL default '',
  reg_key varchar(64) default NULL,
  user_status tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (user_id),
  UNIQUE KEY username (username)
) TYPE=MyISAM COMMENT = 'Table for calendar users';
# ----------------------------------------------------

#
# Table structure for table EXT_categories
#

CREATE TABLE EXT_categories (
  cat_id int(11) NOT NULL auto_increment,
  cat_parent int(11) NOT NULL default '0',
  cat_name varchar(150) NOT NULL default '',
  description text NOT NULL,
	color varchar(10) default '#000000',
	bgcolor varchar(10) default '#EEF0F0',
	options tinyint(4) default '0',
	enabled tinyint(4) default '0',
  PRIMARY KEY  (cat_id),
  UNIQUE KEY cat_id (cat_id)
) TYPE=MyISAM COMMENT = 'Table for event categories';
# ----------------------------------------------------

#
# Table structure for table EXT_events
#

CREATE TABLE EXT_events (
  id int(11) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  description text NOT NULL,
  contact text NOT NULL,
  url varchar(100) NOT NULL default '',
  email varchar(120) NOT NULL default '',
  picture varchar(100) NOT NULL default '',
  cat tinyint(2) NOT NULL default '0',
  day tinyint(2) NOT NULL default '0',
  month smallint(2) NOT NULL default '0',
  year smallint(4) NOT NULL default '0',
  approved tinyint(1) NOT NULL default '0',
  start_date datetime NOT NULL default '0000-00-00 00:00:00',
  end_date datetime default '0000-00-00 00:00:00',
  recur_type varchar(16) default NULL,
  recur_val tinyint(4) default '0',
  recur_end_type tinyint(1) unsigned NOT NULL default '0',
  recur_count tinyint unsigned NOT NULL default '0',
  recur_until date default '0000-00-00',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY start_date (start_date)
) TYPE=MyISAM COMMENT = 'Table holding events and their attributes';
# ----------------------------------------------------

#
# Table structure for table EXT_config
#

CREATE TABLE EXT_config (
  name varchar(40) NOT NULL default '',
  value varchar(255) NOT NULL default '',
  PRIMARY KEY  (name)
) TYPE=MyISAM COMMENT = 'Table for configurable parameters';
# ----------------------------------------------------

#
# Table structure for table EXT_template
#

CREATE TABLE EXT_templates (
	template_id int(11) NOT NULL auto_increment ,
	template_type varchar(16) NOT NULL ,
  template_description varchar(255) default NULL,
	template_status tinyint(1) default '0' NOT NULL ,
	template_value text default NULL,
	last_access datetime default '0000-00-00 00:00:00' NOT NULL ,
	PRIMARY KEY (template_id) ,
	INDEX (template_status) ,
	UNIQUE (template_type),
	FULLTEXT (template_value)
) TYPE = MYISAM COMMENT = 'Table for custom interface template';
# ----------------------------------------------------

#
# Table structure for table EXT_template
#

CREATE TABLE EXT_plugins (
  plugin_id int(11) NOT NULL auto_increment,
  plugin_name varchar(64) NOT NULL,
  plugin_priority tinyint(2) unsigned NOT NULL default '50',
  plugin_path varchar(255) default null,
  PRIMARY KEY(plugin_id)
) TYPE = MYISAM COMMENT = 'Table holding installed plugins';
# ----------------------------------------------------
