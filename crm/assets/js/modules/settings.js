// ********************************************************************************* //
var CRM = CRM ? CRM : new Object();
//********************************************************************************* //
$(document).ready(function() {

	var Settings = $('#settings');
	Settings.delegate('a.ajax', 'click', CRM.AjaxOpen);

	// General Saving
	$('#ModalWrapper').delegate('.ajax_save', 'click', CRM.AjaxSave);

	// Toggle all other tabs
	$('#sidebar .tabs a').on('shown', function (e) {

		$('#sidebar .tabs a').each(function(i, elem){
			if (elem == e.target) return;

			jQuery(elem).parent().removeClass('active');
		});
    });


    // ACL Toggle!
    $('#acl').find('a.acl_toggle').click(CRM.AclToggle);

});

//********************************************************************************* //

CRM.AjaxOpen = function(Event){
	Event.preventDefault();

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Figure out the path to the modal contents
	var Path = (Event.nodeName != 'A') ? $(Event.target).closest('a').data('url') : $(Event.target).data('url');

	// Open the modal and get it's content
	ModalWrapper.modal().empty().load(CRM.BASE+Path, {}, function(){

		// Activate Chosen!
		ModalWrapper.find('select.chosen').chosen();

	});

	// Remove the style attribute, so when we recall it, the fade effect happens
	ModalWrapper.on('hidden', function () {
		$('#ModalWrapper').removeAttr('style');
	});

};

//********************************************************************************* //

CRM.AjaxSave = function(Event){
	Event.preventDefault();

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Gather all form fields
	var Params = ModalWrapper.find('.modal-body').find(':input').serializeArray();

	// Build the AJAX URL
	var URL = CRM.BASE + jQuery(Event.target).data('url');

	// Execute the POST
	$.post(URL, Params, function(rData){

		// Hide the modal!
		ModalWrapper.modal('hide');

	});
};

//********************************************************************************* //

CRM.AclToggle = function(Event){
	Event.preventDefault();

	var BTN = jQuery(Event.target);
	var Parent = BTN.closest('td');

	// Toggle to DENIED?
	if (BTN.hasClass('btn-success') == true){
		
		// Remove the class and add the other one
		BTN.removeClass('btn-success').addClass('btn-danger');

		// Add the text
		BTN.html('Denied');

		// Fill in!
		Parent.find('input').attr('value', '0');
	}
	else {
		// Remove the class and add the other one
		BTN.removeClass('btn-danger').addClass('btn-success');

		// Add the text
		BTN.html('Allowed');

		// Fill in!
		Parent.find('input').attr('value', '1');
	}
	
};

//********************************************************************************* //