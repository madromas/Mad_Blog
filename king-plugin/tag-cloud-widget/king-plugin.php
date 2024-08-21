<?php


/*
	Plugin Name: Tag Cloud Widget
	Plugin URI: 
	Plugin Description: Provides a list of tags with size indicating popularity
	Plugin Version: 1.0.1
	Plugin Date: 2011-12-06
	Plugin Author: KingMedia
	Plugin Author URI: 
	Plugin License: GPLv2
	Plugin Minimum KingMedia Version: 1
	Plugin Update Check URI: 
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_module('widget', 'king-tag-cloud.php', 'qa_tag_cloud', 'Tag Cloud');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/