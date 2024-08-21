<?php
/*

	File: king-include/king-page-admin-categories.php
	Description: Controller for admin page for editing categories


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
require_once QA_INCLUDE_DIR.'king-db/selects.php';
require_once QA_INCLUDE_DIR.'king-db/admin.php';

ini_set('user_agent', 'Mozilla/5.0');

//	Check admin privileges (do late to allow one DB query)

if (!qa_admin_check_privileges2($qa_content))
	return $qa_content;

//	Process saving options

$savedoptions=false;
$securityexpired=false;
$bundle_id = '19106207';

$king_key = qa_opt('king_key');

$enavato_itemid =  '7877877';
$label = '';
	$code = qa_post_text('king_key');
	$personalToken = "R5QWDaq9cwBv5BtwYFDzVLzaCZEeQzUS";
	$userAgent = "Purchase code verification on https://localhost/env/";
		$label='DONE !';
		qa_set_option('king_key', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');

if (qa_clicked('dosaveoptions')) {
	if (!qa_check_form_security_code('admin/categories', qa_post_text('code')))
		$securityexpired=true;

	else {
		$savedoptions=false;
	}
}



//	Prepare content for theme

$qa_content=qa_content_prepare();

$qa_content['title']=qa_lang_html('admin/admin_title').' - '.qa_lang_html('admin/categories_title');
$qa_content['error']=$securityexpired ? qa_lang_html('admin/form_security_expired') : qa_admin_page_error();

$qa_content['form']=array(
	'tags' => 'method="post" action="'.qa_path_html(qa_request()).'"',

	'ok' => $savedoptions ? qa_lang_html('admin/options_saved') : null,

	'style' => 'tall',

	'fields' => array(
		'intro' => array(
			'label' => $label,
			'type' => 'static',
		),
		'name' => array(
			'id' => 'king_key',
			'tags' => 'name="king_key" id="king_key" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"',
			'label' => 'King Media Purchase Code',
			'value' => qa_html(isset($code) ? $code : @$king_key),
			'error' => qa_html(@$errors['king_key']),
		),				
	),

	'buttons' => array(
		'save' => array(
			'tags' => 'name="dosaveoptions" id="dosaveoptions"',
			'label' => qa_lang_html('main/save_button'),
		),

	),

	'hidden' => array(
		'code' => qa_get_form_security_code('admin/categories'),
	),
);

$qa_content['navigation']['sub']=qa_admin_sub_navigation();


return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/