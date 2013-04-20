<script type="text/javascript" src="<?=base_url()?>assets/js/modules/budget.js"></script>
<div id="budget" class="clear">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sectors</h6>
	<div class="columns sector-toggler">
		<?php foreach($program_sectors as $sector_id => $sector_label): ?>
		<span class="label label-success sector_id_<?=$sector_id?>" data-id="<?=$sector_id?>" ><?=$sector_label?></span>
		<?php endforeach; ?>
		<a href="#" class="linkbtn add item_category-add" data-url="budget/ajax_new_program_sector_modal"><span>New Sector</span></a>
	</div>

	<h6>Quick Manage</h6>
	<div>

	</div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Qualifications</h2>
		<a href="#" class="linkbtn add item_category-add" data-url="budget/ajax_new_program_modal"><span>New Qualification</span></a>
		<a href="#" class="linkbtn add item_category-add" data-url="budget/ajax_new_program_department_modal"><span>New Department</span></a>
	</div>

	<div id="content">
	<?php foreach($program_sectors as $sector_id => $sector_label): ?>
	<div id="sector_id_<?=$sector_id?>">

		<table class="CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-radius: 4px 4px 0 0;">
			<thead>
				<tr><th colspan="10" style="text-align: center"><h4><?=$sector_label?></h4></th></tr>
			</thead>
		</table>

		<?php if (isset($program_departments[$sector_id]) == FALSE) $program_departments[$sector_id] = array();?>
		<?php foreach($program_departments[$sector_id] as $dep_id => $dep_label):?>
		<table class="table table-striped table-bordered" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-radius:0;">
			<thead>
			<tr>
				<tr><th colspan="10" class="onhovertrigger">
					<h4>
					<a href="#" class="item_category-add" data-url="budget/ajax_new_program_department_modal/edit/<?=$dep_id?>"><?=$dep_label?></a> &nbsp;&nbsp;&nbsp;
					<a href="#" class="linkbtn add item_category-add" data-url="budget/ajax_new_program_modal/<?=$dep_id?>"><span>&nbsp;</span></a>
					<span class="onhover"><a href="#"><span class="badge badge-success">Manage</span></a></span>
					</h4>
				</th></tr><!--
				<tr>
					<th>Qualification</th>
					<th>Partial Qualifications</th>
				</tr> -->
			</thead>
			<tbody>
				<?php if (isset($programs[$dep_id]) == FALSE) $programs[$dep_id] = array();?>
				<?php foreach($programs[$dep_id] as $program_id => $prog):?>
				<tr>
					<td class="onhovertrigger" style="width:50%">
						<a href="#" class="item_category-add" data-url="budget/ajax_new_program_modal/edit/<?=$prog->program_id?>"><?=$prog->program_label?></a> &nbsp;
						<span class="badge badge-info"><?=$prog->program_students?></span>&nbsp;&nbsp;&nbsp;
						<span class="onhover"><a href="#"><span class="badge badge-success">Manage</span></a></span>
					</td>
					<td style="width:50%">
						<ul class="partials">
						<?php if (isset($programs_partials[$program_id]) == FALSE) $programs_partials[$program_id] = array();?>
						<?php foreach($programs_partials[$program_id] as $program_partial_id => $partial):?>
							<li class="onhovertrigger"><a href="#" class="item_category-add" data-url="budget/ajax_new_program_partial_modal/edit/<?=$program_partial_id?>"><?=$partial->program_partial_label?></a> <span class="onhover"><a href="<?=site_url('budget/manage_partials/'.$partial->program_partial_id)?>"><span class="badge badge-success">Manage</span></a></span></li>
						<?php endforeach; ?>
						</ul>
						<a href="#" class="linkbtn add item_category-add" data-url="budget/ajax_new_program_partial_modal/<?=$prog->program_id?>"><span>New Partial</span></a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php endforeach; ?>

		<br><br>
	</div>
	<?php endforeach; ?>
	</div>


</div>


</div>


