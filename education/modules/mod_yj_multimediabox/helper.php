<?php
/**
* @version		$Id: helper.php 11074 2008-10-13 04:54:12Z ian $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses. 
* See COPYRIGHT.php for copyright notices and details.
*/

/// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modMultimediaBoxHelper
{
	function getList(&$params)
	{
		global $mainframe;
		
		$dispatcher	=& JDispatcher::getInstance(); 
		$row->id = '';
		$row->text = $params->get( 'code', '' );
		
		//force to display module even if onBeforeDisplayContent is not active
		$found = 0;
		foreach ($dispatcher->_observers as $observer){
			if (is_array($observer)){
				if($observer['event'] == 'onBeforeDisplayContent') $found = 1;
				break;
			}
		}
		//force to add event onBeforeDisplayContent
		if($found == 0){
			require_once (JPATH_SITE.DS.'plugins'.DS.'content'.DS.'bot_mb.php');
			$dispatcher->_observers[] = array("event" => "onBeforeDisplayContent","handler" => "pop_box_media");
		}

		//display module				
		foreach ($dispatcher->_observers as $observer){
			if (is_array($observer)){
				if ($observer['event'] == 'onBeforeDisplayContent' && $observer['handler'] == 'pop_box_media'){
					$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $row, & $params, ''));
					return $row;
				}
			}
		}

		$row->text = "Please install and publish Multi Media Box plugin";
		return $row;
	}
}
