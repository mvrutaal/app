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
<?php if($topmenu_off == 2 || $itemid == 0 ) {?>
    <!--top menu-->
<div id="topmenu_holder">
    <div class="top_menu" style="font-size:<?php echo $css_font; ?>;">
        <div id="horiznav" class="horiznav<?php if ($text_direction == 1) { ?> horiz_rtl<?php } ?>"><?php echo $topmenu; ?></div>
    </div>
</div>
    <!-- end top menu -->
<?php } ?>