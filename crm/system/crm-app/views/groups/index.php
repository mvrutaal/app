<div id="contacts">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
			<input type="text" name="group_name" placeholder="Group Name">
		</div>
		<div class="filter">
			<?=form_multiselect('item_types[]', array('contacts' => 'Contacts', 'companies' => 'Companies'), '', ' class="chosen" data-placeholder="Items Type" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('authors[]', $authors, '', ' class="chosen" data-placeholder="Authors" style="width:100%;"')?>
		</div>
	</div>

	<h6>Visible Columns</h6>
	<div class="columns">
		<?php foreach($standard_cols as $name => $col):?>
		<span class="label" rel="<?=$name?>"><?=$col['name']?></span>
		<?php endforeach;?>
	</div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Groups</h2>
		<?php if ($this->acl->can_write($this->session->userdata['group'], 'groups')):?>
		<a href="<?=site_url('groups/add')?>" class="linkbtn add"><span>New Group</span></a>
		<?php endif;?>
	</div>

	<div id="content">

		<table id="GroupsDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="groups/ajax_datatable/" data-name="groups">
			<thead>
				<tr>
					<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<?php foreach($standard_cols as $col_name => $col):?>
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
CRM.DatatablesCols['groups'] = [];
CRM.DatatablesCols['groups'].push({mDataProp:'group_id', bSortable: false, sWidth:'38px'});

<?php foreach ($standard_cols as $col_name => $col):?>
CRM.DatatablesCols['groups'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>
</script>

</div>