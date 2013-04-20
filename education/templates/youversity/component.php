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
defined( '_JEXEC' ) or die( 'Restricted access' );
define( 'TEMPLATEPATH', dirname(__FILE__) );
require( TEMPLATEPATH.DS."yjsgcore/yjsg_core.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />
<?php if ($compress == 0){ ?>
		<link href="<?php echo $yj_site ?>/css/template.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $yj_site ?>/css/<?php echo $css_file; ?>.css" rel="stylesheet" type="text/css" />
<?php }elseif ($compress == 1){ ?>
		<link href="<?php echo $yj_site ?>/css/compress.php" rel="stylesheet" type="text/css" />
<?php  } ?>

<?php if ($text_direction == 1) { ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/template_rtl.css" type="text/css" />
<?php  } ?>
</head>
<body class="contentpane">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
