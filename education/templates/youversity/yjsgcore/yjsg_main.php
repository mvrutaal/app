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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_head.php");/* <head> files containing css , js and php */?>
</head>
<body id="stylef<?php echo $default_font_family ?>">
<?php if ($yjsg_mobile):/*back to mobile view visible on mobile devices*/
	require( TEMPLATEPATH.DS."layouts/mobile/yjm_switch.php");
 endif; ?>
 
 
<?php require( TEMPLATEPATH.DS."layouts/grids/quicklinks.php"); /* quicklinks */ ?>
<div id="header_holder">
    <div class="pre_center" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width.$css_widthdefined; ?>;"> 
            <?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_headerblock.php");/* header - header grid located in this file */?>
    </div>  
</div>
<div id="centertop" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width.$css_widthdefined; ?>;">
	<?php require( TEMPLATEPATH.DS."layouts/grids/yjsg2.php");/* grid 2 adv1-adv5*/ ?>
    <?php require( TEMPLATEPATH.DS."layouts/grids/yjsg3.php");/*grid 3 user1-user5*/ ?>
</div>
<!-- end centartop-->
<div id="centerbottom" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width.$css_widthdefined; ?>;">
	<div id="centerbottom_r">
		<?php require( TEMPLATEPATH.DS."layouts/grids/yjsg4.php");/* grid4 user6-user10*/ ?>
		<?php require( TEMPLATEPATH.DS."layouts/layout".$site_layout.".php");/* mid grid - mainbody grids located in layout1.php - layout3.php*/?>
		<?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_pathway.php");/* pathway including breadcrumb module position */ ?>
		<?php require( TEMPLATEPATH.DS."layouts/grids/yjsg5.php");/* grid 5 user11-user15*/ ?>
	</div>
</div>
<!-- end centerbottom-->
<div id="big_gridholder">
   <?php require( TEMPLATEPATH.DS."layouts/grids/yjsg6.php");/* grid 6 user16-user20*/ ?>
   <?php require( TEMPLATEPATH.DS."layouts/grids/yjsg7.php");/* grid 7 user21-user25*/ ?>
</div>  
<div id="footer_holder"> 
    <?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_footer.php");/* footer -  footer menu , copyright , YJSG logo , validation links*/?>
    <?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_notices.php");/* IE6 and nonscript notices*/?>
</div>   
</body>
</html>