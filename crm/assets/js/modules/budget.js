// ********************************************************************************* //
var CRM = CRM ? CRM : new Object();
CRM.prototype = {}; // Get Outline Going
//********************************************************************************* //
$(document).ready(function() {

	$('#budget').delegate('.item-add', 'click', CRM.BudgetItemAdd);
	$('#budget').delegate('.item_category-add', 'click', CRM.BudgetItemCategoryAdd);

	// Qualifications Page
	$('#sidebar .sector-toggler').delegate('.label', 'click', CRM.BudgetOpenSector);
	$('#budget').delegate('.newsubitem', 'click', CRM.BudgetAddSubItem);

	$('#LevelOne').delegate('.blocklink', 'click', CRM.BudgetOpenDepartment);
	$('#LevelTwo').delegate('.blocklink', 'click', CRM.BudgetOpenProgram);
	$('#LevelThree').delegate('.blocklink', 'click', CRM.BudgetOpenPartialProgram);
	$('#LevelThree').delegate('.generallink', 'click', function(){ $('#LevelThree').find('.BudgetManageWrapper').slideToggle('slow'); });
	$('#LevelThree').delegate('.BudgetManage .NewRow', 'click', CRM.BudgetGeneralAddRow);
	$('#LevelThree').delegate('.BudgetManageWrapper .save', 'click', CRM.BudgetSaveProgram);
	$('#LevelFour').delegate('.left span', 'click', CRM.BudgetToggleAccounts);
	$('#LevelFour').delegate('.right .save', 'click', CRM.BudgetSavePartial);

	$('#budget').delegate('.BudgetManage .NewRow', 'click', CRM.BudgetManageAddRow);
	$('#budget').delegate('.BudgetManage select', 'change', CRM.Updateitem);
	$('#budget').delegate('.BudgetManage', 'UpdateTotalPrice', CRM.UpdateTotalPrice);
	$('#budget').delegate('.BudgetManage input[type=text]', 'keyup', function(Event){ $(Event.target).closest('table').trigger('UpdateTotalPrice'); });
	$('#budget').delegate('.BudgetManage .remove', 'click', function(Event){  $(Event.target).closest('tr').fadeOut('slow', function(){ $(this).remove(); }); });
	$('#budget').delegate('.BudgetManage .AddItem', 'click', CRM.AddItemManage);

	/*
	if ($('#grandov_menu').length > 0){
		$('#grandov_menu .menu').dcDrilldown({classParent: 'dd-parent', classActive: 'active', eventType: 'click', linkType: 'breadcrumb', hoverDelay: 300, saveState: false, disableLink: true, resetText: 'Sectors', defaultText: 'Sectors', includeHdr: true, showCount: true, speed: 'fast'});
		$('#grandov_menu').delegate('a', 'click', CRM.BudgetGrandOverviewClick);

		CRM.BudgetGrandOverviewClick();
	}
	*/

	$('#budget').delegate('.reportbox .reportbtn', 'click', CRM.BudgetOpenReport);

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

CRM.BudgetOpenSector = function(Event){

	// Remove all open indicators
	$('#sidebar .sector-toggler').find('span').removeClass('label-success');

	// What Sector?
	var SectorID = $(Event.target).data('id');
	var SectorLabel = $(Event.target).text();
	$(Event.target).addClass('label-success');

	$('#LevelOne .content').empty();

	// Kill the start message
	$('#budget .StartMessage').slideUp('slow', function(){

		// Kill all other levels
		$('#LevelOne, #LevelTwo, #LevelThree, #LevelFour').fadeOut('slow').promise().done(function(){

			$('#LevelOne .title_block').find('h2 span').html(SectorLabel);

			$('#LevelOne .title_block').find('.newsubitem').attr('data-id', SectorID);

			$.get(CRM.BASE + 'budget/ajax_get_sector/' + SectorID, {}, function(rData){
				$('#LevelOne .content').html(rData);

			});

			$('#LevelOne').effect('slide', { direction:"right"}, 1000);
		});

	});


};

//********************************************************************************* //

CRM.BudgetOpenDepartment = function(Event){

	// Remove all open indicators
	$('#LevelOne .blocklink').removeClass('label-success').addClass('label-info');

	// What Department?
	var DepID = $(Event.target).data('id');
	var DepLabel = $(Event.target).text();
	$(Event.target).addClass('label-success').removeClass('label-info');

	// Kill all other levels
	$('#LevelTwo, #LevelThree, #LevelFour').fadeOut('slow').promise().done(function(){

		$('#LevelTwo .title_block').find('h2 span').html(DepLabel);

		$('#LevelTwo .title_block').find('.newsubitem').attr('data-id', DepID);

		$.get(CRM.BASE + 'budget/ajax_get_department/' + DepID, {}, function(rData){
			$('#LevelTwo .content').html(rData);
		});

		$('#LevelTwo').effect('slide', { direction:"right"}, 1000);
	});
};

//********************************************************************************* //

CRM.BudgetOpenProgram = function(Event){

	// Remove all open indicators
	$('#LevelTwo .blocklink').removeClass('label-success').addClass('label-info');

	// What Department?
	var ProgID = $(Event.target).data('id');
	var ProgLabel = $(Event.target).text();
	$(Event.target).addClass('label-success').removeClass('label-info');

	// Kill all other levels
	$('#LevelThree, #LevelFour').fadeOut('slow').promise().done(function(){

		$('#LevelThree .title_block').find('h2 span').html(ProgLabel);

		$('#LevelThree .title_block').find('.newsubitem').attr('data-id', ProgID);

		$.get(CRM.BASE + 'budget/ajax_get_program/' + ProgID, {}, function(rData){
			$('#LevelThree .content').html(rData);
			CRM.SyncOrderNumbers();
		});

		$('#LevelThree').effect('slide', { direction:"right"}, 1000);
	});
};

//********************************************************************************* //

CRM.BudgetOpenProgramGeneral = function(Event){
	$('#LevelThree')
};

//********************************************************************************* //

CRM.BudgetOpenPartialProgram = function(Event){

	// Remove all open indicators
	$('#LevelThree .blocklink').removeClass('label-success').addClass('label-info');

	// What Department?
	var PartialID = $(Event.target).data('id');
	var PartialLabel = $(Event.target).text();
	$(Event.target).addClass('label-success').removeClass('label-info');

	// Kill all other levels
	$('#LevelFour').fadeOut('slow').promise().done(function(){

		$('#LevelFour .title_block ').find('h2 span').html(PartialLabel);

		$.get(CRM.BASE + 'budget/ajax_get_partial_program/' + PartialID, {}, function(rData){
			$('#LevelFour .content').html(rData);
			$('#LevelFour .BudgetManage').trigger('UpdateTotalPrice');
			CRM.SyncOrderNumbers();
		});

		$('#LevelFour').effect('slide', { direction:"right"}, 1000);
	});
};

//********************************************************************************* //

CRM.BudgetToggleAccounts = function(Event){

	var Parent = $(Event.target).closest('.manageblock');
	var ID = $(Event.target).data('id');

	Parent.find('.NothingSelected').hide();
	Parent.find('.BudgetManageWrapper').hide();
	Parent.find('.left span').removeClass('label-success');

	Parent.find('.right').find('#account_id_' + ID).fadeIn('slow');
	$(Event.target).addClass('label-success');
};

//********************************************************************************* //

CRM.BudgetAddSubItem = function(Event){
	Event.preventDefault();

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Figure out the path to the modal contents
	var Path = (Event.nodeName != 'A') ? $(Event.target).closest('a').data('url') : $(Event.target).data('url');

	if ($(Event.target).closest('a').data('id')) Path += $(Event.target).closest('a').data('id');

	// Open the modal and get it's content
	ModalWrapper.modal().empty().load(CRM.BASE+Path, {}, function(){

		// Activate Chosen!
		ModalWrapper.find('select.chosen').chosen();

		// Find the first input and focus on it!
		ModalWrapper.find('input[type=text]:first').focus();

		// General Saving
		ModalWrapper.find('.ajax_save').click(function(E){

			E.preventDefault();

			// Gather all form fields
			var Params = ModalWrapper.find('.modal-body').find(':input').serializeArray();

			// Build the AJAX URL
			var URL = CRM.BASE + jQuery(E.target).data('url');

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

				if (rData.new_item == 'yes'){
					var El = '<a data-id="'+rData.item_id+'" class="blocklink">'+rData.item_label+'</a>';
					$(Event.target).closest('.box_shadow').find('.blocklinks').append(El);
				}

			}, 'json');

		});




	});

	// Remove the style attribute, so when we recall it, the fade effect happens
	ModalWrapper.on('hidden', function () {
		$('#ModalWrapper').removeAttr('style');
	});
};

//********************************************************************************* //

CRM.BudgetManageAddRow = function(Event){
	var Target = $(Event.target);
	var ID = Target.data('account');
	var RowSelect = $('#HiddenItemsSelect').clone();
	RowSelect.find('select').css('width', '90%').addClass('chosen');

	var HTML = [];
	HTML.push('<tr class="ItemRow">');
	HTML.push('<td><input name="account['+ ID +'][items][][quantity]" value="1" type="text" class="RowQuantity"> <span class="remove" data-original-title="Remove"></span></td>');
	HTML.push('<td>'+ RowSelect.html() +' <span class="AddItem"></span></td>');
	HTML.push('<td><input name="account['+ ID +'][items][][desc]" value="" type="text"></td>');
	HTML.push('<td><input name="account['+ ID +'][items][][price]" value="0" type="text" class="RowPrice"> <input name="account['+ ID +'][items][][item_id]" value="0" type="hidden" class="ItemID"></td>');
	HTML.push('<td style="text-align:right;"><span class="RowTotal"></span> <input name="account['+ ID +'][items][][total]" type="hidden" class="RowTotalHidden"></td>');
	HTML.push('</tr>');

	HTML = HTML.join('');

	Target.closest('table').find('tbody').find('.NoItems').hide();
	Target.closest('table').find('tbody').append(HTML);
	CRM.SyncOrderNumbers();

	Target.closest('table').find('tbody tr:last').find('.chosen').chosen();
	Target.closest('table').trigger('UpdateTotalPrice');
	Target.closest('table').find('tbody tr:last').find('.remove').tooltip({placement:'top'});
	return false;
};

//********************************************************************************* //

CRM.Updateitem = function(Event){
	var Target = $(Event.target);

	if (Target.data('type') == 'items') {

		var Price = $(Event.target).find(':selected').data('price');
		var Item_ID = $(Event.target).find(':selected').data('id');

		$(Event.target).closest('tr').find('.RowPrice').attr('value', Price);
		$(Event.target).closest('tr').find('.ItemID').attr('value', Item_ID);

	}

	if (Target.data('type') == 'accounts') {
		$(Event.target).closest('tr').find('.AccountID').attr('value', Target.val());
	}

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

CRM.BudgetSavePartial = function(Event){
	Event.preventDefault();
	var POST = $(Event.target).closest('form').serializeArray();
	var URL = $(Event.target).closest('form').attr('action');

	$(Event.target).attr('value', 'saving...');
	$(Event.target).addClass('disabled').attr('disabled', 'disabled');


	$.post(URL, POST, function(rData){

		$(Event.target).attr('value', 'Save');
		$(Event.target).removeClass('disabled').removeAttr('disabled');

	});
};

//********************************************************************************* //

CRM.BudgetGrandOverviewClick = function(Event){
	var Params = {};



	if (typeof(Event) == 'undefined'){
		Params.type = 'grand';
		Params.id = 0;
	}
	else {
		var Target = (Event.target.nodeName != 'A') ? $(Event.target).closest('a') : $(Event.target);

		Target.closest('.dcjq-drilldown').find('a').removeClass('active');

		if (Target.hasClass('first') == true){
			Params.type = 'grand';
			Params.id = 0;
		}
		else {
			Params.type = Target.data('type');
			Params.id = Target.data('id');
		}

		if (Params.type == 'program'){
			$(Event.target).addClass('active');
		}
	}

	if (typeof(Params.type) == 'undefined') return;

	$.post(CRM.BASE+'budget/grandov_'+Params.type, Params, function(rData){
		$('#budget .grandover .right').html(rData);
	});
};

//********************************************************************************* //

CRM.AddItemManage = function(Event){
	Event.preventDefault();

	CRM.AddItemElem = $(Event.target);

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Figure out the path to the modal contents
	var Path = 'budget/ajax_new_item_modal';

	// Open the modal and get it's content
	ModalWrapper.modal().empty().load(CRM.BASE+Path, {}, function(){

		// Activate Chosen!
		ModalWrapper.find('select.chosen').chosen();

		// General Saving
		ModalWrapper.find('.ajax_save').click(CRM.AddItemManageSave);

		// Find the first input and focus on it!
		ModalWrapper.find('input[type=text]:first').focus();
	});

	// Remove the style attribute, so when we recall it, the fade effect happens
	ModalWrapper.on('hidden', function () {
		$('#ModalWrapper').removeAttr('style');
	});
};

//********************************************************************************* //

CRM.AddItemManageSave = function(Event){

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

		// Any Chosen.js?
		if (rData.chosen != false){
			var ChosenElem = CRM.AddItemElem.closest('td').find('.chosen');

			// Loop over all items and remove the selected ones
			ChosenElem.find('option').removeAttr('selected');

			if (ChosenElem.length > 0) {
				ChosenElem.append( '<option value="'+rData.item_id+'" data-price="'+rData.chosen.item_price+'" selected>'+rData.chosen.item_label+'</option>' ).trigger('liszt:updated').trigger('change');
			}
		}

		delete CRM.DataSetsQuickAddElem;

	}, 'json');
};


//********************************************************************************* //

CRM.BudgetOpenReport = function(Event){
	Event.preventDefault();
	var Target = $(Event.target);
	var ID = Target.data('id');
	var Type = Target.data('type');

	$('#sidebar .reportbtn').removeClass('label-success');

	$(Event.target).addClass('label-success');

	$('#PDFLOADING').css('display', 'block');

	var myPDF = new PDFObject({
		url: CRM.BASE + 'budget/report?type=' + Type + '&id=' + ID,
		pdfOpenParams: {view: 'FitH', toolbar: '1', statusbar: '0', messages: '0', }
	}).embed('PDFWRAPPER');

};

//********************************************************************************* //

CRM.BudgetGeneralAddRow = function(Event){
	var Target = $(Event.target);
	var RowSelect = $('#HiddenAccountsSelect').clone();
	RowSelect.find('select').css('width', '98%').addClass('chosen');

	var ItemRowSelect = $('#HiddenItemsSelect').clone();
	ItemRowSelect.find('select').css('width', '200px').addClass('chosen');

	var HTML = [];
	HTML.push('<tr class="ItemRow">');
	HTML.push('<td>'+RowSelect.html()+'  <input name="account[items][][account_id]" value="0" type="hidden" class="AccountID"></td>');
	HTML.push('<td><input name="account[items][][account_desc]" value="" type="text" style="width:100%"></td>');
	HTML.push('<td><input name="account[items][][item_quantity]" value="1" type="text" class="RowQuantity" style="display:block; float:left; width:65px;"> <span class="remove" data-original-title="Remove"></span></td>');
	HTML.push('<td>'+ ItemRowSelect.html() +' <span class="AddItem"></span></td>');
	HTML.push('<td><input name="account[items][][item_desc]" value="" type="text" style="width:100%"></td>');
	HTML.push('<td><input name="account[items][][item_price]" value="0" type="text" class="RowPrice"> <input name="account[items][][item_id]" value="0" type="hidden" class="ItemID"></td>');
	HTML.push('<td style="text-align:right;"><span class="RowTotal"></span> <input name="account[items][][row_total]" type="hidden" class="RowTotalHidden"></td>');
	HTML.push('</tr>');

	HTML = HTML.join('');

	Target.closest('table').find('tbody').find('.NoItems').hide();
	Target.closest('table').find('tbody').append(HTML);
	CRM.SyncOrderNumbers();

	Target.closest('table').find('tbody tr:last').find('.chosen').chosen();
	Target.closest('table').trigger('UpdateTotalPrice');
	Target.closest('table').find('tbody tr:last').find('.remove').tooltip({placement:'top'});
	return false;
};

//********************************************************************************* //

CRM.BudgetSaveProgram = function(Event){

	Event.preventDefault();
	var POST = $(Event.target).closest('form').serializeArray();
	var URL = $(Event.target).closest('form').attr('action');

	$(Event.target).attr('value', 'saving...');
	$(Event.target).addClass('disabled').attr('disabled', 'disabled');


	$.post(URL, POST, function(rData){

		$(Event.target).attr('value', 'Save');
		$(Event.target).removeClass('disabled').removeAttr('disabled');

	});

};

//********************************************************************************* //