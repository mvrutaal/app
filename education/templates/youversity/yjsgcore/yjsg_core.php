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
defined( '_JEXEC' ) or die( 'Restricted index access' ); 
// XSS PROTECTION
$_GET = preg_replace("|([^\w\s\'])|i",'',$_GET);
//$_POST = preg_replace("|([^\w\s\'])|i",'',$_POST);
// BROWSER RECOGNITION
$who = strtolower($_SERVER['HTTP_USER_AGENT']);
// USABLE VARS 
$yj_site = JURI::base()."templates/".$this->template;          //  Current template folder as http://www.site_name/templates/template_name
$yj_base = JURI::base();                                       //  Site path as  http://www.site_name
$yj_copyrightear = (Date("Y"));                                //  Current Copyright year in footer
$yj_templatename = $this->template;                            //  Get template name
$document	     = &JFactory::getDocument();
// STYLE SETTINGS
$default_color			             = $this->params->get("default_color"); 
$default_font			             = $this->params->get("default_font"); 
$default_font_family			     = $this->params->get("default_font_family");
$selectors_override			         = $this->params->get("selectors_override");
$css_font_family			         = $this->params->get("css_font_family");
$google_api				             = $this->params->get("google_api");
$google_font_family			         = $this->params->get("google_font_family");
$cufon_font_family     				 = $this->params->get("cufon_font_family");
$css_width            				 = $this->params->get("css_width");
$css_widthdefined    				 = $this->params->get ("css_widthdefined");
$text_direction       				 = $this->params->get("text_direction");
$selectors_override_type			 = $this->params->get("selectors_override_type");
$affected_selectors     			 = $this->params->get ("affected_selectors");
$custom_css    		            	 = $this->params->get ("custom_css");
//TOOLS CONTROL
// SHOW FONT SWITCH = 1 | HIDE FONT SWITCH = 0
$show_tools    					     = $this->params->get("show_tools");
$show_fres    					     = $this->params->get("show_fres");
$show_rtlc    					     = $this->params->get("show_rtlc");
// LAYOUT
$site_layout    			        = $this->params->get("site_layout");

// MOBILE 
$site_mobile     				    = $this->params->get("site_mobile");
$iphone_default     		        = $this->params->get("iphone_default");
$android_default                    = $this->params->get("android_default");
$handheld_default                   = $this->params->get("handheld_default");
$mobile_reg                   		= $this->params->get("mobile_reg");

//MENY TYPE 
// mainmenu by default, can be any Joomla! menu name
$menu_name      			        = $this->params->get("menuName");
$sub_width 							= $this->params->get("sub_width");
$yjsg_menu_offset					= $this->params->get("yjsg_menu_offset");
//MENU STYLE SWITCH
//  1 = Suckerfish  | 2  = SMooth Dropdown | 3 = Dropline Menu | 4 SmoothDropline menu |  5  = Split Menu 
$default_menu_style  			    = $this->params->get("default_menu_style"); 


// Logo and header block settings
$logo_height                        = $this->params->get("logo_height");
$logo_width 						= $this->params->get("logo_width");
$turn_logo_off 						= $this->params->get("turn_logo_off");
$turn_header_block_off				= $this->params->get("turn_header_block_off");
$logo_out  							= $logo_width/10;

// 1 = TURN PHP COMPRESSION ON  |  0 = TURN PHPCOMPRESSION OFF 
$compress                           = $this->params->get("compress");	 // 1 = TURN COMPRESSION ON  |  0 = TURN COMPRESSION OFF 


// SEO SECTION //
$seo              				    = $this->params->get ("seo");                     
$tags             				    = $this->params->get ("tags");
$turn_seo_off   		            = $this->params->get ("turn_seo_off");
$ie6notice        			        = $this->params->get("ie6notice"); // 1 = ON | 0 = OFF   


// ADVISE VISITORS THAT THIR JAVASCRIPT IS DISABLED
$nonscript           		        = $this->params->get("nonscript"); // 1 = ON | 0 = OFF 


// ADD JQUERY 
$jq_switch  			    		= $this->params->get ("jq_switch");

// ADD GOOGLE ANALYTICS

$ga_switch  			    		= $this->params->get ("ga_switch");
$GAcode								= $this->params->get ("GAcode");
//FPNI CONTROL
$fp_controll_switch 		        = $this->params->get ("fp_controll_switch");
$fp_chars_limit             		= $this->params->get ("fp_chars_limit");
$fp_after_text              		= $this->params->get ("fp_after_text ");



// YJSimpleGrid LOGO
$branding_off  						= $this->params->get ("branding_off");
$joomlacredit_off  					= $this->params->get ("joomlacredit_off");

require( TEMPLATEPATH.DS."yjsgcore/yjsg_modulestyle.php");


// tp controll
$remove_tp          				= $this->params->get ("remove_tp");
if ($remove_tp == 1){
	JRequest::setVar('tp',0);
}



// widths 
$leftcolumn            				= $this->params->get ("leftcolumn");
$rightcolumn            			= $this->params->get ("rightcolumn"); 
$maincolumn             			= $this->params->get ("maincolumn"); 
$insetcolumn           				= $this->params->get ("insetcolumn");
$widthdefined           			= $this->params->get ("widthdefined");

// widths on specific item id
$leftcolumn_itmid                   = $this->params->get ("leftcolumn_itmid");
$rightcolumn_itmid                  = $this->params->get ("rightcolumn_itmid"); 
$maincolumn_itmid                   = $this->params->get ("maincolumn_itmid"); 
$insetcolumn_itmid                  = $this->params->get ("insetcolumn_itmid");
$widthdefined_itmid                 = $this->params->get ("widthdefined_itmid");



//START COLLAPSING THAT MODULE:)
$left                   			= $this->countModules( 'left' );
$right                  			= $this->countModules( 'right' );
$inset                  			= $this->countModules( 'inset' );

require( TEMPLATEPATH.DS."yjsgcore/yjsg_mgwidths.php");

$itemid 							= JRequest::getVar('Itemid');
$define_itemid             			= $this->params->get ("define_itemid");
$component_switch          			= $this->params->get ("component_switch");
$turn_topmenu_off          			= $this->params->get("turn_topmenu_off");


//COMPONENT OFF SWITCH
if( is_array( $component_switch ) )
 	 $turn_component_off = in_array( $itemid, $component_switch ) ? 1 : 2 ;
else 
 	 $turn_component_off = $itemid == $component_switch ? 1 : 2;

//TOPMENU OFF SWITCH
if( is_array( $turn_topmenu_off ) )
 	 $topmenu_off = in_array( $itemid, $turn_topmenu_off ) ? 1 : 2 ;
else 
 	 $topmenu_off = $itemid == $turn_topmenu_off ? 1 : 2;
	 
// SPECIFIC ITEM ID WIDTHS	 
$countitems = count( $define_itemid );
if ($countitems > 1){

	if( in_array($itemid,$define_itemid)){
		$left                   	= $this->countModules( 'left' );
		$right                  	= $this->countModules( 'right' );
		$inset                  	= $this->countModules( 'inset' );
			if ( $left  ||  $right || $inset ) {
				require( TEMPLATEPATH.DS."yjsgcore/yjsg_mgwidthsitem.php");
			}
	}
}elseif ($countitems < 2) {
	$items = explode('|',$define_itemid);
		if( in_array($itemid, $items) ){
			$left 					= $this->countModules( 'left' );
			$right 					= $this->countModules( 'right' );
			$left                   = $this->countModules( 'left' );
			$right                  = $this->countModules( 'right' );
			$inset                  = $this->countModules( 'inset' );
			require( TEMPLATEPATH.DS."yjsgcore/yjsg_mgwidthsitem.php");
		}
}
// CSS FOR COM_USER ONLY
if (JRequest::getCmd( 'option' ) == 'com_user'){
		$document->addStyleSheet($yj_site.'/css/user_pages.css');
}


//TOP MENU 
    require( TEMPLATEPATH.DS."yjsgcore/yjsg_stylesw.php");	
	$renderer	 = $document->loadRenderer( 'module' );
	$options	 = array( 'style' => "raw" );
	$module	     = JModuleHelper::getModule( 'mod_mainmenu' );
	$topmenu     = false; $subnav = false; $sidenav = false;
// SUCKERFISH OR MOO
	if ($default_menu_style == 1 or $default_menu_style== 2) :
			$module->params	= "menutype=$menu_name\nshowAllChildren=1\nclass_sfx=nav\nmenu_images=n";
			$topmenu = $renderer->render( $module, $options );
			$menuclass = 'horiznav';
			$topmenuclass ='top_menu';
		//echo $mainframe->getPageTitle() ;
// DROPLINE

	elseif ($default_menu_style == 3 or $default_menu_style== 4) :
			$module->params	= "menutype=$menu_name\nshowAllChildren=1\nclass_sfx=navd\nmenu_images=n";
			$topmenu = $renderer->render( $module, $options );
			$menuclass = 'horiznav_d';
			$topmenuclass ='top_menu_d';
// SPLIT MENU  NO SUBS
	elseif ($default_menu_style == 5) :
			$module->params	= "menutype=$menu_name\nstartLevel=0\nendLevel=1\nclass_sfx=split\nmenu_images=n";
			$topmenu = $renderer->render( $module, $options );
			$menuclass = 'horiznav';
			$topmenuclass ='top_menu';
	endif;

// FIND SPECIFIC MOBILES
$iphones 							= preg_match("/ipad/i",$who) || preg_match("/ipod/i",$who) ||preg_match("/iphone/i",$who);
$handhelds 							= preg_match("/opera mini/i",$who) ||preg_match("/blackberry/i",$who) ||preg_match("/(pre\/|palm 			os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i",$who) ||preg_match("/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; 			iemobile)/i",$who);
$android 							= preg_match("/android/i",$who);

//FIND ALL MOBILES
$yjsg_mobile 						= preg_match("/ipad/i",$who) // iPad
									|| preg_match("/ipod/i",$who) // iPod
									|| preg_match("/iphone/i",$who) // iPhone
									|| preg_match("/android/i",$who) // Android
									|| preg_match("/opera mini/i",$who) // Opera Mini
									|| preg_match("/blackberry/i",$who) // Blackberry
									|| preg_match("/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i",$who) // Palm
									|| preg_match("/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i",$who) // Windows Smartphone 
;

// TEMPLATE REQUIRED FILES

require( TEMPLATEPATH.DS."yjsgcore/yjsg_fontapi.php");
require( TEMPLATEPATH.DS."yjsgcore/yjsg_scripts.php");
require( TEMPLATEPATH.DS."yjsgcore/yjsg_links.php");

// DEFAULT SITE FILE
$yjsg_getmain 					   = $site_mobile;


// discover IE 6 only
if (preg_match( "/msie 6.0/",$who)){
			$isie6 =true;
			$dropline ='ie6';
}else{
			$isie6 =null;
			$dropline ='';
}
/* Calculate offset  percent value for YJ Mega Menu */
$offset_value = ($sub_width / 100) * $yjsg_menu_offset;
$final_offset = number_format($sub_width - $offset_value + 10,0, '.', '') ;

/*check if mooo 1.2 is on  */
		if (JPluginHelper::getPlugin('system', 'mtupgrade')) :
			$moo_v = '12';
		else:
			$moo_v = '';
		endif;
// remove all midblock divs if no items ,no columns, no links, no modules on specific itemid
$comcontent =& JComponentHelper::getParams('com_content');
 if (!$this->countModules('bodytop1') &&
 !$this->countModules('bodytop2') &&
 !$this->countModules('bodytop3') &&
 !$this->countModules('bodybottom1') &&
 !$this->countModules('bodybottom2') &&
 !$this->countModules('bodybottom3') &&
 !$this->countModules('left') &&
 !$this->countModules('right') &&
 !$this->countModules('inset') &&
 !$this->countModules('insettop') &&
 !$this->countModules('insetbottom')&& 
 $comcontent->get('num_leading_articles') == 0 && 
 $comcontent->get('num_intro_articles') == 0 && 
 $comcontent->get('num_columns') == 0 && 
 $comcontent->get('num_links') == 0 && 
 ($itemid == $component_switch || $turn_component_off == 1) && JRequest::getCmd( 'Itemid' ) !=='' ) {	 
	$midblock_off = true;
	
}else{
	$midblock_off = false;
}
//INSERT CUSTOM TEMPLATE PARAMS
require( TEMPLATEPATH.DS."yjsgcore/yjsg_custom_params.php");
?>