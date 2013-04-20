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
defined( '_JEXEC' ) or die( 'Restricted index access' );?>
<?php if ($midblock_off == false ) { ?>
<!--MAIN LAYOUT HOLDER -->
<div id="holder3">
  <!-- messages -->
  <jdoc:include type="message" />
  <!-- end messages -->
  <!-- MID BLOCK -->
  <div id="midblock" style="width:<?php echo $midblock ?>;">
    <div class="insidem">
      <?php require( TEMPLATEPATH.DS."layouts/grids/yjsg_bodytop.php"); ?>
      <?php if($turn_component_off == 2 || $itemid == 0 ) {?>
      <!-- component -->
      <jdoc:include type="component"  />
      <!-- end component -->
      <?php } ?>
      <?php require( TEMPLATEPATH.DS."layouts/grids/yjsg_bodybottom.php"); ?>
	  <div class="clearm"></div>
    </div>
    <!-- end mid block insidem class -->
  </div>
  <!-- end mid block div -->
<?php if ($this->countModules('insettop')) { ?>
	<?php if ($this->countModules('inset') || $this->countModules('left') || $this->countModules('right')) { ?>
  <div id="insetsholder_3t" style="width:<?php echo $insettop ?>;">
    <div class="inside">
      <jdoc:include type="modules" name="insettop" style="<?php echo $YJsgit_module_style ?>" />
    </div>
  </div>
  <?php } ?>
 <?php } ?>
  <?php if ($this->countModules('left')) { ?>
  <!-- left block -->
  <div id="leftblock" style="width:<?php echo $leftblock ?>;">
    <div class="inside">
      <jdoc:include type="modules" name="left" style="<?php echo $YJsgl_module_style ?>" />
    </div>
  </div>
  <!-- end left block -->
  <?php } ?>
  <!-- END MID BLOCK -->
  <?php if ($this->countModules('right')) { ?>
  <!-- right block -->
  <div id="rightblock" style="width:<?php echo $rightblock ?>;">
    <div class="inside">
      <jdoc:include type="modules" name="right" style="<?php echo $YJsgr_module_style ?>" />
    </div>
  </div>
  <!-- end right block -->
  <?php } ?>
  <?php if ($this->countModules('inset')) { ?>
  <!-- inset block -->
  <div id="insetblock" style="width:<?php echo $insetblock ?>;">
    <div class="inside">
      <jdoc:include type="modules" name="inset" style="<?php echo $YJsgi_module_style ?>" />
    </div>
  </div>
  <!-- end inset block -->
  <?php } ?>
<?php if ($this->countModules('insetbottom')) { ?>
  <?php if ($this->countModules('inset') || $this->countModules('left') || $this->countModules('right')) { ?>
  <div id="insetsholder_3b" style="width:<?php echo $insettop ?>;">
    <div class="inside">
      <jdoc:include type="modules" name="insetbottom" style="<?php echo $YJsgib_module_style ?>" />
    </div>
  </div>
  <?php } ?>
<?php } ?>
</div>
<!-- end holder div -->
<?php } ?>