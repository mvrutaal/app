<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($account_cat_id > 0):?>Edit<?php else:?>Add<?php endif?> Account Category</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Account Category Label</label></td>
                  <td><input name="account_category_label" type="text" value="<?=$account_category_label?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('account_cat_id', $account_cat_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_account_category/">Save Category</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>