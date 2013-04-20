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

$document->addScript($yj_site.'/src/slideshelf.js'); ?>

<?php if ($this->countModules('top1') 
 || $this->countModules('top2') 
 || $this->countModules('top3') 
 || $this->countModules('top4')
 || $this->countModules('top5'))
 { ?>
<div id="quicklinks" style="display:none; overflow:hidden; height:157px;font-size:<?php echo $css_font; ?>; width:<?php echo $css_width.$css_widthdefined; ?>;margin:0 auto;">
	<div id="quicklinks_r" style="height:157px;">
	<?php require( TEMPLATEPATH.DS."layouts/grids/yjsg1.php"); /* above header grid */ ?>
 	</div>
</div>
<div id="quick_cap">
 <a id="quick_link" href="javascript:;" onmousedown="toggleSlide('quicklinks');" >QUICKLINKS</a>
 </div>
<?php } ?>
