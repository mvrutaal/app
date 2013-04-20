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
$app =& JFactory::getApplication();
JLoader::register('YJSGparams', JPATH_THEMES.DS.$app->getTemplate().DS.'/yjsgcore/yjsg_params.php');
$template = $this->template;
$default_color                = YJSGparams::YJSGparam()->get("default_color"); 
$default_font_family          = YJSGparams::YJSGparam()->get("default_font_family");
$logo_height                  = YJSGparams::YJSGparam()->get("logo_height");
$logo_width                   = YJSGparams::YJSGparam()->get("logo_width");
$css_width                    = YJSGparams::YJSGparam()->get("css_width");
$default_font                 = YJSGparams::YJSGparam()->get("default_font");
$css_widthdefined    		  = YJSGparams::YJSGparam()->get("css_widthdefined");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->error->code ?> - <?php echo $this->title; ?></title>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $template ?>/css/template.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $template ?>/css/<?php echo $default_color; ?>.css" rel="stylesheet" type="text/css" />
<style type="text/css">
/* error page*/
#sitelogo{
background-color:#121212;
}
#errorpage{
margin:0 auto;
width:500px;
background:#fff;
overflow:hidden;
display:block;
padding:10px;
border:1px solid #c9c9c9;
}
.error_title{
font-family: Cambria, serif;
font-weight:bold;
padding:25px 0;
}
.error_title h1{
font-size:48px;
line-height:17px;
}
.error_title h2{
font-size:32px;
line-height:32px;
}
#errorol{
width:480px;
margin:0 auto;
text-align:left;
background:#FFFFCC;
border:1px solid #FFDA2F;
padding:10px;
font-size:14px;
}
p.errorp{
padding:5px 10px;
border-bottom:1px dashed #DFDFDF;
text-align:left;
}
p.error_contact{
padding:5px 10px;
background:#FFFFCC;
border:1px dashed #FFDA2F;
font-weight:bold;
color:#BF6700;
}
.error_link{
text-align:left;
text-decoration:underline;
font-weight:bold;
}
p.error_msg{
border:1px dashed #FFDA2F;
padding:5px;
font-size:15px;
font-weight:bold;
}
</style>
</head>
<body id="stylef<?php echo $default_font_family ?>">

<div id="centertop" style="font-size:12px; width:<?php echo $css_width.$css_widthdefined ; ?>; margin:0 auto; text-align:center;">
	<div id="errorpage">	
         <div id="header" class="png" style="margin:0 auto; text-align:center;float:none;height:<?php echo $logo_height?>;width:100%;">
        <img src="<?php echo $this->baseurl; ?>/templates/<?php echo $template ?>/images/<?php echo $default_color; ?>/logo.png" alt="site_logo" />
        </div>
		<div class="error_title"><h1><?php echo $this->error->code ?></h1><h2><?php echo $this->error->message ?></h2></div>
		
			<p class="errorp"><strong><?php echo JText::_('You may not be able to visit this page because of:'); ?></strong></p>
				<ol id="errorol">
					<li><?php echo JText::_('An out-of-date bookmark/favourite'); ?></li>
					<li><?php echo JText::_('A search engine that has an out-of-date listing for this site'); ?></li>
					<li><?php echo JText::_('A mis-typed address'); ?></li>
					<li><?php echo JText::_('You have no access to this page'); ?></li>
					<li><?php echo JText::_('The requested resource was not found'); ?></li>
					<li><?php echo JText::_('An error has occurred while processing your request.'); ?></li>
				</ol>
			<p class="errorp"><strong><?php echo JText::_('Please try one of the following pages:'); ?></strong></p>
			
				<ol class="error_link">
					<li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('Go to the home page'); ?>"><?php echo JText::_('Home Page'); ?></a></li>
				</ol>
			
			<p class="error_contact"><?php echo JText::_('If difficulties persist, please contact the system administrator of this site.'); ?></p>
			
			<p class="error_msg"><?php echo $this->error->message; ?></p>
			<p><?php if($this->debug) : echo $this->renderBacktrace();	endif; ?></p>
            </div>
</div>
</body>
</html>
