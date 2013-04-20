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
# FOR YOUJOOMLA LLC COPYRIGHT REMOVAL VISIT THIS PAGE 
# http://www.youjoomla.com/faq/joomla-templates-club-faq/can-i-remove-youjoomla.com-copyright-3f.html
?>
<?php
function getYJLINKS($default_font_family,$yj_copyrightear,$yj_templatename){
echo"\n<script type=\"text/javascript\">\n
	window.addEvent('domready', function() {
	new SmoothScroll({duration: 500});	
	})
</script>\n";
echo'<div class="validators"><a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3" target="_blank" title="CSS Validity" style="text-decoration: none;">CSS Valid | </a>';
echo'<a href="http://validator.w3.org/check/referer" target="_blank" title="XHTML Validity" style="text-decoration: none;">XHTML Valid | </a>';
echo'<a href="#stylef'.$default_font_family.'">Top</a></div><br />';
echo'<div class="yjsgcp">Copyright &copy; <span>'.$yj_templatename.'</span> '.$yj_copyrightear.' All rights reserved. <a href="http://www.youjoomla.com" title="Joomla Templates Club">Custom Design by Youjoomla.com </a></div>';
}
function getYJLINKSM(){
echo 'Copyright &copy; YJSimpleGrid  All rights reserved. <a href="http://www.youjoomla.com" title="Joomla Templates Club">Custom Design by Youjoomla.com </a><a class="mscroll" href="#centertop" title="Scroll to top">Top</a>';
}
?>