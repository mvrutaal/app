<?php
/**
* @version		$Id: helper.php 14401 2010-01-26 14:10:00Z louis $
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


jimport('joomla.base.tree');
jimport('joomla.utilities.simplexml');

/**
 * mod_mainmenu Helper class
 *
 * @static
 * @package		Joomla
 * @subpackage	Menus
 * @since		1.5
 */ 
class modMainMenuHelperTemplate extends modMainMenuHelper
{
	function buildXML($params)
	{
		$menu = new JMenuTreeTemplate($params);
		$items = &JSite::getMenu();
        $menu_name                   = YJSGparams::YJSGparam()->get("menuName");
		// Get Menu Items
		$rows = $items->getItems('menutype', ''.$menu_name.'');
		
		//rasio start 6
		//create the parent arrays
		$parent_rows = array();
		$child_rows = array();		
		foreach($rows as $rows_row => $rows_value){
			//create an array with all the parents
			if($rows_value->parent == 0){
				$parent_rows[$rows_value->id] = $rows_value;
			//create an array with all the parent as key and childs as values, and also keep the child node number
			}elseif($rows_value->parent > 0){
				//check to see if parent is inside parent array

				//if not add it, must be a parent which is a child for another parent, doesn't have his parent = 0
				if(!isset($parent_rows[$rows_value->parent])){
					foreach($rows as $rows_value2){
						if($rows_value2->id == $rows_value->parent){
							$parent_rows[$rows_value2->id] = $rows_value2;
							break;
						}
					}
				}

				//add the child as key and node munber as values
				if(isset($child_rows[$rows_value->parent])){
					$child_rows[$rows_value->parent][$rows_value->id] = (int)max($child_rows[$rows_value->parent]) +1;					
				}else{
					$child_rows[$rows_value->parent][$rows_value->id] = 0;
				}
			}
		}
		//rasio end 6		

		$maxdepth = 1;
		//print_r($items);
		// Build Menu Tree root down (orphan proof - child might have lower id than parent)
		$user =& JFactory::getUser();
		$ids = array();
		$ids[0] = true;
		$last = null;
		$unresolved = array();
		// pop the first item until the array is empty if there is any item
		if ( is_array($rows)) {
			while (count($rows) && !is_null($row = array_shift($rows)))
			{
				
				if (array_key_exists($row->parent, $ids)) {
					$row->ionly = $menu->get('menu_images_link');
					//rasio start 6
					if( $row->parent > 0 && isset($parent_rows[$row->parent])){
						$menu->addNode($params, $row, $parent_rows[$row->parent], $child_rows[$row->parent][$row->id]);
					}else{
						$menu->addNode($params, $row);
					}
					//rasio end 6
					//$menu->addNode($params, $row);

					// record loaded parents
					$ids[$row->id] = true;
				} else {
					// no parent yet so push item to back of list
					// SAM: But if the key isn't in the list and we dont _add_ this is infinite, so check the unresolved queue
					if(!array_key_exists($row->id, $unresolved) || $unresolved[$row->id] < $maxdepth) {
						array_push($rows, $row);
						// so let us do max $maxdepth passes
						// TODO: Put a time check in this loop in case we get too close to the PHP timeout
						if(!isset($unresolved[$row->id])) $unresolved[$row->id] = 1;
						else $unresolved[$row->id]++;
					}
				}
			}
		}
		
		
//		$document = &JFactory::getDocument();
//		if (JPluginHelper::getPlugin('system', 'YJMegaMenu')) {
//			echo'is on';
//		}
		return $menu->toXML();
	}

	function &getXML($type, &$params, $decorator)
	{
		static $xmls;

		if (!isset($xmls[$type])) {
			$cache =& JFactory::getCache('mod_mainmenu');
			$string = $cache->call(array('modMainMenuHelperTemplate', 'buildXML'), $params);
			$xmls[$type] = $string;
		}

		// Get document
		$xml = JFactory::getXMLParser('Simple');
		$xml->loadString($xmls[$type]);
		$doc = &$xml->document;

		$menu	= &JSite::getMenu();
		$active	= $menu->getActive();
		$start	= $params->get('startLevel');
		$end	= $params->get('endLevel');
		$sChild	= $params->get('showAllChildren');
		$path	= array();

		// Get subtree
		if ($start)
		{
			$found = false;
			$root = true;
			if(!isset($active)){
				$doc = false;
			}
			else{
				$path = $active->tree;
				for ($i=0,$n=count($path);$i<$n;$i++)
				{
					foreach ($doc->children() as $child)
					{
						if ($child->attributes('id') == $path[$i]) {
							$doc = &$child->ul[0];
							$root = false;
							break;
						}
					}

					if ($i == $start-1) {
						$found = true;
						break;
					}
				}
				if ((!is_a($doc, 'JSimpleXMLElement')) || (!$found) || ($root)) {
					$doc = false;
				}
			}
		}

		if ($doc && is_callable($decorator)) {
			$doc->map($decorator, array('end'=>$end, 'children'=>$sChild));
		}
		return $doc;
	}

	function render(&$params, $callback)
	{
		// Include the new menu class
		$xml = modMainMenuHelperTemplate::getXML($params->get('menutype'), $params, $callback);
		if ($xml) {
			$class = $params->get('class_sfx');
			$xml->addAttribute('class', 'menu'.$class);
			if ($tagId = $params->get('tag_id')) {
				$xml->addAttribute('id', $tagId);
			}
		
			$result = JFilterOutput::ampReplace($xml->toString((bool)$params->get('show_whitespace')));
			$result = str_replace(array('<ul/>', '<ul />'), '', $result);
			$result = html_entity_decode($result);
			$result = str_replace("&","&amp;",$result);
			echo $result;
			//echo $result;
		}
	}
}

/**
 * Main Menu Tree Class.
 *
 * @package		Joomla
 * @subpackage	Menus
 * @since		1.5
 */
class JMenuTreeTemplate extends JMenuTree
{
	/**
	 * Node/Id Hash for quickly handling node additions to the tree.
	 */
	var $_nodeHash = array();

	/**
	 * Menu parameters
	 */
	var $_params = null;

	/**
	 * Menu parameters
	 */
	var $_buffer = null;
	
		
// costta
	 var $tmp_params = array();
	 var $curr = 0;
// costta end
	
	function __construct(&$params)
	{
		$this->_params		=& $params;
		$this->_root		= new JMenuNode(0, 'ROOT');
		$this->_nodeHash[0]	=& $this->_root;
		$this->_current		=& $this->_root;
	}

	function addNode(&$params, $item, $parent = '', $child_node = ''){

		$item_params = new JParameter( $item->params );
		//don't add the menu node if this menu contains module and is parent(level0) or level1 menu
		if( ($item_params->get( 'yj_item_type' ) == 1 || $item_params->get( 'yj_item_type' ) == 2) && count($item->tree) < 2 ){
			return '';
		}
		
		// Get menu item data
		$data = $this->_getItemData($params, $item);
	
		// Create the node and add it
		//rasio start
		/*set the css class name
		* if link type module or mod position , li holding node should get class  <li class="has_module">  case modpositon  <li class="has_modpoz"
		*/
		//get the parent params for yj_sub_group_width value
		if($parent != ''){
			$parent_params = new JParameter( $parent->params );
			$yj_sub_group_width = $parent_params->get( 'yj_sub_group_width' );
			$yj_parent_group_holder = $parent_params->get( 'yj_group_holder' );
			//if(strstr("\n",$yj_sub_group_width)){
				$yj_sub_group_width_array = explode("\n",$yj_sub_group_width);
			//}
		}else{
			$yj_sub_group_width_array='';
		}

		if(isset($item->params) && $item->params != '' && JPluginHelper::getPlugin('system', 'YJMegaMenu')){

			if($item->parent > 0 && isset($item->params) && $item_params->get( 'yj_item_type' ) == 1){
				if(is_array($yj_sub_group_width_array) && $child_node !== '' && isset($yj_sub_group_width_array[$child_node]) && $yj_parent_group_holder == 1 && $yj_sub_group_width !==''){
					$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data, 'has_module', 'style="width:'.$yj_sub_group_width_array[$child_node].'px!important;"');
				}else{
					$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data, 'has_module');
				}
			}elseif($item->parent > 0 && isset($item->params) && $item_params->get( 'yj_item_type' ) == 2){   //print_r($parent->params);
				if(is_array($yj_sub_group_width_array) && $child_node !== '' && isset($yj_sub_group_width_array[$child_node]) && $yj_parent_group_holder == 1 && $yj_sub_group_width !==''){
					$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data, 'has_modpoz', 'style="width:'.$yj_sub_group_width_array[$child_node].'px!important;"');
				}else{
					$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data, 'has_modpoz');
				}
			}else{
				if(is_array($yj_sub_group_width_array) && $child_node !== '' && isset($yj_sub_group_width_array[$child_node]) && $yj_parent_group_holder == 1 && $yj_sub_group_width !==''){
					$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data, '', 'style="width:'.$yj_sub_group_width_array[$child_node].'px!important;"');
				}else{
					$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data);
				}
			}
			//assign
		}else{
			if(is_array($yj_sub_group_width_array) && $child_node !== '' && isset($yj_sub_group_width_array[$child_node]) && $yj_parent_group_holder == 1 && $yj_sub_group_width !=='' && JPluginHelper::getPlugin('system', 'YJMegaMenu')){
				$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data, '', 'style="width:'.$yj_sub_group_width_array[$child_node].'px!important;"');
			}else{
				$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data);
			}
		}

		//rasio end
		
		//original code here
		//$node = new JMenuNodeTemplate($item->id, $item->name, $item->access, $data);

		if (isset($item->mid)) {
			$nid = $item->mid;
		} else {
			$nid = $item->id;
		}
		$this->_nodeHash[$nid] =& $node;
		$this->_current =& $this->_nodeHash[$item->parent];

		if ($item->type == 'menulink' && !empty($item->query['Itemid'])) {
			$node->mid = $item->query['Itemid'];
		}

		if ($this->_current) {
			$this->addChild($node, true);
		} else {
			// sanity check
			JError::raiseError( 500, 'Orphan Error. Could not find parent for Item '.$item->id );
		}
	}

	function toXML()
	{	
		// Initialize variables
		$this->_current =& $this->_root;

		// Recurse through children if they exist
		while ($this->_current->hasChildren())
		{
			$this->_buffer .= '<ul class="subul_main">';
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->_getLevelXML(0);
			}
			$this->_buffer .= '</ul>';
		}
		if($this->_buffer == '') { $this->_buffer = '<ul />'; }
		return $this->_buffer;
		
	}

	function _getLevelXML($depth)
	{
		$depth++;
		// Start the item
		$rel = (!empty($this->_current->mid)) ? ' rel="'.$this->_current->mid.'"' : '';
		//rasio add the li class - start
		$class = (!empty($this->_current->class)) ? ' class="'.$this->_current->class.'"' : '';
		//rasio add the li class - end
		//rasio add the li custom values - start
		$custom_values = (!empty($this->_current->custom_values)) ? $this->_current->custom_values : '';
		//rasio add the li custom values - end		
		$this->_buffer .= '<li access="'.$this->_current->access.'" level="'.$depth.'" id="'.$this->_current->id.'"'.$rel.' '.$class.' '.$custom_values.'>';

		// Append item data
		$this->_buffer .= $this->_current->link;

		// Recurse through item's children if they exist
		
// costa
		$params = $this->tmp_params[$this->_current->id];
// costta end
		 //print_r($params);
		  $yj_group_holder		 = $params->get('yj_group_holder');
		  $yj_menu_holder_width	 = $params->get('yj_menu_holder_width');
		  $yj_menu_groups_count	 = $params->get('yj_menu_groups_count');
		  $yj_sub_group_width	 = $params->get('yj_sub_group_width');
		  $yj_group_id 			 =  $this->_current->id;
		  $yj_group_left_poz	= $params->get('yj_sub_group_width');

			if ($yj_group_holder == '1' && $yj_menu_holder_width !=='' && $yj_menu_groups_count !=='0' ){
				$group_holder_class =' group_holder';
				$group_holder_style = ' style="width:'.$yj_menu_holder_width.'px!important;"';
				$group_num_items 	= ' count'.$yj_menu_groups_count.'';
				$group_holder_id = ' group_'.$yj_group_id.'';
				
			if($yj_menu_groups_count == '3' && $yj_sub_group_width == '' ) {
				$group_holder_style = ' style="width:'.($yj_menu_holder_width -1 ).'px!important;"';
			}
			}else{
				$group_holder_class ='';
				$group_holder_style ='';
				$group_num_items='';
				$group_holder_id ='';
			}
			//$document = &JFactory::getDocument();
		if (!JPluginHelper::getPlugin('system', 'YJMegaMenu')) {
				$group_holder_class ='';
				$group_holder_style ='';
				$group_num_items='';
				$group_holder_id ='';
		}		  
		while ($this->_current->hasChildren())
		{
			$this->_buffer .= '<ul class="subul_main'.$group_holder_class.$group_num_items.$group_holder_id.' level'.$depth.'"'.$group_holder_style.'><li class="bl"></li><li class="tl"></li><li class="tr"></li>';
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->_getLevelXML($depth);
			}
			$this->_buffer .= '<li class="right"></li><li class="br"></li></ul>';
			
		}

		// Finish the item
		$this->_buffer .= '</li>';
	}

	function _getItemData(&$params, $item)
	{
		$data 	= null;
		$db		=& JFactory::getDBO();
		
		// Menu Link is a special type that is a link to another item
		if ($item->type == 'menulink')
		{
			$menu = &JSite::getMenu();

			if ($newItem = $menu->getItem($item->query['Itemid'])) {
    			$tmp = clone($newItem);
				$tmp->name	 = '<![CDATA['.$item->name.']]>';
				//$tmp->name	 = $item->name;
				$tmp->mid	 = $item->id;
				$tmp->parent = $item->parent;
			} else {
				return false;
			}
		} else {
			$tmp = clone($item);
			$tmp->name = '<![CDATA['.$item->name.']]>';
			//$tmp->name	 = $item->name;
		}
		
		
		$iParams = new JParameter($tmp->params);
// costa 
		$this->tmp_params[$item->id] =& $iParams;
// costa end
		//print_r($iParams);
		//echo $iParams->get('yj_menu_sub_title');
		$yj_menu_title 	=  $tmp->name;
		$yj_sub_title 	=  $iParams->get('yj_menu_sub_title');
		$yj_item_type 	=  $iParams->get('yj_item_type');
		$yj_mod_id 		=  $iParams->get('yj_mod_id');
		$yj_position	=  $iParams->get('yj_position');
		$yj_menu_show_title	=  $iParams->get('yj_menu_show_title');
		$yj_menu_custom_class	=  $iParams->get('yj_menu_custom_class');
		$yj_menu_add_custom_class =' '.$yj_menu_custom_class.'';

		$yj_group_id  =  $item->id;
		$yj_menu_groups_count = $iParams->get('yj_menu_groups_count');
		$yj_menu_holder_width = $iParams->get('yj_menu_holder_width');
		$sub_sub_position     = YJSGparams::YJSGparam()->get("sub_sub_position");
		
		if($iParams->get('menu_image') ==-1){
			$image = null;

		}else{
			$image = JURI::base(true).'/images/stories/'.$iParams->get('menu_image');
		}
		
		//return empty if the menu is a module and is on level0 or level1
		if( ($yj_item_type == 1 && count($item->tree) <= 1) || ($yj_item_type == 2 && count($item->tree) <= 1) ){
			return "";
		}
				
		if (JPluginHelper::getPlugin('system', 'YJMegaMenu')) {	
			// No image no description
			if(empty($yj_sub_title) && $iParams->get('menu_image') ==-1){
				$itemw_image ='itemno_img_desc';
				$item_link ='
				<span class="yjm_has_none">
					<span class="yjm_title">'.$yj_menu_title.'</span>
				</span>
				';
			// Description only	
			}elseif(!empty($yj_sub_title) && $iParams->get('menu_image') ==-1){
				
				$itemw_image ='itemw_desc_only';
				$item_link = '
					<span class="yjm_has_desc">
						<span class="yjm_title">'.$yj_menu_title.'</span>
						<span class="yjm_desc"><![CDATA['.$yj_sub_title.']]></span>
					</span>
				';
				
			// Image only	
			}elseif(empty($yj_sub_title) && $iParams->get('menu_image') !==-1){
				
				$itemw_image ='itemw_image_only';
				$item_link = '
					<span class="yjm_has_image" style="background-image:url('.$image.');">
						<span class="yjm_title">'.$yj_menu_title.'</span>
					</span>
				';
				
			}elseif(!empty($yj_sub_title) && $iParams->get('menu_image') !==-1 ){
			// Image and description	
				$itemw_image ='itemw_image_desc';
				$item_link = '
					<span class="yjm_has_all" style="background-image:url('.$image.');">
						<span class="yjm_title">'.$yj_menu_title.'</span>
						<span class="yjm_desc"><![CDATA['.$yj_sub_title.']]></span>
					</span>
				';
	
			}else{
			// No image no description all other cases
				$itemw_image='itemno_img_desc';
				$item_link ='
				<span class="yjm_has_none">
					<span class="yjm_title">'.$yj_menu_title.'</span>
				</span>
				';
			}
		}else{
			if($image !=null){
				
				$itemw_image ='itemw_image_only';
				$item_link = '
					<span class="yjm_has_image" style="background-image:url('.$image.');">
						<span class="yjm_title">'.$yj_menu_title.'</span>
					</span>
				';
				
				}else{
				$itemw_image ='';
				$item_link ='
				<span class="yjm_has_none">
					<span class="yjm_title">'.$yj_menu_title.'</span>
				</span>
				';
			}
		}
		///////////////////  MODULE LINK TYPE OUTPUT ////////////////////
		if($yj_sub_title !=='' && $yj_menu_show_title == 1 ){
			$yj_module_sub_title         	= '<span class="yjm_desc"><![CDATA['.$yj_sub_title.']]></span>';
		}else{
			$yj_module_sub_title         	= '';
		}
		if ($image !== null && $yj_menu_show_title == 1 ){
			$yj_module_class				='_img';	
			$yj_module_image         		= ' style="background-image:url('.$image.');"';
		}else{
			$yj_module_class				='';	
			$yj_module_image         		='';
		}
		if($yj_menu_show_title == 1 ) {
			$yj_module_link_title 				= '<span class="yjm_title">'.$yj_menu_title.'</span>';
			$module_details						= '<span class="yjm_module_details'.$yj_module_class.'"'.$yj_module_image .'>'.$yj_module_link_title.$yj_module_sub_title.'</span>';
		}else{
			$yj_module_link_title 				='';
			$module_details						='';
		}
		//rasio start
		if($yj_item_type == 1) {
			// CHECK: custom module name is given by the title field, otherwise it's just 'om' ??
			//check if we have multiple modules selected
			if(!is_array($yj_mod_id)){
				$yj_mod_id = array($yj_mod_id);
			}
			$item_link_array = array();
			foreach($yj_mod_id as $yj_mod_id_value){
				//get the module name
				$query = 'SELECT module, title '
					. ' FROM #__modules AS m'
					. ' WHERE 1 '//m.published = 1
					. ' AND m.id = '. (int)$yj_mod_id_value;
				$db->setQuery( $query );
				$module_details = $db->loadObjectList();

				jimport( 'joomla.application.module.helper' );
				if($module_details[0]->module == 'mod_custom'){
					$module = YJModuleHelper::getModule( 'custom', $module_details[0]->title );
				}else{
					$module = YJModuleHelper::getModule( strtolower(substr( $module_details[0]->module, 4, strlen($module_details[0]->module) )), $module_details[0]->title );			
				}
				$attribs['style'] = 'YJsgxhtml';
				$yj_load_mod = YJModuleHelper::renderModule( $module, $attribs );
				$item_link_array[] =''.$module_details.'<div class="yjm_module"><![CDATA['.$yj_load_mod.']]></div>';
			}
			if (JPluginHelper::getPlugin('system', 'YJMegaMenu')) {	
				$item_link = implode($item_link_array);
			}
		}elseif($yj_item_type == 2) {
			if(!is_array($yj_position)){
				$yj_position = array($yj_position);
			}
			$item_link_array = array();
			foreach($yj_position as $yj_position_value){
				jimport( 'joomla.application.module.helper' );
				$modules = YJModuleHelper::getModules($yj_position_value); 
				$attribs['style'] = 'YJsgxhtml';
				foreach($modules as $module){
					$yj_load_mod  = YJModuleHelper::renderModule($module,$attribs);
					$item_link_array[] =''.$module_details.'<div class="yjm_module"><![CDATA['.$yj_load_mod.']]></div>';
				}
			}
			if (JPluginHelper::getPlugin('system', 'YJMegaMenu')) {	
				$item_link = implode($item_link_array);
			}
		}
		//rasio end
		
		switch ($tmp->type)
		{
			case 'separator' :
				return '<span class="separator">'.$tmp->name.'</span>';
				break;

			case 'url' :
				if ((strpos($tmp->link, 'index.php?') === 0) && (strpos($tmp->link, 'Itemid=') === false)) {
					$tmp->url = $tmp->link.'&amp;Itemid='.$tmp->id;
				} else {
					$tmp->url = $tmp->link;
				}
				break;

			default :
				$router = JSite::getRouter();
				$tmp->url = $router->getMode() == JROUTER_MODE_SEF ? 'index.php?Itemid='.$tmp->id : $tmp->link.'&Itemid='.$tmp->id;
				break;
		}

		// Print a link if it exists
		if ($tmp->url != null)
		{
			// Handle SSL links
			$iSecure = $iParams->def('secure', 0);
			if ($tmp->home == 1) {
				$tmp->url = JURI::base();
			} elseif (strcasecmp(substr($tmp->url, 0, 4), 'http') && (strpos($tmp->link, 'index.php?') !== false)) {
				$tmp->url = JRoute::_($tmp->url, true, $iSecure);
			} else {
				$tmp->url = str_replace('&', '&amp;', $tmp->url);
			}

			switch ($tmp->browserNav)
			{
				default:
				case 0:
					// _top
				if(JPluginHelper::getPlugin('system', 'YJMegaMenu')) {
					if($yj_item_type == 0 ) {
						$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$tmp->url.'">'.$item_link.'</a></span>';
					}else{
						$data = '<div class="yj_menu_module_holder'.$yj_menu_add_custom_class.'">'.$item_link.'</div>';
					}
				}else{
					$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$tmp->url.'">'.$item_link.'</a></span>';
				}
					break;
				case 1:
					// _blank
				if(JPluginHelper::getPlugin('system', 'YJMegaMenu')) {
					if($yj_item_type == 0 ) {
						$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$tmp->url.'" target="_blank">'.$item_link.'</a></span>';
					}else{
						$data = '<div class="yj_menu_module_holder'.$yj_menu_add_custom_class.'">'.$item_link.'</div>';
					}
				}else{
					$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$tmp->url.'" target="_blank">'.$item_link.'</a></span>';
				}
					break;
				case 2:
					// window.open
					
					$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$this->_params->get('window_open');
					// hrm...this is a bit dickey
					$link = str_replace('index.php', 'index2.php', $tmp->url);
				if(JPluginHelper::getPlugin('system', 'YJMegaMenu')) {
					if($yj_item_type == 0 ) {
						$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$link.'" onclick="window.open(this.href,\'targetWindow\',\''.$attribs.'\');return false;">'.$item_link.'</a></span>';
					}else{
						$data = '<div class="yj_menu_module_holder'.$yj_menu_add_custom_class.'">'.$item_link.'</div>';
					}
				}else{
					$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$link.'" onclick="window.open(this.href,\'targetWindow\',\''.$attribs.'\');return false;">'.$item_link.'</a></span>';
				}
					break;
			}
		} else {
	if(JPluginHelper::getPlugin('system', 'YJMegaMenu')) {		
			if($yj_item_type == 0 ) {
				$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$tmp->url.'">'.$item_link.'</a></span>';
			}else{
				$data = '<div class="yj_menu_module_holder'.$yj_menu_add_custom_class.'">'.$item_link.'</div>';
			}
	}else{
		$data = '<span class="mymarg"><a class="'.$itemw_image.''.$yj_menu_add_custom_class.'" href="'.$tmp->url.'">'.$item_link.'</a></span>';
	}
		}

		return $data;
	}
}

/**
 * Main Menu Tree Node Class.
 *
 * @package		Joomla
 * @subpackage	Menus
 * @since		1.5
 */
class JMenuNodeTemplate extends JMenuNode
{
	/**
	 * Node Title
	 */
	var $title = null;

	/**
	 * Node Link
	 */
	var $link = null;

	/**
	 * CSS Class for node
	 */
	var $class = null;
	

	/**
	 * CSS Class for node
	 */
	var $custom_values = null;	

	function __construct($id, $title, $access = null, $link = null, $class = null, $custom_values = null)
	{
		$this->id				= $id;
		$this->title			= $title;
		$this->access			= $access;
		$this->link				= $link;
		$this->class			= $class;
		$this->custom_values	= $custom_values;		
	}

}

class YJModuleHelper
{
	/**
	 * Get module by name (real, eg 'Breadcrumbs' or folder, eg 'mod_breadcrumbs')
	 *
	 * @access	public
	 * @param	string 	$name	The name of the module
	 * @param	string	$title	The title of the module, optional
	 * @return	object	The Module object
	 */
	function &getModule($name, $title = null )
	{
		$result		= null;
		$modules	=& YJModuleHelper::_load();
		$total		= count($modules);
		for ($i = 0; $i < $total; $i++) 
		{
			// Match the name of the module
			if ($modules[$i]->name == $name)
			{
				// Match the title if we're looking for a specific instance of the module
				if ( ! $title || $modules[$i]->title == $title )
				{
					$result =& $modules[$i];
					break;	// Found it
				}
			}
		}

		// if we didn't find it, and the name is mod_something, create a dummy object
		if (is_null( $result ) && substr( $name, 0, 4 ) == 'mod_')
		{
			$result				= new stdClass;
			$result->id			= 0;
			$result->title		= '';
			$result->module		= $name;
			$result->position	= '';
			$result->content	= '';
			$result->showtitle	= 0;
			$result->control	= '';
			$result->params		= '';
			$result->user		= 0;
		}

		return $result;
	}

	/**
	 * Get modules by position
	 *
	 * @access public
	 * @param string 	$position	The position of the module
	 * @return array	An array of module objects
	 */
	function &getModules($position)
	{
		$position	= strtolower( $position );
		$result		= array();

		$modules =& YJModuleHelper::_load();

		$total = count($modules);
		for($i = 0; $i < $total; $i++) {
			if($modules[$i]->position == $position) {
				$result[] =& $modules[$i];
			}
		}
		if(count($result) == 0) {
			if(JRequest::getBool('tp')) {
				$result[0] = YJModuleHelper::getModule( 'mod_'.$position );
				$result[0]->title = $position;
				$result[0]->content = $position;
				$result[0]->position = $position;
			}
		}

		return $result;
	}

	/**
	 * Checks if a module is enabled
	 *
	 * @access	public
	 * @param   string 	$module	The module name
	 * @return	boolean
	 */
	function isEnabled( $module )
	{
		$result = &YJModuleHelper::getModule( $module);
		return (!is_null($result));
	}

	function renderModule($module, $attribs = array())
	{
		static $chrome;
		global $mainframe, $option;

		$scope = $mainframe->scope; //record the scope
		$mainframe->scope = $module->module;  //set scope to component name

		// Handle legacy globals if enabled
		if ($mainframe->getCfg('legacy'))
		{
			// Include legacy globals
			global $my, $database, $acl, $mosConfig_absolute_path;

			// Get the task variable for local scope
			$task = JRequest::getString('task');

			// For backwards compatibility extract the config vars as globals
			$registry =& JFactory::getConfig();
			foreach (get_object_vars($registry->toObject()) as $k => $v) {
				$name = 'mosConfig_'.$k;
				$$name = $v;
			}
			$contentConfig = &JComponentHelper::getParams( 'com_content' );
			foreach (get_object_vars($contentConfig->toObject()) as $k => $v)
			{
				$name = 'mosConfig_'.$k;
				$$name = $v;
			}
			$usersConfig = &JComponentHelper::getParams( 'com_users' );
			foreach (get_object_vars($usersConfig->toObject()) as $k => $v)
			{
				$name = 'mosConfig_'.$k;
				$$name = $v;
			}
		}

		// Get module parameters
		$params = new JParameter( $module->params );

		// Get module path
		$module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);
		$path = JPATH_BASE.DS.'modules'.DS.$module->module.DS.$module->module.'.php';

		// Load the module
		if (!$module->user && file_exists( $path ) && empty($module->content))
		{
			$lang =& JFactory::getLanguage();
			$lang->load($module->module);

			$content = '';
			ob_start();
			require $path;
			$module->content = ob_get_contents().$content;
			ob_end_clean();
		}

		// Load the module chrome functions
		if (!$chrome) {
			$chrome = array();
		}

		require_once (JPATH_BASE.DS.'templates'.DS.'system'.DS.'html'.DS.'modules.php');
		$chromePath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'modules.php';
		if (!isset( $chrome[$chromePath]))
		{
			if (file_exists($chromePath)) {
				require_once ($chromePath);
			}
			$chrome[$chromePath] = true;
		}

		//make sure a style is set
		if(!isset($attribs['style'])) {
			$attribs['style'] = 'none';
		}

		//dynamically add outline style
		if(JRequest::getBool('tp')) {
			$attribs['style'] .= ' outline';
		}

		foreach(explode(' ', $attribs['style']) as $style)
		{
			$chromeMethod = 'modChrome_'.$style;

			// Apply chrome and render module
			if (function_exists($chromeMethod))
			{
				$module->style = $attribs['style'];

				ob_start();
				$chromeMethod($module, $params, $attribs);
				$module->content = ob_get_contents();
				ob_end_clean();
			}
		}

		$mainframe->scope = $scope; //revert the scope

		return $module->content;
	}

	/**
	 * Get the path to a layout for a module
	 *
	 * @static
	 * @param	string	$module	The name of the module
	 * @param	string	$layout	The name of the module layout
	 * @return	string	The path to the module layout
	 * @since	1.5
	 */
	function getLayoutPath($module, $layout = 'default')
	{
		global $mainframe;

		// Build the template and base path for the layout
		$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$module.DS.$layout.'.php';
		$bPath = JPATH_BASE.DS.'modules'.DS.$module.DS.'tmpl'.DS.$layout.'.php';

		// If the template has a layout override use it
		if (file_exists($tPath)) {
			return $tPath;
		} else {
			return $bPath;
		}
	}

	/**
	 * Load published modules
	 *
	 * @access	private
	 * @return	array
	 */
	function &_load()
	{
		global $mainframe, $Itemid;

		static $modules;

		if (isset($modules)) {
			return $modules;
		}

		$user	=& JFactory::getUser();
		$db		=& JFactory::getDBO();

		$aid	= $user->get('aid', 0);

		$modules	= array();

		$wheremenu = isset( $Itemid ) ? ' AND ( mm.menuid = '. (int) $Itemid .' OR mm.menuid = 0 )' : '';

		$query = 'SELECT id, title, module, position, content, showtitle, control, params'
			. ' FROM #__modules AS m'
			//. ' LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id'
			//. ' WHERE 1 '
			//. ' AND m.access <= '. (int)$aid
			//. ' AND m.client_id = '. (int)$mainframe->getClientId()
			//. $wheremenu
			. ' ORDER BY position, ordering';

		$db->setQuery( $query );

		if (null === ($modules = $db->loadObjectList())) {
			JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'Error Loading Modules' ) . $db->getErrorMsg());
			return false;
		}

		$total = count($modules);
		for($i = 0; $i < $total; $i++)
		{
			//determine if this is a custom module
			$file					= $modules[$i]->module;
			$custom 				= substr( $file, 0, 4 ) == 'mod_' ?  0 : 1;
			$modules[$i]->user  	= $custom;
			// CHECK: custom module name is given by the title field, otherwise it's just 'om' ??
			$modules[$i]->name		= $custom ? $modules[$i]->title : substr( $file, 4 );
			$modules[$i]->style		= null;
			$modules[$i]->position	= strtolower($modules[$i]->position);
		}

		return $modules;
	}
}