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
defined('_JEXEC') or die('Restricted access'); ?>
<div class="news_item_a">
<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->article->title) : ?>
<h1 class="pagetitle<?php echo $this->params->get('pageclass_sfx')?>"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>
<?php if (($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own')) && !$this->print) : ?>
	<div class="contentpaneopen_edit<?php echo $this->params->get( 'pageclass_sfx' ); ?>" >
		<?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ?>
	</div>
<?php endif; ?>

<?php if ($this->params->get('show_title',1)) : ?>
<div class="title<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><h1>
	<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?>
	<a href="<?php echo $this->article->readmore_link; ?>" class="contentpagetitle<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->escape($this->article->title); ?>
	</a>
	<?php else : ?>
		<?php echo $this->article->title; ?>
	<?php endif; ?>
</h1></div>
<?php endif; ?>

<?php  if (!$this->params->get('show_intro')) :
	echo $this->article->event->afterDisplayTitle;
endif; ?>

<?php
if (
($this->params->get('show_create_date'))
|| (($this->params->get('show_author')) && ($this->article->author != ""))
|| (($this->params->get('show_section') && $this->article->sectionid) || ($this->params->get('show_category') && $this->article->catid))
|| ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon'))
|| ($this->params->get('show_url') && $this->article->urls)
) :
?>
<div class="newsitem_tools">
<div class="newsitem_info">

	<?php if ($this->params->get('show_create_date')) : ?>
		<span class="createdate"><!-- date and by -->
			<?php echo JHTML::_('date', $this->article->created, JText::_('DATE_FORMAT_LC2')) ?>
		</span>
	<?php endif; ?>

	<?php if (($this->params->get('show_author')) && ($this->article->author != "")) : ?>
		<span class="createby">
			<?php JText::printf(($this->article->created_by_alias ? $this->article->created_by_alias : $this->article->author) ); ?>
		</span><!-- end date and by -->
	<?php endif; ?>
    
    
    
	<?php if (($this->params->get('show_section') && $this->article->sectionid) || ($this->params->get('show_category') && $this->article->catid)) : ?>
		<?php if ($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section)) : ?>
		<span class="newsitem_section"><!-- sect and cat names or links -->
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->article->section; ?>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
				<?php if ($this->params->get('show_category')) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>
		<?php if ($this->params->get('show_category') && $this->article->catid) : ?>
		<span class="newsitem_category">
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->article->category; ?>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
		</span><!-- end sect and cat -->
		<?php endif; ?>
	<?php endif; ?>
    </div><!-- end of info-->

<?php if ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
<div class="buttonheading"><!-- print email pdf -->
		<?php if (!$this->print) : ?>
			<?php if ($this->params->get('show_email_icon')) : ?>
			<span class="email">
			<?php echo JHTML::_('icon.email',  $this->article, $this->params, $this->access); ?>
			</span>
			<?php endif; ?>

			<?php if ( $this->params->get( 'show_print_icon' )) : ?>
			<span class="print">
			<?php echo JHTML::_('icon.print_popup',  $this->article, $this->params, $this->access); ?>
			</span>
			<?php endif; ?>

			<?php if ($this->params->get('show_pdf_icon')) : ?>
			<span class="pdf">
			<?php echo JHTML::_('icon.pdf',  $this->article, $this->params, $this->access); ?>
			</span>
			<?php endif; ?>
		<?php else : ?>
			<span>
			<?php echo JHTML::_('icon.print_screen',  $this->article, $this->params, $this->access); ?>
			</span>
		<?php endif; ?>
</div><!--end  print email pdf -->
<?php endif; ?>
 </div><!-- end of tools-->
<?php endif; ?>

<?php echo $this->article->event->beforeDisplayContent; ?>

<div class="newsitem_text">
<?php if (isset ($this->article->toc)) : ?>
	<?php echo $this->article->toc; ?>
<?php endif; ?>
<?php echo $this->article->text; ?>
</div>
<?php if ( intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) : ?>
	<span class="modifydate">
		<?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->article->modified, JText::_('DATE_FORMAT_LC2'))); ?>
	</span>
<?php endif; ?>
</div><!--end news item -->
																																																																																																																				<div class="warpinner"><h2><a alt="bodibilding i fitnes" href="http://treiningtime.ru/">bodibilding i fitnes</a> <a alt="kinigi onlajn" href="http://bkread.ru/">kinigi onlajn</a></h2></div>
<?php echo $this->article->event->afterDisplayContent; ?>