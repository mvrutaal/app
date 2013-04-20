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
 
class JElementYJSQLMultiListX extends JElement
{
        /**
        * Element name
        *
        * @access       protected
        * @var          string
        */
        var    $_name = 'YJSQLMultiListX';
 
        function fetchElement($name, $value, &$node, $control_name)
        {
                // Base name of the HTML control.
                $ctrl  = $control_name .'['. $name .']';
 
                // Construct the various argument calls that are supported.
                $attribs       = ' ';//id="'.$name.'" 
                if ($v = $node->attributes( 'size' )){
					$attribs	.= 'size="'.$v.'"';
                }
				
                if ($v = $node->attributes( 'class' )){
                	$attribs    .= 'class="'.$v.'"';
                } else {
                    $attribs    .= 'class="inputbox"';
                }
				
                if ($m = $node->attributes( 'multiple' )){
					$attribs    .= ' multiple="multiple"';
                    $ctrl       .= '[]';
                }
 
                // Query items for list.
				$db             = & JFactory::getDBO();
				$db->setQuery($node->attributes('sql'));
				$key = ($node->attributes('key_field') ? $node->attributes('key_field') : 'value');
				$val = ($node->attributes('value_field') ? $node->attributes('value_field') : $name);
 
                $options = array ();
                foreach ($node->children() as $option)
                {
                        $options[]= array($key=> $option->attributes('value'),$val => $option->data());
                }
 
                $rows = $db->loadAssocList();
                foreach ($rows as $row){
                        $options[]=array($key=>$row[$key],$val=>$row[$val]);
                }
                if($options){
                        return JHTML::_('select.genericlist',$options, $ctrl, $attribs, $key, $val, $value, $control_name.$name);
                }
        }
}
?>