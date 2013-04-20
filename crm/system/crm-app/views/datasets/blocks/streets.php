<div id="title_block">
	<h2>Streets</h2>
	<a href="#" class="linkbtn add dataset-add" data-url="datasets/ajax/street_add"><span>New Street</span></a>
	<input type="text" class="global_filter" placeholder="Filter">
</div>

<div id="content">
	<table id="StreetsDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="datasets/ajax/street_datatable/" data-name="streets" data-savestate="no">
		<thead>
			<tr>
				<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>Street Name</th>
				<th>Count</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<br clear="all">
</div>

<script type="text/javascript">
CRM.DatatablesCols['streets'] = [];
CRM.DatatablesCols['streets'].push({mDataProp:'street_id', bSortable: true, sWidth:'38px'});
CRM.DatatablesCols['streets'].push({mDataProp:'street_label', bSortable: true});
CRM.DatatablesCols['streets'].push({mDataProp:'street_count', bSortable: false,});
</script>