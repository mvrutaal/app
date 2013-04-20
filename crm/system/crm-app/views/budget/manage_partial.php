<script type="text/javascript" src="<?=base_url()?>assets/js/modules/budget.js"></script>
<div id="budget" class="clear">
<?=form_open('budget/update_manage_partials', array('id' => 'add_contact', 'enctype' => 'multipart/form-data', 'method'=>'POST'));?>

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

  
  <h6>Sectors</h6>
  <div class="columns account-toggler">
    <?php foreach ($accounts as $account): ?>
    <span class="label label-success account_id_<?=$account->account_id?>" data-id="<?=$account->account_id?>" ><?=$account->account_number?> - <?=$account->account_label?></span>
    <?php endforeach; ?>
  </div>


	<!--
	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
			<input type="text" name="item_label" placeholder="Item Label">
		</div>
		<div class="filter">
			<?=form_multiselect('item_categories[]', $item_categories, '', ' class="chosen" data-placeholder="Item Category" style="width:100%;"')?>
		</div>
	</div>
	-->
	<br>
	<div style="width:80%; margin:auto;">
            <input type="submit" class="btn btn-primary btn-large" value="Save" style="width:80%;">
  </div>


</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Manage: <?=$partial->program_partial_label?></h2>
	</div>

	<div id="content">

		<?php foreach ($accounts as $account): ?>
    <table class="CrmTable BudgetManage" id="account_id_<?=$account->account_id?>" cellspacing="0" cellpadding="0" border="0" width="100%">
          <thead>
                <tr>
                      <th colspan="10">
                      	<h4><?=$account->account_number?> - <?=$account->account_label?></h4>
                      </th>
                </tr>
                <tr>
                      <th style="width:10%">Quantity</th>
                      <th style="width:30%">Item</th>
                      <th style="width:30%">Description</th>
                      <th style="width:10%">Price</th>
                      <th style="width:10%">Total</th>
                      <th style="width:10%">Actions</th>
                </tr>
          </thead>
          <tbody>
          	<tr class="NoItems"><td colspan="10">No Items have yet been added.</td></tr>
          </tbody>
          <tfoot>
          	<tr>
          		<th colspan="3"><a href="#" class="NewRow" data-account="<?=$account->account_id?>">New Row</a></th>
              <th style="text-align:right">Total</th>
          		<th class="TotalTablePrice"></th>
          		<th></th>
          	</tr>
          </tfoot>
    </table>
    <?php endforeach;?>


	</div>


</div>

<input name="program_partial_id" type="hidden" value="<?=$partial->program_partial_id?>">

<?=form_close();?>
</div>





<div class="HiddenSelect" style="display:none">
<?php

$form = "<select name=''>\n";

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