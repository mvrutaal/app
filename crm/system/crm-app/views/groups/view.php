<div id="groups" class="clear">


<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<?php if ($group_item_types == 'contacts'):?>
		<li><a href="#items" class="smoothscroll">Contacts</a></li>
		<?php endif; ?>
		<?php if ($group_item_types == 'companies'):?>
		<li><a href="#items" class="smoothscroll">Companies</a></li>
		<?php endif; ?>
	</ul>
</div>

<div id="contentwrapper">

	<?php if ($group_item_types == 'contacts'):?>
	<div id="title_block"><h2 id="items">Contacts</h2></div>
	<?php endif; ?>
	<?php if ($group_item_types == 'companies'):?>
	<div id="title_block"><h2 id="items">Companies</h2></div>
	<?php endif; ?>


	<div id="content">
		<table class="table table-striped" cellspacing="0" cellpadding="0" border="0" width="100%">
				<thead>
					<?php if ($group_item_types == 'contacts'):?>
				    <tr>
						<th>Full Name</th>
						<th>Job Title</th>
						<th>Email (Work)</th>
						<th>Email (Personal)</th>
						<th>Tel (Work)</th>
						<th>Tel (Mobile)</th>
				    </tr>
					<?php endif;?>

					<?php if ($group_item_types == 'companies'):?>
				    <tr>
						<th>Company</th>
						<th>Type</th>
						<th>Email</th>
						<th>Tel. Number</th>
				    </tr>
					<?php endif;?>
				</thead>
				<tbody>
			<?php if ($group_item_types == 'contacts'):?>
				<?php foreach($contacts as $row):?>
				<tr>
					<td><a href="<?=site_url('contacts/view/' . $row->contact_id)?>"><?=($row->first_name .' '. $row->last_name)?></a></td>
					<td><?=$row->job_title?></td>
					<td><?=$row->email_work?></td>
					<td><?=$row->email_personal?></td>
					<td><?php if ($row->tel_work_number != FALSE):?>+<?=$row->tel_work_cc?>-<?=$row->tel_work_number?><?php endif;?></td>
					<td><?php if ($row->tel_mobile_number != FALSE):?>+<?=$row->tel_mobile_cc?>-<?=$row->tel_mobile_number?><?php endif;?></td>
				</tr>
				<?php endforeach;?>
				<?php if (empty($contacts) == TRUE):?> <tr><td colspan="10">No contacts have been linked.</td></tr><?php endif;?>	          </tbody>
			<?php endif;?>

			<?php if ($group_item_types == 'companies'):?>
				<?php foreach($companies as $row):?>
				<tr>
					<td><a href="<?=site_url('companies/view/' . $row->company_id)?>"><?=$row->company_title?></a></td>
					<td><?=$row->company_type_label?></td>
					<td><?php if ($row->company_email != FALSE):?> <a href="mailto:<?=$row->company_email?>"><?=$row->company_email?></a><?php endif;?></td>
					<td><?php if ($row->company_tel_number != FALSE):?>+<?=$row->company_tel_cc?>-<?=$row->company_tel_number?><?php endif;?></td>
				</tr>
				<?php endforeach;?>
				<?php if (empty($companies) == TRUE):?> <tr><td colspan="10">No Companies have been linked.</td></tr><?php endif;?>	          </tbody>
			<?php endif;?>
		</table>
	</div>

</div>
</div>