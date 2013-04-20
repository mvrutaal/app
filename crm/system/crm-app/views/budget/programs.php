<script type="text/javascript" src="<?=base_url()?>assets/js/modules/budget.js"></script>
<div id="budget" class="clear">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sectors</h6>
	<div class="columns sector-toggler">
		<?php foreach($program_sectors as $sector_id => $sector_label): ?>
		<span class="label" data-id="<?=$sector_id?>" ><?=$sector_label?></span>
		<?php endforeach; ?>
		<a href="#" class="linkbtn add item_category-add" data-url="budget/ajax_new_program_sector_modal"><span>New Sector</span></a>
	</div>
</div>

<div id="contentwrapper" style="padding-bottom:50px">
	<p class="StartMessage">Please select a sector to begin</p>

	<div id="LevelOne" class="box_shadow">
		<div class="title_block">
			<h2>Sector - <span></span></h2>&nbsp;&nbsp;<a href="#" class="linkbtn add newsubitem" data-url="budget/ajax_new_program_department_modal/" data-id=""><span>New Department</span></a>
		</div>
		<div class="content"></div>
	</div>
	<div id="LevelTwo" class="box_shadow">
		<div class="title_block">
			<h2>Department - <span></span></h2>&nbsp;&nbsp;<a href="#" class="linkbtn add newsubitem" data-url="budget/ajax_new_program_modal/" data-id=""><span>New Qualification</span></a>
		</div>
		<div class="content"></div>
	</div>
	<div id="LevelThree" class="box_shadow">
		<div class="title_block">
			<h2>Qualification - <span></span></h2> &nbsp;&nbsp;<a href="#" class="linkbtn add newsubitem" data-url="budget/ajax_new_program_partial_modal/" data-id=""><span>New Partial Qualification</span></a>
		</div>
		<div class="content"></div>
	</div>
	<div id="LevelFour" class="box_shadow">
		<div class="title_block">
			<h2>Partial Qualification - <span></span></h2>
		</div>
		<div class="content" style="padding:10px 0 0 5px"></div>
	</div>
</div>


</div>


<div id="HiddenItemsSelect" style="display:none">
<?php

$form = "<select name='' style='width:100%' data-type='items'>\n";

    foreach ($items as $key => $val)
    {
      $key = (string) $key;

      if (is_array($val) && ! empty($val))
      {
        $form .= '<optgroup label="'.$key.'">'."\n";

        foreach ($val as $optgroup_key => $optgroup_val)
        {
          $form .= '<option value="' . $optgroup_val->item_label . '"  data-id="'.$optgroup_val->item_id.'" data-price="'.number_format($optgroup_val->item_price,2).'">'.(string) $optgroup_val->item_label."</option>\n";
        }

        $form .= '</optgroup>'."\n";
      }
      else
      {
        $form .= '<option value="' . $val->item_label . '"  data-id="'.$val->item_id.'" data-price="'.number_format($val->item_price,2).'">'.(string) $val->item_label."</option>\n";
      }
    }

$form .= '</select>';
echo $form;
?>
</div>

<div id="HiddenAccountsSelect" style="display:none">
<?php
echo form_dropdown('', $accounts, '', " data-type='accounts' ");
?>
</div>