// ********************************************************************************* //
var CRM = CRM ? CRM : new Object();
CRM.prototype = {}; // Get Outline Going
CRM.SelectedContacts = {};
//********************************************************************************* //
$(document).ready(function() {

	// Execute Action
	$('a.ExecAction.export').click(CRM.GenerateExcel);
	$('a.ExecAction.pdf').click(CRM.GeneratePdf);
	//$('a.ExecAction.pdf').click(CRM.PdfReportActionModal);
	$('a.ExecAction.add2group').click(CRM.Add2GroupAction);
	$('a.ExecAction.vcard').click(CRM.GenerateVcard);

	//----------------------------------------
	// Datatables
	//----------------------------------------
	if (document.getElementById('DooraccessDT') != null){

		// Store it for easy access later
		CRM.DooraccessDT = jQuery('#DooraccessDT');

		CRM.CheckboxTimeout = 0;
		CRM.DooraccessDT.delegate('tbody tr', 'click', CRM.ToggleExecActionVis);
	}

	//----------------------------------------
	// Add/Edit Contacts
	//----------------------------------------
	if (document.getElementById('add_contact') != null){

		// Live preview Contact FirstName/lastName
		$('#first_name, #last_name').keyup(function(){
			$('#title_block .contact_name').html( $('#first_name').val() + ' ' + $('#last_name').val());
		});

		// Toggle all other tabs
		$('#sidebar .tabs a').on('shown', function (e) {
			for (i in CRM.Datatables) {
				CRM.Datatables[i].fnAdjustColumnSizing(false);
			};
	    });
	}

});

//********************************************************************************* //

CRM.ToggleExecActionVis = function(Event){
	
	clearTimeout(CRM.CheckboxTimeout);

	CRM.CheckboxTimeout = setTimeout(function(){
		if (CRM.DooraccessDT.find('tbody').find('tr.Checked').length > 0){
			$('a.ExecAction').removeClass('disabled');
		}
		else {
			$('a.ExecAction').addClass('disabled');
		}
	}, 200);

	// Get The Current Index
	var Index = $(this).index();

	// Get Current Row Data
	var Data = CRM.Datatables[ CRM.DooraccessDT.data('name') ].fnGetData(Index);

	if ( $(this).hasClass('Checked') == true ){
		CRM.SelectedContacts[Data.DT_RowId] = Data.first_name + ' ' + Data.last_name;
	}
	else {
		delete CRM.SelectedContacts[Data.DT_RowId];
	}

};

//********************************************************************************* //
CRM.GenerateExcel = function(Event){
	Event.preventDefault();

	// Is the button disabled? do nothing
	if ($(Event.target).hasClass('disabled') == true) return;

	// Lets create our list
	var Contacts = [];
	for (ID in CRM.SelectedContacts){

		// Add to list
		Contacts.push(ID);
	}
	if(Contacts.length == 0){
		alert("Please select atleast one data");
		return false;
	}
	// Send People away!
	window.location.href = CRM.BASE + 'dooraccess/ajax_generate_excel?ids=' + Contacts.join('|');// +'&conditions=' + Conditions.join('|') ;
};


CRM.GeneratePdf = function(Event){
	Event.preventDefault();

	// Is the button disabled? do nothing
	if ($(Event.target).hasClass('disabled') == true) return;
	
	
	// Lets create our list
	var Contacts = [];
	for (ID in CRM.SelectedContacts){

		// Add to list
		Contacts.push(ID);
	}
	if(Contacts.length == 0){
		alert("Please select atleast one data");
		return false;
	}

	// Send People away!
	//window.location.href = CRM.BASE + 'dooraccess/ajax_generate_pdf?ids=' + Contacts.join('|');// +'&conditions=' + Conditions.join('|') ;
	$('#content1').css('display', 'block');
	$('#PDFLOADING').css('display', 'block');
	
	var myPDF = new PDFObject({
		url: CRM.BASE + 'dooraccess/pdf_report_dooraccess?ids=' + Contacts.join('|'),
		pdfOpenParams: {view: 'FitH', toolbar: '1', statusbar: '0', messages: '0', }
	}).embed('PDFWRAPPER');
	
	
};

CRM.ExportActionModal = function(Event){
	
	Event.preventDefault();

	// Is the button disabled? do nothing
	if ($(Event.target).hasClass('disabled') == true) return;

	// Store the Modal for quicker access
	var ExportActionModal = $('#ExportActionModal');

	// Post Params
	var Params = [];

	// Lets create our list
	var Contacts = '<p>';
	for (ID in CRM.SelectedContacts){

		// Add to list
		Contacts += '<span id="C'+ID+'">' + CRM.SelectedContacts[ID] + '</span>';

		// Add to POST params
		Params.push({name:'ids[]', value:ID});
	}
	Contacts + '</p>';

	// Close the warning
	ExportActionModal.find('.action_body .alert').hide();

	// Default Values
	ExportActionModal.find('.action_body .email_subject').val('');
	ExportActionModal.find('.single_emails').removeAttr('checked');

	// Open the Modal and add the contact list!
	ExportActionModal.modal().find('.contact_list').html(Contacts);

	// Send our POST request!
	$.post(CRM.BASE+'contacts/ajax_check_contact_emails', Params, function(rData){

		// Loop over all errors
		if (rData.errors.length > 0){

			// Mark them all
			for (i=0; i < rData.errors.length; i++){
				EmailActionModal.find('#C'+rData.errors[i]).css( {color:'red', 'font-weight':'bold'} );
			}

			// Open the warning
			EmailActionModal.find('.action_body .alert').show();
		}
	}, 'json');

	// We don't want to attacht it two times
	if ( typeof(ExportActionModal.find('.btn-primary').data("events")) != 'undefined'  && typeof(EmailActionModal.find('.btn-primary').data("events").click) != 'undefined') return false;

	// Attach the click handler!
	ExportActionModal.find('.btn-primary').click(function(Event){

		// Store it
		var BTN = $(this);
		var BTNText = BTN.html();
		BTN.html('loading..');

		// Disable the button
		BTN.addClass('disabled').html;

		// Send our POST request!
		$.post(CRM.BASE+'contacts/ajax_get_contact_emails', Params, function(rData){

			BTN.removeClass('disabled').html(BTNText);

			// Email Subject
			var EmailSubject = ExportActionModal.find('.action_body .email_subject').val();

			// Single Email or multiple?
			if (ExportActionModal.find('.single_emails').is(':checked')){
				// Split them into individual emails
				CRM.Emails = rData.split(';');
				var Email = '';

				// Loop over all those emails
				for (i=0; i < CRM.Emails.length; i++){
					//window.open("mailto:" + Emails[i] + '?subject=' + EmailSubject);
					//
					//window.location.href = "mailto:" + Emails[i] + '?subject=' + EmailSubject;
					Email = CRM.Emails[i];

					setTimeout("var iFrame = '<iframe src=\""+CRM.BASE+"contacts/ajax_open_mailto?email="+escape(Email)+"&subject="+escape(EmailSubject)+"\" style=\"position:absolute; left:-9999px;\"></iframe>';" 
        				+ "$('body').append(iFrame);"
        			, (500*i));
				}
			}
			else {
				// open outlook or so
				window.location.href = "mailto:" + rData + '?subject=' + EmailSubject;
			}

			// Close the warning
			ExportActionModal.find('.action_body .alert').hide();

			ExportActionModal.modal('hide');

		}, 'html');

		return false;
	});
};

CRM.OpenEmailAction = function(Event){
	
	Event.preventDefault();

	// Is the button disabled? do nothing
	if ($(Event.target).hasClass('disabled') == true) return;

	// Store the Modal for quicker access
	var EmailActionModal = $('#EmailActionModal');

	// Post Params
	var Params = [];

	// Lets create our list
	var Contacts = '<p>';
	for (ID in CRM.SelectedContacts){

		// Add to list
		Contacts += '<span id="C'+ID+'">' + CRM.SelectedContacts[ID] + '</span>';

		// Add to POST params
		Params.push({name:'ids[]', value:ID});
	}
	Contacts + '</p>';

	// Close the warning
	EmailActionModal.find('.action_body .alert').hide();

	// Default Values
	EmailActionModal.find('.action_body .email_subject').val('');
	EmailActionModal.find('.single_emails').removeAttr('checked');

	// Open the Modal and add the contact list!
	EmailActionModal.modal().find('.contact_list').html(Contacts);

	// Send our POST request!
	$.post(CRM.BASE+'contacts/ajax_check_contact_emails', Params, function(rData){

		// Loop over all errors
		if (rData.errors.length > 0){

			// Mark them all
			for (i=0; i < rData.errors.length; i++){
				EmailActionModal.find('#C'+rData.errors[i]).css( {color:'red', 'font-weight':'bold'} );
			}

			// Open the warning
			EmailActionModal.find('.action_body .alert').show();
		}
	}, 'json');

	// We don't want to attacht it two times
	if ( typeof(EmailActionModal.find('.btn-primary').data("events")) != 'undefined'  && typeof(EmailActionModal.find('.btn-primary').data("events").click) != 'undefined') return false;

	// Attach the click handler!
	EmailActionModal.find('.btn-primary').click(function(Event){

		// Store it
		var BTN = $(this);
		var BTNText = BTN.html();
		BTN.html('loading..');

		// Disable the button
		BTN.addClass('disabled').html;

		// Send our POST request!
		$.post(CRM.BASE+'contacts/ajax_get_contact_emails', Params, function(rData){

			BTN.removeClass('disabled').html(BTNText);

			// Email Subject
			var EmailSubject = EmailActionModal.find('.action_body .email_subject').val();

			// Single Email or multiple?
			if (EmailActionModal.find('.single_emails').is(':checked')){
				// Split them into individual emails
				CRM.Emails = rData.split(';');
				var Email = '';

				// Loop over all those emails
				for (i=0; i < CRM.Emails.length; i++){
					//window.open("mailto:" + Emails[i] + '?subject=' + EmailSubject);
					//
					//window.location.href = "mailto:" + Emails[i] + '?subject=' + EmailSubject;
					Email = CRM.Emails[i];

					setTimeout("var iFrame = '<iframe src=\""+CRM.BASE+"contacts/ajax_open_mailto?email="+escape(Email)+"&subject="+escape(EmailSubject)+"\" style=\"position:absolute; left:-9999px;\"></iframe>';" 
        				+ "$('body').append(iFrame);"
        			, (500*i));
				}
			}
			else {
				// open outlook or so
				window.location.href = "mailto:" + rData + '?subject=' + EmailSubject;
			}

			// Close the warning
			EmailActionModal.find('.action_body .alert').hide();

			EmailActionModal.modal('hide');

		}, 'html');

		return false;
	});
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
	$.get(CRM.BASE+'groups/ajax_get_groups_dt/contacts/', {}, function(rData){
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
		for (ID in CRM.SelectedContacts){

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

CRM.GenerateVcard = function(Event){
	Event.preventDefault();

	// Is the button disabled? do nothing
	if ($(Event.target).hasClass('disabled') == true) return;

	// Lets create our list
	var Contacts = [];
	for (ID in CRM.SelectedContacts){

		// Add to list
		Contacts.push(ID);
	}

	// Send People away!
	window.location.href = CRM.BASE + 'contacts/ajax_generate_vcard?ids=' + Contacts.join('|');
};

//********************************************************************************* //