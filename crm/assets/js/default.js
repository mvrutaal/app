// ********************************************************************************* //
var CRM = CRM ? CRM : {};
CRM.Datatables = {};
CRM.DatatablesCols = {};
CRM.Timeout = 0;
//********************************************************************************* //
$(document).ready(function() {

	// Add Placeholder everywhere
	if ( ('placeholder' in document.createElement('input')) == false){
		$('input[placeholder]').inputHint({fadeOutSpeed: 200, fontFamily:'Helvetica, Arial, sans-serif', fontSize:'12px', hintColor:'#888', padding:'4px'});
	}

	// Activate Chosen! (but give the browser some time)
	$('select.chosen').chosen({allow_single_deselect: true});

	// Add Focus to the first input element
	$("input.focus:first").focus();

	// When tabs are switched
	$('a[data-toggle="tab"]').on('shown', function (e) {

		// All "Chosen" dropdowns should be recalculated
		$('.chzn-container').css('width', '82%').find('.chzn-drop').css('width', '99.3%');

	});

	// Activate Datepickers
	$('.datepicker').datepicker({dateFormat:'yy-mm-dd', changeYear: true, changeMonth: true, yearRange: '1940:2020', onSelect:function(){
		$(this).trigger('keyup');
	}});
	$('.timepicker').timepicker({
		onSelect:function(){
			$(this).trigger('keyup');
		}
	});
	
	// Activate Tooltips
	$('#menu-tooltips').tooltip({placement:'bottom', selector:'a'});

	// Activate Tooltips
	$('.tooltips').tooltip();

	// Delete Warning
	$('body').delegate('a.delete_warning', 'click', function(){
		var answer = confirm("Are you sure? This will remove all associated data too!");
		if (!answer) return false;
	});

	//----------------------------------------
	// Datatables
	//----------------------------------------
	if ($('table.datatable').length > 0){

		// Store Column element for fast access
		CRM.SidebarColumns = $('#sidebar .columns');
		CRM.SidebarColumns.delegate('span', 'click', CRM.DTColumnToggler);

		// Initialize Filters!
		CRM.SidebarFilters = $('#sidebar .dtfilters');

		// Normal Text Inputs
		var TextInput = 0;
		CRM.SidebarFilters.find('input[type=text]').keyup(function(Event){
			if (Event.target.name == false) return;

			// Clear the timeout
			clearTimeout(TextInput);

			// Trigger a new drawing
			TextInput = setTimeout(function(){
				for (DT in CRM.Datatables) {
					CRM.Datatables[DT].fnDraw();
				}
			}, 300);
		});

		// Dropdowns
		CRM.SidebarFilters.find('select').change(function(Event){
			for (DT in CRM.Datatables) {
				CRM.Datatables[DT].fnDraw();
			}
		});

		// We want a better drop down there
		CRM.SidebarFilters.find('.chzn-drop').css({width:'300px', border:'2px solid #aaa'});

		// Initialize
		CRM.DataTablesInit();

		// Checkbox
		CRM.DataTableElem = $('.datatable');
		CRM.DataTableElem.find('.CheckAll').click(CRM.ToggleCheckAll);
		CRM.DataTableElem.delegate('tbody tr', 'click', CRM.SelectTableTR);
	}

	//----------------------------------------
	// Datasets Quick Add
	//----------------------------------------
	$('body').delegate('a.dataset-add', 'click', CRM.DataSetsQuickAdd);

	//----------------------------------------
	// Note Quick Add
	//----------------------------------------
	$('body').delegate('a.note-add', 'click', CRM.NotesQuickAdd);
	var NotesToggler = $('div.note_type_toggler');
	if ( NotesToggler.length > 0 ) {
		NotesToggler.delegate('.label', 'click', CRM.NotesTypeToggler);
		CRM.NotesTypesToggler = (localStorage) ? JSON.parse( localStorage.getItem('NotesTypeToggler') ) : {};
		if (CRM.NotesTypesToggler == null) CRM.NotesTypesToggler = {};

		for (ID in CRM.NotesTypesToggler){
			if (CRM.NotesTypesToggler[ID] == 'hidden') {
				NotesToggler.find('.note_type_' + ID).removeClass('label-success');
				$('#note_type_' + ID).css({display:'none'});
			}
		}
	}

	//----------------------------------------
	// ACL
	//----------------------------------------
	$('body').delegate('.acl_who_toggler .label', 'click', CRM.ACLTypeToggler);

	//----------------------------------------
	// Smoothscroll
	//----------------------------------------
	$('a.smoothscroll[href^="#"]').bind('click.smoothscroll',function (e) {
	    e.preventDefault();

	    var target = this.hash,
	        $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 500, 'swing', function () {
	        window.location.hash = target;
	    });
	});

});

//********************************************************************************* //

CRM.DataTablesInit = function(){

	$('table.datatable').each(function(index, elem){

		// Store, for quick access
		var DTE = $(elem);

		if (DTE.data('disabled') == 'yes') {
			return;
		}

		// Initialize the datatable
		CRM.Datatables[DTE.data('name')] = DTE.dataTable({
			sPaginationType: 'full_numbers',
			sDom: 'R<"toptable"l>t<"bottomtable" ip>',
			sAjaxSource: CRM.BASE+DTE.data('url'),
			aoColumns:CRM.DatatablesCols[DTE.data('name')],
			bAutoWidth: false,
			iDisplayLength: 15,
			fnServerData: function ( sSource, aoData, fnCallback ) {

				var DT = CRM.Datatables[ $(this).data('name') ];

				// Add all filters to the POST
				var Filters = CRM.SidebarFilters.find(':input').serializeArray();
				for (var attrname in Filters) {
					aoData.push( {name: Filters[attrname]['name'], value:Filters[attrname]['value'] } );
				}

				if ($(this).data('addicon') == 'yes'){
					aoData.push( {name: 'addicon', value:'yes' } );
				}

				// Send the AJAX request
				$.ajax({dataType:'json', type:'POST', url:sSource, data:aoData, success:function(rData){

					// Give it back
					fnCallback(rData);

					// Recalculate column sizes, if it's not the first time
					if (DT) DT.fnAdjustColumnSizing(false);

					// If it's the first time, lets do some magic
					else setTimeout(function(){

						// Find all datatables
						$('table.datatable').each(function(i, e){

							if ($(e).data('disabled') == 'yes') return;

							// And Resize their columns!
							CRM.Datatables[ $(e).data('name') ].fnAdjustColumnSizing(false);
						});
					}, 200);

				}});

			},
			fnDrawCallback: function(){

				

				// Activate Tooltips
				CRM.DataTableElem.find('.edit').tooltip({title:'Edit'});
				CRM.DataTableElem.find('.delete').tooltip({title:'Delete'});
				CRM.DataTableElem.find('.add').tooltip({title:'Add'});
				CRM.DataTableElem.find('.view').tooltip({title:'View'});

			},
			fnInitComplete: function(oSettings, json) {
				
				// Remove all column classes
				CRM.SidebarColumns.find('span').removeClass('label-success');

				// Loop over all rows to check the already checked ones
				if ( typeof(oSettings.aoData[0]) != 'undefined' ){

					// Coumns 2 Index! (only if we have data!)
					CRM.DatatableCols = {};

					var Cols = oSettings.aoColumns;

					// Loop over all columns
					for(col in Cols){

						// Since are already looping over all cols, lets store the mapping here!
						CRM.DatatableCols[ Cols[col].mDataProp ] = col;

						// Is it visible?
						if (Cols[col].bVisible == true)  CRM.SidebarColumns.find('span[rel='+Cols[col].mDataProp+']').addClass('label-success');
					}
				}
			},
			bServerSide: true,
			oLanguage: {
				sLengthMenu: 'Display <select>'+
					'<option value="15">15</option>'+
					'<option value="25">25</option>'+
					'<option value="50">50</option>'+
					'<option value="100">100</option>'+
					'<option value="-1">All</option>'+
					'</select> records'
			},
			oColReorder: {iFixedColumns:1},

			bStateSave: ((DTE.data('savestate') == 'no') ? false : true),
			fnStateSave: function (oSettings, oData) {
				if (localStorage) localStorage.setItem( 'DataTables_'+DTE.data('url'), JSON.stringify(oData) );
	        },
	        fnStateLoad: function (oSettings) {
	           if (localStorage) return JSON.parse( localStorage.getItem('DataTables_'+DTE.data('url')) );
	        },
	        aaSorting: []
		});

		// Global Filter?
		if (DTE.find('.global_filter input').length > 0){
			DTE.find('.global_filter input').keyup(function(EV){
				clearTimeout(CRM.Timeout);
				CRM.Timeout = setTimeout(function(){
					CRM.Datatables[ DTE.data('name') ].fnFilter($(EV.target).val());
				}, 300);
			});
		}

	});

};

// ********************************************************************************* //

CRM.DTColumnToggler = function(Event){

	var Target = $(Event.target);
	var ToggledColumn = $(Event.target).attr('rel');

	// Lets grab the first Datatable
	for (D in CRM.Datatables) {
		var DT = CRM.Datatables[D];
		break;
	}

	if ( typeof(DT.fnSettings().aoData[0]) != 'undefined' ){

		// Create local var
		var Cols = DT.fnSettings().aoColumns;

		// Loop over all cols
		for(col in Cols){
			if (ToggledColumn == Cols[col].mDataProp){

				// Is the column already visible?
				if (Target.hasClass('label-success') == true) {

					// Make it hidden
					DT.fnSetColumnVis(col, false, false);

					// Re-Calculate the column sizes (and don't fetch new data)
					DT.fnAdjustColumnSizing(false);

					// Remove the class of course
					Target.removeClass('label-success');
				}

				// The column was hidden
				else {
					// Mark it visible!
					DT.fnSetColumnVis(col, true);

					// Re-Calculate the column sizes (and don't fetch new data)
					DT.fnAdjustColumnSizing(false);

					// Add the class of course
					Target.addClass('label-success');
				}

			}
		}

	}

};

//********************************************************************************* //

CRM.ToggleCheckAll = function(Event){

	// Grab all TR's
	var TRS = jQuery(Event.target).closest('table').find('tbody tr');

	// Is it Checked?
	if (Event.target.checked == true){

		TRS.each(function(i, elem){
			var TR = $(elem);

			// Is it NOT Checked?
			if (TR.hasClass('Checked') == false){
				TR.click();
			}

		});
	}
	else {

		TRS.each(function(i, elem){
			var TR = $(elem);

			// Is it NOT Checked?
			if (TR.hasClass('Checked') == true){
				TR.click();
			}

		});
	}

	delete TR;
	delete TempCheckBox;
};

//********************************************************************************* //

CRM.SelectTableTR = function(Event){

	var TR = $(this);

	// Only if we can do it
	if (! TR.closest('table').data('checkable')) return;

	// Is it Checked?
	if ( TR.hasClass('Checked') == false ){
		TR.addClass('Checked');
	}
	else {
		TR.removeClass('Checked');
	}
};

//********************************************************************************* //

CRM.DataSetsQuickAdd = function(Event){
	Event.preventDefault();

	var Type = $(Event.target).data('type');

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Figure out the path to the modal contents
	var Path = (Event.nodeName != 'A') ? $(Event.target).closest('a').data('url') : $(Event.target).data('url');

	// Open the modal and get it's content
	ModalWrapper.modal().empty().load(CRM.BASE+Path, {}, function(){

		// Activate Chosen!
		ModalWrapper.find('select.chosen').chosen();

		// General Saving
		ModalWrapper.find('.ajax_save').click(CRM.DataSetsAjaxSave);

		// Find the first input and focus on it!
		ModalWrapper.find('input[type=text]:first').focus();
	});

	// Remove the style attribute, so when we recall it, the fade effect happens
	ModalWrapper.on('hidden', function () {
		$('#ModalWrapper').removeAttr('style');
	});

	// Cache the element who called this
	CRM.DataSetsQuickAddElem = $(Event.target);

};

//********************************************************************************* //


CRM.DataSetsAjaxSave = function(Event){
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

CRM.NotesQuickAdd = function(Event){
	Event.preventDefault();

	var Type = $(Event.target).data('type');

	// Store the Modal Wrapper
	var ModalWrapper = $('#ModalWrapper');

	// Figure out the path to the modal contents
	var Path = (Event.nodeName != 'A') ? $(Event.target).closest('a').data('url') : $(Event.target).data('url');

	// Open the modal and get it's content
	ModalWrapper.modal().empty().load(CRM.BASE+Path, {}, function(){

		// Activate Chosen!
		ModalWrapper.find('select.chosen').chosen();

		// General Saving
		ModalWrapper.find('.ajax_save').click(CRM.NotesAjaxSave);

		// Find the first input and focus on it!
		ModalWrapper.find(':input:first').focus();

		ModalWrapper.find('.datepicker').datepicker({dateFormat:'yy-mm-dd', changeYear: true, changeMonth: true, yearRange: '1940:2020'});
		ModalWrapper.find('.timepicker').timepicker();
	});

	// Remove the style attribute, so when we recall it, the fade effect happens
	ModalWrapper.on('hidden', function () {
		$('#ModalWrapper').removeAttr('style');
	});

	// Cache the element who called this
	CRM.NotesAddElem = $(Event.target);
};

//********************************************************************************* //


CRM.NotesAjaxSave = function(Event){
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

		delete CRM.NotesAddElem;

	}, 'json');
};

//********************************************************************************* //

CRM.NotesTypeToggler = function(Event){
	var Target = $(Event.target);

	if (Target.hasClass('label-success') == true){
		Target.removeClass('label-success');
		$('#note_type_' + Target.data('id')).slideUp('slow');
		CRM.NotesTypesToggler[ Target.data('id') ] = 'hidden';
	}
	else {
		Target.addClass('label-success');
		$('#note_type_' + Target.data('id')).slideDown('slow');
		CRM.NotesTypesToggler[ Target.data('id') ] = 'show';
	}

	if (localStorage) localStorage.setItem( 'NotesTypeToggler', JSON.stringify(CRM.NotesTypesToggler) );

};

//********************************************************************************* //

CRM.ACLTypeToggler = function(Event){

	var Target = $(Event.target);

	if (Target.hasClass('label-success') == true){
		Target.removeClass('label-success');
		Target.closest('td').find('.acl-'+Target.data('value')).remove();
		Target.closest('tbody').find('tr.acl_type-' + Target.data('value')).hide();
	}
	else {
		Target.addClass('label-success');
		Target.closest('td').append('<input name="acl[who][]" class="acl-'+Target.data('value')+'" value="'+Target.data('value')+'" type="hidden">');
		Target.closest('tbody').find('tr.acl_type-' + Target.data('value')).show();
	}
};

//********************************************************************************* //