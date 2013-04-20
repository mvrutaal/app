<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($user_id > 0):?>Edit<?php else:?>Add<?php endif?> User</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Contact Link</label></td>
                  <td><?=form_dropdown('contact_id', $contacts, $contact_id)?></td>
            </tr>
            <tr>
                  <td><label>Username</label></td>
                  <td><input name="username" type="text" value="<?=$username?>"></td>
            </tr>
            <tr>
                  <td><label>Password</label></td>
                  <td><input name="password" type="password"></td>
            </tr>
            <tr>
                  <td><label>Member Group</label></td>
                  <td><?=form_dropdown('group_id', $groups, $group_id)?></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('user_id', $user_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="settings/ajax/update_user/">Save User</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
      <?php if ($user_id > 0):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="settings/ajax/update_user/delete/">Delete User</a><?php endif;?>
</div>