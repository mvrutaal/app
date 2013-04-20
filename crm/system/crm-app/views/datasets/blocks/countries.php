<div id="title_block">
	<h2>Countries</h2>
	<a href="#" class="linkbtn add dataset-add" data-url="datasets/ajax/country_add"><span>New Country</span></a>
	<input type="text" class="global_filter" placeholder="Filter">
</div>

<div id="content">
	<table id="CountriesDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="datasets/ajax/country_datatable/" data-name="countries" data-savestate="no">
		<thead>
			<tr>
				<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>Country Label</th>
				<th>Country Code</th>
				<th>Count</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<br clear="all">
</div>

<script type="text/javascript">
CRM.DatatablesCols['countries'] = [];
CRM.DatatablesCols['countries'].push({mDataProp:'country_id', bSortable: true, sWidth:'38px'});
CRM.DatatablesCols['countries'].push({mDataProp:'country_label', bSortable: true});
CRM.DatatablesCols['countries'].push({mDataProp:'country_code', bSortable: true});
CRM.DatatablesCols['countries'].push({mDataProp:'country_count', bSortable: false,});
</script>