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
defined('_JEXEC') or die('Restricted access'); ?>
<div class="yjsg-morearticles">
<h3>
	<?php echo JText::_( 'More Articles...' ); ?>
</h3>

<ul>
	<?php foreach ($this->links as $link) : ?>
	<li>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($link->slug, $link->catslug, $link->sectionid)); ?>"><?php echo $this->escape($link->title); ?></a>
	</li>
	<?php endforeach; ?>
</ul>
</div>