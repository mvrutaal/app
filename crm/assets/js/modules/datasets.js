// ********************************************************************************* //
var CRM = CRM ? CRM : new Object();
//********************************************************************************* //
$(document).ready(function() {

	// Toggle all other tabs
	$('#sidebar .tabs a').on('shown', function (e) {
		for (i in CRM.Datatables) {
			CRM.Datatables[i].fnAdjustColumnSizing(false);
		};
    });

    // Global Filter
    CRM.GlobalFilterTimeout = 0;
    $('input.global_filter').keyup(CRM.DatasetsGlobalFilter);

});

//********************************************************************************* //

CRM.DatasetsGlobalFilter = function(Event){

	clearTimeout(CRM.GlobalFilterTimeout);

	CRM.GlobalFilterTimeout = setTimeout(function(){
		var DTName = $(Event.target).closest('.tab-pane').find('.datatable').data('name');

		CRM.Datatables[DTName].fnFilter( $(Event.target).val() );
	}, 200);

};

//********************************************************************************* //
