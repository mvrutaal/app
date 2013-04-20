<?php 
/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
|| # Copyright (C) 2010  Youjoomla LLC. All Rights Reserved.            ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla LLC                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/
	if(!defined('_JEXEC')) define( '_JEXEC', 1 );
	define( 'DS', DIRECTORY_SEPARATOR );		

	$parts = explode( DS, dirname(__FILE__) );
	$template1 = $parts[ count($parts)-2 ];
	
	
    
	define('JPATH_BASE', str_replace("templates".DS.$template1.DS."css","",dirname(__FILE__)) );
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

	$mainframe 	=& JFactory::getApplication('site');
	$mainframe->initialise(); 	
	
	$app = & JFactory::getApplication();
    $template = $app->getTemplate();

	// Parse the template INI file if it exists for parameters and insert
	// them into the template.
	if (is_readable( JPATH_BASE.DS.'templates'.DS.$template.DS.'params.ini' ) ){
		$content = file_get_contents(JPATH_BASE.DS.'templates'.DS.$template.DS.'params.ini');
		$params = new JParameter($content);
	}else{
		$default_color = "turquoise";		
	}
	
	$default_color  = $params->get("default_color");	
	$mystyles = array(
	'turquoise', 'red', 'orange', 'blue'
	);
	$default_style = 'turquoise';

//*
if ( isset($_GET['change_css']) && !empty($_GET['change_css']) ) {
	// check if style is valid
	if( in_array( $_GET['change_css'], $mystyles ) ){
		
		$_SESSION['frontend_css'] = $_GET['change_css'];
		$_SESSION['frontend_changed_css'] = true;
		$valid_styles = $_GET['change_css'];
		
	}else {
		// else set to default style
		$valid_styles = $default_style;		
	}	
		
} else { 
	// second case, checkes for admin changes or front-end changes
	
	if ( isset($_SESSION['frontend_changed_css']) && in_array( $_SESSION['frontend_css'], $mystyles ) ){

		$valid_styles = $_SESSION['frontend_css'];
	
	}else if( isset( $_SESSION['admin_change'] ) ){
		
		$valid_styles = $params->get("default_color");	
	
	}else {
		$valid_styles = 'turquoise';
	}
}
//*/
$css_file = in_array( $valid_styles, $mystyles ) ? $valid_styles : $default_style;
	ob_start ("ob_gzhandler");
	header("Content-type: text/css; charset: UTF-8");
	header("Cache-Control: must-revalidate");
	$comp_expire ='1440'; // 1440 = minutes in 1 day |  60x24
	$offset = 60 * $comp_expire ; // 60 = seconds in 1 minute | 60 * 1 day minutes = 24H 
	$ExpStr = "Expires: " .
	gmdate("D, d M Y H:i:s",
	time() + $offset) . " GMT";
	header($ExpStr);
	ob_start("compress");
	function compress($buffer) {
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
}

/* css */
include('template.css');
include($css_file.'.css');
ob_end_flush();
?>