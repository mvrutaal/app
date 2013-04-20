<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($program_id > 0):?>Edit<?php else:?>Add<?php endif?> Partial Qualification</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Label</label></td>
                  <td><input name="program_partial_label" type="text" value="<?=$program_partial_label?>"></td>
            </tr>
            <tr>
                  <td><label>Qualification</label></td>
                  <td><?=form_dropdown('program_id', $programs, $program_id, " class='chosen' ")?></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('program_partial_id', $program_partial_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_program_partial/">Save Partial Qualification</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>