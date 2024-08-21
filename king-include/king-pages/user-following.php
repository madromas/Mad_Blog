<?php
/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: LICENCE.html
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

	require_once QA_INCLUDE_DIR.'king-db/selects.php';
	require_once QA_INCLUDE_DIR.'king-app/format.php';
	require_once QA_INCLUDE_DIR.'king-app/favorites.php';
	
$start = qa_get_start();

//	Check that we're logged in
		$handle = qa_request_part(1);
		if (!strlen($handle)) {
			$handle = qa_get_logged_in_handle();
		}
			
        $user=qa_db_select_with_pending(
        qa_db_user_account_selectspec($handle, false)
        );

	$followingid = $user['userid'];


//	Get lists of favorites for this user

	$pagesize_qs = qa_opt('page_size_qs');
	$pagesize_users = qa_opt('page_size_users');
	$pagesize_tags = qa_opt('page_size_tags');
	$loginuserid = qa_get_logged_in_userid();
	
	list($useraccount, $questions, $numUsers, $users, $numTags, $tags, $categories) = qa_db_select_with_pending(
		qa_db_selectspec_count( qa_db_user_favorite_qs_selectspec($followingid) ),
		qa_db_user_favorite_qs_selectspec($followingid, $pagesize_qs),

		QA_FINAL_EXTERNAL_USERS ? null : qa_db_selectspec_count( qa_db_user_favorite_users_selectspec($followingid) ),
		QA_FINAL_EXTERNAL_USERS ? null : qa_db_user_favorite_users_selectspec($followingid, $pagesize_users, $start),

		qa_db_selectspec_count( qa_db_user_favorite_tags_selectspec($followingid) ),
		qa_db_user_favorite_tags_selectspec($followingid, $pagesize_tags),

		qa_db_user_favorite_categories_selectspec($followingid)
	);

	$usershtml = qa_userids_handles_html(QA_FINAL_EXTERNAL_USERS ? $questions : array_merge($questions, $users));


//	Prepare and return content for theme

	$qa_content = qa_content_prepare(true);

	$qa_content['title'] = qa_lang_html('main/nav_following');




//	Favorite users

	if (!QA_FINAL_EXTERNAL_USERS) {
		$qa_content['ranking_users'] = qa_favorite_users_view($users, $usershtml);
		$qa_content['ranking_users']['title'] = count($users) ? qa_lang_html('main/nav_following') : qa_lang_html('main/no_following');
		if ($numUsers['count'] > count($users)) {
			$qa_content['page_links'] = qa_html_page_links(qa_request(), $start, $pagesize_users, $numUsers['count'], qa_opt('pages_prev_next'));
		}
	}


//	Sub navigation for account pages and suggestion

	

	$ismyuser = isset($loginuserid) && $loginuserid == (QA_FINAL_EXTERNAL_USERS ? $userid : $followingid);
	$qa_content['navigation']['sub'] = qa_user_sub_navigation($handle, 'following', $ismyuser);

	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/