<?php
/*
	Plugin Name: KingMEDIA Extra Field
	Plugin URI: 
	Plugin Description: Add extra field
	Plugin Version: 1
	Plugin Date: 2014-03-13
	Plugin Author: KingMEDIA
	Plugin Author URI: 
	Plugin License: 
	Plugin Minimum KingMEDIA Version: 1
	Plugin Update Check URI: 
*/
if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}
qa_register_plugin_phrases('king-eqf-lang-*.php', 'extra_field');
qa_register_plugin_module('module', 'king-eqf.php', 'qa_eqf', 'Extra MEDIA Field');
qa_register_plugin_module('event', 'king-eqf-event.php', 'qa_eqf_event', 'Extra MEDIA Field');
qa_register_plugin_layer('king-eqf-layer.php', 'Extra MEDIA Field');
qa_register_plugin_module('filter', 'king-eqf-filter.php', 'qa_eqf_filter', 'Extra MEDIA Field');
/*
	Omit PHP closing tag to help avoid accidental output
*/