<?php

	class qa_html_theme_layer extends qa_html_theme_base {
		
		var $plugin_url_onsitenotifications;

		// needed to get the plugin url
		function qa_html_theme_layer($template, $content, $rooturl, $request)
		{
			if(qa_opt('kingm_onsitenotifications_enabled')) {
				global $qa_layers;
				$this->plugin_url_onsitenotifications = $qa_layers['kingm On-Site-Notifications Layer']['urltoroot'];
			}
			qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
		}
		
		function head_script(){
			qa_html_theme_base::head_script();
			if(qa_opt('kingm_onsitenotifications_enabled')) {
				$this->output('<script type="text/javascript">
						var eventnotifyAjaxURL = "'.qa_path_html('eventnotify').'";
					</script>');  
				$this->output('<script type="text/javascript" src="'. qa_opt('site_url') . $this->plugin_url_onsitenotifications .'script.js"></script>');
				$this->output('<link rel="stylesheet" type="text/css" href="'. qa_opt('site_url') . $this->plugin_url_onsitenotifications .'styles.css">');
			}
		}
		
		function userpanel() {
		qa_html_theme_base::userpanel();
			if(qa_opt('kingm_onsitenotifications_enabled') && qa_get_logged_in_userid()) {

				qa_db_query_sub(
					'CREATE TABLE IF NOT EXISTS ^usermeta (
					meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					user_id bigint(20) unsigned NOT NULL,
					meta_key varchar(255) DEFAULT NULL,
					meta_value longtext,
					PRIMARY KEY (meta_id),
					UNIQUE (user_id,meta_key)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8'
				);		

				$last_visit = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT UNIX_TIMESTAMP(meta_value) FROM ^usermeta WHERE user_id=# AND meta_key=$',
						qa_get_logged_in_userid(), 'visited_profile'
					),
					true
				);

				// first time visitor, we set the last visit manually in the past
				if(is_null($last_visit)) {
					$last_visit = '1981-03-31 06:25:00';
				}
				// select and count all in_eventcount that are newer as last visit
				$eventcount = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT COUNT(event) FROM ^eventlog 
								WHERE userid=# AND DATE_SUB(CURDATE(),INTERVAL # DAY) <= datetime 
								AND FROM_UNIXTIME(#) <= datetime 
								AND event LIKE "in_%"',
								qa_get_logged_in_userid(), 
								qa_opt('kingm_onsitenotifications_maxage'), 
								$last_visit
					)
				);
				$tooltip = qa_lang('kingm_onsitenotifications_lang/show_notifications');
				if($eventcount) {
					$tooltip = $eventcount.' '.qa_lang('kingm_onsitenotifications_lang/x_notifications');
					// only one event
					if($eventcount==1) {
						$tooltip = qa_lang('kingm_onsitenotifications_lang/one_notification');
					}
					// add notify bubble to user navigation highlighted
					$this->output('<li> <a class="king-history-new-event-link" title="'.$tooltip.'"><span class="notifybub ntfy-event-new">'.$eventcount.'<i class="far fa-bell fa-lg"></i></span></a></li>');
				}
				else {
					// add notify bubble to user navigation
					$this->output('<li> <a class="king-history-new-event-link" title="'.$tooltip.'"><span class="notifybub ntfy-event-nill"><i class="far fa-bell fa-lg"></i></span></a></li>');
				}
			}
			
		
		}

	} // end qa_html_theme_layer
	

/*
	Omit PHP closing tag to help avoid accidental output
*/