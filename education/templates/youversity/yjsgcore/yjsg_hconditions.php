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
		<?php if ($text_direction == 1) { ?>
			<script type="text/javascript">
				document.documentElement.style.overflowX = 'hidden';
			</script>
		<?php }?>
		<?php 	if ($isie6 == true ) { ?>
			<script type="text/javascript" src="<?php echo $yj_site ?>/src/ie6hover.js"></script>
		<?php }?>
		<?php if ( $default_menu_style == 2 && $isie6 == false ) { ?>
			<script type="text/javascript"> var YJSG_topmenu_font = '<?php echo $css_font ?>'; </script>
			<script type="text/javascript" src="<?php echo $yj_site ?>/src/mouseover<?php echo $moo_v ?>.js"></script>
			<script language="javascript" type="text/javascript">	
			window.addEvent('domready', function(){
				new SmoothDrop({
					'container':'horiznav',	
					contpoz: <?php echo preg_match("/msie 7/",$who)?>,
					horizLeftOffset: <?php echo $final_offset ?>, // submenus, left offset
					horizRightOffset: -<?php echo $final_offset ?>, // submenus opening into the opposite direction
					horizTopOffset: 20, // submenus, top offset
					verticalTopOffset:30, // main menus top offset
					verticalLeftOffset: 10, // main menus, left offset
					maxOutside: 50
				});
			});				
			</script>	
		<?php }elseif ( $default_menu_style == 4 && $isie6 == false  ) {?>
			<script type="text/javascript" src="<?php echo $yj_site ?>/src/dropd<?php echo $moo_v ?>.js"></script>
			<script type="text/javascript"> 
				var YJSG_topmenu_font = '<?php echo $css_font ?>'; 
				var SmoothDroplineParams = {
					contpoz: <?php echo preg_match("/msie 7/",$who)?>,
					horizLeftOffset: <?php echo $final_offset ?>, // submenus, left offset
					horizRightOffset: -<?php echo $final_offset ?>, // submenus opening into the opposite direction
					horizTopOffset: 20, // submenus, top offset
					verticalTopOffset: 30, // main menus top offset
					verticalLeftOffset: 10, // main menus, left offset
					maxOutside: 50
				};			
			</script>		
		<?php } ?>
		<?php if ( $default_menu_style == 3 || ( $default_menu_style == 4 && $isie6 == true )) { ?>
			<script type="text/javascript">
			window.addEvent('domready', function(){
				var dropholder = $E('div','topmenu_holder');
				$$('li.haschild').addEvent('mouseover', function() {
					dropholder.setStyle('height','74px');
				});
				
				$$('ul.level1').addEvent('mouseleave', function() {
					dropholder.setStyle('height','37px');
				});
				
				var mainList = $('horiznav').getElement('.menunavd');
				var firstRow = mainList.getChildren();
				firstRow.addEvent('mouseenter', function(){
					if( !this.hasClass('haschild') )
						dropholder.setStyle('height','37px');
				})				
			});
			</script>
		<?php } ?>
<?php } ?>
<?php if ($text_direction == 1 && preg_match("/msie 7/",$who)) { ?>
	<style type="text/css">
	.html, body {
	overflow-x: hidden!important;
	}
	.yjround {
	margin:0px auto;
	margin-left:10px!Important;  /* default, width of left corner */
 	margin-right:0!Important; 
	}
	.yjround .content {
		 padding:0px 10px 0px 0px!Important; 
	}
	.yjround .content,
	.yjround .t,
	.yjround .b,
	.yjround .b div.bin {
		 background-position:top right!Important; 
	}
	.yjround .t {
 /* top+left vertical slice */
	 position:absolute;
	 right:auto!Important; 
	 left:0px!Important; 
	 margin-left:-10px!Important; 
	 margin-right:0!Important; 
	 background-position:top left!Important; 
	}
	.yjround .b {
	 background-position:bottom right!Important; 
	}
	.yjround .b div.bin {
	 position:relative;
	 width:10px!Important; /* bottom right corner width */
	 margin-left:-10px!Important; 
 	margin-right:0!Important; 
 	background-position:bottom left!Important; 
 	float:left;
	}
	#centerbottom{
	padding:0!important;
	}
	#centerbottom_r{
		padding:0!important;
	}	
</style>
<?php } ?>
<?php 	if ($text_direction == 1 && $isie6 == true ) {?>
			<?php if($topmenu_off == 2 || $itemid == 0 ) {?>
				<style type="text/css">
					.horiznav li.sfHover ul,.horiznav li.sfHoverHas ul
					{left:auto!Important;margin:50px 0 0 0;}
			<?php } ?>
		/*rounded RTL IE6 corners*/
			.yjroundout{
			float:right!Important;
			text-align:right!Important;
			display:block;
			}
			.yjround .content,
			.yjround .t,
			.yjround .b,
			.yjround .b div.bin {
			 background-position:top 10px!Important; 
			}
			.yjround .t {
			 /* top+left vertical slice */
			 background-position:top right!Important; 
			}
			
			.yjround .b {
			 background-position:bottom 0px!Important; 
			}
			.yjround .b div.bin {
			 margin-right:-10px!Important; 
			 margin-left:0!Important; 
			 background-position:bottom right!Important; 
			}
			a.mainlevel_yjvmenu,.menu_yjvmenu a{
			 background-position:left top!important;
			}
			.menu_yjvmenu a:hover,.menu_yjvmenu li#current a,a.mainlevel_yjvmenu:hover,a#active_menu_yjvmenu{
			 background-position:left bottom!important;
			}
			a.mainlevel_yjvmenu,a.mainlevel_yjvmenu:hover,ul.menu_yjvmenu a,ul.menu_yjvmenu a:hover,.menu_yjvmenu li,a#active_menu_yjvmenu,a#active_menu_yjvmenu:hover{
			 padding-right:0px!important;
			}
			a.mainlevel_yjvmenu:hover{
			 background-position:left top!important;
			}
			/*NF CAT TITLE*/
			.yjsquare_yjnfu3 .itemdetails{
			margin-right:29%!important;
			}
			.yjsquare_yjnfu3 span.month{
			margin-left:15px;
			}
			.yjsquare_yjnfu1  a.itemtitle {
			padding-left:25px!important;
			}
			#yjsg3 #user1,#yjsg3 #user2,#yjsg3 #user3,#yjsg3 #user4,#yjsg3 #user5,
			#yjsg1 #top1,#yjsg1 #top2,#yjsg1 #top3,#yjsg1 #top4,#yjsg1 #top5,
			#yjsg5 #user11,#yjsg5 #user12,#yjsg5 #user13,#yjsg5 #user14,#yjsg5 #user15{
			background-position:left center!important;
			}
		</style>
<?php } ?>
<?php if($topmenu_off == 2 || $itemid == 0 ) {?>
	<style type="text/css">
	<?php if ( $default_menu_style == 1 ||  $default_menu_style == 2 ){ ?>
		.horiznav li li,.horiznav ul ul a, .horiznav li ul,.YJSG_listContainer{
			width:<?php echo $sub_width ?>;
		}
	<?php } ?>
	<?php if ( $default_menu_style == 3 ||  $default_menu_style == 4 ){ ?>
		.horiznav ul ul.subul_main{width:<?php echo $css_width.$css_widthdefined; ?>;}
		.horiznav ul ul.subul_main li a, .horiznav ul ul.subul_main li a:hover{width:auto;}
		.horiznav ul ul.subul_main ul,.horiznav ul ul.subul_main ul a,.horiznav ul ul.subul_main ul a:hover  {width:<?php echo $sub_width ?>;}
	<?php } ?>
	</style>
<?php } ?>
<?php if ( $isie6 == true ) { ?>
		<link href="<?php echo $yj_site?>/css/ifie.php" rel="stylesheet" type="text/css" />
		<style type="text/css">
		<?php if ($text_direction == 2 && ( $default_menu_style == 1 ||  $default_menu_style == 2) ) { ?>
		.horiznav li.sfHover ul,
		.horiznav li li.sfHover ul,
		.horiznav li li li.sfHover ul,
		.horiznav li li li li.sfHover ul,
		.horiznav li li li li li.sfHover ul ,
		.horiznav li li li li li li.sfHover ul ,
		.horiznav li li li li li li li.sfHover ul ,
		.horiznav li li li li li li li li.sfHover ul,
		.horiznav li.sfHoverHas ul,
		.horiznav li li.sfHoverHas ul,
		.horiznav li li li.sfHoverHas ul,
		.horiznav li li li li.sfHoverHas ul,
		.horiznav li li li li li.sfHoverHas ul ,
		.horiznav li li li li li li.sfHoverHas ul ,
		.horiznav li li li li li li li.sfHoverHas ul ,
		.horiznav li li li li li li li li.sfHoverHas ul
		{left:0;top:50px!important;}
		<?php } ?>
		.yjround .content,
		.yjround .t,
		.yjround .b,
		.yjround .b div {
			_background-image: url(<?php echo $yj_site?>/images/<?php echo $css_file?>/rounded_ie.gif);
		}
		</style>
<?php } ?>
<?php if($topmenu_off == 2 || $itemid == 0 ) {?>
	<?php if ($text_direction == 1) { ?>
		<style type="text/css">
		a.sublevel {
		background: url(<?php echo $yj_site ?>/images/<?php echo $css_file ?>/bodyli_rtl.gif) no-repeat 98% 9px;
		}
		body li{
		background: url(<?php echo $yj_site ?>/images/<?php echo $css_file ?>/bodyli_rtl.gif) no-repeat right 6px;
		}
		ul.subul_main li.haschild span.child a,
		ul.subul_main ul.subul_main li.haschild span.child a,
		ul.subul_main ul.subul_main ul.subul_main li.haschild span.child a,
		ul.subul_main ul.subul_main ul.subul_main ul.subul_main li.haschild span.child a{
		background-image:url(<?php echo $yj_site ?>/images/<?php echo $css_file ?>/topmenu/arrow_left.gif)!important;
		background-position:5% center!important;
		background-repeat:no-repeat;
		}
		</style>
	<?php  } ?>
<?php  } ?>
<?php if ($selectors_override == 1) { ?>
	<?php if($selectors_override_type == 1 || $selectors_override_type == 2){ ?>
		<?php echo $font_sheet ?>
   			<style type="text/css">
   			  <?php echo $affected_selectors ?>{
       			 font-family:<?php echo $nice_font ?>}
  			</style>
<?php } ?>
	<?php if ($selectors_override_type == 3){ ?>
            <script src="<?php echo $yj_site ?>/src/cufon/cufon-yui.js" type="text/javascript"></script>
            <script src="<?php echo $yj_site ?>/src/cufon/<?php echo $cufon_get_file ?>" type="text/javascript"></script>
            <script type="text/javascript">
                Cufon.replace('<?php echo $affected_selectors ?>', { fontFamily: '<?php echo $cufon_get_family?>' });
            </script>
    <?php } ?>
<?php } ?>