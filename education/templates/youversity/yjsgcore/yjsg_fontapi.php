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
if ($selectors_override_type == 1 ){ // CSS
	require( TEMPLATEPATH.DS."yjsgcore/yjsg_cssfonts.php");
	$nice_font   =  $css_font_family;
	$font_sheet ='';
}elseif ($selectors_override_type == 2){ // GOOGLE
	require( TEMPLATEPATH.DS."yjsgcore/yjsg_googlefonts.php");
	$nice_font   =  $gapi_font_family;
	$font_sheet  ='<link href="http://fonts.googleapis.com/css?family='.$gapi_font_param.'" rel="stylesheet" type="text/css" />';
}elseif ($selectors_override_type == 3){ // CUFON
	require( TEMPLATEPATH.DS."yjsgcore/yjsg_cufonfonts.php");
//	$nice_font  = $cufon_get_family;
//	$font_file  = $cufon_get_file;
}?>