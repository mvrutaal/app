<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla LLC - YJ- Licence Number 3850LE193
|| # Licensed to - Tanya Eldert
|| # ---------------------------------------------------------------- # ||
|| # Copyright (c) 2006-2009 Youjoomla LLC. All Rights Reserved.        ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- THIS IS NOT FREE SOFTWARE ---------------- #      ||
|| # http://www.youjoomla.com | http://www.youjoomla.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
	defined('_JEXEC') or die('Direct Access to this location is not allowed.');
	$who = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser they use.
	/**
	 * Parameters
	 */
	$class_fx = $params->get('moduleclass_sfx');
	$swidth = $params->get('swidth');
	$sheight = $params->get('sheight');
	$sorient = $params->get('sorient');
	$start_slide = $params->get('start_slide');
	$type_slider = $params->get('type_slider');
	$stime = $params->get('stime');
	$sduration = $params->get('sduration');
	$smenu = $params->get('smenu');
	$pagination = $params->get('pagination');
	$balons = $params->get('balons');
	$is_copy = $params->get('is_copy');
	$order = $params->get('order');
	$yjspacer = $params->get('yjspacer');
  
  	echo "<!-- http://www.Youjoomla.com  Image Slider V3 for Joomla 1.6 starts here -->	";
 	/**
 	 * Load scripts and stylesheets
 	 */
	JHTML::_('behavior.mootools');
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::base() . 'modules/mod_yjis3/css/mod_yjis312.css');
	if(preg_match("/msie 6/", $who)) {
	$document->addStyleSheet(JURI::base() . 'modules/mod_yjis3/css/ifie12.php');
	}
	$document->addScript(JURI::base() . 'modules/mod_yjis3/src/mod_yjis312.js');

  	/**
  	 * display or disable navigation according to settings
  	 */
	$fwd = $smenu == 0 ? 'YJS_right':'null';
	$bkwd = $smenu == 0 ? 'YJS_left':'null';
	/**
	 * prepare js class parameters
	 */
	$document->addScriptDeclaration("		
		window.addEvent('load', function(){
			new YJSlide({
				outerContainer : 'YJSlide_outer$is_copy',
				innerContainer : 'YJSlide_inner$is_copy',
				elements: '.YJSlide_slide',
				navigation: {
					'forward':'$fwd$is_copy', 
					'back':'$bkwd$is_copy'
				},
				slideType: $type_slider,
				orientation: $sorient,
				slideTime: $stime,
				duration: $sduration,
				tooltips: $balons,
				autoslide: $start_slide,
				navInfo: 'YJS_nav_info$is_copy',
				navLinks: '.YJS_navLink$is_copy' 
			});
		});
	");

	
	/**
	 * slides order
	 */
	$show_order = range(0,19);
	if($order == 1)
	{
		srand((float)microtime()*1000000);
  		shuffle($show_order); 
	}
	/**
	 * Slides info
	 */
	$slides = array();
  	foreach ($show_order as $i)
  	{
  		$image = $params->get('slide_image_'.($i+1));
  		if (empty($image) || $image==-1)  continue;	

  		$slide = array(
  			'title' => $params->get('slide_title_'.($i+1)),
  			'intro' => $params->get('slide_intro_'.($i+1)),
  			'image' => $image,
			'mbox' => $params->get('mbox_'.($i+1)),
			'mboxlink' => $params->get('mboxlink_'.($i+1)),
			'mboxtype' => $params->get('mboxtype_'.($i+1)),
			'video_width' => $params->get('video_width_'.($i+1)),
			'video_height' => $params->get('video_height_'.($i+1)),
			'image_group' => $params->get('image_group_'.($i+1)),
			'image_group_name' => $params->get('image_group_name_'.($i+1)),
			'link' => $params->get('slide_link_'.($i+1)),
  			'open' => $params->get('slide_open_'.($i+1)),
  			'description' => $params->get('slide_description_'.($i+1))
			
  		);
  		$slides[] = $slide;
		
  	}

foreach ($slides as $slide){
 if(($slide['mbox'])==1){
	 require_once (dirname(__FILE__).DS.'blaster.php');
	 	if (preg_match( "/msie/",$who) || preg_match( "/opera/",$who)){
			$cssfile ='yjmboxcss3all';
		}else{
			$cssfile ='yjmmbox12';
		}
	$document->addScript(JURI::base() . 'modules/mod_yjis3/src/yjmmbox12.js'); 
	$document->addStyleSheet(JURI::base() . 'modules/mod_yjis3/css/'.$cssfile.'.css');
	}
}
  	/**
  	 * HTML output
  	 */
?>

<div id="YJSlide_outer<?php echo $is_copy?>" class="<?php echo $class_fx;?>slide" style="width:<?php echo $swidth;?>px; height:<?php echo $sheight;?>px;">
	<div id="YJSlide_inner<?php echo $is_copy?>" style="width:<?php echo $swidth;?>px;">
	<?php foreach ($slides as $slide):?>
		<div class="YJSlide_slide" style="width:<?php echo $swidth;?>px; height:<?php echo $sheight;?>px;">
		<?php if($slide['link'] || $slide['mbox']):?>
           <?php if(($slide['mbox'])==1){?>
                     
			<a href="<?php echo $slide['mboxlink']?>" class="popbox" rel="lightbox[<?php echo $slide['image_group_name'];?><?php if(($slide['mboxtype'])==1):?> <?php echo $slide['video_width'];?> <?php echo $slide['video_height'];?><?php endif;?>]">
            <?php }else{ ?>
            <a href="<?php echo $slide['link']?>"  target="<?php echo $slide['open']==0 ? '_blank':'_self';?>">  
            <?php }?>	
		<?php endif;?>	

			<img src="<?php echo JURI::base();?>images/upload_slides/<?php echo $slide['image'];?>" <?php if($slide['title']):?>class="YJS_link" title="<?php echo $slide['title']?><?php if ($balons == '0'){ ?>::<?php echo $slide['description']?><?php }?>"<?php endif;?> alt="<?php echo $slide['title'];?>" />
		<?php if($slide['link'] || $slide['mbox']):?></a><?php endif;?>
			
		<?php if ($slide['description']):?>
			<div class="YJSlide_description"><?php echo $slide['description'];?></div>
		<?php endif;?>
		
		<?php if ($slide['intro']):?>
			<div class="YJSlide_intro"><p><?php echo $slide['intro'];?></p></div>
		<?php endif;?>
		
		</div>
		
	<?php endforeach;?>
	</div>
	<?php if($smenu==0):?>
	<a href="#" title="previous" id="YJS_left<?php echo $is_copy?>"></a>
	<a href="#" title="next" id="YJS_right<?php echo $is_copy?>"></a>
	<?php endif;?>
</div>
<?php if($pagination==0):?>
<div class="navContainer" style="width:<?php echo $swidth;?>px;">
	<span id="YJS_nav_info<?php echo $is_copy?>">Link 1 of <?php echo count($slides);?></span>
	<?php foreach ($slides as $key=>$slide):?>
	<a href="#" class="YJS_navLink<?php echo $is_copy?>" title="Navigate to slide <?php echo $key+1;?>" rel="<?php echo $slide['title'];?>"><?php echo $key+1;?></a>	
	<?php endforeach;?>
</div>
<?php endif;?>