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
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
<head>
<jdoc:include type="head" />
<?php JHTML::_('behavior.mootools');
$document->addScript($yj_site.'/src/myjaccordion.js'); ?>
    <link href="<?php echo $yj_site ?>/css/template.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $yj_site ?>/css/<?php echo $css_file; ?>.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $yj_site ?>/css/mobile/iphone.css" rel="stylesheet" type="text/css" />
    <?php if ($text_direction == 1) { ?>
		<link rel="stylesheet" href="<?php echo $yj_site ?>/css/mobile/rtl_iphone.css" type="text/css" />
        <style type="text/css">
		    body li{
            background: url(<?php echo $yj_site ?>/images/<?php echo $css_file ?>/bodyli_rtl.gif) no-repeat right 6px;
             }
		</style>
    <?php  } ?>
    <link rel="shortcut icon" href="<?php echo $yj_site ?>/favicon.ico" />
<script type="text/javascript">
	window.addEvent('domready', function() {
	new SmoothScroll({duration: 500});	
});
</script>
</head>
<body id="stylef<?php echo $default_font_family ?>">
<div id="centertop" style="font-size:12px; width:100%;">
	<?php require( TEMPLATEPATH.DS."layouts/mobile/yjsg_iphone_menu.php"); ?>
</div>
  
  <!--header-->
  <div id="header" style="font-size:<?php echo $css_font; ?>; width:100%;height:<?php echo $logo_height?>;">
    <div id="logo" class="png" style="margin:0 auto;height:<?php echo $logo_height?>;width:<?php echo $logo_width?>;">
      <h1><a href="index.php" style="height:<?php echo $logo_height?>;width:<?php echo $logo_width?>;" title="<?php echo $tags?>"><?php echo $seo ?></a> </h1>
    </div>
    <!-- end logo -->
  </div>
  <!-- end header -->
</div>
<!-- end centartop-->
<!-- BOTTOM PART OF THE SITE LAYOUT -->

<div id="centerbottom" style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width; ?>;">
<!-- pathway -->
<?php if ($this->countModules('breadcrumb')) { ?>
<div id="pathway"> You are here:&nbsp;&nbsp;
    <jdoc:include type="modules" name="breadcrumb" />
</div>
<?php } ?>
<!-- end pathway -->
<!--MAIN LAYOUT HOLDER -->
<div id="holder">
    <!-- messages -->
    <jdoc:include type="message" />
    <!-- end messages -->
  
    <!-- MID BLOCK WITH TOP AND BOTTOM MODULE POSITION -->
    <div id="midblock" style="width:100%;">
        <div class="insidem">
            <?php if ($this->countModules('mobiletop')) { ?>
            <!-- top module-->
            <div id="topmodule">
                <jdoc:include type="modules" name="mobiletop" style="yjsgxhtml" />
            </div>
            <!-- end top module-->
            <?php } ?>
            <!-- component -->
            <jdoc:include type="component"  />
            <!-- end component -->
            <?php if ($this->countModules('mobilebottom')) { ?>
            <!-- bottom module position -->
            <div id="bottommodule">
                <jdoc:include type="modules" name="mobilebottom" style="yjsgxhtml" />
            </div>
            <!-- end module position -->
            <?php } ?>
        </div>
        <!-- end mid block insidem class -->
    </div>
    <!-- end mid block div -->
    <!-- END MID BLOCK -->

</div>
<!-- end holder div -->
<!-- END BOTTOM PART OF THE SITE LAYOUT -->

</div>
<!-- end centerbottom-->

<?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_footer.php"); ?>
<div class="mobiletoptools">
<?php if (!preg_match("/iphone/i",$who)){ ?>
	<?php echo getYJLINKSM() ?> 
 <?php } ?>
<a class="changemobile" href="<?php echo JURI::base()?>index.php?change_mobile=2&amp;Itemid=1&amp;option=com_content" onclick="return confirm('<?php echo JText::_('DESKTOP MODE')?>');" title="<?php echo JText::_('MOBILE OFF')?>" ><span><?php echo JText::_('MOBILE OFF')?></span></a> </div>
</body>
</html>