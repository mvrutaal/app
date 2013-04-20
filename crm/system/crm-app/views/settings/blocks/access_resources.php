<div id="title_block">
	<h2>Resources</h2>
	<a href="#" class="linkbtn add ajax" data-url="settings/ajax/add_access_resource/"><span>New Access Resource</span></a>
</div>

<div id="content">
	<table class="CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>Description</th>
			<th>Resource</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($user_resources as $resource):?>
		<tr>
			<td><a href="#" class="ajax" data-url="settings/ajax/add_access_resource/<?=$resource->resource_id?>"><?=$resource->resource_id?></a></td>
			<td><?=$resource->description?></td>
			<td><?=$resource->name?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
	</table>
</div>