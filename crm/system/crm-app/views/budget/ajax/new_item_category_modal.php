<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($item_cat_id > 0):?>Edit<?php else:?>Add<?php endif?> Item Category</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Item Category Label</label></td>
                  <td><input name="item_cat_label" type="text" value="<?=$item_cat_label?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('item_cat_id', $item_cat_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_item_category/">Save Category</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>