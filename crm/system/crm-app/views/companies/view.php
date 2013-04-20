<div id="companies" class="clear">


<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Sections</h6>

	<ul class="tabs">
		<li><a href="#company" class="smoothscroll">Company</a></li>
		<li><a href="#contacts" class="smoothscroll">Contacts</a></li>

        <?php if ($company_id > 0 && $this->acl->can_read($this->session->userdata['group'], 'notes')):?>
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
		<h2 id="company"><?=$company_title?> <?php if ($company_type_label != FALSE):?>(<?=$company_type_label?>)<?php endif;?></h2>
	</div>


	<div id="content">

            <div class="clear">
                  <div class="address" style="width:33%; float:left;">
                        <h3>Address</h3>
                        <?php if ($street_label != FALSE):?><?=$street_label?> <?=$company_housenumber?> <br><?php endif;?>
                        <?php if ($company_street2 != FALSE):?><?=$company_street2?><br><?php endif;?>
                        <?php if ($suburb_label):?><?=$suburb_label?>,<?php endif;?> <?php if ($city_label):?><?=$city_label?> <br><?php endif;?> 
                        <?php if ($country_label):?><?=$country_label?><?php endif;?>
                  </div>
                  <div class="communication" style="width:33%; float:left;">
                        <h3>Tel. Numbers </h3>
                        <?php if ($company_tel_number != FALSE):?>+<?=$company_tel_cc?>-<?=$company_tel_number?> (Office) <br><?php endif;?>
                        <?php if ($company_tel2_number != FALSE):?>+<?=$company_tel2_cc?>-<?=$company_tel2_number?> (Office 2) <br><?php endif;?>
                        <?php if ($company_fax_number != FALSE):?>+<?=$company_fax_cc?>-<?=$company_fax_number?> (Fax) <br><?php endif;?>
                        <?php if ($company_fax2_number != FALSE):?>+<?=$company_fax2_cc?>-<?=$company_fax2_number?> (Fax 2) <br><?php endif;?>
                  </div>
                  <div class="emails" style="width:33%; float:left;">
                        <h3>Emails </h3>
                        <?php if ($company_email != FALSE):?> <a href="mailto:<?=$company_email?>"><?=$company_email?></a> (Office) <br> <?php endif;?>
                        <?php if ($company_email2 != FALSE):?> <a href="mailto:<?=$company_email2?>"><?=$company_email2?></a> (Office) <br> <?php endif;?>
                  </div>
            </div>
            <br>

            <div class="clear">
                  <div class="personal" style="width:33%; float:left;">
                        <h3>Company</h3>
                        <?php if ($company_website != FALSE):?> <strong>Website:</strong> <a href="<?=$company_website?>"><?=$company_website?></a> <br> <?php endif;?>
                        <?php if ($company_coc_id != FALSE):?> <strong>KVK ID:</strong> <?=$company_coc_id?> <br> <?php endif;?>
                        <?php if ($first_name != FALSE):?> <strong>Author:</strong> <?=$first_name?> <?=$last_name?> <br> <?php endif;?>
                  </div>
            </div>
	</div>
	<br>

	<div id="title_block"><h2 id="contacts">Contacts</h2></div>

	<div id="content">
		<table class="table table-striped" cellspacing="0" cellpadding="0" border="0" width="100%">
				<thead>
				    <tr>
						<th>Full Name</th>
						<th>Job Title</th>
						<th>Email (Work)</th>
						<th>Email (Personal)</th>
						<th>Tel (Mobile)</th>
				    </tr>
				</thead>
				<tbody>
				<?php foreach($contacts as $row):?>
				<tr>
					<td><?=($row->first_name .' '. $row->last_name)?></td>
					<td><?=$row->job_title?></td>
					<td><?=$row->email_work?></td>
					<td><?=$row->email_personal?></td>
					<td><?php if ($row->tel_mobile_number != FALSE):?>+<?=$row->tel_mobile_cc?>-<?=$row->tel_mobile_number?><?php endif;?></td>
				</tr>
				<?php endforeach;?>
				<?php if (empty($contacts) == TRUE):?> <tr><td colspan="10">No contacts have been linked.</td></tr><?php endif;?>	          </tbody>
		</table>
	</div>

	<div id="title_block"><h2 id="notes">Notes</h2></div>

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