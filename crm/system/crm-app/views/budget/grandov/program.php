<?php foreach($partials as $partial):?>

<table class="CrmTable BudgetManage" cellspacing="0" cellpadding="0" border="0" width="100%">
      <thead>
            <tr>
                  <th colspan="10">
                  	<h4><?=$partial->program_partial_label?></h4>
                  </th>
            </tr>
            <tr>
                  <th style="width:50px">Account</th>
                  <th>Account Label</th>
                  <th style="width:75px">Total</th>
            </tr>
      </thead>
      <tbody>
        <?php if (empty($posts[$partial->program_partial_id]) == TRUE) echo '<tr class="NoItems"><td colspan="10">Nothing has been recorded..</td></tr>';?>
        <?php $partial_total = 0 ?>
        <?php foreach($posts[$partial->program_partial_id] as $account_id => $account_total):?>
        <?php $partial_total += $account_total;?>
          <tr class="ItemRow">
            <td><?=$accounts[$account_id]->account_number?></td>
            <td><?=$accounts[$account_id]->account_label?></td>
            <td style="text-align:right;"><?=number_format($account_total, 2)?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
      	<tr>
			<th colspan="2" style="text-align:right;">Total</th>
      		<th style="text-align:right;"><?=number_format($partial_total, 2)?></th>
      	</tr>
      </tfoot>
</table>

<?php endforeach; ?>

<?php if (empty($partials) == TRUE):?>
<h4>No Partial Qualifications have been recorded!</h4>
<?php endif; ?>