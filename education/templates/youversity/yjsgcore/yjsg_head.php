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
defined( '_JEXEC' ) or die( 'Restricted index access' ); ?>
<jdoc:include type="head" />
<?php JHTML::_('behavior.mootools'); echo $add_jq .$add_jq_noc?>
<?php if ($compress == 0){ ?>
		<link href="<?php echo $yj_site ?>/css/template.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $yj_site ?>/css/<?php echo $css_file; ?>.css" rel="stylesheet" type="text/css" />
<?php }elseif ($compress == 1){ ?>
		<link href="<?php echo $yj_site ?>/css/compress.php" rel="stylesheet" type="text/css" />
<?php  } ?>
<?php if ($yjsg_mobile){?>
		<link href="<?php echo $yj_site ?>/css/mobile/iphone.css" rel="stylesheet" type="text/css" />
<?php }?>
<?php if (in_array('YJsground', $mod_round_style)){ ?>
		<link href="<?php echo $yj_site ?>/css/rounded.css" rel="stylesheet" type="text/css" />
<?php } ?>

<?php if ( $default_menu_style == 3 ||  $default_menu_style == 4 ){ ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/dropline<?php echo $dropline ?>.css" type="text/css" />
<?php  } ?>

<?php if ($text_direction == 1) { ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/template_rtl.css" type="text/css" />
		<?php if (preg_match("/chrome/",$who) || preg_match("/safari/",$who)) { ?><?php }else{ ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/menu_rtl.css" type="text/css" />
		<?php  } ?>
<?php  } ?>
<?php require_once( TEMPLATEPATH.DS."yjsgcore/yjsg_hconditions.php"); ?>
<?php require_once( TEMPLATEPATH.DS."yjsgcore/yjsg_menuoffsets.php"); ?>
<?php if ($text_direction == 1 && $isie6 == true && ($default_menu_style == 3 ||  $default_menu_style == 4)) { ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/droplineie6-rtl.css" type="text/css" />
<?php  } ?>
<?php if ($text_direction == 1 && preg_match("/msie 7/",$who) && ($default_menu_style == 3 ||  $default_menu_style == 4)) { ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/droplineie7-rtl.css" type="text/css" />
<?php  } ?>
<?php if ($custom_css   == 1) { ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/custom.css" type="text/css" />
<?php  } ?>