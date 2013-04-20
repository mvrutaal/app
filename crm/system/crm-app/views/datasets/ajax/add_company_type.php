<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($company_type_id > 0):?>Edit<?php else:?>Add<?php endif?> Company Type</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Company Type Label</label></td>
                  <td><input name="company_type_label" type="text" value="<?=$company_type_label?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('company_type_id', $company_type_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="datasets/ajax/company_types_update/">Save Company Type</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>

      <?php if ($company_type_id > 0):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="datasets/ajax/company_types_update/delete/">Delete Company Type</a><?php endif;?>
</div>