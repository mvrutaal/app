<script type="text/javascript" src="<?=base_url()?>assets/js/modules/contacts.js"></script>

<div id="contacts" class="clear">


<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<li><a href="#personalia" class="smoothscroll">Personalia</a></li>

            <?php if ($contact_id > 0 && $this->acl->can_read($this->session->userdata['group'], 'notes')):?>
            <li><a href="#notes" class="smoothscroll">Notes</a></li>
            <?php endif;?>
		<!--
            <li><a data-toggle="tab" href="#lE">Media</a></li>
		<li><a data-toggle="tab" href="#lF">Files</a></li>
		<li><a data-toggle="tab" href="#lG">Extra</a></li> -->
	</ul>
</div>

<div id="contentwrapper">

	<div id="title_block">
		<h2 id="personalia"><?=$first_name?> <?=$last_name?> (<?=ucfirst($sex)?>)</h2>
	</div>


	<div id="content">

            <div class="clear">
                  <div class="address" style="width:33%; float:left;">
                        <h3>Address </h3>
                        <?php if ($street_label != FALSE):?><?=$street_label?> <?=$housenumber?> <br><?php endif;?>
                        <?php if ($street2 != FALSE):?><?=$street2?><br><?php endif;?>
                        <?php if ($suburb_label):?><?=$suburb_label?>,<?php endif;?> <?php if ($city_label):?><?=$city_label?> <br><?php endif;?> 
                        <?php if ($country_label):?><?=$country_label?><?php endif;?>
                  </div>
                  <div class="communication" style="width:33%; float:left;">
                        <h3>Tel. Numbers </h3>
                        <?php if ($tel_work_number != FALSE):?>+<?=$tel_work_cc?>-<?=$tel_work_number?> <?php if ($tel_work_ext != FALSE) echo "ext " . $tel_work_ext?> (Office) <br><?php endif;?>
                        <?php if ($tel_home_number != FALSE):?>+<?=$tel_home_cc?>-<?=$tel_home_number?> (Home) <br><?php endif;?>
                        <?php if ($tel_mobile_number != FALSE):?>+<?=$tel_mobile_cc?>-<?=$tel_mobile_number?> (Mobile) <br><?php endif;?>
                        <?php if ($tel_mobile2_number != FALSE):?>+<?=$tel_mobile2_cc?>-<?=$tel_mobile2_number?> (Mobile 2) <br><?php endif;?>
                  </div>
                  <div class="emails" style="width:33%; float:left;">
                        <h3>Emails </h3>
                        <?php if ($email_work != FALSE):?> <a href="mailto:<?=$email_work?>"><?=$email_work?></a> (Work) <br> <?php endif;?>
                        <?php if ($email_work2 != FALSE):?> <a href="mailto:<?=$email_work2?>"><?=$email_work2?></a> (Work 2) <br> <?php endif;?>
                        <?php if ($email_personal != FALSE):?> <a href="mailto:<?=$email_personal?>"><?=$email_personal?></a> (Personal) <br> <?php endif;?>
                        <?php if ($email_personal2 != FALSE):?> <a href="mailto:<?=$email_personal2?>"><?=$email_personal2?></a> (Personal 2) <br> <?php endif;?>
                  </div>
            </div>
            <br>

            <div class="clear">
                  <div class="personal" style="width:33%; float:left;">
                        <h3>Personal</h3>
                        <?php if ($nickname != FALSE):?> <strong>Nickname:</strong> <?=$nickname?> <br> <?php endif;?>
                        <?php if ($birthday != FALSE):?> <strong>Birthdate:</strong> <?=$birthday?> <br> <?php endif;?>
                        <?php if ($birthplace != FALSE):?> <strong>Place of Birth:</strong> <?=$birthplace?> <br> <?php endif;?>
                        <?php if ($id_number != FALSE):?> <strong>ID Number:</strong> <?=$id_number?> <br> <?php endif;?>
                        <?php if ($bankaccount_number != FALSE):?> <strong>Bank Account #:</strong> <?=$bankaccount_number?> <br> <?php endif;?>
                  </div>

                  <?php if ($company_id > 0): ?>
                  <div class="company" style="width:33%; float:left;">
                        <h3>Company</h3>
                        <a href="<?=site_url('companies/view/' . $company->company_id)?>"><?=$company->company_title?></a> <br>
                        <?php if ($company_tel_number != FALSE):?>+<?=$company_tel_cc?>-<?=$company_tel_number?> (Office) <br><?php endif;?>
                  </div>
                  <?php endif;?>
            </div>
	</div>
      <br>

      <div id="title_block">
            <h2 id="notes">Notes</h2>
      </div>

      <div id="content">
      <?php foreach ($note_types as $note_type_id => $note_type_label): ?>
            <table class="table table-striped" cellspacing="0" cellpadding="0" border="0" width="100%">
                  <thead>
                        <tr><th colspan="10"><h4><?=$note_type_label?></h4></th></tr>
                        <tr>
                              <th style="width:42px">ID</th>
                              <?php foreach($notes_cols as $col_name => $col):?>
                              <th><?=$col['name']?></th>
                              <?php endforeach;?>
                        </tr>
                  </thead>
                  <tbody>
                        <?php foreach ($notes[$note_type_id] as $num => $note): ?>
                        <tr>
                              <td><?=$note->note_id?></td>
                              <td><?=$note->first_name?> <?=$note->last_name?></td>
                              <td><?=date('l, d-M-Y H:i', strtotime($note->note_date))?></td>
                              <td><?=$note->note_text?></td>
                        </tr>
                        <?php endforeach;?>
                        <?php if (empty($notes[$note_type_id]) == TRUE):?> <tr><td colspan="5">No notes have been added</td></tr><?php endif;?>
                  </tbody>
            </table>
      <?php endforeach;?>
      </div>
</div>
</div>