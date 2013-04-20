<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($program_id > 0):?>Edit<?php else:?>Add<?php endif?> Qualification</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Qualification Label</label></td>
                  <td><input name="program_label" type="text" value="<?=$program_label?>"></td>
            </tr>
            <tr>
                  <td><label>Number Of Students</label></td>
                  <td><input name="program_students" type="text" value="<?=$program_students?>"></td>
            </tr>
            <tr>
                  <td><label>Department</label></td>
                  <td><?=form_dropdown('program_department_id', $program_departments, $program_department_id, " class='chosen' ")?></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('program_id', $program_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_program/">Save Program</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>