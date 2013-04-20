<div class='mainInfo'>

	<h1></h1>
	<p></p>

	<div id="infoMessage"><?php echo $message;?></div>

	<table cellpadding=0 cellspacing=10>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Group</th>
			<th>Status</th>
			<th>Edit</th>
		</tr>
		<?php foreach ($users as $user):?>
			<tr>
				<td><?=$user['first_name']?></td>
				<td><?=$user['last_name']?></td>
				<td><?=$user['email'];?></td>
				<td><?=$user['group_description'];?></td>
				<td><?php echo ($user['active']) ? anchor("auth/deactivate/".$user['id'], 'Active') : anchor("auth/activate/". $user['id'], 'Inactive');?></td>
				<td><?=anchor("settings/edit_user/".$user['id'], 'Edit User');?></td>
			</tr>
		<?php endforeach;?>
	</table>

	<p><a href="<?php echo site_url('auth/create_user');?>">Create a new user</a></p>

	<p><a href="<?php echo site_url('auth/logout'); ?>">Logout</a></p>

</div>
