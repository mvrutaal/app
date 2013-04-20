<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($note_type_id > 0):?>Edit<?php else:?>Add<?php endif?> Note Type</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Note Type Name</label></td>
                  <td><input name="note_type_label" type="text" value="<?=$note_type_label?>"></td>
            </tr>
            <tr>
                  <td><label>Module</label></td>
                  <td><?=form_dropdown('note_type_module', $modules, $note_type_module)?></td>
            </tr>
            <tr>
                  <td><label>Acess Control</label></td>
                  <td class="acl_who_toggler">
                        <span class="label <?php if (in_array('everyone', $acl['who'])) echo "label-success"?>" data-value="everyone">Everyone</span>
                        <span class="label <?php if (in_array('author', $acl['who'])) echo "label-success"?>" data-value="author">Only Original Author</span>
                        <span class="label <?php if (in_array('user_roles', $acl['who'])) echo "label-success"?>" data-value="user_roles">User Roles</span>
                        <span class="label <?php if (in_array('users', $acl['who'])) echo "label-success"?>" data-value="users">Individual Users</span>

                        <?php foreach($acl['who'] as $who):?>
                        <input name="acl[who][]" value="<?=$who?>" class="acl-<?=$who?>" type="hidden">
                        <?php endforeach;?>
                  </td>
            </tr>
            <tr class="acl_type-user_roles <?php if (in_array('user_roles', $acl['who']) == FALSE) echo "hidden"?>">
                   <td><label>User Roles</label></td>
                   <td><?=form_multiselect('acl[user_roles][]', $user_roles, $acl['user_roles'], " class='chosen' style='width:300px' ")?></td>
            </tr>
            <tr class="acl_type-users <?php if (in_array('users', $acl['who']) == FALSE) echo "hidden"?>">
                   <td><label>Individual Users</label></td>
                   <td><?=form_multiselect('acl[users][]', $users, $acl['users'], " class='chosen' style='width:300px' ")?></td>
            </tr>
             <tr>
                   <td><label>Exclude User</label></td>
                   <td><?=form_multiselect('acl[exclude][]', $users, $acl['exclude'], " class='chosen' style='width:300px' ")?></td>
            </tr>
            <tr>
                  <td colspan="2">
                        <small style="font-size:10px;">
                              Choosing "Only Me" or "Everyone" will override all other Access Controls
                        </small>
                  </td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('note_type_id', $note_type_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="settings/ajax/update_note_type/">Save Note Type</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
      <?php if ($note_type_id > 0):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="settings/ajax/update_note_type/delete/">Delete Note Type</a><?php endif;?>
</div>