<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($account_id > 0):?>Edit<?php else:?>Add<?php endif?> Account</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Account Number</label></td>
                  <td><input name="account_number" type="text" value="<?=$account_number?>"></td>
            </tr>
            <tr>
                  <td><label>Account Label</label></td>
                  <td><input name="account_label" type="text" value="<?=$account_label?>"></td>
            </tr>
            <tr>
                  <td><label>Account Category</label></td>
                  <td><?=form_dropdown('account_cat_id', $account_categories, $account_cat_id)?></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('account_id', $account_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_account/">Save Account</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>