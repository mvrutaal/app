<div id="title_block">
	<h2>Notes Settings</h2>
	<a href="#" class="linkbtn add ajax" data-url="settings/ajax/add_note_type"><span>Add New Note Type</span></a>
</div>

<div id="content">
	<table class="CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>Note Type Label</th>
			<th>Module</th>
			<th>Author</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($notes_types as $type):?>
		<tr>
			<td><a href="#" class="ajax" data-url="settings/ajax/add_note_type/<?=$type->note_type_id?>"><?=$type->note_type_id?></a></td>
			<td><?=$type->note_type_label?></td>
			<td><?=$type->note_type_module?></td>
			<td><?=$type->first_name?>  <?=$type->last_name?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
	</table>
</div>