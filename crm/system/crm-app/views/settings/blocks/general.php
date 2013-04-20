<div id="title_block">
		<h2>General</h2>
</div>

<div id="content">
<?=form_open('settings/update_settings', array('id' => 'update_settings', 'enctype' => 'multipart/form-data', 'method'=>'POST'));?>

	<table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
	<tbody>
		<tr>
			<td><label>Main Company</label></td>
			<td><?=form_dropdown('preferences[general_main_company]', $companies, $preferences['general_main_company'], ' class="chosen" data-placeholder="Select a Company"')?></td>
		</tr>
		<tr>
			<td><label>Affiliated Companies</label></td>
			<td><?=form_multiselect('preferences[general_aff_companies][]', $companies, $preferences['general_aff_companies'], ' class="chosen"  ')?></td>
		</tr>
		<tr>
			<td><label>Default Tel. Country Code</label></td>
			<td><?=form_input('preferences[general_default_tel_cc]', $preferences['general_default_tel_cc'])?></td>
		</tr>
		<tr>
			<td><label>Default City (New Items)</label></td>
			<td><?=form_dropdown('preferences[general_default_city]', $cities, $preferences['general_default_city'], ' class="chosen" data-placeholder="Select a City"')?></td>
		</tr>
		<tr>
			<td><label>Default Country (New Items)</label></td>
			<td><?=form_dropdown('preferences[general_default_country]', $countries, $preferences['general_default_country'], ' class="chosen" data-placeholder="Select a Country"')?></td>
		</tr>
	</tbody>
	</table>

	<button class="btn btn-primary">Save Preferences</button>

<?=form_close();?>
</div>