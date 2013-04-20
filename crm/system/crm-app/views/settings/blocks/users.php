<div id="title_block">
	<h2>Users</h2>
	<a href="#" class="linkbtn add ajax" data-url="settings/ajax/add_user"><span>New User</span></a>
</div>

<div id="content">
	<table class="CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Username</th>
				<th>Full Name</th>
				<th>Email</th>
				<th>Group</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($users as $user):?>
			<tr>
				<td><a href="#" class="ajax" data-url="settings/ajax/add_user/<?=$user['id']?>"><?=$user['id']?></a></td>
				<td><?=$user['username']?></td>	
				<td><?php $temp = $this->db->select('first_name, last_name')->from('crm_contacts')->where('contact_id', $user['contact_id'])->get(); echo $temp->row('first_name') .' '. $temp->row('last_name');?></td>
				<td><?=$user['email'];?></td>
				<td><?=$user['group_description'];?></td>
				<td><?php echo ($user['active']) ? anchor("auth/deactivate/".$user['id'], 'Active') : anchor("auth/activate/". $user['id'], 'Inactive');?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>