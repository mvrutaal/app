<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($resource_id > 0):?>Edit<?php else:?>Add<?php endif?> Resource</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Resource Name</label></td>
                  <td><input name="name" type="text" value="<?=$name?>"></td>
            </tr>
            <tr>
                  <td><label>Resource Description</label></td>
                  <td><input name="description" type="text" value="<?=$description?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('resource_id', $resource_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="settings/ajax/update_access_resource/">Save Resource</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>