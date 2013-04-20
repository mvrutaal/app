// ********************************************************************************* //
var CRM = CRM ? CRM : new Object();
CRM.prototype = {}; // Get Outline Going
//********************************************************************************* //
$(document).ready(function() {

	$('#group_item_types select').change(CRM.ToggleGroupItemType);
	$('#group_item_types select').trigger('change');

	$('.searchwrapper .datatable').delegate('a.add', 'click', CRM.AddGroupItem);

	$('.link_types .add_link').click(function(Event){
		$(Event.target).closest('tr').find('.searchwrapper').toggle();

		for (Name in CRM.Datatables) {
			CRM.Datatables[ Name ].fnAdjustColumnSizing(false);
		}

		return false;
	});

	$('.item_list').delegate('.delete', 'click', function(Event){
		$(Event.target).tooltip('hide');
		$(Event.target).closest('tr').fadeOut('slow', function(){
			$(this).remove();
		});

		return false;
	});

});

//********************************************************************************* //

CRM.ToggleGroupItemType = function(Event){

	$('#content').find('div.link_types').css('display', 'none');
	$('#content').find('div.type-' + $(Event.target).val()).css('display', 'block');

};

//********************************************************************************* //

CRM.AddGroupItem = function(Event){

	Event.preventDefault();

	var Target = $(Event.target);
	var Name = Target.closest('table').data('name');
	var Data = CRM.Datatables[ Name ].fnGetData( Target.closest('tr').index() );

	if (Name == 'contacts'){
		CRM.AddItem_Contact(Data, Name);
	}
	else if (Name == 'companies'){
		CRM.AddItem_Company(Data, Name);
	}

	Target.closest('tr').fadeOut('fast');
};

//********************************************************************************* //

CRM.AddItem_Contact = function(Data, Name){

	var TR = $('<tr></tr>');
	TR.append('<td>' + Data.DT_RowId + ' <a class="delete" href="#"></a> <input type="hidden" name="linked_contacts[]" value="'+Data.DT_RowId+'"></td>');
	TR.append('<td>' + Data.first_name + '</td>');
	TR.append('<td>' + Data.last_name + '</td>');

	$('.type-contacts .item_list').find('tr.NoItems').hide();
	$('.type-contacts .item_list').append(TR);
};

//********************************************************************************* //

CRM.AddItem_Company = function(Data, Name){

	var TR = $('<tr></tr>');
	TR.append('<td>' + Data.DT_RowId + ' <a class="delete" href="#"></a> <input type="hidden" name="linked_companies[]" value="'+Data.DT_RowId+'"></td>');
	TR.append('<td>' + Data.company_title + '</td>');
	TR.append('<td>' + Data.company_tel + '</td>');

	$('.type-companies .item_list').find('tr.NoItems').hide();
	$('.type-companies .item_list').append(TR);
};

//********************************************************************************* //