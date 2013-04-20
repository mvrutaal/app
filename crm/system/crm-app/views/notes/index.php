<script type="text/javascript" src="<?=base_url()?>assets/js/modules/companies.js"></script>
<div id="companies">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
			<?=form_multiselect('authors[]', $authors, '', ' class="chosen" data-placeholder="Authors" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('note_types[]', $note_types, '', ' class="chosen" data-placeholder="Note Types" style="width:100%;"')?>
		</div>
		<div class="filter">
			<?=form_multiselect('note_item_types[]', $item_types, '', ' class="chosen" data-placeholder="Item Type" style="width:100%;"')?>
		</div>
		<div class="filter">
			<input type="text" name="item_name" placeholder="Item Name">
		</div>
		<div class="filter">
			<input type="text" name="note_text" placeholder="Note Text">
		</div>
		<div class="filter">
			<input type="text" name="date_from" class="datepicker" placeholder="Date From">
		</div>
		<div class="filter">
			<input type="text" name="date_to" class="datepicker" placeholder="Date To">
		</div>
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
		<h2>Notes</h2>
	</div>

	<div id="content">

		<table id="notesDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="notes/ajax_datatable/" data-name="notes">
			<thead>
				<tr>
					<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<?php foreach($standard_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach;?>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>

		<div class="clear"></div>
	</div>


</div>
<div class="clear"></div>


<script type="text/javascript">
CRM.DatatablesCols['notes'] = [];
CRM.DatatablesCols['notes'].push({mDataProp:'note_id', bSortable: false, sWidth:'38px'});

<?php foreach ($standard_cols as $col_name => $col):?>
CRM.DatatablesCols['notes'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>
</script>

</div>