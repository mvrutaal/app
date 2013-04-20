<div id="title_block">
	<h2>Cities</h2>
	<a href="#" class="linkbtn add dataset-add" data-url="datasets/ajax/city_add"><span>New City</span></a>
	<input type="text" class="global_filter" placeholder="Filter">
</div>

<div id="content">
	<table id="CitiesDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="datasets/ajax/city_datatable/" data-name="cities" data-savestate="no">
		<thead>
			<tr>
				<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>City</th>
				<th>Count</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<br clear="all">
</div>

<script type="text/javascript">
CRM.DatatablesCols['cities'] = [];
CRM.DatatablesCols['cities'].push({mDataProp:'city_id', bSortable: true, sWidth:'38px'});
CRM.DatatablesCols['cities'].push({mDataProp:'city_label', bSortable: true});
CRM.DatatablesCols['cities'].push({mDataProp:'city_count', bSortable: false,});
</script>