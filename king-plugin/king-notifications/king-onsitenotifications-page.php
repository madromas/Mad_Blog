<?php

	class kingm_onsitenotifications_page {
		
		var $directory;
		var $urltoroot;
		
		function load_module($directory, $urltoroot)
		{
			$this->directory=$directory;
			$this->urltoroot=$urltoroot;
		}
		
		// for display in admin interface under admin/pages
		function suggest_requests() 
		{	
			return array(
				array(
					'title' => 'On-Site-Notifications Page', // title of page
					'request' => 'eventnotify', // request name
					'nav' => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
				),
			);
		}
		
		// for url query
		function match_request($request)
		{
			if ($request=='eventnotify') {
				return true;
			}

			return false;
		}

		function process_request($request)
		{
		
			// we received post data, it is the ajax call!
			$transferString = qa_post_text('ajax');
			if( $transferString !== null ) {
				
				// prevent empty userid
				$userid = qa_get_logged_in_userid();
				if(empty($userid)) {
					echo 'Userid is empty!';
					return;
				}	
				
				// this is echoed via ajax success data
				$notifyBoxEvents = '';
				
				// ajax return all user events
				if(isset($userid) && $transferString=='receiveNotify'){
					$last_visit = qa_db_read_one_value(
						qa_db_query_sub(
							'SELECT UNIX_TIMESTAMP(meta_value) FROM ^usermeta WHERE user_id=# AND meta_key="visited_profile"',
							$userid
						), true
					);

					$maxEvents = qa_opt('kingm_onsitenotifications_maxevshow'); // maximal events to show

					// query all new events of user
					$event_query = qa_db_query_sub(
						'SELECT 
							e.event, 
							BINARY e.params as params, 
							UNIX_TIMESTAMP(e.datetime) AS datetime
						FROM 
							^eventlog AS e
						WHERE
							e.userid=#
							AND FROM_UNIXTIME(#) <= datetime
							AND e.event LIKE \'in_%\'
						ORDER BY datetime DESC
						LIMIT #', // Limit
						$userid, 
						qa_opt('kingm_onsitenotifications_maxage'), // events of last x days
						$maxEvents
					);

					
					$events = array();
					$postids = array();
					$count = 0;
					while ( ($event=qa_db_read_one_assoc($event_query,true)) !== null ) {
						if(preg_match('/postid=([0-9]+)/',$event['params'],$m) === 1) {
							$event['postid'] = (int)$m[1];
							$postids[] = (int)$m[1];
							$events[$m[1].'_'.$count++] = $event;
						}
					}
					
					// get post info, also makes sure post exists
					$posts = null;
					if(!empty($postids)) {
						$post_query = qa_db_read_all_assoc(
							qa_db_query_sub(
								'SELECT postid, type, parentid, BINARY title as title FROM ^posts 
									WHERE postid IN ('.implode(',',$postids).')'
							)
						);
						foreach($post_query as $post) {
							$posts[(string)$post['postid']] = $post;
						}
					}

					// List all events
					$notifyBoxEvents = '<div id="nfyWrap" class="nfyWrap"><div class="arrow"></div>
					<div class="nfyTop">'.qa_lang('kingm_onsitenotifications_lang/my_notifications').'</div>
					<div class="nfyContainer">
						<div id="nfyContainerInbox"><span class="no-nfy" >'.qa_opt('kingm_onsitenotifications_nill').'</span>
						';
					
					// BIG FOREACH
					foreach($events as $postid_string => $event) {
					
						$type = $event['event'];
						$postid = preg_replace('/_.*/','',$postid_string);
						$post = null;
						$post = @$posts[$postid];
						
						$params = array();
						$paramsa = explode("\t",$event['params']);
						foreach($paramsa as $param) {
							$parama = explode('=',$param);
							if(isset($parama[1])) {
								$params[$parama[0]]=$parama[1];
							}
							else {
								$params[$param]=$param;
							}
						}
						
						$link = '';
						$linkTitle = '';
						$activity_url = '';
						
						// comment or answer
						if($post != null && strpos($event['event'],'q_') !== 0 && strpos($event['event'],'in_q_') !== 0) {
							if(!isset($params['parentid'])) {
								$params['parentid'] = $post['parentid'];
							}

							$parent = qa_db_select_with_pending(
								qa_db_full_post_selectspec(
									$userid,
									$params['parentid']
								)
							);
							if($parent['type'] == 'A') {
								$parent = qa_db_select_with_pending(
									qa_db_full_post_selectspec(
										$userid,
										$parent['parentid']
									)
								);				
							}
							
							$anchor = qa_anchor((strpos($event['event'],'a_') === 0 || strpos($event['event'],'in_a_') === 0?'A':'C'), $params['postid']);
							$activity_url = qa_path_html(qa_q_request($parent['postid'], $parent['title']), null, qa_opt('site_url'),null,$anchor);
							$linkTitle = $parent['title'];
							$link = '<a target="_blank" href="'.$activity_url.'">'.$parent['title'].'</a>';
						}
						else if($post != null) { // question
							if(!isset($params['title'])) {
								$params['title'] = $posts[$params['postid']]['title'];
							}
							if($params['title'] !== null) {
								$qTitle = qa_db_read_one_value( qa_db_query_sub("SELECT title FROM `^posts` WHERE `postid` = ".$params['postid']." LIMIT 1"), true );
								if(!isset($qTitle)) $qTitle = ''; // (isset($getQtitle[0])) ? $getQtitle[0] : "";
								$activity_url = qa_path_html(qa_q_request($params['postid'], $qTitle), null, qa_opt('site_url'), null, null);
								$linkTitle = $qTitle;
								$link = '<a target="_blank" href="'.$activity_url.'">'.$qTitle.'</a>';
							}
						}
						
						$eventtime = $event['datetime'];
						
						$whenhtml = qa_html(qa_time_to_string(qa_opt('db_time')-$eventtime));
						$when = qa_lang_html_sub('main/x_ago', $whenhtml);

						// event name
						$eventName = '';
						$itemIcon = '';
						if($type=='in_c_question' || $type=='in_c_answer' || $type=='in_c_comment') { // added in_c_comment
							$eventName = qa_lang('kingm_onsitenotifications_lang/in_comment');
							$itemIcon = '<img src="'.qa_opt('site_url').'king-plugin/king-notifications/img/reply.png" />';
						}
						else if($type=='in_q_vote_up' || $type=='in_a_vote_up') {
							$eventName = qa_lang('kingm_onsitenotifications_lang/in_upvote');
							$itemIcon = '<img src="'.qa_opt('site_url').'king-plugin/king-notifications/img/upvote.png" />';
						}
						else if($type=='in_q_vote_down' || $type=='in_a_vote_down') {
							$eventName = qa_lang('kingm_onsitenotifications_lang/in_downvote');
							$itemIcon = '<img src="'.qa_opt('site_url').'king-plugin/king-notifications/img/votedown.png" />';
						}
						else if($type=='in_a_question') {
							$eventName = qa_lang('kingm_onsitenotifications_lang/in_answer');
							$itemIcon = '<img src="'.qa_opt('site_url').'king-plugin/king-notifications/img/comment.png" />';
						}
						else if($type=='in_a_select') {
							$eventName = qa_lang('kingm_onsitenotifications_lang/in_bestanswer');
							$itemIcon = '<img src="'.qa_opt('site_url').$this->urltoroot.'best_answer_mini.png" />';
						}
						else {
							// ignore other events such as in_c_flag
							continue;
						}
						
						// if post has been deleted there is no link, dont output
						if($activity_url=='') {
							continue;
						}
						
						// extra CSS for highlighting new events
						$cssNewEv = '';
						if($eventtime > $last_visit) {
							$cssNewEv = '-new';
						}
						
						$notifyBoxEvents .= '<div class="itemBox'.$cssNewEv.'">
							<p class="nfyIcon">'.$itemIcon.'</p>
							<div class="nfyItemLine">
								<p>'.$eventName.' <a href="'.$activity_url.'" target="_blank">'.$linkTitle.'</a></p>
								<p class="nfyTime">'.$when.'</p>
							</div>
						</div>';

					} // END FOREACH

					$notifyBoxEvents .= '</div>
						</div>
						<div class="nfyFooter">
						<a id="nfyReadClose" >'.qa_lang('kingm_onsitenotifications_lang/close').'</a>	
						</div>
					</div>
					';
					
					header('Access-Control-Allow-Origin: '.qa_opt('site_url'));
					echo $notifyBoxEvents;
					
					// update database entry so that all user notifications are seen as read
					qa_db_query_sub(
						'INSERT INTO ^usermeta (user_id,meta_key,meta_value) VALUES(#,$,NOW()) ON DUPLICATE KEY UPDATE meta_value=NOW()',
						$userid, 'visited_profile'
					);

					exit(); 
				} // END AJAX RETURN
				else {
					echo 'Unexpected problem detected! No userid, no transfer string.';
					return;
				}
			}
			
			
			/* start */
			$qa_content = qa_content_prepare();

			$qa_content['title'] = ''; // page title

			// return if not admin!
			if(qa_get_logged_in_level() < QA_USER_LEVEL_ADMIN) {
				$qa_content['custom0'] = '<div>Access denied</div>';
				return $qa_content;
			}

			return $qa_content;
		}
		
	};
	

/*
	Omit PHP closing tag to help avoid accidental output
*/