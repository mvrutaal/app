<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($city_id > 0):?>Edit<?php else:?>Add<?php endif?> City</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>City Name</label></td>
                  <td><input name="city_label" type="text" value="<?=$city_label?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('city_id', $city_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="datasets/ajax/city_update/">Save City</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>

      <?php if ($city_id > 0):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="datasets/ajax/city_update/delete/">Delete City</a><?php endif;?>
</div>