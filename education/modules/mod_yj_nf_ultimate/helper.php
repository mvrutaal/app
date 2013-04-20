<?php
/**
* @version		$Id: helper.php 15198 2010-03-05 09:06:05Z ian $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
require_once('modules/mod_yj_nf_ultimate/lib/slike.php');

class modYJNewsFlashHelper
{
	function getItems(&$params)
	{
		global $mainframe;

		$nitems 		= $params->get ('nitems',4);
		$ordering 		= $params->get('ordering',3);// 1 = ordering | 2 = popular | 3 = random 
		$get_items 		= $params->get('get_items',1);
		$getspecific 	= $params->get ('getspecific');
		$showcomments 	= $params->get('showcomments');	
		$chars 			= $params->get ('chars',40);
		$allow_tags		= $params->get ('allow_tags');
		
		/* add styles and scripts */			
		$document = &JFactory::getDocument();
		$document->addStyleSheet(JURI::base() . 'modules/mod_yj_nf_ultimate/css/stylesheet.css');
		
		/* prepare database */
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');
		$aid		= $user->get('aid', 0);
		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('shownoauth');
		$nullDate	= $db->getNullDate();
		$date =       & JFactory::getDate();
		$now =         $date->toMySQL(); //date('Y-m-d H:i:s');
		
		
		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;
		// select specific items
		if(!empty($getspecific)){
		$countitems = count($getspecific);
		}
		if(!empty($getspecific) && $countitems > 1 ){
			$specificitems = implode(",", $getspecific);
			$specific_order= 'field(a.id,'.$specificitems.')';
			$where .= ' AND a.id IN ('.$specificitems.')';
		}elseif(!empty($getspecific) && $countitems == 1 ){
			$specificitems = $getspecific;
			$specific_order= 'field(a.id,'.$specificitems.')';
			$where .= ' AND a.id IN ('.$specificitems.')';
		}else{
			$specificitems='';
			$specific_order='NULL';
			$where .= ' AND cc.id = '.$get_items.'';
		}
		/* set items order */
		$ord = array(
			1=>'ordering',
			2=>'hits',
			3=>'RAND()',
			4=>'created ASC',
			5=>'created DESC',
			6=>$specific_order
		);
		$order = $ord[$ordering];
		/* get items */
		$sql = 	'SELECT a.*, ' .
				' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'. 
				' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
				'cc.title as cattitle,'.
				's.title as sectitle'.
				
				' FROM #__content AS a' .
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
				' WHERE '. $where .'' .
				($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
				' AND s.published = 1' .
				' AND cc.published = 1' .
				' ORDER BY '.$order .' LIMIT 0,'.$nitems.'';
					
		$db->setQuery( $sql );
		$load_items = $db->loadObjectList();
		
		
		$yjnf_items =array();
		foreach ( $load_items as $row ) {
			
			// get comments 
			if($showcomments == 1 && JFolder::exists(JPATH_SITE.DS.'components'.DS.'com_jcomments')):
					$comments = 'SELECT count(com.object_id) as comments FROM #__jcomments AS com WHERE published = 1 AND com.object_id = '.$row->id.'';
					$db->setQuery( $comments );
					$comments_results = $db->loadResult();
			elseif ($showcomments == 1 && !JFolder::exists(JPATH_SITE.DS.'components'.DS.'com_k2')) :
					$comments_results = 'JComments is not installed!';
			elseif ($showcomments == 2) :
					$comments_results ='';
			endif;
					
		
			$yjnf_item = array(
					'item_date' 	=> JHTML::_('date', $row->created,JText::_('CREATEDATE')),
					'item_intro' 	=> substr(strip_tags($row->introtext,''.$allow_tags.''),0,$chars),
					'item_link' 	=> ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid),
					'item_img' 		=> yjnfu_image($row),
					'item_title' 	=> $row->title,
					'item_id'	 	=> $row->id,
					'item_ctitle' 	=> $row->cattitle,
					'item_comment'  => $comments_results
				);
				$yjnf_items[] = $yjnf_item;
			}
			return $yjnf_items;
	}
}
?>