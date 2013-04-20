<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($country_id > 0):?>Edit<?php else:?>Add<?php endif?> Country</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Country Name</label></td>
                  <td><input name="country_label" type="text" value="<?=$country_label?>"></td>
            </tr>
            <tr>
                  <td><label>Country Code</label></td>
                  <td><input name="country_code" type="text" value="<?=$country_code?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('country_id', $country_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="datasets/ajax/country_update/">Save Country</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>

      <?php if ($country_id > 0):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="datasets/ajax/country_update/delete/">Delete Country</a><?php endif;?>
</div>