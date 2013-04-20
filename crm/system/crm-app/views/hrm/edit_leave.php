<script type="text/javascript">
function validateForm(){
	
	var holiday_hours = $("#holiday_hours").val();	
	var contacts_id = $("select[name=contacts_id]").val();
	var holiday_type = $("select[name=holiday_type]").val(); 
	var fromDate = $("#from_date").val();
	var toTime = $("#to_time").val();	
			
	if(contacts_id == 0){
		alert('Please select Employee name.');
		$("select[name=holiday_type]").focus();
		return false;
	}
	else if(holiday_hours == ""){
		alert('Please fill holiday hours.');
		$("#holiday_hours").focus();
		return false;
	}
	else if(isNaN(holiday_hours)){
		alert('Please provide integer value for holiday.');
		$("#holiday_hours").focus();
		return false;
	}
	else if(holiday_type == 0){
		alert('Please fill holiday type.');
		return false;
	}
	else if(fromDate == ""){
		alert('Please fill From Date field.');
		return false;
	}
	else if(toTime == ""){
		alert('Please fill To Time field.');
		return false;
	}
	
		
	return true;
}

</script>
<style type="text/css">
#edit_leaveDetail label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
	color:#F00;
}
</style>
<div id="dooraccess">


<?php /* if ($contact_id > 0):?> <input type="hidden" name="contact_id" value="<?=$contact_id?>"/> <?php endif; */ ?>


<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#lA">Employee Holiday Details</a></li>
	</ul>

	<br />
</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Edit Entry for:  <?php echo $name; ?></h2>
	</div>

	<div id="content" class="tab-content">
		<div id="lA" class="tab-pane active">
        	<?=form_open('hrm/edit_leave/'.$hrm_id, array('id' => 'edit_leaveDetail', 'enctype' => 'multipart/form-data', 'method'=>'POST', 'onsubmit'=>'return validateForm();'));?>
            <input type="hidden" name="oldAttachment" value="<?php echo $attachment;?>" />
            <input type="hidden" name="contact_id" value="<?php echo $contact_id;?>" />
            <input type="hidden" name="hrm_id" value="<?php echo $hrm_id;?>" /> 
            
            <?php if($this->session->userdata('error')){ ?>
                <label class="error">
                	<?php echo $this->session->userdata('error'); ?>
                </label>
            <?php } ?>
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
                	<tr>
            			<td><label>Holiday Type</label></td>
                        <?php 
							//$holiday_type = array('0' => '', '1' => 'Sick Leave', '2' => 'Holiday Leave');
							if($holiday_type == 1){
								$sickSelected = 'selected="selected"';
								$leaveSelected = ""; 
							}else{
								$sickSelected = "";
								$leaveSelected = 'selected="selected"'; 
							}
						?>
                        
            			<td>
                        	<select name="holiday_type" id="holiday_type" style="width:365px;" class="chosen">
                            	<option <?php echo $sickSelected;?> value="1">Sick Leave</option>
                                <option <?php echo $leaveSelected;?> value="2">Holiday Leave</option>
                            </select>
						</td>
            		</tr>
                    <tr>
                    	<td><label>From Date</label></td>
                        <td>
	                    	<input name="from_date" id="from_date" value="<?php echo date('Y-m-d',strtotime($from_date)); ?>" type="text" class="focus validate[required] datepicker" style="width:73% !important;">
                        </td>    
                    </tr>
                    <tr>
                    	<td><label>To Date</label></td>
                        <td>
	                    	<input name="to_date" id="to_date" value="<?php echo date('Y-m-d',strtotime($to_date)); ?>" type="text" class="focus validate[required] datepicker" style="width:73% !important;">
                        </td>    
                    </tr>
            		<tr>
            			<td><label>Holiday (in hours)</label></td>
            			<td><input name="holiday_hours" id="holiday_hours" value="<?php echo $holiday_taken; ?>" type="text" style="width:73% !important;"></td>
            		</tr>
                    <tr>
            			<td><label>Previous Attachment: </label></td>
                        <?php if($attachment == ""){$attachment = "No attachment found"; } ?>
            			<td><?php echo $attachment; ?></td>
            		</tr>
                    <tr>
            			<td><label>Attachment: </label></td>
            			<td><?php echo form_upload('userfile'); ?></td>
            		</tr>
                    <tr>
                    	<td><a href="<?=site_url('hrm/summary/'.$contact_id)?>" class="btn">Cancel</a></td>
                        <td><input type="submit" class="btn btn-primary btn-large" value="Save Entry" ></td>
                    </tr>
            	</tbody>
            </table>
            
            <?=form_close();?>
		</div>
        
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