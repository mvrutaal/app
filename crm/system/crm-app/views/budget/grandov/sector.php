<?php $totaal = 0; ?>

<?php foreach($accounts as $account_cat => $accounts):?>
<?php $cat_total = 0; ?>

<table class="CrmTable BudgetManage" cellspacing="0" cellpadding="0" border="0" width="100%">
      <thead>
            <tr>
                  <th colspan="10">
                  	<h4><?=$account_cat?></h4>
                  </th>
            </tr>
            <tr>
                  <th style="width:50px">Account</th>
                  <th>Account Label</th>
                  <th style="width:75px">Total</th>
            </tr>
      </thead>
      <tbody>
        <?php foreach($accounts as $account):?>
        <?php $cat_total += $posts[$account->account_id];?>
        <?php $totaal += $posts[$account->account_id];?>
          <tr class="ItemRow">
            <td><?=$account->account_number?></td>
            <td><?=$account->account_label?></td>
            <td style="text-align:right;"><?=number_format($posts[$account->account_id], 2)?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
      	<tr>
			<th colspan="2" style="text-align:right;">Total</th>
      		<th style="text-align:right;"><?=number_format($cat_total, 2)?></th>
      	</tr>
      </tfoot>
</table>

<?php endforeach; ?>

<table class="CrmTable BudgetManage" cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
	<tr>
		<td><h4>Totaal: Nafl. <?=number_format($totaal, 2)?></h4></td>
	</tr>
</tbody>
</table>