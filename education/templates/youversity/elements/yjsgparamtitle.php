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
// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementYJSGParamTitle extends JElement {
	var	$_name = 'YJSGParamTitle';
	
	function fetchElement($name, $value, &$node, $control_name){

		// Output
		
		return '
	
		<div class="yjsg_param_title">
			<div class="yjsg_param_title_l">
			'.JText::_($value).'
			</div>
		</div>
		';
	}
		function fetchTooltip($label, $description, &$xmlElement, $control_name='', $name='') {
		return false;
	}
}

?>