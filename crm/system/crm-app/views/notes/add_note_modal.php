<div class="modal-header">
      <a class="close" data-dismiss="modal">&times;</a>
      <h3><?php if ($note_id > 0):?>Edit<?php else:?>Add<?php endif?> Note</h3>
</div>

<div class="modal-body">
      <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tbody>
            <tr>
                  <td><label>Note</label></td>
                  <td colspan="3"><textarea name="note_text" type="text" rows="3" style="height:100px; width:92%" maxlength="500"><?=$note_text?></textarea></td>
            </tr>
             <tr>
                  <td><label>Date</label></td>
                  <td>
                        <?=form_input('note_date', (($note_date != FALSE) ? date('Y-m-d', strtotime($note_date)) : ''), " class='datepicker' ");?>
                        <small style="font-size:10px">Today's date will be used if left empty</small>
                  </td>
                  <td><label>Time</label></td>
                  <td>
                        <?=form_input('note_time', (($note_date != FALSE) ? date('G:i', strtotime($note_date)) : ''), " class='timepicker' ");?>
                        <small style="font-size:10px">Current time will be used if left empty</small>
                  </td>
            </tr>
      </tbody>
      </table>

      <?=form_hidden('note_id', $note_id)?>
      <?=form_hidden('note_type_id', $note_type_id)?>
      <?=form_hidden('note_item_id', $note_item_id)?>
      <?=form_hidden('note_item_type', $note_item_type)?>
</div>

<div class="modal-footer">
      <a href="#" class="btn btn-primary ajax_save" data-url="notes/ajax_save_note/">Save Note</a>
      <a href="#" class="btn" data-dismiss="modal">Close</a>

      <?php if ($note_id > 0 && $this->acl->can_delete($this->session->userdata['group'], 'notes')):?><a href="#" class="btn btn-danger ajax_save" style="float:left" data-url="notes/ajax_save_note/delete/">Delete Note</a><?php endif;?>
</div>