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
	define( '_JEXEC', 1 );
	define( 'DS', DIRECTORY_SEPARATOR );
	
	$parts = explode( DS, dirname(__FILE__) );
	$template = $parts[ count($parts)-2 ];
	
	
	$t_path = DS.'templates'.DS.$template.DS.'yjsgcore';	
	define('JPATH_BASE', str_replace($t_path ,'',dirname(__FILE__)) );	
	
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	
	$mainframe =& JFactory::getApplication('site');
	$mainframe->initialise();
	$session =& JFactory::getSession();

	unset( $_SESSION['frontend_changed_css'] );
	unset( $_SESSION['frontend_changed_font'] );
	unset( $_SESSION['frontend_changed_menu'] );
	unset( $_SESSION['frontend_changed_layout'] );
	unset( $_SESSION['frontend_changed_mobile'] );
	unset( $_SESSION['frontend_changed_direction'] );	
	$_SESSION['admin_change'] = true;
?>