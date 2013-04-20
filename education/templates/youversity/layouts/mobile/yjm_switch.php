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
<!-- switch back to mobile -->
<div class="mobiletoptools" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width.$css_widthdefined; ?>;"> <a class="changemobile" href="<?php echo JURI::base()?>index.php?change_mobile=1&amp;Itemid=1&amp;option=com_content" onClick="return confirm('<?php echo JText::_('MOBILE MODE')?>');" title="<?php echo JText::_('DESKOTP OFF')?>" ><span><?php echo JText::_('DESKOTP OFF')?></span></a> </div>
