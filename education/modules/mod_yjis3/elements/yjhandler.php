<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla LLC
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2010 Youjoomla LLC.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
JHTML::_('behavior.modal');
/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementYjHandler extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	var	$_name = 'YjHandler';

	function fetchElement($name, $value, &$node, $control_name){

		// Output
		  jimport('joomla.filesystem.file');
		  $mainframe = &JFactory::getApplication();
		  $e_folder = basename(dirname(dirname(__FILE__)));

		  $destpath = JPATH_ROOT.DS."images".DS."upload_slides"; 
		  JFolder::create($destpath);
		  $empty="";
 		  JFile::write($destpath.DS."index.html",$empty);
		  
		return ;
	}
		function fetchTooltip($label, $description, &$xmlElement, $control_name='', $name='') {
		return false;
	}
}
