<div id="title_block">
	<h2>User Roles</h2>
	<a href="#" class="linkbtn add ajax" data-url="settings/ajax/add_user_role"><span>New User Role</span></a>
</div>

<div id="content">
	<table class="CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>Description</th>
			<th>Role</th>
			<th>Users</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($user_roles as $role):?>
		<tr>
			<td><a href="#" class="ajax" data-url="settings/ajax/add_user_role/<?=$role->id?>"><?=$role->id?></a></td>
			<td><?=$role->description?></td>
			<td><?=$role->name?></td>
			<td><?=$role->count?></td>
			<td><a href="<?=site_url('settings/acl/'.$role->id)?>" class="linkbtn tune"><span>Manage ACL</span></a></td>
		</tr>
	<?php endforeach;?>
	</tbody>
	</table>
</div>