<script type="text/javascript" src="<?=base_url()?>assets/js/modules/hrm.js"></script>
<div id="contacts" class="clear">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
        	<?=form_multiselect('holiday_type[]', array('1' => 'Sick Leaves', '2' => 'Holiday Leves'), '', ' class="chosen" data-placeholder="Holiday Type" style="width:100%;"')?>
		</div>
		<div class="filter">
			<input type="text" name="from_date" placeholder="From Date" class="datepicker" />
		</div>
		<div class="filter">
			<input type="text" name="to_date" placeholder="To Date" class="datepicker" />
		</div>
		<hr>
	</div>

	<h6>Visible Columns</h6>
	<div class="columns">
		<?php foreach($standard_cols as $name => $col):?>
		<span class="label" rel="<?=$name?>"><?=$col['name']?></span>
		<?php endforeach;?>
	</div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2 id="personalia">Holiday Details for : <span><?php echo $employee_fname." ".$employee_lname;?></span></h2>
	</div>

	<div id="content">

		<table id="leaveDetailsDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="hrm/ajax_leavedatatable/<?php echo $contact_id; ?>" data-name="leaveDetails" data-checkable="yes">
			<thead>
				<tr>
					<th style="width:38px"><input type="checkbox" class="CheckAll">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID&nbsp;&nbsp;&nbsp;</th>
					<?php foreach($standard_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach;?>
                    
					<?php /* foreach($extra_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach; */ ?>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
        <br />
        <div id="content1" style="position:relative; height:500px; width:98%; display:none;">
            <div id="PDFLOADING" class="hidden" style="position:absolute; z-index:800"><img src="<?=base_url()?>assets/images/loading_text.gif"></div>
            <div id="PDFWRAPPER" style="position:absolute; height:500px; width:100%; z-index:900">
                <h2 style="padding:30px">Please Select a Report. <br>
                    <small>Note that sometimes the actual loading might take a while...</small>
                </h2>
        </div>
        </div>
	</div>


</div>


<script type="text/javascript">
CRM.DatatablesCols['leaveDetails'] = [];
CRM.DatatablesCols['leaveDetails'].push({mDataProp:'contact_id', bSortable: false, sWidth:'38px'});

<?php foreach ($standard_cols as $col_name => $col):?>
CRM.DatatablesCols['leaveDetails'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>

</script>



</div>


<div id="PdfReportActionModal" class="modal fade">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>Door Access</h3>
	</div>

	<div class="modal-body">
        
	</div>

	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
	</div>
</div>