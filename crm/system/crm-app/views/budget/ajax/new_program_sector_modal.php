<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($program_sector_id > 0):?>Edit<?php else:?>Add<?php endif?> Program Sector</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Sector Label</label></td>
                  <td><input name="program_sector_label" type="text" value="<?=$program_sector_label?>"></td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('program_sector_id', $program_sector_id)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="budget/ajax_save_program_sector/">Save Sector</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>
</div>