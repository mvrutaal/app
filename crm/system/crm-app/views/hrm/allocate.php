<script type="text/javascript">
function validateForm(){
	
	var initial_holidays = $("#initial_holidays").val();	
	var contacts_id = $("select[name=contacts_id]").val();
	
	if(contacts_id == 0){
		alert('Please select Employee name.');
		return false;
	}
	else if(initial_holidays == ""){
		alert('Please fill total hours of holiday.');
		$("#initial_holidays").focus();
		return false;
	}
	else if(isNaN(initial_holidays)){
		alert('Please provide integer value.');
		$("#initial_holidays").focus();
		return false;
	}
		
	return true;
}

</script>
<style type="text/css">
#add_holidays label.error {
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
		<li class="active"><a data-toggle="tab" href="#lA">Holiday Allocation</a></li>
	</ul>


</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>New Entry: <span class="contact_name"></span></h2>
	</div>

	<div id="content" class="tab-content">
		<div id="lA" class="tab-pane active">
        	<?=form_open('hrm/allocate/', array('id' => 'add_holidays', 'enctype' => 'multipart/form-data', 'method'=>'POST','onsubmit'=>'return validateForm();'));?>
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
                	<tr>
            			<td><label>Employee Name </label></td>
            			<td><?=form_dropdown('contacts_id', $employee, $contact_id, ' class="chosen" data-placeholder="Select Employee" style="width:365px;"')?></td>
            		</tr>
            		<tr>
            			<td><label>Holidays</label></td>
                        <td><input name="initial_holidays" id="initial_holidays" value="" type="text"></td>
            		</tr>
                    <tr>
                    	<td><a href="<?=site_url('hrm/index')?>" class="btn">Cancel</a></td>
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