<div id="title_block">
	<h2>Company Types</h2>
	<a href="#" class="linkbtn add dataset-add" data-url="datasets/ajax/company_types_add"><span>New Company Types</span></a>
	<input type="text" class="global_filter" placeholder="Filter">
</div>

<div id="content">
	<table id="CompanyTypesDT" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="datasets/ajax/company_types_datatable/" data-name="company_types" data-savestate="no">
		<thead>
			<tr>
				<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>Company Type Label</th>
				<th>Count</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<br clear="all">
</div>

<script type="text/javascript">
CRM.DatatablesCols['company_types'] = [];
CRM.DatatablesCols['company_types'].push({mDataProp:'company_type_id', bSortable: true, sWidth:'38px'});
CRM.DatatablesCols['company_types'].push({mDataProp:'company_type_label', bSortable: true});
CRM.DatatablesCols['company_types'].push({mDataProp:'company_type_count', bSortable: false,});
</script>