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
<div class="mobiletoptools">
    <?php if (!preg_match("/iphone/i",$who)){ ?><div class="title"><?php echo $page_title?></div><?php }?>
	<div id="mmholder">
		<div id="mobmenu">
		  <div class="YJ_mtoggler"><span>Menu</span></div>
		</div>

		<?php if ($this->countModules('mobilelogin')) { ?> 
		<div id="moblogin">
		  <div class="YJ_mtoggler"><span>Login</span></div>
		</div><?php  } ?>
		<?php if($mobile_reg == 1 ) { ?>
		<div id="mobreg">
		  <div class="YJ_mtoggler"><span>Register</span></div>
		</div><?php  } ?>
		
				<?php if ($this->countModules('mobilesearch')) { ?> 
		<div id="mobsearch">
		  <div class="YJ_mtoggler"><span>Search</span></div>
		</div>  <?php  } ?>
	</div>
    <a class="changemobile" href="<?php echo JURI::base()?>index.php?change_mobile=2&amp;Itemid=1&amp;option=com_content" onclick="return confirm('<?php echo JText::_('DESKTOP MODE')?>');" title="<?php echo JText::_('MOBILE OFF')?>" ><span><?php echo JText::_('MOBILE OFF')?></span></a> </div>
  <div class="YJ_mparams" style="display:none;">
    <div id="mobilemenu"> <?php echo $topmenu; ?> </div>
  </div>
  	<?php if ($this->countModules('mobilelogin')) { ?> 
	  <div class="YJ_mparams" style="display:none;">
		<div class="mobtoolelements">
		  <jdoc:include type="modules" name="mobilelogin" style="raw" />
		</div>
	  </div>
	<?php  } ?>
  <?php if($mobile_reg == 1 ) { ?>  
  <div class="YJ_mparams" style="display:none;">
   <div class="mobtoolelements">
    <?php require( TEMPLATEPATH.DS."yjsgcore/yjsg_register.php");?>
	</div>
  </div>
  <?php  } ?>
<?php if ($this->countModules('mobilesearch')) { ?> 
  <div class="YJ_mparams" style="display:none;">
     <jdoc:include type="modules" name="mobilesearch" style="raw" />
    </div>
<?php } ?>