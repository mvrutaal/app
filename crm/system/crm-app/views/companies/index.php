<script type="text/javascript" src="<?=base_url()?>assets/js/modules/companies.js"></script>
<div id="companies">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
			<input type="text" name="company_title" placeholder="Company Name">
		</div>
		<div class="filter">
			<?=form_multiselect('company_types[]', $company_types, '', ' class="chosen" data-placeholder="Company Type" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('company_employee_count[]', $dropdowns['company_employees'], '', ' class="chosen" data-placeholder="Employees" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('company_languages[]', $dropdowns['languages'], '', ' class="chosen" data-placeholder="Languages" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('company_authors[]', $authors, '', ' class="chosen" data-placeholder="Authors" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('suburb[]', $suburbs, '', ' class="chosen" data-placeholder="Suburb" style="width:100%;"')?>
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
		<h2>Companies</h2>
		<?php if ($this->acl->can_write($this->session->userdata['group'], 'companies')):?>
		<a href="<?=site_url('companies/add')?>" class="linkbtn add"><span>New Company</span></a>
		<?php endif;?>
		|
		<?php if ($this->acl->can_write($this->session->userdata['group'], 'groups')):?>
		<a href="#" class="linkbtn ExecAction disabled add2group"><span>Add To Group</span></a>
		<?php endif;?>
	</div>

	<div id="content">

		<table id="CompaniesDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="companies/ajax_datatable/" data-name="companies" data-checkable="yes">
			<thead>
				<tr>
					<th style="width:42px"><input type="checkbox" class="CheckAll">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID&nbsp;&nbsp;&nbsp;</th>
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

		<div class="clear"></div>
	</div>


</div>
<div class="clear"></div>


<script type="text/javascript">
CRM.DatatablesCols['companies'] = [];
CRM.DatatablesCols['companies'].push({mDataProp:'company_id', bSortable: false, sWidth:'42px'});

<?php foreach ($standard_cols as $col_name => $col):?>
CRM.DatatablesCols['companies'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>

<?php foreach ($extra_cols as $col_name => $col):?>
CRM.DatatablesCols['companies'].push({mDataProp:'<?=$col_name?>', bVisible: false, bSortable: <?=$col['sortable']?>});
<?php endforeach;?>
</script>

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