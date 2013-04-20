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
?>
<?php if ($text_direction == 1) { ?>
<style type="text/css">
.horiznav li ul ul,.subul_main.group_holder ul.subul_main ul.subul_main, .subul_main.group_holder ul.subul_main ul.subul_main ul.subul_main, .subul_main.group_holder ul.subul_main ul.subul_main ul.subul_main ul.subul_main,.horiznav li li li:hover ul.subul_main.dropline<?php if($isie6 == true ){ ?>
,.horiznav li li.sfHover ul,
.horiznav li li li.sfHover ul,
.horiznav li li li li.sfHover ul,
.horiznav li li li li li.sfHover ul ,
.horiznav li li li li li li.sfHover ul ,
.horiznav li li li li li li li.sfHover ul ,
.horiznav li li li li li li li li.sfHover ul,
.horiznav li li.sfHoverHas ul,
.horiznav li li li.sfHoverHas ul,
.horiznav li li li li.sfHoverHas ul,
.horiznav li li li li li.sfHoverHas ul ,
.horiznav li li li li li li.sfHoverHas ul ,
.horiznav li li li li li li li.sfHoverHas ul ,
.horiznav li li li li li li li li.sfHoverHas ul<?php } ?>{
margin-right:<?php echo $yjsg_menu_offset ?>%!important;
<?php if ((preg_match("/msie 7/",$who) || $isie6 == true ) && ($default_menu_style == 3 ||  $default_menu_style == 4)) { ?>
	margin-top:0px!important;
<?php }elseif($isie6 == true && ($default_menu_style == 2 || $default_menu_style == 1)){ ?>
	margin-top: 0px!important;
<?php }else{ ?>
	margin-top: -32px!important;
<?php } ?>
}
</style>
<?php }else{ ?>
<style type="text/css">
.horiznav li ul ul,.subul_main.group_holder ul.subul_main ul.subul_main, .subul_main.group_holder ul.subul_main ul.subul_main ul.subul_main, .subul_main.group_holder ul.subul_main ul.subul_main ul.subul_main ul.subul_main,.horiznav li li li:hover ul.dropline<?php if($isie6 == true ){ ?>
,.horiznav li li li.sfHover ul,
.horiznav li li li li.sfHover ul,
.horiznav li li li li li.sfHover ul ,
.horiznav li li li li li li.sfHover ul ,
.horiznav li li li li li li li.sfHover ul ,
.horiznav li li li li li li li li.sfHover ul,
.horiznav li li li li li li li li li.sfHover ul,
.horiznav li li li li li li li li li li.sfHover ul,
.horiznav li li li.sfHoverHas ul,
.horiznav li li li li.sfHoverHas ul,
.horiznav li li li li li.sfHoverHas ul ,
.horiznav li li li li li li.sfHoverHas ul ,
.horiznav li li li li li li li.sfHoverHas ul ,
.horiznav li li li li li li li li.sfHoverHas ul,
.horiznav li li li li li li li li li.sfHoverHas ul,
.horiznav li li li li li li li li li li.sfHoverHas ul<?php } ?>{
	margin-top: -32px!important;
	margin-left:<?php echo $yjsg_menu_offset ?>%!important;
<?php if($isie6 == true && ($default_menu_style == 3 || $default_menu_style == 4)){ ?>
	left:0;
	margin-top:0px!important;
<?php } ?>
}
</style>
<?php } ?>