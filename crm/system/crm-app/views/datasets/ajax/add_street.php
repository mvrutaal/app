<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($street_id > 0):?>Edit<?php else:?>Add<?php endif?> Street</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Street Name</label></td>
                  <td><input name="street_label" type="text" value="<?=$street_label?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('street_id', $street_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="datasets/ajax/street_update/">Save Street</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>

      <?php if ($street_id > 0):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="datasets/ajax/street_update/delete/">Delete Street</a><?php endif;?>
</div>