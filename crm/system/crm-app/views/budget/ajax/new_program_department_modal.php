<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($program_department_id > 0):?>Edit<?php else:?>Add<?php endif?> Program Department</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Department Label</label></td>
                  <td><input name="program_department_label" type="text" value="<?=$program_department_label?>"></td>
            </tr>
             <tr>
                  <td><label>Sector</label></td>
                  <td><?=form_dropdown('program_sector_id', $program_sectors, $program_sector_id)?></td>
            </tr
      </tbody>
      </table>

      <?=form_hidden('program_department_id', $program_department_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_program_department/">Save Department</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>