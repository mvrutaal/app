// ********************************************************************************* //
var CRM = CRM ? CRM : new Object();
CRM.prototype = {}; // Get Outline Going
//********************************************************************************* //
$(document).ready(function() {

	$('#budget').delegate('.item-add', 'click', CRM.BudgetItemAdd);
	$('#budget').delegate('.item_category-add', 'click', CRM.BudgetItemCategoryAdd);

	var SectorToggler = $('div.sector-toggler');
	if ( SectorToggler.length > 0 ) {
		SectorToggler.delegate('.label', 'click', CRM.SectorToggler);
		CRM.SectorTogglerStorage = (localStorage) ? JSON.parse( localStorage.getItem('SectorToggler') ) : {};
		if (CRM.SectorTogglerStorage == null) CRM.SectorTogglerStorage = {};

		for (ID in CRM.SectorTogglerStorage){
			if (CRM.SectorTogglerStorage[ID] == 'hidden') {
				SectorToggler.find('.sector_id_' + ID).removeClass('label-success');
				$('#sector_id_' + ID).css({display:'none'});
			}
		}
	}


	var AccountToggler = $('div.account-toggler');
	if ( AccountToggler.length > 0 ) {
		AccountToggler.delegate('.label', 'click', CRM.AccountToggler);
		CRM.AccountTogglerStorage = (localStorage) ? JSON.parse( localStorage.getItem('AccountToggler') ) : {};
		if (CRM.AccountTogglerStorage == null) CRM.AccountTogglerStorage = {};

		for (ID in CRM.AccountTogglerStorage){
			if (CRM.AccountTogglerStorage[ID] == 'hidden') {
				AccountToggler.find('.account_id_' + ID).removeClass('label-success');
				$('#account_id_' + ID).css({display:'none'});
			}
		}
	}

	// Adding new row
	$('#budget').delegate('.NewRow', 'click', CRM.ManageAddRow);

	// Selecting item
	$('#budget .BudgetManage').delegate('select', 'change', CRM.Updateitem);
	$('#budget .BudgetManage').bind('UpdateTotalPrice', CRM.UpdateTotalPrice);
	$('#budget .BudgetManage').delegate('input[type=text]', 'keyup', function(Event){ $(Event.target).closest('table').trigger('UpdateTotalPrice'); });
	$('#budget .BudgetManage').trigger('UpdateTotalPrice');
});

//********************************************************************************* //

CRM.BudgetItemAdd = function(Event){
	Event.preventDefault();

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Figure out the path to the modal contents
	var Path = (Event.nodeName != 'A') ? $(Event.target).closest('a').data('url') : $(Event.target).data('url');

	// Open the modal and get it's content
	ModalWrapper.modal().empty().load(CRM.BASE+Path, {}, function(){

		// Activate Chosen!
		ModalWrapper.find('select.chosen').chosen();

		// General Saving
		ModalWrapper.find('.ajax_save').click(CRM.BudgetItemAjaxSave);

		// Find the first input and focus on it!
		ModalWrapper.find('input[type=text]:first').focus();
	});

	// Remove the style attribute, so when we recall it, the fade effect happens
	ModalWrapper.on('hidden', function () {
		$('#ModalWrapper').removeAttr('style');
	});

};

//********************************************************************************* //


CRM.BudgetItemAjaxSave = function(Event){
	Event.preventDefault();

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Gather all form fields
	var Params = ModalWrapper.find('.modal-body').find(':input').serializeArray();

	// Build the AJAX URL
	var URL = CRM.BASE + jQuery(Event.target).data('url');

	// Execute the POST
	$.post(URL, Params, function(rData){

		// Did something go wrong?
		if (rData.success == 'no') {
			// Remove the old ones
			ModalWrapper.find('.modal-body .alert').remove();

			ModalWrapper.find('.modal-body').append('<div class="alert alert-error" style="display: block;">' +
				'<h4 class="alert-heading">Oh snap!</h4>' + rData.body + '</div>');

			ModalWrapper.effect("shake", { times:3 }, 75);
			return;
		}

		// Hide the modal!
		ModalWrapper.modal('hide');

		// Loop over all datatables as refresh?
		for (i in CRM.Datatables) {
			CRM.Datatables[i].fnDraw();
		};

		// Any Chosen.js?
		if (rData.chosen != false){
			var ChosenElem = CRM.DataSetsQuickAddElem.closest('td').find('.chosen');

			if (ChosenElem.length > 0) {
				ChosenElem.append( rData.chosen ).trigger('liszt:updated');
			}
		}

		delete CRM.DataSetsQuickAddElem;

	}, 'json');
};

//********************************************************************************* //

CRM.BudgetItemCategoryAdd = function(Event){
	Event.preventDefault();

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Figure out the path to the modal contents
	var Path = (Event.nodeName != 'A') ? $(Event.target).closest('a').data('url') : $(Event.target).data('url');

	// Open the modal and get it's content
	ModalWrapper.modal().empty().load(CRM.BASE+Path, {}, function(){

		// Activate Chosen!
		ModalWrapper.find('select.chosen').chosen();

		// General Saving
		ModalWrapper.find('.ajax_save').click(CRM.BudgetItemCategoryAjaxSave);

		// Find the first input and focus on it!
		ModalWrapper.find('input[type=text]:first').focus();
	});

	// Remove the style attribute, so when we recall it, the fade effect happens
	ModalWrapper.on('hidden', function () {
		$('#ModalWrapper').removeAttr('style');
	});

};

//********************************************************************************* //


CRM.BudgetItemCategoryAjaxSave = function(Event){
	Event.preventDefault();

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Gather all form fields
	var Params = ModalWrapper.find('.modal-body').find(':input').serializeArray();

	// Build the AJAX URL
	var URL = CRM.BASE + jQuery(Event.target).data('url');

	// Execute the POST
	$.post(URL, Params, function(rData){

		// Did something go wrong?
		if (rData.success == 'no') {
			// Remove the old ones
			ModalWrapper.find('.modal-body .alert').remove();

			ModalWrapper.find('.modal-body').append('<div class="alert alert-error" style="display: block;">' +
				'<h4 class="alert-heading">Oh snap!</h4>' + rData.body + '</div>');

			ModalWrapper.effect("shake", { times:3 }, 75);
			return;
		}

		// Hide the modal!
		ModalWrapper.modal('hide');

		window.location.reload(true);

	}, 'json');
};

//********************************************************************************* //

CRM.SectorToggler = function(Event){
	var Target = $(Event.target);

	if (Target.hasClass('label-success') == true){
		Target.removeClass('label-success');
		$('#sector_id_' + Target.data('id')).slideUp('slow');
		CRM.SectorTogglerStorage[ Target.data('id') ] = 'hidden';
	}
	else {
		Target.addClass('label-success');
		$('#sector_id_' + Target.data('id')).slideDown('slow');
		CRM.SectorTogglerStorage[ Target.data('id') ] = 'show';
	}

	if (localStorage) localStorage.setItem( 'SectorToggler', JSON.stringify(CRM.SectorTogglerStorage) );

};

//********************************************************************************* //

CRM.AccountToggler = function(Event){
	var Target = $(Event.target);

	if (Target.hasClass('label-success') == true){
		Target.removeClass('label-success');
		$('#account_id_' + Target.data('id')).slideUp('slow');
		CRM.AccountTogglerStorage[ Target.data('id') ] = 'hidden';
	}
	else {
		Target.addClass('label-success');
		$('#account_id_' + Target.data('id')).slideDown('slow');
		CRM.AccountTogglerStorage[ Target.data('id') ] = 'show';
	}

	if (localStorage) localStorage.setItem( 'AccountToggler', JSON.stringify(CRM.AccountTogglerStorage) );

};

//********************************************************************************* //
//
CRM.ManageAddRow = function(Event){
	var Target = $(Event.target);
	var ID = Target.data('account');
	var RowSelect = $('.HiddenSelect').clone();
	RowSelect.find('select').addClass('chosen');

	var HTML = [];
	HTML.push('<tr class="ItemRow">');
	HTML.push('<td><input name="account['+ ID +'][items][][quantity]" value="1" type="text" class="RowQuantity"></td>');
	HTML.push('<td>'+ RowSelect.html() +'</td>');
	HTML.push('<td><input name="account['+ ID +'][items][][desc]" value="" type="text"></td>');
	HTML.push('<td><input name="account['+ ID +'][items][][price]" value="0" type="text" class="RowPrice"> <input name="account['+ ID +'][items][][item_id]" value="0" type="hidden" class="ItemID"></td>');
	HTML.push('<td><span class="RowTotal"></span> <input name="account['+ ID +'][items][][total]" type="hidden" class="RowTotalHidden"></td>');
	HTML.push('<td></td>');
	HTML.push('</tr>');

	HTML = HTML.join('');

	Target.closest('table').find('tbody').find('.NoItems').hide();
	Target.closest('table').find('tbody').append(HTML);
	CRM.SyncOrderNumbers();

	Target.closest('table').find('tbody tr:last').find('.chosen').chosen();
	Target.closest('table').trigger('UpdateTotalPrice');
	return false;
};

//********************************************************************************* //

CRM.Updateitem = function(Event){
	var Price = $(Event.target).find(':selected').data('price');
	var Item_ID = $(Event.target).find(':selected').data('id');

	$(Event.target).closest('tr').find('.RowPrice').attr('value', Price);
	$(Event.target).closest('tr').find('.ItemID').attr('value', Item_ID);
	$(Event.target).closest('table').trigger('UpdateTotalPrice');
};

//********************************************************************************* //

CRM.UpdateTotalPrice = function(Event){
	var Table = $(Event.target);
	var TotalPrice = 0;

	Table.find('tbody tr').each(function(){
		var TR = $(this);
		var Quantity = TR.find('.RowQuantity').val();
		var Price = TR.find('.RowPrice').val();
		var Total = Quantity * Price;
		if (isNaN(Total)) Total = 0;

		Total = Total.toFixed(2);
		TR.find('.RowTotalHidden').attr('value', Total);
		TR.find('.RowTotal').html(Total);
		TotalPrice = parseFloat(TotalPrice) + parseFloat(Total);
	});

	Table.find('.TotalTablePrice').html(TotalPrice.toFixed(2));
};

//********************************************************************************* //

CRM.SyncOrderNumbers = function(){

	$('#budget .BudgetManage').each(function(i, btable){
		var Table = $(btable);

		Table.find('tbody tr.ItemRow').each(function(index, tr){
			
			jQuery(tr).find('input, textarea, select').each(function(ielem, Elem){

				if (jQuery(Elem).attr('name')){
					attr = jQuery(Elem).attr('name').replace(/\[items\]\[.*?\]/, '[items][' + (index+1) + ']');
					jQuery(Elem).attr('name', attr);
				}
			});

		});
	});

};

//********************************************************************************* //