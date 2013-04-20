<?php
defined('_JEXEC') or die('Restricted access');
/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * three arguments.
 */
/**
 * Custom module chrome, echos the whole module in a <div> and the header in <h{x}>. The level of
 * the header can be configured through a 'headerLevel' attribute of the <jdoc:include /> tag.
 * Defaults to <h4> if none given
 */
 /* usable module chromes
 
 YJsghtml  = square mods  2 divs 
 YJsground = round mods  6 divs. 4 used for rounded corners 1 for spacing , 1 for inside mod content
 YYsgplain = square mods , no title no additional divs
 
  */
 // DEFAULT SQUARE
function modChrome_YJsgxhtml($module, &$params, &$attribs)
{
?>
<div class="yjsquare<?php echo $params->get('moduleclass_sfx'); ?>">
  <?php if ($module->showtitle) : ?>
  <h4>
    <?php
					$title = $module->title;
					$title = explode(' ', $title);
					$title[0] = '<span>'.$title[0].'</span>';
					$title = join(' ', $title);
					$title = str_replace("&","&amp;",$title);
					echo $title; 
				?>
  </h4>
  <?php endif; ?>
  <div class="yjsquare_in"><?php echo $module->content; ?></div>
</div>
<?php } ?>
<?php 
// DEFAULT PLAIN OUTPUT NO TITLE OR SURROUNDING DIVS
function modChrome_YJsgplain($module, &$params, &$attribs)
{
?>
<div class="yjplain"><?php echo $module->content; ?></div>
<?php } ?>
<?php 
// DEFAULT ROUND CORNERS NEW CSS
function modChrome_YJsground($module, &$params, &$attribs)
{
?>
<div class="yjroundout<?php echo $params->get('moduleclass_sfx'); ?>">
<div class="yjround<?php echo $params->get('moduleclass_sfx'); ?>">
 <div class="content">
  <div class="t"></div>
<?php if ($module->showtitle) : ?>
<h4><?php $title = $module->title; $title = explode(' ', $title);$title[0] = '<span>'.$title[0].'</span>';$title= join(' ', $title);$title = str_replace("&","&amp;",$title);echo $title;?></h4><?php endif; ?>
<div class="yjround_in"><?php echo $module->content; ?></div>
   </div>
 <div class="b"><div class="bin"></div></div>
</div>
</div>
<?php } ?>