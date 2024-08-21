<?php

/*
	Plugin Name: Event Logger
	Plugin URI: 
	Plugin Description: Stores a record of user activity in the database and/or log files
	Plugin Version: 1.0
	Plugin Date: 2014-07-03
	Plugin Author: KingMEDIA
	Plugin Author URI
	Plugin License: 
	Plugin Minimum KingMEDIA Version: 1
	Plugin Update Check URI: 
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_module('event', 'king-event-logger.php', 'qa_event_logger', 'Event Logger');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/