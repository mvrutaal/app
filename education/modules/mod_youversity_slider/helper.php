<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla YouStorage News Slider 
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

JHTML::_('behavior.mootools');
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
require_once('modules/mod_youversity_slider/lib/slike.php');

class modYouversidtySLiderhHelper
{
	function getYouversitySliderItems(&$params)
	{
		
		  $get_items             	= $params->get   ('get_items',1);
		  $nitems                	= $params->get   ('nitems',4);
		  $chars                 	= $params->get   ('chars',40);
		  $chars_nav             	= $params->get   ('chars_nav',40);
		  $ordering              	= $params->get   ('ordering',3);// 1 = ordering | 2 = popular | 3 = random 
		  $getspecific 	         	= $params->get ('getspecific');
		  $allow_tags				= $params->get ('allow_tags');
	
							
		 $slider_width           	= $params->get   ('slider_width');
		 $slider_height          	= $params->get   ('slider_height');
		 $slider_image_width     	= $params->get   ('slider_image_width');
		 $slider_image_height     	= $params->get   ('slider_image_height');
		 
						  
		 $visible_items       	 	= $params->get   ('visible_items','2');
		 $autoslide    			 	= $params->get   ('autoslide');
		 $items_width 				=  number_format(($slider_width/$visible_items),0, '.', '');
		 $effectDuration			= $params->get   ('effectDuration');

		if($slider_image_width){
			$slider_image_width = "width=\"".$slider_image_width."px\"";
		}
		if($slider_image_height){
			$slider_image_height = "height=\"".$slider_image_height."px\"";
		}
		$document = &JFactory::getDocument();
		if (JPluginHelper::getPlugin('system', 'mtupgrade')) :
			$moo_v = '12';
		else:
			$moo_v = '';
		endif;
		$document->addStyleSheet(JURI::base() . 'modules/mod_youversity_slider/css/stylesheet.css');
		$document->addScript(JURI::base() . 'modules/mod_youversity_slider/src/youversity_slider'.$moo_v.'.js');
		
	 
		$document->addScriptDeclaration("
	window.addEvent('load', function(){
		new YouVersitySlides({
			container : 'yv_container', 
			items :'.yv_slideitems',
			itemWidth : ".$items_width.",
			visibleItems: ".$visible_items.",
			effectDuration : ". $effectDuration.",
			autoSlide : ". $autoslide .",
			mouseEventSlide: ". $autoslide .",
			navigation: {
				'forward':'linkForward', 
				'back':'linkBackward'
			}
		});
	})
		");

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
		
		
		$youversity_slides = array();
		foreach ( $load_items as $row ) {
			$youversity_slide = array(
					'intro' => substr(strip_tags($row->introtext,''.$allow_tags.''),0,$chars),
					'link' => ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid),
					'title' => $row->title,
					'img_url' => $img_url = article_imageyouvers($row),
					'img_out' => "<img src=\"".$img_url."\" title=\"".$row->title." \" ".$slider_image_width." ".$slider_image_height." alt=\"\"/>"
				);
				$youversity_slides[] = $youversity_slide;
		}
		
				return $youversity_slides;
	}
}
?>