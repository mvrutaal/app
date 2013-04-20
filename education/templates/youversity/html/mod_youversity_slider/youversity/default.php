<?php
/*======================================================================*\
|| #################################################################### ||
|| # Copyright (C) 2006-2010 Youjoomla LLC. All Rights Reserved.        ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- THIS IS NOT FREE SOFTWARE ---------------- #      ||
|| # http://www.youjoomla.com | http://www.youjoomla.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<!-- http://www.Youjoomla.com  Youversity News Slider Module for Joomla 1.5 starts here -->
<div id="versity_holder" style="width:<?php echo $slider_width ?>;height:<?php echo $slider_height ?>;">
<div id="yv_container" style="width:<?php echo $slider_width ?>;height:<?php echo $slider_height ?>;">
    <a class="linkForward"></a>
    <div id="yv_slider" style="width:<?php echo $slider_width -0 ?>px;height:<?php echo $slider_height ?>;">
		<?php foreach ($youversity_slides as $youversity_slide): ?>
			<div class="yv_slideitems" style="width:<?php echo $items_width ?>px;height:<?php echo $yv_slideitems_height ?>px;">
				<div class="yv_slideitems_in" style="height:<?php echo $yv_slideitems_height ?>px;">
					<?php if (isset($youversity_slide['img_url']) && $youversity_slide['img_url'] != "" && $show_img =='1' ) : ?>
						<div class="imageholder">
							<a href="<?php echo $youversity_slide['link'] ?>">
							<?php if(isset($youversity_slide['img_url']) && $youversity_slide['img_url'] != "")  echo $youversity_slide['img_out'] ?>
							</a>
						</div>
					<?php endif ?>
					<?php if($show_title=='1') : ?>
						<div class="title">
							<a href="<?php echo $youversity_slide['link'] ?>">
								<?php echo  $youversity_slide['title'] ?>
							</a>
						</div>
					<?php endif ?>
					<div class="intro">
						<?php echo  $youversity_slide['intro'] ?>
					</div>
					<?php if($show_read=='1') : ?>
						<a class="readmore" href="<?php echo $youversity_slide['link'] ?>">
							<span>
								<?php echo JText::_( 'READ_MORE_TEXT' ); ?>
							</span>
						</a>
					<?php endif ?>
				</div>
			</div>
		 <?php endforeach;?> 
    </div>
    <a class="linkBackward"></a>
</div>
</div>