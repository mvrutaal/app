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

/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementYjSpacer extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	var	$_name = 'YjSpacer';

	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '&nbsp;';
			
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
		if ($value) {
		$document =& JFactory::getDocument();
		$document->addCustomTag('
		<style type="text/css">
		.yjspacer_hoder{
			background:#fff;
			padding:5px;
			display:block;
			width:400px;
			text-align:center;
			overflow:hidden;
			border:1px solid #DDDDDD;
		}
		.yjspacer{
			padding:5px;
			background:#DEDEDE;
			border:1px solid #DDDDDD;
			text-shadow:1px 1px #fff;
			font-size:12px;
		}
		#menu-pane input,#menu-pane option,#menu-pane selected{
			height:20px;
			line-height:20px;
			font-size:12px;
			padding:0 0 0 5px;
		}
		#menu-pane .inputbox{
			height:22px;
			line-height:20px;
			font-size:12px;
			}
		#menu-pane input,#menu-pane option{
			margin:0 5px 0 0;
		}
		#menu-pane .text_area{
			font-size:12px;
		}
		#menu-pane .button2-left{
			margin-top:3px;
		}
		</style>
		
		');
			return '<div class="yjspacer_hoder"><div class="yjspacer">'.JText::_($value).'</div></div>';
		} else {
			return '<hr />';
		}
	}
}
