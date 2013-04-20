<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($suburb_id > 0):?>Edit<?php else:?>Add<?php endif?> Company Type</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Suburb Name</label></td>
                  <td><input name="suburb_label" type="text" value="<?=$suburb_label?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('suburb_id', $suburb_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="datasets/ajax/suburb_update/">Save Suburb</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>

      <?php if ($suburb_id > 0):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="datasets/ajax/suburb_update/delete/">Delete Suburb</a><?php endif;?>
</div>