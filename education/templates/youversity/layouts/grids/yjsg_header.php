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
if($turn_logo_off == 1) {
$headergrid_width = 100;
}else{
$headergrid_width = 100 -$logo_out;
}
?>
<div id="yjsgheadergrid" style="width:<?php echo $headergrid_width; ?>%;">
  		<?php require( TEMPLATEPATH.DS."layouts/grids/top_search.php");/* top_search*/ ?>
		<?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_topmenu.php");/* top menu */?>
</div>