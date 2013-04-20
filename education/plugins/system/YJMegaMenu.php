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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.event.plugin' );
jimport('joomla.application.component.view');
jimport('joomla.html.pane');

/**
 * YJ system plugin
 *
 * @package		Joomla
 * @subpackage	System 
 */ 
class  plgSystemYJMegaMenu extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param	object		$subject The object to observe
	  * @param 	array  		$config  An array that holds the plugin configuration
	 * @since	1.0
	 */

	
	var $_params;
	
	
	function plgSystemYJMegaMenu(& $subject){
		parent::__construct($subject);
		
		   $this->_plugin = JPluginHelper::getPlugin( 'system', 'YJMegaMenu' );
           $this->_params = new JParameter( $this->_plugin->params );
	}

 	function getSystemParams(){

		$params	= null;
		$item	= $this->getModulesData();
		if(isset($item->params))
			$params = new JParameter( $item->params );
		else
			$params = new JParameter( "" );
			
			$path = JPATH_PLUGINS.DS.'system'.DS.'YJMegaMenu'.DS.'element'.DS.'YJMegaMenu.xml';	
	
				if (file_exists( $path )) {
					$xml =& JFactory::getXMLParser('Simple');
					if ($xml->loadFile($path)) {
						$document =& $xml->document;
						$params->setXML($document->getElementByPath('state/params'));
						
					}
				}
			return $params->render('params');	

	}
	
	function onAfterRender(){
		global $mainframe;
			
			if (!isset($this->_plugin)) return;
	
			if ( JRequest::getVar("option") == "com_menus" && JRequest::getVar("task") == "edit" && JRequest::getVar("type") !== "separator"   ) {

				$params		= $this->getSystemParams();
				JView::assignRef('params' , $params);
				$item	= $this->getModulesData();
						ob_start();
				
						$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));
						JView::assignRef('pane', $pane);
						
							echo $this->pane->startPanel(JText :: _('Parameters - YJMegaMenu'), "YJ-page");
							echo $params;
							echo $this->pane->endPanel();
						
						$thexml = ob_get_clean();
						$body = JResponse::getBody();
						
						$start_string = '<div id="menu-pane" class="pane-sliders">';
						
						$body = str_replace($start_string,$start_string.$thexml, $body);
						JResponse::setBody($body);

			}	
		return true;		
	}

	// for getItem
	function getModulesData(){
		$db =& JFactory::getDBO();
		$id = JRequest::getVar ( 'cid', 0, '', 'array' );
		$id = ( int ) $id [0];
		if($id == "") $id = 0;
		$query = "SELECT * FROM #__menu WHERE id = '".$id."'";
		$db->setQuery($query);
		if($return = $db->loadObject()){
			return $return;
		}else{
			return array();
		}
		//return $db->loadObject();
	}
}
?>