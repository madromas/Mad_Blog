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
	

//	Check that we're logged in
	
	$userid=qa_get_logged_in_userid();

	if (!isset($userid))
		qa_redirect('login');
		

//	Get lists of favorites for this user

	list($questions, $users, $tags, $categories)=qa_db_select_with_pending(
		qa_db_user_favorite_qs_selectspec($userid),
		QA_FINAL_EXTERNAL_USERS ? null : qa_db_user_favorite_users_selectspec($userid),
		qa_db_user_favorite_tags_selectspec($userid),
		qa_db_user_favorite_categories_selectspec($userid)
	);
	
	$usershtml=qa_userids_handles_html(QA_FINAL_EXTERNAL_USERS ? $questions : array_merge($questions, $users));

	
//	Prepare and return content for theme

	$qa_content=qa_content_prepare(true);

	$qa_content['title']=qa_lang_html('misc/my_favorites_title');
	

//	Favorite questions

	$qa_content['q_list']=array(		
		'qs' => array(),
	);
	
	if (count($questions)) {
		$qa_content['q_list']['form']=array(
			'tags' => 'method="post" action="'.qa_self_html().'"',

			'hidden' => array(
				'code' => qa_get_form_security_code('vote'),
			),
		);
		
		$defaults=qa_post_html_defaults('Q');
			
		foreach ($questions as $question)
			$qa_content['q_list']['qs'][]=qa_post_html_fields($question, $userid, qa_cookie_get(),
				$usershtml, null, qa_post_html_options($question, $defaults));
	}
	
	


//	Sub navigation for account pages and suggestion
	
	$qa_content['suggest_next']=qa_lang_html_sub('misc/suggest_favorites_add', '<span class="king-favorite-image"><i class="fa-solid fa-heart"></i></span>');
	
	$qa_content['navigation']['sub']=qa_user_sub_navigation(qa_get_logged_in_handle(), 'favorites', true);
	
	
	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/