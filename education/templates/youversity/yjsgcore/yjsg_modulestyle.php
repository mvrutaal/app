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
//MODULE STYLES
$YJsg1_module_style  				= $this->params->get ("YJsg1_module_style");
$YJsgh_module_style  				= $this->params->get ("YJsgh_module_style");
$YJsg2_module_style  				= $this->params->get ("YJsg2_module_style");
$YJsg3_module_style  				= $this->params->get ("YJsg3_module_style");
$YJsg4_module_style  				= $this->params->get ("YJsg4_module_style");
$YJsgmt_module_style  				= $this->params->get ("YJsgmt_module_style");
$YJsgl_module_style  				= $this->params->get ("YJsgl_module_style");
$YJsgr_module_style  				= $this->params->get ("YJsgr_module_style");
$YJsgi_module_style  				= $this->params->get ("YJsgi_module_style");
$YJsgit_module_style  				= $this->params->get ("YJsgit_module_style");
$YJsgib_module_style  				= $this->params->get ("YJsgib_module_style");
$YJsgmb_module_style  				= $this->params->get ("YJsgmb_module_style");
$YJsg5_module_style  				= $this->params->get ("YJsg5_module_style");
$YJsg6_module_style  				= $this->params->get ("YJsg6_module_style");
$YJsg7_module_style  				= $this->params->get ("YJsg7_module_style");
//$YJsg7_module_style  				= $this->params->get ("YJsg7_module_style"); // change this param to your grid number and 
//uncomment the top line by removing first 2 forward slashes

// FIND OUT IF WE SHOULD USE ROUNDED.CSS
$mod_round_style 					= array(
$YJsg1_module_style,$YJsgh_module_style,$YJsg2_module_style,
$YJsg3_module_style,$YJsg4_module_style,$YJsgmt_module_style,$YJsgl_module_style,
$YJsgr_module_style,$YJsgi_module_style,$YJsgit_module_style,$YJsgib_module_style,
$YJsgmb_module_style,$YJsg5_module_style,$YJsg6_module_style,$YJsg7_module_style 
//,$YJsg7_module_style // change this param to your grid number and uncomment the line by removing first 2 forward slashes. WATCH THE COMMA!
);
?>