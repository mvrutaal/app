<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla YouVersity News Slider 
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

$module_template 			=  		$params->get('module_template','Default');

$slider_width           	= $params->get   ('slider_width');
$slider_height          	= $params->get   ('slider_height');
$show_img    			 	= $params->get   ('show_img');
$show_read    			 	= $params->get   ('show_read');
$show_title   			 	= $params->get   ('show_title');
$slider_image_width    	 	= $params->get   ('slider_image_width');
$slider_image_height     	= $params->get   ('slider_image_height');


$visible_items       	 	= $params->get   ('visible_items','2');

$yv_slideitems_height 		= $slider_height -40;
$items_width 				=  number_format(($slider_width/$visible_items),0, '.', '');


$youversity_slides 			= modYouversidtySLiderhHelper::getYouversitySliderItems($params);
require(JModuleHelper::getLayoutPath('mod_youversity_slider',''.$module_template.'/default'));
?>