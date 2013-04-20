<?php
/**
 * @package   YJSimpleGrid Joomla! Template Framework
 * @author    Youjoomla LLC
 * @websites  http://www.youjoomla.com, http://www.yjsimplegrid.com
 * @license - PHP files are licensed under  GNU/GPL V
 * @license - CSS  - JS - IMAGE files  are Copyrighted material
 * @bound by Proprietary License of Youjoomla LLC
 * @This file is based on the Template Overrides from YOOtheme
 * @package   Template Overrides YOOtheme
 * @version   1.5.9 2010-04-30 10:32:15
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/
 // no direct access
defined('_JEXEC') or die('Restricted access'); 
$app =& JFactory::getApplication();
JLoader::register('YJSGparams', JPATH_THEMES.DS.$app->getTemplate().DS.'/yjsgcore/yjsg_params.php');
$fp_controll_switch         = YJSGparams::YJSGparam()->get("fp_controll_switch");
$fp_chars_limit             = YJSGparams::YJSGparam()->get("fp_chars_limit");
$fp_after_text              = YJSGparams::YJSGparam()->get("fp_after_text");
?>
<div class="news_item_f">
	<?php if ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own')) : ?>
	<div class="contentpaneopen_edit<?php echo $this->item->params->get( 'pageclass_sfx' ); ?>" style="float: left;"> <?php echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access); ?> </div>
	<?php endif; ?>
	<?php if ($this->item->params->get('show_title')) : ?>
	<div class="title<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<h2>
			<?php if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
			<a href="<?php echo $this->item->readmore_link; ?>"><?php echo $this->item->title; ?></a>
			<?php else : ?>
			<?php echo $this->item->title; ?>
			<?php endif; ?>
		</h2>
	</div>
	<?php endif; ?>
	<?php  if (!$this->item->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>
	<?php
if (
($this->item->params->get('show_create_date'))
|| (($this->item->params->get('show_author')) && ($this->item->author != ""))
|| (($this->item->params->get('show_section') && $this->item->sectionid) || ($this->item->params->get('show_category') && $this->item->catid))
|| ($this->item->params->get('show_pdf_icon') || $this->item->params->get('show_print_icon') || $this->item->params->get('show_email_icon'))
|| ($this->item->params->get('show_url') && $this->item->urls)
) :
?>
	<div class="newsitem_tools">
		<div class="newsitem_info">
			<?php if ($this->item->params->get('show_create_date')) : ?>
			<span class="createdate">
			<!-- date and by -->
			<?php echo JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?> </span>
			<?php endif; ?>
			<?php if (($this->item->params->get('show_author')) && ($this->item->author != "")) : ?>
			<span class="createby">
			<?php JText::printf(($this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author) ); ?>
			</span>
			<!-- end date and by -->
			<?php endif; ?>
			<?php if (($this->item->params->get('show_section') && $this->item->sectionid) || ($this->item->params->get('show_category') && $this->item->catid)) : ?>
			<?php if ($this->item->params->get('show_section') && $this->item->sectionid && isset($this->item->section)) : ?>
			<span class="newsitem_section">
			<!-- sect and cat names or links -->
			<?php if ($this->item->params->get('link_section')) : ?>
			<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->item->section; ?>
			<?php if ($this->item->params->get('link_section')) : ?>
			<?php echo '</a>'; ?>
			<?php endif; ?>
			<?php if ($this->item->params->get('show_category')) : ?>
			<?php echo ' - '; ?>
			<?php endif; ?>
			</span>
			<?php endif; ?>
			<?php if ($this->item->params->get('show_category') && $this->item->catid) : ?>
			<span class="newsitem_category">
			<?php if ($this->item->params->get('link_category')) : ?>
			<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->item->category; ?>
			<?php if ($this->params->get('link_category')) : ?>
			<?php echo '</a>'; ?>
			<?php endif; ?>
			</span>
			<!-- end sect and cat -->
			<?php endif; ?>
			<?php endif; ?>
		</div>
		<!-- end of info-->
		<?php if ($this->item->params->get('show_pdf_icon') || $this->item->params->get('show_print_icon') || $this->item->params->get('show_email_icon')) : ?>
		<div class="buttonheading">
			<!-- print email pdf -->
			<?php if ($this->item->params->get('show_email_icon')) : ?>
			<span class="email"> <?php echo JHTML::_('icon.email', $this->item, $this->item->params, $this->access); ?> </span>
			<?php endif; ?>
			<?php if ( $this->item->params->get( 'show_print_icon' )) : ?>
			<span class="print"> <?php echo JHTML::_('icon.print_popup', $this->item, $this->item->params, $this->access); ?> </span>
			<?php endif; ?>
			<?php if ($this->item->params->get('show_pdf_icon')) : ?>
			<span class="pdf"> <?php echo JHTML::_('icon.pdf', $this->item, $this->item->params, $this->access); ?> </span>
			<?php endif; ?>
		</div>
		<!--end  print email pdf -->
		<?php endif; ?>
		<?php if ($this->item->params->get('show_url') && $this->item->urls) : ?>
		<span class="newsitems_link"> <a href="http://<?php echo $this->item->urls ; ?>" target="_blank"> <?php echo $this->item->urls; ?></a> </span>
		<?php endif; ?>
	</div>
	<!-- end of tools-->
	<?php endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>
	<div class="newsitem_text">
		<?php if (isset ($this->item->toc)) : ?>
		<?php echo $this->item->toc; ?>
		<?php endif; ?>
		<?php
	 if ($fp_controll_switch == 1 ){
	 echo  $fp_intro_text = substr(strip_tags($this->item->text,
	 '<br><img><p><div><ul><li><a><strong><b><h1><h2><h3><h4><h5><h6><span>'),0,$fp_chars_limit)."$fp_after_text";
	 }else{
		echo $this->item->text;
	 } 
	?>
	</div>
	<?php if ( intval($this->item->modified) != 0 && $this->item->params->get('show_modify_date')) : ?>
	<span class="modifydate"> <?php echo JText::_( 'Last Updated' ); ?> ( <?php echo JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2')); ?> ) </span>
	<?php endif; ?>
	<?php if ($this->item->params->get('show_readmore') && $this->item->readmore) : ?>
	<a href="<?php echo $this->item->readmore_link; ?>" class="readon<?php echo $this->item->params->get('pageclass_sfx'); ?>"><span>
	<?php if ($this->item->readmore_register) :
				echo JText::_('Register to read more...');
			elseif ($readmore = $this->item->params->get('readmore')) :
				echo $readmore;
			else :
				echo JText::sprintf('Read more...');
			endif; ?>
	</span></a>
	<?php endif; ?>
</div>
<!--end news item -->
<span class="article_separator">&nbsp;</span> <?php echo $this->item->event->afterDisplayContent; ?> 