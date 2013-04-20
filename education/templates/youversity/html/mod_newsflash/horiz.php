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

defined('_JEXEC') or die('Restricted access'); ?>

<?php if (count($list) == 1) : ?>
	<?php $item = $list[0]; ?>
	<div class="yjsg-newsflash">
		<?php modNewsFlashHelper::renderItem($item, $params, $access); ?>
	</div>
<?php elseif (count($list) > 1) : ?>
	<div class="yjsg-newsflash">
		<div class="horizontal <?php echo $params->get('moduleclass_sfx'); ?>">
			<?php for ($i = 0, $n = count($list); $i < $n; $i ++) : ?>
			<div class="item <?php if ($i == $n - 1) echo 'last'; ?>">
				<?php modNewsFlashHelper::renderItem($list[$i], $params, $access); ?>
			</div>
			<?php endfor; ?>
		</div>
	</div>
<?php endif; ?>
