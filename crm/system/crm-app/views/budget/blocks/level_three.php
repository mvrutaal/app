<div class="blocklinks">
<a class="label label-important generallink">GENERAL</a>
<?php foreach($partials as $partial_id => $partial_label):?>
<a class="blocklink label label-info" data-id="<?=$partial_id?>"><?=$partial_label?></a>
<?php endforeach;?>
</div>

 <div class="BudgetManageWrapper" style="display:none">
 	<?=form_open('budget/update_manage_program', array('id' => 'add_contact', 'enctype' => 'multipart/form-data', 'method'=>'POST'));?>
 	<br><br>
    <table class="CrmTable BudgetManage" cellspacing="0" cellpadding="0" border="0" width="100%">
          <thead>
            <tr>
            	<th style="width:225px;">Account</th>
            	<th style="width:150px;">Account Desc.</th>
				<th style="width:100px">Quantity</th>
				<th style="width:180px;">Item</th>
				<th style="">Description</th>
				<th style="width:100px">Price</th>
				<th style="width:50px; text-align:right;">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($posts) == TRUE) echo '<tr class="NoItems"><td colspan="10">No Items have yet been added.</td></tr>';?>
            <?php foreach($posts as $post):?>
              <tr class="ItemRow">
              	<td>
              		<?=$post->account_label?>
                	<input name="account[items][][account_id]" value="<?=$post->account_id?>" type="hidden" class="AccountID">
                </td>
                <td><input name="account[items][][account_desc]" value="<?=$post->account_alt_label?>" type="text" style="width:100%"></td>
                <td>
                	<input name="account[items][][item_quantity]" value="<?=number_format($post->item_quantity, 2)?>" type="text" class="RowQuantity" style="display:block; float:left; width:65px;">
                	<span class="remove" data-original-title="Remove"></span>
                </td>
                <td><?=$post->item_label?></td>
                <td><input name="account[items][][item_desc]" value="<?=$post->item_desc?>" type="text" style="width:100%"></td>
                <td>
                	<input name="account[items][][item_price]" value="<?=$post->item_price?>" type="text" class="RowPrice" style="width:83px;">
                	<input name="account[items][][item_id]" value="<?=$post->item_id?>" type="hidden" class="ItemID">
                </td>
                <td style="text-align:right;">
                	<span class="RowTotal"><?=number_format($post->row_total, 2)?></span>
                	<input name="account[items][][row_total]" type="hidden" value="<?=$post->row_total?>" class="RowTotalHidden">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
          	<tr>
          		<th colspan="5"><a href="#" class="NewRow">New Row</a></th>
              <th style="text-align:right;">Total</th>
          		<th style="text-align:right;" class="TotalTablePrice"></th>
          	</tr>
          </tfoot>
    </table>

    <?=form_hidden('program_id', $program_id);?>

    <div class="save_wrapper">
      <input type="submit" class="btn btn-primary save" value="Save" style="">
    </div>
    <br>
	<?=form_close();?>
</div>