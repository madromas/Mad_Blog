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

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	// language file
	qa_register_plugin_phrases('king-onsitenotifications-lang-*.php', 'kingm_onsitenotifications_lang');

	// page for ajax
	qa_register_plugin_module('page', 'king-onsitenotifications-page.php', 'kingm_onsitenotifications_page', 'On-Site-Notifications Page');

	// layer
	qa_register_plugin_layer('king-onsitenotifications-layer.php', 'kingm On-Site-Notifications Layer');

	// admin
	qa_register_plugin_module('module', 'king-onsitenotifications-admin.php', 'kingm_onsitenotifications_admin', 'kingm On-Site-Notifications Admin');
   
	// track events
	qa_register_plugin_module('event', 'king-history-check.php','kingm_history_check','kingm History Check Mod');


/*
	Omit PHP closing tag to help avoid accidental output
*/