<?php

/*
	Plugin Name: King Ads
	Plugin URI: 
	Plugin Update Check 
	Plugin Description: Add ads in homepage
	Plugin Version: 1.2
	Plugin Date: 2014-01-10
	Plugin Author: KingMEDIA
	Plugin Author URI:
	Plugin License: 
	Plugin Minimum KingMEDIA Version: 1
*/

if (!defined('QA_VERSION')){header('Location: ../../'); exit;}

qa_register_plugin_layer('king-adverts-layer.php', 'King Ads');
qa_register_plugin_module('module', 'king-adverts-options.php', 'qa_adverts', 'King Ads');
	
?>