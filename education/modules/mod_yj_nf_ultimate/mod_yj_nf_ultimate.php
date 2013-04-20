<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla LLC - YJ- Licence Number 3850LE193
|| # Licensed to - Tanya Eldert
|| # ---------------------------------------------------------------- # ||
|| # Copyright (C) 2006-2009 Youjoomla LLC. All Rights Reserved.        ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- THIS IS NOT FREE SOFTWARE ---------------- #      ||
|| # http://www.youjoomla.com | http://www.youjoomla.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).DS.'helper.php');
$module_template 		=  		$params->get('module_template','Default');
$showtitle 				= 		$params->get('showtitle',1);
$showimage 				= 		$params->get('showimage',1);
$imgwidth				= 		$params->get('imgwidth',"90px");
$imgheight				= 		$params->get('imgheight',"50px");
$imgalign 				= 		$params->get('imgalign',1);
$showintro				= 		$params->get('showintro',1);
$showrm 				= 		$params->get('showrm',1);
$show_cat_title			= 		$params->get('show_cat_title',1);
$showdate				= 		$params->get('showdate',1);
$showcomments 	= $params->get('showcomments');	
/* image align */
$alig = array(
	1=>'left',
	2=>'right',
	3=>'none'
	);
$align = $alig[$imgalign];




$yjnf_items = modYJNewsFlashHelper::getItems($params);
require(JModuleHelper::getLayoutPath('mod_yj_nf_ultimate',''.$module_template.'/default'));
?>