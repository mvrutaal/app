<div id="title_block">
	<h2>Suburbs</h2>
	<a href="#" class="linkbtn add dataset-add" data-url="datasets/ajax/suburb_add"><span>New Suburb</span></a>
	<input type="text" class="global_filter" placeholder="Filter">
</div>

<div id="content">
	<table id="SuburbsDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="datasets/ajax/suburb_datatable/" data-name="suburbs" data-savestate="no">
		<thead>
			<tr>
				<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>Suburb</th>
				<th>Count</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<br clear="all">
</div>

<script type="text/javascript">
CRM.DatatablesCols['suburbs'] = [];
CRM.DatatablesCols['suburbs'].push({mDataProp:'suburb_id', bSortable: true, sWidth:'38px'});
CRM.DatatablesCols['suburbs'].push({mDataProp:'suburb_label', bSortable: true});
CRM.DatatablesCols['suburbs'].push({mDataProp:'suburb_count', bSortable: false,});
</script>