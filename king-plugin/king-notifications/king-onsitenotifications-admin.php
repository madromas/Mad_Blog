<?php

/*
	Plugin Name: King Notifications
	Plugin URI: 
	Plugin Description: King Notifications.
	Plugin Version: 1.0
	Plugin Date: 2014-03-29
	Plugin Author: 
	Plugin Author URI: 
	Plugin Minimum KingMEDIA Version: 1
	Plugin Update Check URI:

*/

	class kingm_onsitenotifications_admin {

		// initialize db-table 'eventlog' if it does not exist yet
		function init_queries($tableslc) {
		
			$tablename = qa_db_add_table_prefix('eventlog');
			
			// check if event logger has been initialized already (check for one of the options and existing table)
			require_once QA_INCLUDE_DIR.'king-app-options.php';
			if(qa_opt('event_logger_to_database') && in_array($tablename, $tableslc)) {
				// options exist, but check if really enabled
				if(qa_opt('event_logger_to_database')=='' && qa_opt('event_logger_to_files')=='') {
					// enabled database logging
					qa_opt('event_logger_to_database', 1);
				}
			}
			else {
				// not enabled, let's enable the event logger
			
				// set option values for event logger
				qa_opt('event_logger_to_database', 1);
				qa_opt('event_logger_to_files', '');
				qa_opt('event_logger_directory', '');
				qa_opt('event_logger_hide_header', '');
			
				if (!in_array($tablename, $tableslc)) {
					require_once QA_INCLUDE_DIR.'king-app-users.php';
					require_once QA_INCLUDE_DIR.'king-db-maxima.php';

					return 'CREATE TABLE IF NOT EXISTS ^eventlog ('.
						'datetime DATETIME NOT NULL,'.
						'ipaddress VARCHAR (15) CHARACTER SET ascii,'.
						'userid '.qa_get_mysql_user_column_type().','.
						'handle VARCHAR('.QA_DB_MAX_HANDLE_LENGTH.'),'.
						'cookieid BIGINT UNSIGNED,'.
						'event VARCHAR (20) CHARACTER SET ascii NOT NULL,'.
						'params VARCHAR (800) NOT NULL,'.
						'KEY datetime (datetime),'.
						'KEY ipaddress (ipaddress),'.
						'KEY userid (userid),'.
						'KEY event (event)'.
					') ENGINE=MyISAM DEFAULT CHARSET=utf8';
				}
			}
			// memo: would be best to check if plugin is installed in king-plugin/ folder or using plugin_exists()
			// however this functionality is not available in q2a v1.6.3 
			
		} // end init_queries

		// option's value is requested but the option has not yet been set
		function option_default($option) {
			switch($option) {
				case 'kingm_onsitenotifications_enabled':
					return 1; // true
				case 'kingm_onsitenotifications_nill':
					return 'N'; // days
				case 'kingm_onsitenotifications_maxage':
					return 365; // days
				case 'kingm_onsitenotifications_maxevshow':
					return 100; // max events to show in notify box
				default:
					return null;
			}
		}
			
		function allow_template($template) {
			return ($template!='admin');
		}       
			
		function admin_form(&$qa_content){                       

			// process the admin form if admin hit Save-Changes-button
			$ok = null;
			if (qa_clicked('kingm_onsitenotifications_save')) {
				qa_opt('kingm_onsitenotifications_enabled', (bool)qa_post_text('kingm_onsitenotifications_enabled')); // empty or 1
				qa_opt('kingm_onsitenotifications_nill', qa_post_text('kingm_onsitenotifications_nill')); // string
				qa_opt('kingm_onsitenotifications_maxevshow', (int)qa_post_text('kingm_onsitenotifications_maxevshow')); // int
				$ok = qa_lang('admin/options_saved');
			}
			
			// form fields to display frontend for admin
			$fields = array();
			
			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('kingm_onsitenotifications_lang/enable_plugin'),
				'tags' => 'name="kingm_onsitenotifications_enabled"',
				'value' => qa_opt('kingm_onsitenotifications_enabled'),
			);
			
			
			$fields[] = array(
				'type' => 'input',
				'label' => qa_lang('kingm_onsitenotifications_lang/no_notifications_label'),
				'tags' => 'name="kingm_onsitenotifications_nill" style="width:100px;"',
				'value' => qa_opt('kingm_onsitenotifications_nill'),
			);

			$fields[] = array(
				'type' => 'input',
				'label' => qa_lang('kingm_onsitenotifications_lang/admin_maxeventsshow'),
				'tags' => 'name="kingm_onsitenotifications_maxevshow" style="width:100px;"',
				'value' => qa_opt('kingm_onsitenotifications_maxevshow'),
			);

			return array(           
				'ok' => ($ok && !isset($error)) ? $ok : null,
				'fields' => $fields,
				'buttons' => array(
					array(
						'label' => qa_lang_html('main/save_button'),
						'tags' => 'name="kingm_onsitenotifications_save"',
					),
				),
			);
		}
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/