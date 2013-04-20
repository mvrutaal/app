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
/**
 * YJ system plugin
 *
 * @package		YJSG Framework V 1.0.10
 * @subpackage	System 
 */ 
 
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
 
/**
 * Renders a multiple item select element 
 * using SQL result and explicitly specified params
 *
 */
 
class JElementyjmenutype extends JElement
{
        /**
        * Element name
        *
        * @access       protected
        * @var          string
        */
        var    $_name = 'yjmenutype';
 
        function fetchElement($name, $value, &$node, $control_name)
        {
			// Base name of the HTML control.
			$field_name  = $control_name .'['. $name .']';

			$uri = str_replace(DS,"/",str_replace( JPATH_SITE, JURI::base (), dirname(__FILE__) ));
			$uri = str_replace("/administrator/", "", $uri);

			$layout_js =  '<script type="text/javascript" src="'.$uri.'/yjmenutype.js"></script>';
			
			$yj_item_type = array(
				JHTML::_( 'select.option',  "0", "Normal link"),
				JHTML::_( 'select.option',  "1", "Module"),
				JHTML::_( 'select.option',  "2", "Module position")
			); 			
			$layout_js .=  JHTML::_('select.radiolist', $yj_item_type, $field_name, array('class'=>'inputbox', 'size'=>'1'),'value','text', $value);
			return $layout_js;
        }
}
?>