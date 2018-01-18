#
# Dumping data for table EXT_groups
#

INSERT INTO EXT_groups VALUES (1, 'Administrators', 'Members of this group have the absolute power.', 1, 1, 1, 1, 0, 1);
INSERT INTO EXT_groups VALUES (2, 'Moderators', 'Moderators can manage content of the calendar', 1, 0, 0, 1, 0, 0);
INSERT INTO EXT_groups VALUES (3, 'Anonymous', 'Group of anonymous users', 0, 0, 0, 0, 1, 1);
INSERT INTO EXT_groups VALUES (4, 'Banned', 'The damned group.', 0, 0, 0, 0, 1, 1);

#
# Dumping data for table EXT_categories
#

INSERT INTO EXT_categories VALUES (1,0,'General', 'This is the default category', '#000000','#EEF0F0', 0, 1);

#
# Dumping data for table EXT_config
#

INSERT INTO EXT_config VALUES ('allowed_file_extensions', 'GIF/PNG/JPG/JPEG');
INSERT INTO EXT_config VALUES ('max_upl_size', '20000');
INSERT INTO EXT_config VALUES ('cookie_name', 'ext20');
INSERT INTO EXT_config VALUES ('cookie_path', '/');
INSERT INTO EXT_config VALUES ('debug_mode', '0');
INSERT INTO EXT_config VALUES ('events_per_page', '10');
INSERT INTO EXT_config VALUES ('calendar_name', 'ExtCalendar 2');
INSERT INTO EXT_config VALUES ('calendar_admin_email', 'you@somewhere.com');
INSERT INTO EXT_config VALUES ('calendar_description', 'Your online events calendar');
INSERT INTO EXT_config VALUES ('lang', 'english');
INSERT INTO EXT_config VALUES ('charset', 'language file');
INSERT INTO EXT_config VALUES ('main_table_width', '650');
INSERT INTO EXT_config VALUES ('max_tabs', '12');
INSERT INTO EXT_config VALUES ('theme', 'default');
# INSERT INTO EXT_config VALUES ('timezone', '-5');
INSERT INTO EXT_config VALUES ('time_format_24hours', '1');
INSERT INTO EXT_config VALUES ('auto_daylight_saving', '1');
INSERT INTO EXT_config VALUES ('default_view', '2');
INSERT INTO EXT_config VALUES ('popup_event_mode', '0');
INSERT INTO EXT_config VALUES ('popup_event_width', '550');
INSERT INTO EXT_config VALUES ('popup_event_height', '300');
INSERT INTO EXT_config VALUES ('add_event_view', '1');
INSERT INTO EXT_config VALUES ('cats_view', '1');
INSERT INTO EXT_config VALUES ('daily_view', '1');
INSERT INTO EXT_config VALUES ('weekly_view', '1');
INSERT INTO EXT_config VALUES ('monthly_view', '1');
INSERT INTO EXT_config VALUES ('flyer_view', '1');
INSERT INTO EXT_config VALUES ('search_view', '1');
INSERT INTO EXT_config VALUES ('day_start', '0');
INSERT INTO EXT_config VALUES ('archive', '0');
INSERT INTO EXT_config VALUES ('flyer_show_picture', '1');
INSERT INTO EXT_config VALUES ('addevent_allow_html', '0');
INSERT INTO EXT_config VALUES ('addevent_allow_contact', '1');
INSERT INTO EXT_config VALUES ('addevent_allow_email', '1');
INSERT INTO EXT_config VALUES ('addevent_allow_url', '1');
INSERT INTO EXT_config VALUES ('addevent_allow_picture', '1');
INSERT INTO EXT_config VALUES ('new_post_notification', '1');
INSERT INTO EXT_config VALUES ('cal_view_max_chars', '100');
INSERT INTO EXT_config VALUES ('flyer_view_max_chars', '100');
INSERT INTO EXT_config VALUES ('weekly_view_max_chars', '100');
INSERT INTO EXT_config VALUES ('daily_view_max_chars', '100');
INSERT INTO EXT_config VALUES ('cats_view_max_chars', '100');
INSERT INTO EXT_config VALUES ('mini_cal_def_picture', 'def_pic.gif');
INSERT INTO EXT_config VALUES ('mini_cal_diplay_options', '1');
INSERT INTO EXT_config VALUES ('release_name', '{RELEASE_NAME}');
INSERT INTO EXT_config VALUES ('release_version', '{RELEASE_VERSION}');
INSERT INTO EXT_config VALUES ('release_type', '{RELEASE_TYPE}');
INSERT INTO EXT_config VALUES ('sort_order', 'ta');
INSERT INTO EXT_config VALUES ('picture_chmod', '0644');
INSERT INTO EXT_config VALUES ('max_upl_dim', '450');
INSERT INTO EXT_config VALUES ('allow_user_registration', '1');
INSERT INTO EXT_config VALUES ('reg_email_verify', '1');
INSERT INTO EXT_config VALUES ('reg_duplicate_emails', '0');
INSERT INTO EXT_config VALUES ('calendar_status', '1');
INSERT INTO EXT_config VALUES ('show_recurrent_events', '1');
INSERT INTO EXT_config VALUES ('multi_day_events', 'all');
INSERT INTO EXT_config VALUES ('cal_view_show_week', '1');
INSERT INTO EXT_config VALUES ('mail_method', 'mail');
INSERT INTO EXT_config VALUES ('mail_smtp_host', 'smtp.myhost.com');
INSERT INTO EXT_config VALUES ('mail_smtp_auth', '0');
INSERT INTO EXT_config VALUES ('mail_smtp_username', '');
INSERT INTO EXT_config VALUES ('mail_smtp_password', '');

INSERT INTO EXT_config VALUES ('legend_cat_columns', '4');

#
# Dumping data for table EXT_template
#

INSERT INTO EXT_templates VALUES (1, 'header', 'Custom header structure to display on top', 0, '<body>\r\n<div align="center">\r\n  <div class="apptitle">{CAL_NAME}</div>\r\n  <div class="appdesc">{CAL_DESCRIPTION}</div>\r\n  <br>\r\n  {ADMIN_MENU}\r\n  <br>\r\n  {MAIN_MENU} \r\n  <br>\r\n  <div width="{MAIN_TABLE_WIDTH}">\r\n', NOW());
INSERT INTO EXT_templates VALUES (2, 'footer', 'Custom footer structure to display at the bottom', 0, '  </div>\r\n</div>\r\n</body>', NOW());
INSERT INTO EXT_templates VALUES (3, 'meta', 'Space to hold meta tags and other browser related information', 1, '', NOW());
