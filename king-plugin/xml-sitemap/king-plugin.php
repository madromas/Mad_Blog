<?php

/*
	Plugin Name: XML Sitemap
	Plugin URI: 
	Plugin Description: Generates sitemap.xml file for submission to search engines
	Plugin Version: 1.1.1
	Plugin Date: 2011-12-06
	Plugin Author: KingMedia
	Plugin Author URI: 
	Plugin License: GPLv2
	Plugin Minimum KingMedia Version:  1.5
	Plugin Update Check URI: 
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_module('page', 'king-xml-sitemap.php', 'qa_xml_sitemap', 'XML Sitemap');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/