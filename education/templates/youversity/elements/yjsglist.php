<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
|| # Copyright (C) 2010  Youjoomla LLC. All Rights Reserved.            ||
|| # Authors - Dragan Todorovic and Constantin Boiangiu                 ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla LLC                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a text element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementYJSGList extends JElement
{
	/**
	* Element type
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'List';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		$options = array ();
		
		foreach ($node->children() as $option)
		{
			$val	= $option->attributes('value');
			$class = $option->attributes('disable') ? ' class="disable_next '.$option->attributes('disable').' ' : ' class="';
			$class .= $option->attributes('enable') ? 'enable_next '.$option->attributes('enable').'"' : '"';
			$selected = $val == $value ? ' selected="selected"':'';
			$text	= $option->data();
			//$item_help  = JText::_($node->attributes('item_help'));
			$options[] = '<option value="'.$val.'"'.$class.$selected.'>'.JText::_($text).'</option>';
		}
		
		$s = '<select name="'.$control_name.'['.$name.']'.'" '.$class.' id="'.$control_name.$name.'">';
		$s.= implode("\n", $options);
		$s.= '</select>';
		
		return $s;

		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $class, 'value', 'text', $value, $control_name.$name);
	}
}