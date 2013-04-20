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
?>
<!-- footer -->
<div id="footer"  style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width.$css_widthdefined; ?>;">
  <div id="youjoomla">
    <?php if ($this->countModules('footer')) { ?>
        <div id="footmod">
            <jdoc:include type="modules" name="footer" style="YJsgplain" />
        </div>
	<?php } ?>
	<?php if (!preg_match("/iphone/i",$who)){ ?>
    	<div id="cp">
		<?php echo getYJLINKS($default_font_family,$yj_copyrightear,$yj_templatename)  ?>
			<?php if ($show_tools == 1): ?>
                <?php include TEMPLATEPATH.DS."yjsgcore/yjsg_tools.php";/* site tools , font resizer , rtl switch */?>
            <?php endif;?>

			<?php if ($branding_off == 2) { ?>
                <a class="yjsglogo png" href="http://yjsimplegrid.com/" target="_blank">
					<span>YJSimpleGrid Joomla! Templates Framework official website</span>
                </a>
			<?php } ?>
		<?php } ?>
       </div>
  </div>
</div>
<!-- end footer -->
<?php if (!preg_match("/iphone/i",$who)){ ?>
	<?php if ($joomlacredit_off ==2): ?>
		<div id="joomlacredit"  style="font-size:<?php echo $css_font; ?>; width:<?php echo $css_width.$css_widthdefined; ?>;">
			<a href="http://www.joomla.org" target="_blank">Joomla!</a> is Free Software released under the 
			<a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU/GPL License.</a>
		</div>
	<?php endif; ?>
<?php } ?>

<?php if ($selectors_override_type == 3 && $selectors_override == 1){ ?>
<script type="text/javascript"> Cufon.now(); </script>
<?php } ?>
<?php echo $add_ga ?>