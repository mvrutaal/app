<table id="GroupsSimpleDT" class="CrmTable" cellspacing="0" cellpadding="0" border="0" style="width:90%">
	<thead>
	<tr>
		<?php if (isset($first_col) == TRUE):?><th style="width:20px"></th><?php endif;?>
		<th style="width:30px">ID</th>
		<th>Group Name</th>
		<th>Total Items</th>
	</tr>
</thead>
<tbody class="item_list">
	<?php foreach($groups as $group):?>
	<tr>
		<?php if (isset($first_col) == TRUE):?>
		<td>
			<?php if ($first_col == 'radio'):?> <input type="radio" value="<?=$group->group_id?>" name="group"> <?php endif;?>
		</td>
		<?php endif;?>
		<td><?=$group->group_id?></td>
		<td><?=$group->group_name?></td>
		<td><?=$group->group_total_items?></td>
	</tr>
	<?php endforeach; ?>
<?php if (count($groups) == 0):?> <tr class="NoItems"><td colspan="3">No Groups found...</td></tr> <?php endif;?>
</tbody>
</table>