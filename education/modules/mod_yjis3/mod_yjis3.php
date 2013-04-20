<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla LLC - YJ- Licence Number 3850LE193
|| # Licensed to - Tanya Eldert
|| # ---------------------------------------------------------------- # ||
|| # Copyright (c) 2006-2009 Youjoomla LLC. All Rights Reserved.        ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- THIS IS NOT FREE SOFTWARE ---------------- #      ||
|| # http://www.youjoomla.com | http://www.youjoomla.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
	defined('_JEXEC') or die('Direct Access to this location is not allowed.');
	$who = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser they use.
	/**
	 * Parameters
	 */
	$class_fx = $params->get('moduleclass_sfx');
	$swidth = $params->get('swidth');
	$sheight = $params->get('sheight');
	$sorient = $params->get('sorient');
	$start_slide = $params->get('start_slide');
	$type_slider = $params->get('type_slider');
	$stime = $params->get('stime');
	$sduration = $params->get('sduration');
	$smenu = $params->get('smenu');
	$pagination = $params->get('pagination');
	$balons = $params->get('balons');
	$is_copy = $params->get('is_copy');
	$order = $params->get('order');
	$yjspacer = $params->get('yjspacer');
  
  	echo "<!-- http://www.Youjoomla.com  Image Slider V3 for Joomla 1.5 starts here -->	";
	JHTML::_('behavior.mootools');
		if (JPluginHelper::getPlugin('system', 'mtupgrade')) :
			require_once (dirname(__FILE__).DS.'libs'.DS.'yjis12.php');
		else:
			require_once (dirname(__FILE__).DS.'libs'.DS.'yjis.php');
		endif;
?>