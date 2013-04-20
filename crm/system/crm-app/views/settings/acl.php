<script type="text/javascript" src="<?=base_url()?>assets/js/modules/settings.js"></script>

<div id="acl">

<?php echo form_open("settings/acl_update");?>
<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>
</div> <!-- </sidebar> -->

<div id="contentwrapper">

	<div id="title_block">
		<h2>Access Control List - <?=$description?></h2>
	</div>

	<div id="content">

		<table id="acl" class="CrmTable" cellspacing="0" cellpadding="0" border="0">
			<thead>
				<tr>
					<th>Resource</th>
					<th>Read</th>
					<th>Create</th>
					<th>Modify</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($resources as $rid => $rname):?>
			<tr>
				<td><?=$rname?></td>
				<td>
					<?php $val = (isset($perm[$rid]['read']) == FALSE) ? 0 : $perm[$rid]['read'];?>
					<?php if ($val == 1):?> <a href="#" class="acl_toggle btn btn-success">Allowed</a>
					<?php else:?> <a href="#" class="acl_toggle btn btn-danger">Denied</a> <?php endif;?>
					<?=form_hidden("acl[{$rid}][read]", $val)?>
				</td>
				<td>
					<?php $val = (isset($perm[$rid]['write']) == FALSE) ? 0 : $perm[$rid]['write'];?>
					<?php if ($val == 1):?> <a href="#" class="acl_toggle btn btn-success">Allowed</a>
					<?php else:?> <a href="#" class="acl_toggle btn btn-danger">Denied</a> <?php endif;?>
					<?=form_hidden("acl[{$rid}][write]", $val)?>
				</td>
				<td>
					<?php $val = (isset($perm[$rid]['modify']) == FALSE) ? 0 : $perm[$rid]['modify'];?>
					<?php if ($val == 1):?> <a href="#" class="acl_toggle btn btn-success">Allowed</a>
					<?php else:?> <a href="#" class="acl_toggle btn btn-danger">Denied</a> <?php endif;?>
					<?=form_hidden("acl[{$rid}][modify]", $val)?>
				</td>
				<td>
					<?php $val = (isset($perm[$rid]['delete']) == FALSE) ? 0 : $perm[$rid]['delete'];?>
					<?php if ($val == 1):?> <a href="#" class="acl_toggle btn btn-success">Allowed</a>
					<?php else:?> <a href="#" class="acl_toggle btn btn-danger">Denied</a> <?php endif;?>
					<?=form_hidden("acl[{$rid}][del]", $val)?>
				</td>
			</tr>
			<?php endforeach;?>
			</tbody>
		</table>
		<input type="hidden" name="role_id" value="<?=$id?>"/>
		<br><button class="btn btn-large btn-primary">Save ACL</button>
	</div>

</div>
<div class="clear"></div>


<?php echo form_close();?>
</div> <!-- </acl> -->
