<script type="text/javascript" src="<?=base_url()?>assets/js/modules/groups.js"></script>

<div id="groups">

<?=form_open('groups/update/', array('id' => 'add_group', 'enctype' => 'multipart/form-data', 'method'=>'POST'));?>
<?php if ($i->group_id > 0):?> <input type="hidden" name="group_id" value="<?=$i->group_id?>"> <?php endif;?>

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#lA">General</a></li>
	</ul>

	<br />
	<div style="width:80%; margin:auto;">
		<input type="submit" class="btn btn-primary btn-large" value="Save Group">

		<?php if ($i->group_id > 0 && $this->acl->can_delete($this->session->userdata['group'], 'groups')):?>
		<br><br><a href="<?=site_url('groups/delete/'.$i->group_id)?>" class="btn btn-danger delete_warning">Delete Group</a>
		<?php endif; ?>
	</div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2><?php if ($i->group_id > 0):?>Edit Group: <?=$i->group_name?> <?php else:?>New Group<?php endif;?></h2>
	</div>

	<div id="content" class="tab-content">
		<div id="lA" class="tab-pane active">
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
            		<tr>
            			<td><label>Group Name</label></td>
            			<td><input name="group_name" id="group_name" value="<?=$i->group_name?>" type="text"></td>
            		</tr>
            		<tr>
            			<td><label>Group Contents</label></td>
            			<td id="group_item_types">
            				<?=form_dropdown('group_item_types', $item_types, $i->group_item_types, (($i->group_id > 0) ? 'disabled' : ''))?>
            				<?php if ($i->group_id > 0):?><?=form_hidden('group_item_types', $i->group_item_types)?><?php endif;?>
            				<br><small style="font-size:11px">This cannot be changed once a group has been created</small>
            			</td>
            		</tr>
            		<tr>
						<td><label>Acess Control</label></td>
						<td class="acl_who_toggler">
						    <span class="label <?php if (in_array('everyone', $acl['who'])) echo "label-success"?>" data-value="everyone">Everyone</span>
						    <span class="label <?php if (in_array('author', $acl['who'])) echo "label-success"?>" data-value="author">Only Original Author</span>
						    <span class="label <?php if (in_array('user_roles', $acl['who'])) echo "label-success"?>" data-value="user_roles">User Roles</span>
						    <span class="label <?php if (in_array('users', $acl['who'])) echo "label-success"?>" data-value="users">Individual Users</span>

						    <?php foreach($acl['who'] as $who):?>
						    <input name="acl[who][]" value="<?=$who?>" class="acl-<?=$who?>" type="hidden">
						    <?php endforeach;?>
						</td>
					</tr>
					<tr class="acl_type-user_roles <?php if (in_array('user_roles', $acl['who']) == FALSE) echo "hidden"?>">
					       <td><label>User Roles</label></td>
					       <td><?=form_multiselect('acl[user_roles][]', $user_roles, $acl['user_roles'], " class='chosen' style='width:300px' ")?></td>
					</tr>
					<tr class="acl_type-users <?php if (in_array('users', $acl['who']) == FALSE) echo "hidden"?>">
					       <td><label>Individual Users</label></td>
					       <td><?=form_multiselect('acl[users][]', $users, $acl['users'], " class='chosen' style='width:300px' ")?></td>
					</tr>
					 <tr>
					       <td><label>Exclude User</label></td>
					       <td><?=form_multiselect('acl[exclude][]', $users, $acl['exclude'], " class='chosen' style='width:300px' ")?></td>
					</tr>
					<tr>
					      <td colspan="2">
					            <small style="font-size:10px;">
					                  Choosing "Only Me" or "Everyone" will override all other Access Controls
					            </small>
					      </td>
					</tr>
            	</tbody>
            </table>
            <br>

            <div class="link_types type-contacts hidden">
				<table id="ContactsSimpleDT" class="CrmTable datatable" cellspacing="0" cellpadding="0" border="0" style="width:70%" data-disabled="yes">
				<thead>
					<tr>
						<th colspan="10">
							<h4>
								Contacts in this Group &nbsp;
								<a href="#" class="linkbtn add add_link" data-type="contacts"><span>Add Contacts</span></a>

								<div class="searchwrapper hidden">
									<table id="ContactsSimpleDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="contacts/ajax_datatable/" data-name="contacts" data-addicon="yes" data-savestate="no">
										<thead>
											<tr>
												<th colspan="10">
													<div class="global_filter">Filter <input type="text"></div>
												</th>
											</tr>
											<tr>
												<th style="width:42px">ID</th>
												<?php foreach($dtcols['contacts'] as $col_name => $col):?>
												<th><?=$col['name']?></th>
												<?php endforeach;?>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>

							</h4>
						</th>
					</tr>
					<tr>
						<th style="width:42px">ID</th>
						<th>First Name</th>
						<th>Last Name</th>
					</tr>
				</thead>
				<tbody class="item_list">
				<?php if ($i->group_item_types == 'contacts'):?>
					<?php foreach($i->linked_items as $item):?>
					<tr>
						<td>
								<?=$item->contact_id?>
								<a class="delete" href="#"></a>
								<input type="hidden" name="linked_contacts[]" value="<?=$item->contact_id?>">
						</td>
						<td><?=$item->first_name?></td>
						<td><?=$item->last_name?></td>
					</tr>
					<?php endforeach; ?>
				<?php endif;?>

				<?php if (count($i->linked_items) == 0):?> <tr class="NoItems"><td colspan="3">No Contacts have yet been linked</td></tr> <?php endif;?>
				</tbody>
				</table>
			</div>

            <div class="link_types type-companies hidden">
				<table id="CompaniesSimpleDT" class="CrmTable datatable" cellspacing="0" cellpadding="0" border="0" style="width:70%" data-disabled="yes">
				<thead>
					<tr>
						<th colspan="10">
							<h4>
								Companies in this Group &nbsp;
								<a href="#" class="linkbtn add add_link" data-type="company"><span>Add Companies</span></a>

								<div class="searchwrapper hidden">
									<table id="CompaniesSimpleDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="companies/ajax_datatable/" data-name="companies" data-addicon="yes" data-savestate="no">
										<thead>
											<tr>
												<th colspan="10">
													<div class="global_filter">Filter <input type="text"></div>
												</th>
											</tr>
											<tr>
												<th style="width:38px">ID</th>
												<?php foreach($dtcols['companies'] as $col_name => $col):?>
												<th><?=$col['name']?></th>
												<?php endforeach;?>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>

							</h4>
						</th>
					</tr>
					<tr>
						<th style="width:42px">ID</th>
						<th>Company Name</th>
						<th>Tel. Number</th>
					</tr>
				</thead>
				<tbody class="item_list">
				<?php if ($i->group_item_types == 'companies'):?>
					<?php foreach($i->linked_items as $item):?>
					<tr>
						<td>
								<?=$item->company_id?>
								<a class="delete" href="#"></a>
								<input type="hidden" name="linked_companies[]" value="<?=$item->company_id?>">
						</td>
						<td><?=$item->company_title?></td>
						<td><?=$item->company_tel_number?></td>
					</tr>
					<?php endforeach; ?>
				<?php endif;?>

				<?php if (count($i->linked_items) == 0):?> <tr class="NoItems"><td colspan="3">No Companies have yet been linked</td></tr> <?php endif;?>
				</tbody>
				</table>
			</div>



		</div>
	</div>
	<div class="clear"></div>

</div>
<div class="clear"></div>

<?=form_close();?>
</div>



<script type="text/javascript">
CRM.DatatablesCols['companies'] = [];
CRM.DatatablesCols['companies'].push({mDataProp:'company_id', bSortable: false, sWidth:'38px'});
<?php foreach($dtcols['companies'] as $col_name => $col):?>
CRM.DatatablesCols['companies'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>

CRM.DatatablesCols['contacts'] = [];
CRM.DatatablesCols['contacts'].push({mDataProp:'contact_id', bSortable: false, sWidth:'38px'});
<?php foreach($dtcols['contacts'] as $col_name => $col):?>
CRM.DatatablesCols['contacts'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>

</script>

</div>