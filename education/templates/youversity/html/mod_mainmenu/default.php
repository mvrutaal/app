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
// no direct access
defined('_JEXEC') or die('Restricted access');
$who = strtolower($_SERVER['HTTP_USER_AGENT']);
$app = & JFactory::getApplication();
JLoader::register('YJSGparams', JPATH_THEMES.DS.$app->getTemplate().DS.'/yjsgcore/yjsg_params.php');
// MENU
$mymenu = array(
        1, 2, 3, 4, 5
);
$default_menu = YJSGparams::YJSGparam()->get("default_menu_style");
$valid_menu ='';
//*
if ( isset($_GET['change_menu']) && !empty($_GET['change_menu']) ) {
        // check if style is valid
        if( in_array( $_GET['change_menu'], $mymenu ) ){

                $_SESSION['frontend_menu'] = $_GET['change_menu'];
                $_SESSION['frontend_changed_menu'] = true;
                $valid_menu = $_GET['change_menu'];

        }else {
                // else set to default style
                $valid_menu = $default_style;
        }

} else {
        // second case, checkes for admin changes or front-end changes

        if ( isset($_SESSION['frontend_changed_menu']) && in_array( $_SESSION['frontend_menu'], $mymenu ) ){

                $valid_menu = $_SESSION['frontend_menu'];

        }else if( isset( $_SESSION['admin_change'] ) ){

                $default_menu = YJSGparams::YJSGparam()->get("default_menu_style");

        }else {
                $valid_menu = $default_menu;
        }
}
$default_menu_style = in_array( $valid_menu, $mymenu ) ? $valid_menu : $default_menu;
//*/
//echo $default_menu_style;
if($params->_registry['_default']['data']->class_sfx == 'nav'
 || $params->_registry['_default']['data']->class_sfx == 'navd'
 || $params->_registry['_default']['data']->class_sfx == 'split'){
	if (preg_match( "/msie 6.0/",$who) && ($default_menu_style == 1 ||  $default_menu_style == 2)){
	 require_once (dirname(__FILE__).DS.'helpertopie6.php');
	}elseif($default_menu_style == 3 ||  $default_menu_style == 4){
		require_once (dirname(__FILE__).DS.'helperdropline.php');
	}else{
		require_once (dirname(__FILE__).DS.'helpertop.php');
	}
 }else{
	 require_once (dirname(__FILE__).DS.'helpersides.php');
 }

if ( ! defined('modMainMenuXMLCallbackDefined') )
{
function modMainMenuXMLCallback(&$node, $args)
{ 
	$user	= &JFactory::getUser();
	$menu	= &JSite::getMenu();
	$active	= $menu->getActive();
	$path	= isset($active) ? array_reverse($active->tree) : null;
	
	if (($args['end']) && ($node->attributes('level') >= $args['end']))
	{
		$children = &$node->children();
		foreach ($node->children() as $child)
		{
			if ($child->name() == 'ul') {
				$node->removeChild($child);
			}
		}
	}

	if ($node->name() == 'ul') {
		foreach ($node->children() as $child)
		{	

			if ($child->attributes('access') > $user->get('aid', 0)) {
				$node->removeChild($child);
			}
			

			
		}
	}
	if (($node->name() == 'li') && isset($node->ul)) {
		$node->addAttribute('class', 'haschild');
		$children = $node->children();
		if ($node->attributes('level') == 1) {
			if ($children[0]->name() == 'a' or $children[0]->name() == 'span') {
				$children[0]->addAttribute('class', 'haschild');
				    
					

			}
		} else {
			//if ($children[0]->name() == 'a' or $children[0]->name() == 'span') {
			if ($children[0]->name() == 'a' or $children[0]->name() == 'span') {
				$children[0]->addAttribute('class', 'child');
			}
		}
		
	}

	if (isset($path) && in_array($node->attributes('id'), $path))
	{
		if ($node->attributes('class')) {
			$node->addAttribute('class', $node->attributes('class').' active');
		} else {
			$node->addAttribute('class', 'active');
		}
	}
	else
	{
		if (isset($args['children']) && !$args['children'])
		{
			$children = $node->children();
			foreach ($node->children() as $child)
			{
				if ($child->name() == 'ul') {
					$node->removeChild($child);
				}
			}
		}
	}

	if (($node->name() == 'li') && ($id = $node->attributes('id'))) {
		if ($node->attributes('class')) {
			$node->addAttribute('class', $node->attributes('class'));
		} 
	}

	if (isset($path) && $node->attributes('id') == $path[0]) {
		$node->addAttribute('id', 'current');
	} else {
		$node->removeAttribute('id');
	}
	$node->removeAttribute('level');
	$node->removeAttribute('access');
	
	

	
}

	define('modMainMenuXMLCallbackDefined', true);
}

 if($params->_registry['_default']['data']->class_sfx == 'nav'
 || $params->_registry['_default']['data']->class_sfx == 'navd'
 || $params->_registry['_default']['data']->class_sfx == 'split'){
	modMainMenuHelperTemplate::render($params, 'modMainMenuXMLCallback');
}else{

	YJmodMainMenuHelper::YJrender($params, 'modMainMenuXMLCallback');

}
