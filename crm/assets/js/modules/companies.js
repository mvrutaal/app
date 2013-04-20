// ********************************************************************************* //
var CRM = CRM ? CRM : new Object();
CRM.prototype = {}; // Get Outline Going
CRM.SelectedCompanies= {};
//********************************************************************************* //
$(document).ready(function() {

	// Execute Action
	$('a.ExecAction.add2group').click(CRM.Add2GroupAction);

	//----------------------------------------
	// Datatables
	//----------------------------------------
	if (document.getElementById('CompaniesDT') != null){

		// Store it for easy access later
		CRM.CompaniesDT = jQuery('#CompaniesDT');

		CRM.CheckboxTimeout = 0;
		CRM.CompaniesDT.delegate('tbody tr', 'click', CRM.ToggleExecActionVis);
	}

	// Toggle all other tabs
	$('#sidebar .tabs a').on('shown', function (e) {
		for (i in CRM.Datatables) {
			CRM.Datatables[i].fnAdjustColumnSizing(false);
		};
    });


});

//********************************************************************************* //

CRM.ToggleExecActionVis = function(Event){

	clearTimeout(CRM.CheckboxTimeout);

	CRM.CheckboxTimeout = setTimeout(function(){
		if (CRM.CompaniesDT.find('tbody').find('tr.Checked').length > 0){
			$('a.ExecAction').removeClass('disabled');
		}
		else {
			$('a.ExecAction').addClass('disabled');
		}
	}, 200);

	// Get The Current Index
	var Index = $(this).index();

	// Get Current Row Data
	var Data = CRM.Datatables[ CRM.CompaniesDT.data('name') ].fnGetData(Index);

	if ( $(this).hasClass('Checked') == true ){
		CRM.SelectedCompanies[Data.DT_RowId] = Data.first_name + ' ' + Data.last_name;
	}
	else {
		delete CRM.SelectedCompanies[Data.DT_RowId];
	}

};

//********************************************************************************* //

CRM.Add2GroupAction = function(Event){

	Event.preventDefault();

	// Is the button disabled? do nothing
	if ($(Event.target).hasClass('disabled') == true) return;

	// Store the Modal for quicker access
	var Add2GroupActionModal = $('#Add2GroupActionModal');

	// Open the Modal and add the contact list!
	Add2GroupActionModal.modal().find('.select_wrapper').empty();

	// Get all groups!
	$.get(CRM.BASE+'groups/ajax_get_groups_dt/companies/', {}, function(rData){
		Add2GroupActionModal.find('.select_wrapper').html(rData);
	});

	// We don't want to attacht it two times
	if ( typeof(Add2GroupActionModal.find('.btn-primary').data("events")) != 'undefined'  && typeof(Add2GroupActionModal.find('.btn-primary').data("events").click) != 'undefined') return false;

	// Attach the click handler!
	Add2GroupActionModal.find('.btn-primary').click(function(Event){

		// Store it
		var BTN = $(this);
		var BTNText = BTN.html();
		BTN.html('loading..');

		// Disable the button
		BTN.addClass('disabled').html;

		// Add all selected contacts to the POST
		var Params = [];
		for (ID in CRM.SelectedCompanies){

			// Add to POST params
			Params.push({name:'ids[]', value:ID});
		}

		// What group did we select?
		Params.push({name:'group_id', value:Add2GroupActionModal.find('.select_wrapper input[type=radio]:checked').val() });

		// Add to Contacts to Group
		$.post(CRM.BASE + 'groups/ajax_add_items_to_group/', Params, function(rData){

			BTN.removeClass('disabled').html(BTNText);

			Add2GroupActionModal.modal('hide');
		});

		return false;
	});

};

//********************************************************************************* //