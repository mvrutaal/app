<script type="text/javascript" src="<?=base_url()?>assets/js/modules/companies.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/wysiwyg/jquery.wysiwyg.css" type="text/css" media="all">
<script type="text/javascript" src="<?=base_url()?>assets/wysiwyg/ckeditor.js"></script>
<script type="text/javascript">
$(function() {
    CKEDITOR.replace('company_description', {
      toolbar : [ ['Bold', 'Italic', 'Underline','-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'] ]
    });
});
</script>

<div id="add_company">

<?=form_open('companies/update/', array('id' => 'add_company', 'enctype' => 'multipart/form-data', 'method'=>'POST'));?>
<?php if ($company_id > 0):?> <input type="hidden" name="company_id" value="<?=$company_id?>"> <?php endif;?>

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#lA">General</a></li>
            <li><a data-toggle="tab" href="#lB">Address</a></li>
		<li><a data-toggle="tab" href="#lC">Communication</a></li>

            <?php if ($company_id > 0 && $this->acl->can_read($this->session->userdata['group'], 'notes')):?>
            <li><a data-toggle="tab" href="#lD">Notes</a></li>
            <?php endif;?>

            <li><a data-toggle="tab" href="#lE">Description</a></li>
	</ul>

	<br />
	<div style="width:80%; margin:auto;">
            <input type="submit" class="btn btn-primary btn-large" value="Save Company">

            <?php if ($company_id > 0 && $this->acl->can_delete($this->session->userdata['group'], 'companies')):?>
            <br><br><a href="<?=site_url('companies/delete/'.$company_id)?>" class="btn btn-danger delete_warning">Delete Company</a>
            <?php endif; ?>
      </div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2><?php if ($company_id > 0):?>Edit Company: <?=$company_title?> <?php else:?>New Company<?php endif;?></h2>
	</div>

	<div id="content" class="tab-content">
		<div id="lA" class="tab-pane active">
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
            		<tr>
            			<td><label>Company Name</label></td>
            			<td><input name="company_title" id="company_title" value="<?=$company_title?>" type="text" class="focus validate[required]"></td>
            		</tr>
            		<tr>
                              <td><label>Company Type</label></td>
                              <td><?=form_dropdown('company_type_id', $company_types, $company_type_id, ' class="chosen" data-placeholder="Select a Company Type" ')?> <a href="#" class="dataset-add tooltips" title="Add Company Type" data-url="datasets/ajax/company_types_add">&nbsp;</a></td>
                        </tr>
                        <tr>
                              <td><label>Employees</label></td>
                              <td><?=form_dropdown('company_employee_count', $dropdowns['company_employees'], $company_employee_count, ' class="chosen" ')?> </td>
                        </tr>
                        <tr>
                              <td><label>Languages</label></td>
                              <td><?=form_multiselect('company_languages[]', $dropdowns['languages'], $company_languages, ' class="chosen" data-placeholder="Select a Language" ')?> </td>
                        </tr>
            	</tbody>
            </table>
		</div>

            <div id="lB" class="tab-pane">
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
                  <tbody>
                        <tr>
                              <td><label>Street</label></td>
                              <td colspan="99">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                          <tbody>
                                                <tr>
                                                      <td style="width:80%"><?=form_dropdown('company_street_id', $streets, $company_street_id, ' class="chosen" data-placeholder="Select a Street" style="width:83%"')?> <a href="#" class="dataset-add tooltips" title="Add Street" data-url="datasets/ajax/street_add">&nbsp;</a></td>
                                                      <td style="width:70px"><label>Number</label></td>
                                                      <td style="width:50px"><input name="company_housenumber" id="company_housenumber" value="<?=$company_housenumber?>" type="text" class="validate[required]" style="width:100%;"></td>
                                                </tr>
                                          </tbody>
                                    </table>
                              </td>
                        </tr>

                        <tr>
                              <td><label>Street 2</label></td>
                              <td><input name="company_street2" id="company_street2" value="<?=$company_street2?>" type="text"></td>
                        </tr>
                         <tr>
                              <td><label>Suburb</label></td>
                              <td><?=form_dropdown('company_suburb_id', $suburbs, $company_suburb_id, ' class="chosen" data-placeholder="Select a Suburb/Area" ')?> <a href="#" class="dataset-add tooltips" title="Add Suburb/Area" data-url="datasets/ajax/suburb_add">&nbsp;</a></td>
                        </tr>
                        <tr>
                              <td><label>City</label></td>
                              <td><?=form_dropdown('company_city_id', $cities, $company_city_id, ' class="chosen" data-placeholder="Select a City"')?> <a href="#" class="dataset-add tooltips" title="Add City" data-url="datasets/ajax/city_add">&nbsp;</a></td>
                        </tr>
                        <tr>
                              <td><label>Country</label></td>
                              <td><?=form_dropdown('company_country_id', $countries, $company_country_id, ' class="chosen" data-placeholder="Select a Country"')?> <a href="#" class="dataset-add tooltips" title="Add Country" data-url="datasets/ajax/country_add">&nbsp;</a></td>
                        </tr>
                  </tbody>
            </table>
            </div>

		<div id="lC" class="tab-pane">
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
				<tr>
            			<td><label>Tel. Work</label></td>
            			<td><input name="company_tel_cc" id="company_tel_cc" value="<?=$company_tel_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="company_tel_number" id="company_tel_number" value="<?=$company_tel_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            		</tr>
            		<tr>
            			<td><label>Tel. Work 2</label></td>
            			<td><input name="company_tel2_cc" id="company_tel2_cc" value="<?=$company_tel2_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="company_tel2_number" id="company_tel2_number" value="<?=$company_tel2_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            		</tr>
            		<tr>
            			<td><label>Tel. Fax</label></td>
            			<td><input name="company_fax_cc" id="company_fax_cc" value="<?=$company_fax_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="company_fax_number" id="company_fax_number" value="<?=$company_fax_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            		</tr>
            		<tr>
            			<td><label>Tel. Fax 2</label></td>
            			<td><input name="company_fax2_cc" id="company_fax2_cc" value="<?=$company_fax2_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="company_fax2_number" id="company_fax2_number" value="<?=$company_fax2_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            		</tr>
            		<tr>
            			<td><label>Email (Work)</label></td>
            			<td colspan="2"><input name="company_email" id="company_email" value="<?=$company_email?>" type="text" class="validate[custom[email]]"></td>
            		</tr>
            		<tr>
            			<td><label>Email (Work 2)</label></td>
            			<td colspan="2"><input name="company_email2" id="company_email2" value="<?=$company_email2?>" type="text" class="validate[custom[email]]"></td>
            		</tr>
            		<tr>
            			<td><label>Website</label></td>
            			<td colspan="2"><input name="company_website" id="company_website" value="<?=$company_website?>" type="text" class="validate[custom[email]]"></td>
            		</tr>
            	</tbody>
            </table>
		</div>

            <div id="lD" class="tab-pane">
                  <div class="note_type_toggler">
                        <strong>Note Types:</strong>
                        <?php foreach ($note_types as $note_type_id => $note_type_label): ?>
                        <span class="label label-success note_type_<?=$note_type_id?>" data-id="<?=$note_type_id?>"><?=$note_type_label?></span>
                        <?php endforeach;?>
                  </div>

                  <?php foreach ($note_types as $note_type_id => $note_type_label): ?>
                  <div class="note_section" id="note_type_<?=$note_type_id?>">


                        <table id="NotesDT_<?=$note_type_id?>" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="notes/ajax_datatable_simple/<?=$note_type_id?>/<?=$company_id?>" data-name="notes_<?=$note_type_id?>">
                              <thead>
                                    <tr>
                                          <th colspan="10">
                                                <h4>
                                                      <?=$note_type_label?>&nbsp;&nbsp;
                                                      <a href="#" class="linkbtn ExecAction add note-add" data-notetype="<?=$note_type_id?>" data-url="notes/ajax_new_note_modal/<?=$note_type_id?>/<?=$company_id?>/companies/"><span>Add Note</span></a>
                                                </h4>
                                          </th>
                                    </tr>
                                    <tr>
                                          <th style="width:42px">ID</th>
                                          <?php foreach($notes_cols as $col_name => $col):?>
                                          <th><?=$col['name']?></th>
                                          <?php endforeach;?>
                                    </tr>
                              </thead>
                              <tbody>

                              </tbody>
                        </table>

                        <script type="text/javascript">
                        CRM.DatatablesCols['notes_<?=$note_type_id?>'] = [];
                        CRM.DatatablesCols['notes_<?=$note_type_id?>'].push({mDataProp:'note_id', bSortable: false, sWidth:'38px'});

                        <?php foreach ($notes_cols as $col_name => $col):?>
                        CRM.DatatablesCols['notes_<?=$note_type_id?>'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
                        <?php endforeach;?>
                        </script>
                  </div>
                  <?php endforeach;?>

            </div>

            <div id="lE" class="tab-pane">
                  <?=form_textarea('company_description', $company_description, ' id="company_description" ')?>
            </div>
	</div>
	<div class="clear"></div>

</div>
<div class="clear"></div>

<?=form_close();?>
</div>