<?php
///**
// * @version		$Id: pagination.php 10381 2008-06-01 03:35:53Z pasamio $
// * @package		Joomla
// * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
// * @license		GNU/GPL, see LICENSE.php
// * Joomla! is free software. This version may have been modified pursuant
// * to the GNU General Public License, and as distributed it includes or
// * is derivative of works licensed under the GNU General Public License or
// * other free or open source software licenses.
// * See COPYRIGHT.php for copyright notices and details.
// */
//
//// no direct access
//defined('_JEXEC') or die('Restricted access');
//function pagination_list_footer($list)
//{
//	// Initialize variables
//	$lang =& JFactory::getLanguage();
//	$html = "<div class=\"list-footer\">\n";
//
//	$html .= "\n<div class=\"limit\">".JText::_('Display Num').$list['limitfield']."</div>";
//	$html .= $list['pageslinks'];
//	$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";
//
//	$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"".$list['limitstart']."\" />";
//	$html .= "\n</div>";
//
//	return $html;
//}
//
//function pagination_list_render($list)
//{
//	// Initialize variables
//	$lang =& JFactory::getLanguage();
//	$html = "<ul class=\"pagination\">";
//
//	$html .= $list['start']['data'];
//	$html .= $list['previous']['data'];
//
//	foreach( $list['pages'] as $page )
//	{
//		if($page['data']['active']) {
//			// $html .= '<strong>';
//		}
//
//		$html .= $page['data'];
//
//		if($page['data']['active']) {
//			//  $html .= '</strong>';
//		}
//	}
//
//	$html .= $list['next']['data'];
//	$html .= $list['end']['data'];
//	// $html .= '&#171;';
//
//	$html .= "</ul>";
//	return $html;
//}
//
//function pagination_item_active(&$item) {
//	return "<li><strong><a href=\"".$item->link."\" title=\"".$item->text."\">".$item->text."</a></strong></li>";
//}
//
//function pagination_item_inactive(&$item) {
//	return "<li>".$item->text."</li>";
//}
//?>