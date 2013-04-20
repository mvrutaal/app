<script type="text/javascript" src="<?=base_url()?>assets/js/modules/contacts.js"></script>

<div id="contacts">

<?=form_open('contacts/update/', array('id' => 'add_contact', 'enctype' => 'multipart/form-data', 'method'=>'POST'));?>
<?php if ($contact_id > 0):?> <input type="hidden" name="contact_id" value="<?=$contact_id?>"/> <?php endif;?>


<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#lA">General</a></li>
		<li><a data-toggle="tab" href="#lB">Address</a></li>
		<li><a data-toggle="tab" href="#lC">Communication</a></li>
		<li><a data-toggle="tab" href="#lD">Personal</a></li>

            <?php if ($contact_id > 0 && $this->acl->can_read($this->session->userdata['group'], 'notes')):?>
            <li><a data-toggle="tab" href="#lE">Notes</a></li>
            <?php endif;?>
		<!--
            <li><a data-toggle="tab" href="#lE">Media</a></li>
		<li><a data-toggle="tab" href="#lF">Files</a></li>
		<li><a data-toggle="tab" href="#lG">Extra</a></li> -->
	</ul>

	<br />
	<div style="width:80%; margin:auto;">
            <input type="submit" class="btn btn-primary btn-large" value="Save Contact">

            <?php if ($contact_id > 0 && $this->acl->can_delete($this->session->userdata['group'], 'contacts')):?>
            <br><br><a href="<?=site_url('contacts/delete/'.$contact_id)?>" class="btn btn-danger delete_warning">Delete Contact</a>
            <?php endif; ?>
      </div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2><?php if ($contact_id > 0):?>Edit Contact: <span class="contact_name"><?=$first_name?> <?=$last_name?></span> <?php else:?>New Contact: <span class="contact_name"></span> <?php endif;?></h2>
	</div>

	<div id="content" class="tab-content">
		<div id="lA" class="tab-pane active">
            <table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
            		<tr>
            			<td><label>First Name</label></td>
            			<td colspan="99">
            				<table cellspacing="0" cellpadding="0" border="0" width="100%">
            					<tbody>
            						<tr>
            							<td><input name="first_name" id="first_name" value="<?=$first_name?>" type="text" class="focus validate[required]"></td>
            							<td><label>Initials</label></td>
            							<td><input name="initials" id="initials" value="<?=$initials?>" type="text" class="validate[required]" style="width:56%;"></td>
            						</tr>
            					</tbody>
            				</table>
            			</td>
            		</tr>
            		<tr>
            			<td><label>Last Name</label></td>
            			<td><input name="last_name" id="last_name" value="<?=$last_name?>" type="text" class="validate[required]"></td>
            		</tr>
            		<tr>
            			<td><label>Nickname</label></td>
            			<td><input name="nickname" id="nickname" value="<?=$nickname?>" type="text"></td>
            		</tr>
            		<tr>
            			<td><label>Sex</label></td>
            			<td><input name="sex" value="male" type="radio" <?php if ($sex == 'male' OR $sex == FALSE) echo 'checked';?>/> Male <input name="sex" value="female" type="radio" <?php if ($sex == 'female') echo 'checked';?>/> Female</td>
            		</tr>
            		<tr>
            			<td><label>Company</label></td>
            			<td><?=form_dropdown('company_id', $companies, $company_id, ' class="chosen" data-placeholder="Select a Company" style="width:82%"')?></td>
            		</tr>
            		<tr>
            			<td><label>Job Title</label></td>
            			<td><input name="job_title" id="job_title" value="<?=$job_title?>" type="text" class=""></td>
            		</tr>
                        <tr>
                              <td><label>Groups</label></td>
                              <td>
                                    <?=form_multiselect('groups[]', $dbgroups, $groups, ' class="chosen" data-placeholder="Select some Groups" style="width:82%"')?>
                                    <br><small style="font-size:11px">Adding Or Removing a group here will also Add/Remove this person from the Group itself.</small>
                              </td>
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
            							<td style="width:80%"><?=form_dropdown('street_id', $streets, $street_id, ' class="chosen" data-placeholder="Select a Street"')?> <a href="#" class="dataset-add tooltips" title="Add Street" data-url="datasets/ajax/street_add">&nbsp;</a></td>
            							<td style="width:70px"><label>Number</label></td>
            							<td style="width:50px"><input name="housenumber" id="housenumber" value="<?=$housenumber?>" type="text" class="validate[required]" style="width:100%;"></td>
            						</tr>
            					</tbody>
            				</table>
            			</td>
            		</tr>
            		<tr>
            			<td><label>Street 2</label></td>
            			<td><input name="street2" value="<?=$street2?>" type="text"></td>
            		</tr>
            		<tr>
            			<td><label>Suburb/Area</label></td>
            			<td><?=form_dropdown('suburb_id', $suburbs, $suburb_id, ' class="chosen" data-placeholder="Select a Suburb/Area"')?> <a href="#" class="dataset-add tooltips" title="Add Suburb/Area" data-url="datasets/ajax/suburb_add">&nbsp;</a></td>
            		</tr>
            		<tr>
            			<td><label>City</label></td>
            			<td><?=form_dropdown('city_id', $cities, $city_id, ' class="chosen" data-placeholder="Select a City"')?> <a href="#" class="dataset-add tooltips" title="Add City" data-url="datasets/ajax/city_add">&nbsp;</a></td>
            		</tr>
            		<tr>
            			<td><label>Zip</label></td>
            			<td><input name="zip" value="<?=$zip?>" type="text"></td>
            		</tr>
            		<tr>
            			<td><label>Country</label></td>
            			<td><?=form_dropdown('country_id', $countries, $country_id, ' class="chosen" data-placeholder="Select a Country"')?> <a href="#" class="dataset-add tooltips" title="Add Country" data-url="datasets/ajax/country_add">&nbsp;</a></td>
            		</tr>
            	</tbody>
            </table>
		</div>

		<div id="lC" class="tab-pane">
			<table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
            		<tr>
            			<td><label>Tel. Office</label></td>
            			<td><input name="tel_work_cc" id="tel_work_cc" value="<?=$tel_work_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="tel_work_number" id="tel_work_number" value="<?=$tel_work_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="tel_work_ext" id="tel_work_ext" value="<?=$tel_work_ext?>" type="text" style="width:50px" placeholder="Ext" class="validate[custom[onlyNumberSp]]"></td>
            		</tr>
            		<tr>
            			<td><label>Tel. Home</label></td>
            			<td><input name="tel_home_cc" id="tel_home_cc" value="<?=$tel_home_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="tel_home_number" id="tel_home_number" value="<?=$tel_home_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            			<td></td>
            		</tr>
            		<tr>
            			<td><label>Tel. Mobile</label></td>
            			<td><input name="tel_mobile_cc" id="tel_mobile_cc" value="<?=$tel_mobile_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="tel_mobile_number" id="tel_mobile_number" value="<?=$tel_mobile_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            			<td></td>
            		</tr>
            		<tr>
            			<td><label>Tel. Mobile 2</label></td>
            			<td><input name="tel_mobile2_cc" id="tel_mobile2_cc" value="<?=$tel_mobile2_cc?>" type="text" style="width:50px" class="validate[custom[onlyNumberSp]]"></td>
            			<td><input name="tel_mobile2_number" id="tel_mobile2_number" value="<?=$tel_mobile2_number?>" type="text" style="width:175px" placeholder="Number" class="validate[custom[onlyNumberSp]]"></td>
            			<td></td>
            		</tr>
					<tr>
            			<td><label>Email (Work)</label></td>
            			<td colspan="3"><input name="email_work" id="email_work" value="<?=$email_work?>" type="text" class="validate[custom[email]]"></td>
            		</tr>
					<tr>
            			<td><label>Email (Work 2)</label></td>
            			<td colspan="3"><input name="email_work2" id="email_work2" value="<?=$email_work2?>" type="text" class="validate[custom[email]]"></td>
            		</tr>
					<tr>
            			<td><label>Email (Personal)</label></td>
            			<td colspan="3"><input name="email_personal" id="email_personal" value="<?=$email_personal?>" type="text" class="validate[custom[email]]"></td>
            		</tr>
					<tr>
            			<td><label>Email (Personal 2)</label></td>
            			<td colspan="3"><input name="email_personal2" id="email_personal2" value="<?=$email_personal2?>" type="text" class="validate[custom[email]]"></td>
            		</tr>
            	</tbody>
            </table>
		</div>

		<div id="lD" class="tab-pane">
		<table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>
            		<tr>
            			<td><label>Birthday</label></td>
            			<td><input name="birthday" id="birthday" value="<?=$birthday?>" type="text" class="datepicker"></td>
            		</tr>
            		<tr>
            			<td><label>Place of Birth</label></td>
            			<td><input name="birthplace" id="birthplace" value="<?=$birthplace?>" type="text" class=""></td>
            		</tr>
            		<tr>
            			<td><label>ID Number</label></td>
            			<td><input name="id_number" id="id_number" value="<?=$id_number?>" type="text" class=""></td>
            		</tr>
            		<tr>
            			<td><label>Bank Account #</label></td>
            			<td><input name="bankaccount_number" id="bankaccount_number" value="<?=$bankaccount_number?>" type="text" class=""></td>
            		</tr>
            	</tbody>
            </table>
		</div>

		<div id="lE" class="tab-pane">
                  <div class="note_type_toggler">
                        <strong>Note Types:</strong>
                        <?php foreach ($note_types as $note_type_id => $note_type_label): ?>
                        <span class="label label-success note_type_<?=$note_type_id?>" data-id="<?=$note_type_id?>"><?=$note_type_label?></span>
                        <?php endforeach;?>
                  </div>

                  <?php foreach ($note_types as $note_type_id => $note_type_label): ?>
                  <div class="note_section" id="note_type_<?=$note_type_id?>">


                        <table id="NotesDT_<?=$note_type_id?>" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="notes/ajax_datatable_simple/<?=$note_type_id?>/<?=$contact_id?>" data-name="notes_<?=$note_type_id?>">
                              <thead>
                                    <tr>
                                          <th colspan="10">
                                                <h4>
                                                      <?=$note_type_label?>&nbsp;&nbsp;
                                                      <a href="#" class="linkbtn ExecAction add note-add" data-notetype="<?=$note_type_id?>" data-url="notes/ajax_new_note_modal/<?=$note_type_id?>/<?=$contact_id?>/contacts/"><span>Add Note</span></a>
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

		<div id="lF" class="tab-pane">
			<table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>

            	</tbody>
            </table>
		</div>

		<div id="lG" class="tab-pane">
			<table class="FormTable" cellspacing="0" cellpadding="0" border="0" width="60%">
            	<tbody>

            	</tbody>
            </table>
		</div>
	</div>


	<div class="clear"></div>

</div>
<div class="clear"></div>

<?=form_close();?>
</div>