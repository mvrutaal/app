<script type="text/javascript" src="<?=base_url()?>assets/js/modules/contacts.js"></script>
<script type="text/javascript">
function validateForm(type){
	
	if(type == 'in'){
		var inDate = $("#in_date").val();
		var inTime = $("#in_time").val();	
		var in_user_id = $("select[name=in_user_id]").val();
		
		if(in_user_id == 0){
			alert('Please select Employee name.');
			return false;
		}
		else if(inDate == ""){
			alert('Please fill Date field.');
			return false;
		}
		else if(inTime == ""){
			alert('Please fill Time field.');
			return false;
		}
		
	}else if(type == 'out'){
		var outDate = $("#out_date").val();
		var outTime = $("#out_time").val();
		var out_user_id = $("select[name=out_user_id]").val();
		
		if(out_user_id == 0){
			alert('Please select Employee name.');
			return false;
		}
		else if(outDate == ""){
			alert('Please fill Date field.');
			return false;
		}
		else if(outTime == ""){
			alert('Please fill Time field.');
			return false;
		}
	}
		
	return true;
}

</script>
<style type="text/css">
#add_dooraccess label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
}
</style>
<div id="dooraccess">


<?php /* if ($contact_id > 0):?> <input type="hidden" name="contact_id" value="<?=$contact_id?>"/> <?php endif; */ ?>


<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#lA">In</a></li>
        <li><a data-toggle="tab" href="#lB">Out</a></li>
        <!-- 
		<li><a data-toggle="tab" href="#lC">Communication</a></li>
		<li><a data-toggle="tab" href="#lD">Personal</a></li> 
        -->
		<?php // if ($contact_id > 0 && $this->acl->can_read($this->session->userdata['group'], 'notes')):?>
        <!-- <li><a data-toggle="tab" href="#lE">Notes</a></li> -->
        <?php //endif;?>
	</ul>

	<br />
	<div style="width:80%; margin:auto;">
            <?php if ($contact_id > 0 && $this->acl->can_delete($this->session->userdata['group'], 'dooraccess')):?>
            <br><br><a href="<?=site_url('contacts/delete/'.$contact_id)?>" class="btn btn-danger delete_warning">Delete Contact</a>
            <?php endif; ?>
      </div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2><?php if ($contact_id > 0):?>Edit Contact: <span class="contact_name"><?=$first_name?> <?=$last_name?></span> <?php else:?>New Entry: <span class="contact_name"></span> <?php endif;?></h2>
	</div>

	<div id="content" class="tab-content">
		<div id="lA" class="tab-pane active">
        	<?=form_open('dooraccess/add/', array('id' => 'add_dooraccess', 'enctype' => 'multipart/form-data', 'method'=>'POST','onsubmit'=>'return validateForm(\'in\');'));?>
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
                	<tr>
            			<td><label>Employee Name </label></td>
            			<td><?=form_dropdown('in_user_id', $employee, $contact_id, ' class="chosen" data-placeholder="Select Employee" style="width:365px;"')?></td>
            		</tr>
            		<tr>
            			<td><label>In Time</label></td>
            			<td colspan="99">
            				<table cellspacing="0" cellpadding="0" border="0" width="100%">
            					<tbody>
            						<tr>
            							<td><input name="in_date" id="in_date" value="" type="text" class="focus validate[required] datepicker"></td>
                                        <td><label>Time</label></td>
                                        <td><input type="text" name="in_time" id="in_time" class="focus validate[required] timepicker" /></td>
            						</tr>
            					</tbody>
            				</table>
            			</td>
            		</tr>
                    <!--
            		<tr>
            			<td><label>Nickname</label></td>
            			<td><input name="nickname" id="nickname" value="<?//=$nickname?>" type="text"></td>
            		</tr>
            		-->
                    <tr>
                    	<td><a href="<?=site_url('dooraccess/index')?>" class="btn">Cancel</a></td>
                        <td><input type="submit" class="btn btn-primary btn-large" value="Save Entry" ></td>
                    </tr>
            	</tbody>
            </table>
            <?=form_close();?>
		</div>
        <div id="lB" class="tab-pane">
        	<?=form_open('dooraccess/add/', array('id' => 'add_dooraccess', 'enctype' => 'multipart/form-data', 'method'=>'POST','onsubmit'=>'return validateForm(\'out\');'));?>
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
                	<tr>
            			<td><label>Employee Name </label></td>
            			<td><?=form_dropdown('out_user_id', $employee1, $contact_id, ' class="chosen" data-placeholder="Select Employee" style="width:365px;"')?></td>
            		</tr>
            		<tr>
            			<td><label>Out Date</label></td>
            			<td colspan="99">
            				<table cellspacing="0" cellpadding="0" border="0" width="100%">
            					<tbody>
            						<tr>
            							<td><input name="out_date" id="out_date" value="" type="text" class="focus validate[required] datepicker"></td>
                                        <td><label>Time</label></td>
                                        <td><input type="text" name="out_time" id="out_time" class="focus validate[required] timepicker" /></td>
            						</tr>
            					</tbody>
            				</table>
            			</td>
            		</tr>
                    <tr>
                    	<td><a href="<?=site_url('dooraccess/index')?>" class="btn">Cancel</a></td>
                        <td><input type="submit" class="btn btn-primary btn-large" value="Save Entry" ></td>
                    </tr>
            	</tbody>
            </table>
             <?=form_close();?>
		</div>
        <?php /* ?>
        <div id="lE" class="tab-pane">
                  <div class="note_type_toggler">
                        <strong>Note Types:</strong>
                        <?php foreach ($note_types as $note_type_id => $note_type_label): ?>
                        <span class="label label-success note_type_<?=$note_type_id?>" data-id="<?=$note_type_id?>"><?=$note_type_label?></span>
                        <?php endforeach;?>
                  </div>

                  <?php foreach ($note_types as $note_type_id => $note_type_label): ?>
                  <div class="note_section" id="note_type_<?=$note_type_id?>">


                        <table id="NotesDT_<?=$note_type_id?>" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="notes/ajax_datatable_simple/<?=$note_type_id?>/<?=$contact_id?>" data-name="notes_<?=$note_type_id?>">
                              <thead>
                                    <tr>
                                          <th colspan="10">
                                                <h4>
                                                      <?=$note_type_label?>&nbsp;&nbsp;
                                                      <a href="#" class="linkbtn ExecAction add note-add" data-notetype="<?=$note_type_id?>" data-url="notes/ajax_new_note_modal/<?=$note_type_id?>/<?=$contact_id?>/contacts/"><span>Add Note</span></a>
                                                </h4>
                                          </th>
                                    </tr>
                                    <tr>
                                          <th style="width:42px">ID</th>
                                          <?php foreach($notes_cols as $col_name => $col):?>
                                          <th><?=$col['name']?></th>
                                          <?php endforeach;?>
                                    </tr>
                              </thead>
                              <tbody>

                              </tbody>
                        </table>

                        <script type="text/javascript">
                        CRM.DatatablesCols['notes_<?=$note_type_id?>'] = [];
                        CRM.DatatablesCols['notes_<?=$note_type_id?>'].push({mDataProp:'note_id', bSortable: false, sWidth:'38px'});

                        <?php foreach ($notes_cols as $col_name => $col):?>
                        CRM.DatatablesCols['notes_<?=$note_type_id?>'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
                        <?php endforeach;?>
                        </script>
                  </div>
                  <?php endforeach;?>

		</div>
		<?php */ ?>
		<div id="lF" class="tab-pane">
			<table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>

            	</tbody>
            </table>
		</div>

		<div id="lG" class="tab-pane">
			<table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>

            	</tbody>
            </table>
		</div>
	</div>


	<div class="clear"></div>

</div>
<div class="clear"></div>


</div>