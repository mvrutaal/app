<div class="clear manageblock">
<?=form_open('budget/update_manage_partials', array('id' => 'add_contact', 'enctype' => 'multipart/form-data', 'method'=>'POST'));?>

<div class="left" style="width:25%; float:left; padding:0 0 10px;">
	<?php foreach($accounts as $acc): ?>
	<span class="label account_id_<?=$acc->account_id?>" data-id="<?=$acc->account_id?>"><?=$acc->account_number?> - <?=$acc->account_label?></span>
	<?php endforeach; ?>
</div>

<div class="right" style="width:75%; float:left; padding:0 5px 0 0;">
	<h3 class="NothingSelected" style="padding:30px">Please select an account..</h3>
	<?php foreach ($accounts as $account): ?>
  <?php if (isset($posts[$account->account_id]) == FALSE) $posts[$account->account_id] = array(); ?>
  <div class="BudgetManageWrapper" id="account_id_<?=$account->account_id?>" style="display:none">
    <table class="CrmTable BudgetManage" cellspacing="0" cellpadding="0" border="0" width="100%">
          <thead>
                <tr>
                      <th colspan="10">
                      	<h4><?=$account->account_number?> - <?=$account->account_label?></h4>
                      </th>
                </tr>
                <tr>
                      <th style="width:50px">Quantity</th>
                      <th style="width:250px;">Item</th>
                      <th style="">Description</th>
                      <th style="width:50px">Price</th>
                      <th style="width:50px; text-align:right;">Total</th>
                </tr>
          </thead>
          <tbody>
            <?php if (empty($posts[$account->account_id]) == TRUE) echo '<tr class="NoItems"><td colspan="10">No Items have yet been added.</td></tr>';?>
            <?php foreach($posts[$account->account_id] as $post):?>
              <tr class="ItemRow">
                <td><input name="account[<?=$account->account_id?>][items][][quantity]" value="<?=$post->item_quantity?>" type="text" class="RowQuantity"> <span class="remove" data-original-title="Remove"></span></td>
                <td><?=$post->item_label?></td>
                <td><input name="account[<?=$account->account_id?>][items][][desc]" value="<?=$post->item_desc?>" type="text"></td>
                <td><input name="account[<?=$account->account_id?>][items][][price]" value="<?=$post->item_price?>" type="text" class="RowPrice"> <input name="account[<?=$account->account_id?>][items][][item_id]" value="<?=$post->item_id?>" type="hidden" class="ItemID"></td>
                <td style="text-align:right;"><span class="RowTotal"><?=number_format($post->row_total, 2)?></span> <input name="account[<?=$account->account_id?>][items][][total]" type="hidden" value="<?=$post->row_total?>" class="RowTotalHidden"></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
          	<tr>
          		<th colspan="3"><a href="#" class="NewRow" data-account="<?=$account->account_id?>">New Row</a></th>
              <th style="text-align:right;">Total</th>
          		<th style="text-align:right;" class="TotalTablePrice"></th>
          	</tr>
          </tfoot>
    </table>

    <div class="save_wrapper">
      <input type="submit" class="btn btn-primary save" value="Save" style="">
    </div>
  </div>
  <?php endforeach;?>
</div>

<input name="program_partial_id" type="hidden" value="<?=$partial->program_partial_id?>">

<?=form_close();?>
</div>