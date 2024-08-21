<?php

/*
	Plugin Name: King Captcha
	Plugin URI: 
	Plugin Description: Provides support for AntiBot Captcha
	Plugin Version: 1
	Plugin Date: 2014-01-24
	Plugin Author: King MEDIA
	Plugin Author URI: 
	Plugin License: GPLv3
	Plugin Minimum KingMEDIA Version: 1
	Plugin Update Check URI: 
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	qa_register_plugin_module('captcha', 'king-antibot-captcha.php', 'qa_antibot_captcha', 'King Captcha');

/*
	Omit PHP closing tag to help avoid accidental output
*/