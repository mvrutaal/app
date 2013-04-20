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

class JElementYJSGMultitext extends JElement
{

	
	function fetchElement($name, $value, &$node, $control_name)
	{
		// process element properties		
		$items = $node->attributes('items');
		$default = explode('|', $node->attributes('default'));
		$values = explode('|', $value);
		
		$size = $node->attributes('size');
		$css_class = $node->attributes('class');
		$labels = explode('|', $node->attributes('labels'));
		$unique_id = $control_name.'_'.$name;
		$turnof= $node->attributes('turnof');
		$turnoflabel = $node->attributes('turnoflabel');
		if ($turnof == 1){
		$disableme = 'disabled="disabled';
		$disabletext='<div class="disabled_text">'.$turnoflabel.'</div>';
		}else{
		$disableme='';
		$disabletext='';
		}
		
		
		// create input text elements
        $div 	= array(); 
		$new_div= array();

		for ( $i=0; $i < $items; $i++ ){	
			$div[$i] = array();
			$cell_css = $i % 2 == 0 ? 'even':'odd';
			$div[$i][] = '<div class="'.$cell_css.'"><label for="'.$labels[$i].'">'.$labels[$i].'</label></div>';		
			$div[$i][] = '<div class="'.$cell_css.'"><input type="text" id="'.$labels[$i].'" class="'.$css_class.' YJSlider '.$unique_id.'" name="'.$control_name.'['.$name.'[]]" value="'.( isset($values[$i]) ? $values[$i] : $default[$i] ).'" size="'.$size.'" '.$disableme.'/></div>';		
			
			if( array_key_exists( ($i+$items), $values ) )
				$checked = $values[$i+$items] == 1 ? 'checked="checked"' : '';			
			else 
				$checked = '';
					
			$div[$i][] = '<div class="'.$cell_css.'"><div class="option check '.$unique_id.'"><div class="check"><input type="checkbox" name="'.$control_name.'['.$name.'_locked[]]" class="YJSG_checkbox '.$unique_id.'" '.$checked.' /></div>Lock</div></div>';
		}

		foreach($div as $div_row => $div_value){
			if(is_array($div_value)){
				$new_div[] = "<div class='box_".$div_row."'>".implode("\n", $div_value)."</div>";
			}else{
				$new_div[] = "<div class='box_".$div_row."'>".$div_value."</div>";
			}
		}

		$output = '<div class="YJSG_multiple"><div id="'.$unique_id.'">';
		$output.= implode("\n", $new_div);		
		$output.= '<div><a class="YJSG_reset-values" id="'.$unique_id.'" href="#" rel="'.$node->attributes('default').'">Reset to default</a></div>';
		$output.= '</div>'.$disabletext.'</div>';
		
		// return HTML
		return $output; 		
	}	
}