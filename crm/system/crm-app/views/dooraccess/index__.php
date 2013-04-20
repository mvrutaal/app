<script type="text/javascript" src="<?=base_url()?>assets/js/modules/dooraccess.js"></script>
<div id="contacts" class="clear">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
			<input type="text" name="first_name" placeholder="First Name" />
		</div>
		<div class="filter">
			<input type="text" name="last_name" placeholder="Last Name" />
		</div>
		<div class="filter">
			<input type="text" name="date" placeholder="Date" class="datepicker" />
		</div>
		<div class="filter">
			<input type="text" name="start_time" placeholder="Start Time" class="timepicker" />
		</div>
		<div class="filter">
			<input type="text" name="end_time" placeholder="End Time" class="timepicker" />
		</div>
		<hr>
	</div>

	<h6>Visible Columns</h6>
	<div class="columns">
		<?php foreach($standard_cols as $name => $col):?>
		<span class="label" rel="<?=$name?>"><?=$col['name']?></span>
		<?php endforeach;?>
        <!-- 
		<?php //foreach($extra_cols as $name => $col):?>
		<span class="label" rel="<? //=$name?>"><? //=$col['name']?></span>
		<?php //endforeach;?>
        -->
	</div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Door Access</h2>
		<?php if ($this->acl->can_write($this->session->userdata['group'], 'dooraccess')):?>
		<a href="<?=site_url('dooraccess/add')?>" class="linkbtn add"><span>New Entry</span></a>
		<?php endif;?>
		<a href="#" class="linkbtn ExecAction disabled export"><span>Export to Excel</span></a>
		<a href="#" class="linkbtn ExecAction disabled vcard"><span>Print PDF</span></a>
	</div>

	<div id="content">

		<table id="DooraccessDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="dooraccess/ajax_datatable/" data-name="dooraccess" data-checkable="yes">
			<thead>
				<tr>
					<th style="width:38px"><input type="checkbox" class="CheckAll">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID&nbsp;&nbsp;&nbsp;</th>
					<?php foreach($standard_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach;?>
                    
					<?php /* foreach($extra_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach; */ ?>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>

	</div>


</div>


<script type="text/javascript">
CRM.DatatablesCols['dooraccess'] = [];
CRM.DatatablesCols['dooraccess'].push({mDataProp:'contact_id', bSortable: false, sWidth:'38px'});

<?php foreach ($standard_cols as $col_name => $col):?>
CRM.DatatablesCols['dooraccess'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>

</script>

</div>



<div id="ExportActionModal" class="modal fade">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>Email Contacts</h3>
	</div>

	<div class="modal-body">
		<div class="contact_list"></div>
		<div class="action_body">
			<label>Email Subject</label>
			<input name="email_subject" type="text" class="email_subject">

			<input name="ind" type="checkbox" class="single_emails"> Divide into individual mailmessage <br /><br />

			<div class="alert hidden">
				<h4 class="alert-heading">Heads up!</h4>
				Some contacts are excluded, since there are no emails addresses associated with them.
			</div>
		</div>
		<br clear="all">
	</div>

	<div class="modal-footer">
		<a href="#" class="btn btn-primary">Open Email Application</a>
		<a href="#" class="btn" data-dismiss="modal">Close</a>
	</div>
</div>

<div id="Add2GroupActionModal" class="modal fade">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>Add to Group</h3>
	</div>

	<div class="modal-body">
		<div class="select_wrapper" style="max-height:400px;overflow-x:hidden; overflow-y:scroll;"></div>
	</div>

	<div class="modal-footer">
		<a href="#" class="btn btn-primary">Add to Group</a>
		<a href="#" class="btn" data-dismiss="modal">Close</a>
	</div>
</div>

