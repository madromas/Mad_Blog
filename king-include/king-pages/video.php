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


	require_once QA_INCLUDE_DIR.'king-app/format.php';
	require_once QA_INCLUDE_DIR.'king-app/limits.php';
	require_once QA_INCLUDE_DIR.'king-db/selects.php';
	require_once QA_INCLUDE_DIR.'king-util/sort.php';


//	Check whether this is a follow-on question and get some info we need from the database

	$in=array();

	$followpostid=qa_get('follow');
	$in['categoryid']=qa_clicked('doask') ? qa_get_category_field_value('category') : qa_get('cat');
	$userid=qa_get_logged_in_userid();

	list($categories, $followanswer, $completetags)=qa_db_select_with_pending(
		qa_db_category_nav_selectspec($in['categoryid'], true),
		isset($followpostid) ? qa_db_full_post_selectspec($userid, $followpostid) : null,
		qa_db_popular_tags_selectspec(0, QA_DB_RETRIEVE_COMPLETE_TAGS)
	);

	if (!isset($categories[$in['categoryid']]))
		$in['categoryid']=null;

	if (@$followanswer['basetype']!='A')
		$followanswer=null;


//	Check for permission error

	$permiterror=qa_user_maximum_permit_error('permit_post_q', QA_LIMIT_QUESTIONS);

	if ($permiterror) {
		$qa_content=qa_content_prepare();

		// The 'approve', 'login', 'confirm', 'limit', 'userblock', 'ipblock' permission errors are reported to the user here
		// The other option ('level') prevents the menu option being shown, in qa_content_prepare(...)

		switch ($permiterror) {
			case 'login':
				$qa_content['error']=qa_insert_login_links(qa_lang_html('question/ask_must_login'), qa_request(), isset($followpostid) ? array('follow' => $followpostid) : null);
				break;

			case 'confirm':
				$qa_content['error']=qa_insert_login_links(qa_lang_html('question/ask_must_confirm'), qa_request(), isset($followpostid) ? array('follow' => $followpostid) : null);
				break;

			case 'limit':
				$qa_content['error']=qa_lang_html('question/ask_limit');
				break;

			case 'approve':
				$qa_content['error']=qa_lang_html('question/ask_must_be_approved');
				break;

			default:
				$qa_content['error']=qa_lang_html('users/no_permission');
				break;
		}

		return $qa_content;
	}


//	Process input

	$captchareason=qa_user_captcha_reason();

	$in['title']=qa_get_post_title('title'); // allow title and tags to be posted by an external form
	$in['extra']=qa_opt('extra_field_active') ? qa_post_text('extra') : null;
	if (qa_using_tags())
		$in['tags']=qa_get_tags_field_value('tags');

	if (qa_clicked('doask')) {
		require_once QA_INCLUDE_DIR.'king-app/post-create.php';
		require_once QA_INCLUDE_DIR.'king-util/string.php';

		$categoryids=array_keys(qa_category_path($categories, @$in['categoryid']));
		$userlevel=qa_user_level_for_categories($categoryids);

		$in['name']=qa_post_text('name');
		$in['notify'] = strlen(qa_post_text('notify')) > 0;
		$in['email']=qa_post_text('email');
		$in['queued'] = qa_user_moderation_reason($userlevel) !== false;

		qa_get_post_content('editor', 'content', $in['editor'], $in['content'], $in['format'], $in['text']);

		$errors=array();

		if (!qa_check_form_security_code('ask', qa_post_text('code')))
			$errors['page']=qa_lang_html('misc/form_security_again');

		else {
			$filtermodules=qa_load_modules_with('filter', 'filter_question');
			foreach ($filtermodules as $filtermodule) {
				$oldin=$in;
				$filtermodule->filter_question($in, $errors, null);
				qa_update_post_text($in, $oldin);
			}

			if (qa_using_categories() && count($categories) && (!qa_opt('allow_no_category')) && !isset($in['categoryid']))
				$errors['categoryid']=qa_lang_html('question/category_required'); // check this here because we need to know count($categories)
			elseif (qa_user_permit_error('permit_post_q', null, $userlevel))
				$errors['categoryid']=qa_lang_html('question/category_ask_not_allowed');

			if ($captchareason) {
				require_once QA_INCLUDE_DIR.'king-app/captcha.php';
				qa_captcha_validate_post($errors);
			}

			if (empty($errors)) {
				$cookieid=isset($userid) ? qa_cookie_get() : qa_cookie_get_create(); // create a new cookie if necessary
	
	$videocontent = $in['content'];


	function kingsource($videocontent) 
	{
		$parsed = parse_url($videocontent); 
		return str_replace('www.','', strtolower($parsed['host'])); 
	}
	
	function get_thumb($videocontent) {
		$res = file_get_contents("$videocontent");
	    preg_match('/property="og:image" content="(.*?)"/', $res, $output);
		return ($output[1]) ? $output[1] : false;
	}
	
	function king_twitch($videocontent) {
	$res = file_get_contents("$videocontent");
	preg_match('/content=\'(.*?)\' property=\'og:image\'/', $res, $matches);

	return ($matches[1]) ? $matches[1] : false;
	}	

	function king_vk($videocontent) {
	    $page = file_get_contents("$videocontent");
		$page_for_hash = preg_replace("/\\\/","",$page);
		if (preg_match("@,\"jpg\":\"(.*?)\",@",$page_for_hash,$matches)) {
		$result = $matches[1];
		return $result;
		}
	}	

     function king_mailru($videocontent) {
	 $page = file_get_contents("$videocontent");
	 if (preg_match('/content="(.*?)" name="og:image"/',$page,$mailru)) {
	 $king = $mailru[1];
	 return $king;
	 }
	 }
	
	function king_facebook($content) { 
	   $facebook_access_token = qa_opt('fb_user_token');
			$paths = explode("/",$content);
			$num = count($paths);
			for($i=$num-1; $i > 0; $i--){
				if($paths[$i] != ""){
					$video_id = $paths[$i];
					break;
				}  
			}		
			
			
	$data = file_get_contents('https://graph.facebook.com/'.$video_id.'/thumbnails?access_token='.$facebook_access_token.'');
	if ($data !== FALSE)
	{
	 $result=json_decode($data);
	 return $thumbnail=$result->data[0]->uri;
	}
	}

	function king_youtube($url) {
		$queryString = parse_url($url, PHP_URL_QUERY);
		parse_str($queryString, $params);
		if (isset($params['v'])) 
		{
			return "https://i3.ytimg.com/vi/" . trim($params['v']) . "/hqdefault.jpg";
		}
		return true;
	}	
	
	function king_soundcloud($videocontent) {
	    ini_set("user_agent", "SCPHP");
		
		function resolve_sc_track($url = ''){
		return json_decode(file_get_contents("https://api.soundcloud.com/resolve?client_id=KqmJoxaVYyE4XT0XQqFUUQ&format=json&url="
		. $url), true);
		}
		
		function get_artwork_url($track, $format="t500x500"){
		return str_replace("large", $format, $track["artwork_url"]);
		}
		
		// get track data from track url
		$track = resolve_sc_track("$videocontent");
		return get_artwork_url($track);
   }
   
	function king_xhamster($videocontent) {
		$res = file_get_contents("$videocontent");
	    preg_match('/name="twitter:image" property="og:image" content="(.*?)"/', $res, $output);
		return ($output[1]) ? $output[1] : false;
   }     

	function king_okru($videocontent) {
		$res = file_get_contents("$videocontent");
	    preg_match('/rel="image_src" href="(.*?)"/', $res, $output);
		return ($output[1]) ? $output[1] : false;
   }   

     function coub_thumb($videocontent) {
	 $page2 = file_get_contents("$videocontent");
	 if (preg_match('/content="(.*?)" property="og:image"/', $page2, $coub)) {
	 $cou = $coub[1];
	 return $cou;
	 }
	 }	

	function king_gfycat($videocontent) {
		$res = file_get_contents("$videocontent");
	    preg_match('/name="twitter:image" content="(.*?)"/', $res, $output);
		return ($output[1]) ? $output[1] : false;
   }
	 
	$type = kingsource($videocontent);
	
	    if($type=="vimeo.com" || $type=="dailymotion.com" || $type=="metacafe.com" || $type=="vine.co" || $type=="instagram.com" || $type=="vid.me")
		{
			$thumb=get_thumb($videocontent);
		}
		else if($type=="xhamster.com")
		{
			$thumb=king_xhamster($videocontent);
		}
		else if($type=="ok.ru")
		{
			$thumb=king_okru($videocontent);
		}	
	    else if($type=="coub.com")
		{
			$thumb=coub_thumb($videocontent);
		}		
		else if($type=="gfycat.com")
		{
			$thumb=king_gfycat($videocontent);
		}		
	    else if($type=="youtube.com")
		{
			$thumb=king_youtube($videocontent);
		}		
		else if($type=="facebook.com")
		{
			$thumb=king_facebook($videocontent);
		}		
		else if($type=="soundcloud.com")
		{
			$thumb=king_soundcloud($videocontent);
		}	
		else if($type=="vk.com")
		{
			$thumb=king_vk($videocontent);
		}	
		else if($type=="my.mail.ru")
		{
			$thumb=king_mailru($videocontent);
		}
		else if($type=="twitch.tv")
		{
			$thumb=king_twitch($videocontent);
		}		
		else
		{
			if ( !empty($_FILES['king_post_files']['tmp_name']) || is_uploaded_file($_FILES['king_post_files']['tmp_name'])) {
				$ImageName = $_FILES['king_post_files']['name'];
				$TempSrc = $_FILES['king_post_files']['tmp_name'];
				$ImageType = $_FILES['king_post_files']['type'];

				$thumb = king_uploadthumb($ImageName,$TempSrc,$ImageType);
} else {
	$thumb = qa_post_text('submit_image');
}
			
		}				

				$questionid=qa_question_create($followanswer, $userid, qa_get_logged_in_handle(), $cookieid,
					$in['title'], $thumb, $in['format'], $in['text'], isset($in['tags']) ? qa_tags_to_tagstring($in['tags']) : '',
					$in['notify'], $in['email'], $in['categoryid'], $videocontent, $in['queued'], $in['name'], 'V');
				qa_redirect(qa_q_request($questionid, $in['title'])); // our work is done here
			}
		}
	}

function king_uploadthumb($ImageName,$TempSrc,$ImageType) {
	$DestinationDirectory = QA_INCLUDE_DIR.'uploads/';
	$MaxSize = 800;
	$Quality = 90;
	$watermark_png_file = QA_INCLUDE_DIR.'watermark/watermark.png';
	$RandomNumber 	= rand(0, 999999);
	$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
	$ImageExt = str_replace('.','',$ImageExt);		  
	$ImageName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName);

	$NewImageName = $ImageName.'-'.$RandomNumber.'.'.$ImageExt;

	$year_folder = $DestinationDirectory . date("Y");
	$month_folder = $year_folder . '/' . date("m");

	!file_exists($year_folder) && mkdir($year_folder , 0777);
	!file_exists($month_folder) && mkdir($month_folder, 0777);
	
	$DestFolder = $month_folder . '/' . $NewImageName;	

	switch(strtolower($ImageType))
	{
		case 'image/png':
		$CreatedImage =  imagecreatefrompng($TempSrc);
		break;
		case 'image/gif':
		$CreatedImage =  imagecreatefromgif($TempSrc);
		break;
		case 'image/webp':
		$CreatedImage =  imagecreatefromwebp($TempSrc);
		break;				
		case 'image/jpeg':
		case 'image/pjpeg':
		$CreatedImage = imagecreatefromjpeg($TempSrc);
		break;
		default:
		die('Unsupported File!');
	}
	list($CurWidth,$CurHeight)=getimagesize($TempSrc);

	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	$ImageScale      	= min($MaxSize/$CurWidth, $MaxSize/$CurWidth); 
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	// Resize Image
	if(imagecopyresampled($NewCanves, $CreatedImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
	{

	if (!qa_opt('disable_watermark')) {	
		$watermark = imagecreatefrompng($watermark_png_file); //watermark image
		$watermark_width  = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$watermark_left = ($NewWidth)-($watermark_width); //watermark left
		$watermark_bottom = ($NewHeight)-($watermark_height); //watermark bottom	
		imagecopy($NewCanves, $watermark, $watermark_left, $watermark_bottom, 0, 0, $watermark_width, $watermark_height); //merge image
	}
		//Or Save image to the folder
		imagejpeg($NewCanves,$DestFolder,$Quality);

	//Destroy image, frees memory	
		if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
		$pageurl = (isset($_SERVER['HTTPS']) ? "https://" : "https://") . ''.@$_SERVER['HTTP_HOST'].strtr(dirname($_SERVER['SCRIPT_NAME']), '\\', '/').'';
		return $pageurl.'/king-include/uploads/'.date("Y").'/'.date("m").'/'.$NewImageName;
	}
}
//	Prepare content for theme

	$qa_content=qa_content_prepare(false, array_keys(qa_category_path($categories, @$in['categoryid'])));

	$qa_content['title']=qa_lang_html(isset($followanswer) ? 'question/ask_follow_title' : 'question/ask_title');
	$qa_content['error']=@$errors['page'];

	$editorname=isset($in['editor']) ? $in['editor'] : qa_opt('editor_for_qs');
	$editor=qa_load_editor(@$in['content'], @$in['format'], $editorname);

	$field=qa_editor_load_field($editor, $qa_content, @$in['content'], @$in['format'], 'content', 12, false);
	$field['label']=qa_lang_html('question/q_content_label');
	$field['error']=qa_html(@$errors['content']);

	$custom=qa_opt('show_custom_ask') ? trim(qa_opt('custom_ask')) : '';

	if (qa_opt('links_in_new_window')) {
		$vidupload = '<ul class="vidtab" role="tablist">
				<li class="active"><a href="#vidurl" aria-controls="vidurl" class="king-vidurl" role="tab" data-toggle="tab">url</a></li>
				<li ><a href="#vidup" aria-controls="vidup" class="king-vidup" role="tab" data-toggle="tab">upload</a></li>
				</ul>
				<div id="vidurl" role="tabpanel" class="active">
				<span class="king-form-tall-label">
				'.qa_lang_html('question/q_content_label').' <i>Youtube Vimeo Vine Facebook Vk Dailymotion Metacafe Instagram Soundcloud</i> 
				</span>
				<input name="content" value="'.qa_html(@$in['content']).'"  id="content2" autocomplete="off" type="text" class="king-form-tall-text">
				</div>
				<div class="imgprev" role="tabpanel" id="vidup">
				<div id="multipleupload">Upload</div>
				<div id="king-vidthumb2">
									<label id="king-vidthumb" class="king-vidthumb" for="featured-image2" class="king-vidthumb-label">
										<i class="fas fa-cloud-upload-alt"></i> '.qa_lang_html('misc/select_thumb').'
									</label>
									<input name="king_post_files" type="file" id="featured-image2">
								</div>			
				</div>';
	} else {
		
		$vidupload = '<div id="vidurl2" >
				<span class="king-form-tall-label">
				'.qa_lang_html('question/q_content_label').' <i>Youtube Vimeo Vine Facebook Vk Dailymotion Metacafe Instagram Soundcloud</i> 
				</span>
				<input name="content" value="'.qa_html(@$in['content']).'"  id="content2" autocomplete="off" type="text" class="king-form-tall-text">
				</div>';
	}	

	$qa_content['form']=array(
		'tags' => 'name="ask" method="post" ENCTYPE="multipart/form-data" action="'.qa_self_html().'"',
		
		'style' => 'tall',
		
		'fields' => array(
			'custom' => array(
				'type' => 'custom',
				'html' => '<div class="snote">'.$custom.'</div>',
			),
			
'imgprev' => array(
				'type' => 'custom',
				'html' => '<div class="king-form-group"><p style="color:red;">* Only upload preview image, if the link to video  is .mp4 or .webm!</p>
								<span class="inputprev-span">
									<img class="inputprev" /><i class="fas fa-cloud-upload-alt fa-2x"></i>
									<label for="featured-image" class="featured-image-upload">
										'.qa_lang_html('misc/select_thumb').'
									</label>
									<input name="king_post_files" type="file" id="featured-image">
								</span>
							</div>',
				'error' => qa_html(@$errors['file']),
			),
			
			'title' => array(
				'label' => qa_lang_html('question/q_title_label'),
				'tags' => 'name="title" id="title" autocomplete="off"',
				'value' => qa_html(@$in['title']),
				'error' => qa_html(@$errors['title']),
			),
			
			'similar' => array(
				'type' => 'custom',
				'html' => '<span id="similar"></span>',
			),	
			
			'content' => array(
				'type' => 'custom',
				'html' => $vidupload,
				'error' => qa_html(@$errors['content']),			
			),
						
		),
		
		'buttons' => array(
			'ask' => array(
				'tags' => 'onclick="qa_show_waiting_after(this, false); '.
					(method_exists($editor, 'update_script') ? $editor->update_script('content') : '').'"',
				'label' => qa_lang_html('question/ask_button'),
			),
		),
		
		'hidden' => array(
			'editor' => qa_html($editorname),
			'code' => qa_get_form_security_code('ask'),
			'doask' => '1',
		),
	);

	if (!strlen($custom))
		unset($qa_content['form']['fields']['custom']);

	if (qa_opt('do_ask_check_qs') || qa_opt('do_example_tags')) {
		$qa_content['script_rel'][]='king-content/king-ask.js?'.QA_VERSION;
		$qa_content['form']['fields']['title']['tags'].=' onchange="qa_title_change(this.value);"';

		if (strlen(@$in['title']))
			$qa_content['script_onloads'][]='qa_title_change('.qa_js($in['title']).');';
	}

	if (isset($followanswer)) {
		$viewer=qa_load_viewer($followanswer['content'], $followanswer['format']);

		$field=array(
			'type' => 'static',
			'label' => qa_lang_html('question/ask_follow_from_a'),
			'value' => $viewer->get_html($followanswer['content'], $followanswer['format'], array('blockwordspreg' => qa_get_block_words_preg())),
		);

		qa_array_insert($qa_content['form']['fields'], 'title', array('follows' => $field));
	}

	if (qa_using_categories() && count($categories)) {
		$field=array(
			'label' => qa_lang_html('question/q_category_label'),
			'error' => qa_html(@$errors['categoryid']),
		);

		qa_set_up_category_field($qa_content, $field, 'category', $categories, $in['categoryid'], true, qa_opt('allow_no_sub_category'));

		if (!qa_opt('allow_no_category')) // don't auto-select a category even though one is required
			$field['options']['']='';

		qa_array_insert($qa_content['form']['fields'], 'content', array('category' => $field));
	}

	
		$field=array(
			'label' => qa_html(qa_opt('extra_field_prompt')),
			'tags' => 'name="extra" id="extra" class="hide"',
			'value' => qa_html(@$in['extra']),
			'error' => qa_html(@$errors['extra']),
		);
		
		qa_array_insert($qa_content['form']['fields'], null, array('extra' => $field));

	if (qa_using_tags()) {
		$field=array(
			'error' => qa_html(@$errors['tags']),
		);

		qa_set_up_tag_field($qa_content, $field, 'tags', isset($in['tags']) ? $in['tags'] : array(), array(),
			qa_opt('do_complete_tags') ? array_keys($completetags) : array(), qa_opt('page_size_ask_tags'));

		qa_array_insert($qa_content['form']['fields'], null, array('tags' => $field));
	}

	if (!isset($userid))
		qa_set_up_name_field($qa_content, $qa_content['form']['fields'], @$in['name']);

	qa_set_up_notify_fields($qa_content, $qa_content['form']['fields'], 'Q', qa_get_logged_in_email(),
		isset($in['notify']) ? $in['notify'] : qa_opt('notify_users_default'), @$in['email'], @$errors['email']);

	if ($captchareason) {
		require_once QA_INCLUDE_DIR.'king-app/captcha.php';
		qa_set_up_captcha_field($qa_content, $qa_content['form']['fields'], @$errors, qa_captcha_reason_note($captchareason));
	}

	$qa_content['focusid']='title';


	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/