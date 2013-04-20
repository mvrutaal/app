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
defined( '_JEXEC' ) or die( 'Restricted index access' );
global $mainframe;
$midblock 				= $leftcolumn + $rightcolumn + $maincolumn.$widthdefined;
$css_width 				= '100%';
$css_widthdefined       = '';
$page_title             = $mainframe->getPageTitle(); 
$document	 			= &JFactory::getDocument();
$renderer	 			= $document->loadRenderer( 'module' );
$options	 			= array( 'style' => "raw" );
$module	     			= JModuleHelper::getModule( 'mod_mainmenu' );
$topmenu     			= false; $subnav = false; $sidenav = false;
$module->params	= "menutype=$menu_name\nstartLevel=0\nendLevel=1\nclass_sfx=split";
$topmenu = $renderer->render( $module, $options );
$menuclass = 'horiznav';
$topmenuclass ='top_menu';
?>
<?php
if ($iphones && $iphone_default==1 || $android && $android_default==1 || $handhelds && $handheld_default==1 ){
	require( TEMPLATEPATH.DS."layouts/mobile/yjsg_iphone.php");
}elseif($iphones && $iphone_default==2 || $android && $android_default==2 || $handhelds && $handheld_default==2 ){
	require( TEMPLATEPATH.DS."layouts/mobile/yjsg_handheld.php");
}else{
	require( TEMPLATEPATH.DS."layouts/mobile/yjsg_iphone.php");
}
?>