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
//$cuustom1   					     = $this->params->get("cuustom1");

/* K2 CSS */
if (JRequest::getCmd( 'option' ) == 'com_k2' 
	|| JModuleHelper::getModule( 'k2_content' )
	|| JModuleHelper::getModule( 'k2_tools' )
	|| JModuleHelper::getModule( 'k2_comments' )
	){
		$document->addStyleSheet($yj_site.'/css/customk.css');
}

?>