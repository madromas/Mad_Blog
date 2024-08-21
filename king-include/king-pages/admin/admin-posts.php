<?php
/*

	File: king-include/king-page-admin-approve.php
	Description: Controller for admin page showing new users waiting for approval


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

	require_once QA_INCLUDE_DIR.'king-app/admin.php';
	require_once QA_INCLUDE_DIR.'king-db/admin.php';
	require_once QA_INCLUDE_DIR.'king-app/posts.php';


//	Check we're not using single-sign on integration

	if (QA_FINAL_EXTERNAL_USERS)
		qa_fatal_error('User accounts are handled by external code');


//	Find most flagged questions, answers, comments

	$userid=qa_get_logged_in_userid();
	$start = qa_get_start();
	if (qa_check_form_security_code('hideposts', qa_post_text('code')) && isset($_POST['hideid'])) {
		$oldpost=qa_post_get_full($_POST['hideid'], 'QAC');

		if (!$oldpost['hidden']) {
			qa_post_set_hidden($_POST['hideid'], true, null);
			$oldpost=qa_post_get_full($_POST['hideid'], 'QAC');
		}	

	}
	if (qa_check_form_security_code('reshow', qa_post_text('code')) && isset($_POST['reshowid'])) {
		$oldpost=qa_post_get_full($_POST['reshowid'], 'QAC');
		if ($oldpost['hidden']) {
			qa_post_set_hidden($_POST['reshowid'], false, $userid);
			$oldpost=qa_post_get_full($_POST['reshowid'], 'QAC');
		}

	}	
	if (qa_check_form_security_code('editposts', qa_post_text('code')) && isset($_POST['deleteid'])) {
			qa_db_query_sub('DELETE FROM ^posts WHERE parentid=$', $_POST['deleteid']);
			$closepost=qa_post_get_question_closepost($_POST['deleteid']);
			$oldpost=qa_post_get_full($_POST['deleteid'], 'QAC');
			qa_question_delete($oldpost, null, null, null, $closepost);	
	}
	
	list($qs, $qs_queued)=
		qa_db_select_with_pending(
			qa_db_qs_selectspec($userid, 'created', $start, null, null, 'Q', true),
			qa_db_qs_selectspec($userid, 'created', $start, null, null, 'Q_HIDDEN', true)
		);


	$posts=qa_any_sort_by_date(array_merge($qs, $qs_queued));	
	$pagesize = 20;
	$posts = array_slice($posts, 0, $pagesize);	
	$usercount = qa_opt('cache_qcount');


//	Check admin privileges (do late to allow one DB query)

	if (qa_get_logged_in_level()<QA_USER_LEVEL_MODERATOR) {
		$qa_content=qa_content_prepare();
		$qa_content['error']=qa_lang_html('users/no_permission');
		return $qa_content;
	}



//	Check to see if any were approved or blocked here

	$pageerror=qa_admin_check_clicks();


//	Prepare content for theme

	$qa_content=qa_content_prepare();

	$qa_content['title']=qa_lang_html('admin/approve_users_title');
	$qa_content['error']=isset($pageerror) ? $pageerror : qa_admin_page_error();


	$adimvip = '<script type="text/javascript">
$(document).on("submit", ".king-editposts-form", function(event)
{
    event.preventDefault();    
   var id = $(this).closest("form").attr("id");
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: new FormData(this),
        processData: false,
        contentType: false,
		success: function (data)
        {
			$(\'.\'+id+\'\').fadeOut();
        },
        error: function (xhr, desc, err)
        {
alert(err)

        }
    });        
});
	        </script>';

	if (count($posts)) {
	$adimvip .= '<table class="editusers-table">';
	$adimvip .= '<tr><th> ID </th><th>'.qa_lang_html('question/q_title_label').'</th><th>'.qa_lang_html('misc/postcomments').'</th><th>'.qa_lang_html('question/edit_button').'</th><th>'.qa_lang_html('question/delete_button').'</th></tr>';		
		foreach ($posts as $post) {
			$query = $post['postid'];
			$cont = qa_db_read_one_value(qa_db_query_sub("SELECT postformat FROM ^posts WHERE postid = $query "));
			if ($cont=='V') {
				$postformat = qa_lang_html('main/home_video');
			} elseif ($cont=='I') {
				$postformat = qa_lang_html('main/home_image');
			} elseif ($cont=='N') {
				$postformat = qa_lang_html('main/home_news');
			}
			$adimvip .= '<tr class="kingeditli editposts-'.$post['postid'].'">';
			$adimvip .= '<td><strong>'.$post['postid'].'</strong></td>';
			
			$adimvip .= '<td><a href="'.qa_q_path($post['postid'], $post['title'], true).'" target="_blank">'.$post['title'].'</a> - '.$postformat.'</td>';
			$adimvip .= '<td>'.$post['acount'].'</td>';
			$adimvip .= '<td><a class="king-edit-button" href="'.qa_q_path($post['postid'], $post['title'], true).'?state=edit-'.$post['postid'].'" target="_blank">'.qa_lang_html('question/edit_button').'</a>';
			$adimvip .= '<td>';
			if ($post['type'] == 'Q_HIDDEN') {
				$adimvip .= '<form method="POST" class="king-editposts-form" id="editposts-'.$post['postid'].'">';
				$adimvip .= '<input type="hidden" name="deleteid" value="'.$post['postid'].'">';
				$adimvip .= '<input type="submit" class="king-edit-button" id="button_1" name="submit" value="'.qa_lang_html('question/delete_button').'">';
				$adimvip .= '<input type="hidden" name="code" value="'.qa_get_form_security_code('editposts').'">';
				$adimvip .= '</form>';
				$adimvip .= '<form method="POST" class="king-reshow-form" id="editposts-'.$post['postid'].'">';
				$adimvip .= '<input type="hidden" name="reshowid" value="'.$post['postid'].'">';
				$adimvip .= '<input type="submit" class="king-edit-button" name="submit" value="'.qa_lang_html('question/reshow_button').'">';
				$adimvip .= '<input type="hidden" name="code" value="'.qa_get_form_security_code('reshow').'">';
				$adimvip .= '</form>';				
			}			
			if ($post['type'] == 'Q') {
				$adimvip .= '<form method="POST" class="king-hideposts-form" id="editposts-'.$post['postid'].'">';
				$adimvip .= '<input type="hidden" name="hideid" value="'.$post['postid'].'">';
				$adimvip .= '<input type="submit" class="king-edit-button" name="submit" value="'.qa_lang_html('question/hide_button').'">';
				$adimvip .= '<input type="hidden" name="code" value="'.qa_get_form_security_code('hideposts').'">';
				$adimvip .= '</form>';
			}		
			$adimvip .= '</td>';			
			$adimvip .= '</tr>';
		}
		$adimvip .= '</tr></table>';
	} else {
		$qa_content['title']=qa_lang_html('admin/no_unapproved_found');
	}
	$qa_content['custom']=$adimvip;
	$qa_content['page_links'] = qa_html_page_links(qa_request(), $start, $pagesize, $usercount, qa_opt('pages_prev_next'));
	$qa_content['navigation']['sub']=qa_admin_sub_navigation();


	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/
