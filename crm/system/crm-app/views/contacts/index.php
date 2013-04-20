<script type="text/javascript" src="<?=base_url()?>assets/js/modules/contacts.js"></script>
<div id="contacts" class="clear">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
			<input type="text" name="first_name" placeholder="First Name">
		</div>
		<div class="filter">
			<input type="text" name="last_name" placeholder="Last Name">
		</div>
		<div class="filter">
			<input type="text" name="street" placeholder="Street">
		</div>
		<div class="filter">
			<?=form_multiselect('suburb[]', $suburbs, '', ' class="chosen" data-placeholder="Suburb" style="width:100%;"')?>
		</div>
		<div class="filter">
			<input type="text" name="job_title" placeholder="Job Title">
		</div>
		<div class="filter">
			<input type="text" name="tel_number" placeholder="Tel. Number">
		</div>
		<hr>
		<div class="filter">
			<?=form_multiselect('company[]', $companies, '', ' class="chosen" data-placeholder="Company" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('groups[]', $groups, '', ' class="chosen" data-placeholder="Group" style="width:100%;"')?>
		</div>
	</div>

	<h6>Visible Columns</h6>
	<div class="columns">
		<?php foreach($standard_cols as $name => $col):?>
		<span class="label" rel="<?=$name?>"><?=$col['name']?></span>
		<?php endforeach;?>
		<?php foreach($extra_cols as $name => $col):?>
		<span class="label" rel="<?=$name?>"><?=$col['name']?></span>
		<?php endforeach;?>
	</div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Contacts</h2>
		<?php if ($this->acl->can_write($this->session->userdata['group'], 'contacts')):?>
		<a href="<?=site_url('contacts/add')?>" class="linkbtn add"><span>New Contact</span></a>
		|
		<?php endif;?>
		<a href="#" class="linkbtn ExecAction disabled email"><span>Email</span></a>
		<?php if ($this->acl->can_write($this->session->userdata['group'], 'groups')):?> <a href="#" class="linkbtn ExecAction disabled add2group"><span>Add To Group</span></a> <?php endif;?>
		<a href="#" class="linkbtn ExecAction disabled vcard"><span>vCard</span></a>
	</div>

	<div id="content">

		<table id="ContactsDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="contacts/ajax_datatable/" data-name="contacts" data-checkable="yes">
			<thead>
				<tr>
					<th style="width:38px"><input type="checkbox" class="CheckAll">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID&nbsp;&nbsp;&nbsp;</th>
					<?php foreach($standard_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach;?>
					<?php foreach($extra_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach;?>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>

	</div>


</div>


<script type="text/javascript">
CRM.DatatablesCols['contacts'] = [];
CRM.DatatablesCols['contacts'].push({mDataProp:'contact_id', bSortable: false, sWidth:'38px'});

<?php foreach ($standard_cols as $col_name => $col):?>
CRM.DatatablesCols['contacts'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>

<?php foreach ($extra_cols as $col_name => $col):?>
CRM.DatatablesCols['contacts'].push({mDataProp:'<?=$col_name?>', bVisible: false, bSortable: <?=$col['sortable']?>});
<?php endforeach;?>
</script>

</div>



<div id="EmailActionModal" class="modal fade">
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

