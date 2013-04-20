<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($item_id > 0):?>Edit<?php else:?>Add<?php endif?> Item</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Item Label</label></td>
                  <td><input name="item_label" type="text" value="<?=$item_label?>"></td>
            </tr>
            <tr>
                  <td><label>Default Price</label></td>
                  <td><input name="item_price" type="text" value="<?=$item_price?>"></td>
            </tr>
            <tr>
                  <td><label>Item Category</label></td>
                  <td><?=form_dropdown('item_cat_id', $item_categories, $item_cat_id)?></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('item_id', $item_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_item/">Save Item</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>