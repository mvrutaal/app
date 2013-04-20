<script type="text/javascript" src="<?=base_url()?>assets/js/modules/budget.js"></script>
<div id="budget" class="clear">

<style type="text/css">
.reportbox .label {display:block; margin:0 0 5px; cursor:pointer}
</style>

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>OVERVIEW</h6>
	<div class="reportbox" style="padding:10px;">
		<span class="label reportbtn" data-id="" data-type="grand">Grand Overview</span>
	</div>

	<?php foreach($program_sectors as $sector_id => $sector_label): ?>
	<h6><?=$sector_label?></h6>
	<div class="reportbox" style="padding:10px;">
		<?php foreach($program_departments[$sector_id] as $department_id => $dep_label):?>
		<span class="label reportbtn" data-id="<?=$department_id?>" data-type="department"><?=$dep_label?></span>
		<?php endforeach; ?>
	</div>
	<?php endforeach;?>

	<h6>Misc</h6>
	<div class="reportbox" style="padding:10px;">
		<span class="label reportbtn" data-id="" data-type="accounts">Account List</span>
		<span class="label reportbtn" data-id="" data-type="items">Item List</span>
	</div>
</div>

<div id="contentwrapper" style="padding-bottom:50px">
	<div id="title_block">
		<h2>Reports</h2>
	</div>

	<div id="content" style="position:relative; height:500px; width:98%;">
		<div id="PDFLOADING" class="hidden" style="position:absolute; z-index:800"><img src="<?=base_url()?>assets/images/loading_text.gif"></div>
		<div id="PDFWRAPPER" style="position:absolute; height:500px; width:100%; z-index:900">
			<h2 style="padding:30px">Please Select a Report. <br>
				<small>Note that sometimes the actual loading might take a while...</small>
			</h2>
		</div>
	</div>

	
</div>


</div>