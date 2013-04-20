<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<title><?=$title?> | CRM</title>

	<link rel="shortcut icon" href="<?=base_url()?>favicon.ico">
	<link rel="icon" type="image/ico" href="<?=base_url()?>favicon.ico">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/chosen.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.ui.timepicker.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?=base_url()?>assets/css/default.css" type="text/css" media="all">

	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.ui.timepicker.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.datatables.colreorder.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.inputHint.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/pdfobject_min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/default.js"></script>

	<script type="text/javascript">
	var CRM = CRM ?CRM : new Object();
	CRM.BASE = '<?=base_url()?>index.php/';
	</script>

</head>
<body>

<div id="crmwrapper">

	<div id="crmtop" class="clearix">

		<ul id="menu">
			<?php if ($this->acl->can_read($this->session->userdata['group'], 'contacts')):?>
				<li <?php if ($pagetype == 'contacts'):?>class="active"<?php endif;?>><a href="<?=site_url('contacts')?>" class="contacts">Contacts</a></li>
			<?php endif;?>

			<?php if ($this->acl->can_read($this->session->userdata['group'], 'groups')):?>
				<li <?php if ($pagetype == 'groups'):?>class="active"<?php endif;?>><a href="<?=site_url('groups')?>" class="groups">Groups</a></li>
			<?php endif;?>

			<?php if ($this->acl->can_read($this->session->userdata['group'], 'companies')):?>
				<li <?php if ($pagetype == 'companies'):?>class="active"<?php endif;?>><a href="<?=site_url('companies')?>" class="companies">Companies</a></li>
			<?php endif;?>

			<?php if ($this->acl->can_read($this->session->userdata['group'], 'budget')):?>
				<li <?php if ($pagetype == 'budget'):?>class="active dropdown"<?php endif;?> class="dropdown">
					<a href="<?=site_url('budget')?>" class="budget dropdown-toggle" data-toggle="dropdown" href="#menu1">Budget</a>
					<ul class="dropdown-menu">
						<!-- <li><a href="<?=site_url('budget/grand_overview')?>">Grand Overview</a></li> -->
						<li><a href="<?=site_url('budget/programs')?>">Qualifications</a></li>
						<li class="divider"></li>
						<li><a href="<?=site_url('budget/items')?>">Budget Items</a></li>
						<li><a href="<?=site_url('budget/accounts')?>">Budget Accounts</a></li>
						<li class="divider"></li>
						<li><a href="<?=site_url('budget/reports')?>">Reports</a></li>
					</ul>
				</li>
			<?php endif;?>
            
            <?php if ($this->acl->can_read($this->session->userdata['group'], 'dooraccess')):?>
				<li <?php if ($pagetype == 'dooraccess'):?>class="active"<?php endif;?>><a href="<?=site_url('dooraccess')?>" class="door-access">Door Access</a></li>
			<?php endif;?>
<!--
			<?php if ($this->acl->can_read($this->session->userdata['group'], 'calendar')):?>
				<li <?php if ($pagetype == 'calendar'):?>class="active"<?php endif;?>><a href="<?=site_url('calendar')?>" class="scheduling">Calendar</a></li>
			<?php endif;?>

			<?php if ($this->acl->can_read($this->session->userdata['group'], 'courses')):?>
				<li <?php if ($pagetype == 'courses'):?>class="active"<?php endif;?>><a href="<?=site_url('courses')?>" class="courses">Courses</a></li>
			<?php endif;?>

			<?php if ($this->acl->can_read($this->session->userdata['group'], 'attendance')):?>
				<li <?php if ($pagetype == 'attendance'):?>class="active"<?php endif;?>><a href="<?=site_url('attendance')?>" class="attendance">Attendance</a></li>
			<?php endif;?>

			<?php if ($this->acl->can_read($this->session->userdata['group'], 'intern')):?>
				<li <?php if ($pagetype == 'intern'):?>class="active"<?php endif;?>><a href="<?=site_url('intern')?>" class="intern">Internships</a></li>
			<?php endif;?>

			<?php if ($this->acl->can_read($this->session->userdata['group'], 'correspondence')):?>
				<li <?php if ($pagetype == 'correspondence'):?>class="active"<?php endif;?>><a href="<?=site_url('correspondence')?>" class="correspondence">Correspondence</a></li>
			<?php endif;?>
-->
		</ul>

		<div id="menu-right">

			<ul id="menu-tooltips">
<!--
				<?php if ($this->acl->can_read($this->session->userdata['group'], 'reservations')):?>
					<li <?php if ($pagetype == 'reservations'):?>class="active"<?php endif;?>><a href="<?=site_url('reservations')?>" class="reservations">Reservations</a></li>
				<?php endif;?>

				<?php if ($this->acl->can_read($this->session->userdata['group'], 'administration')):?>
					<li <?php if ($pagetype == 'administration'):?>class="active"<?php endif;?>><a href="<?=site_url('administration')?>" class="administration">Administration</a></li>
				<?php endif;?>

				<?php if ($this->acl->can_read($this->session->userdata['group'], 'reports')):?>
					<li <?php if ($pagetype == 'reports'):?>class="active"<?php endif;?>><a href="<?=site_url('reports')?>" class="reports">Reports</a></li>
				<?php endif;?>
-->
				<?php if ($this->acl->can_read($this->session->userdata['group'], 'notes')):?>
					<li <?php if ($pagetype == 'notes'):?>class="active"<?php endif;?>><a href="<?=site_url('notes')?>" class="notes" title="Notes">Notes</a></li>
				<?php endif;?>

				<?php if ($this->acl->can_read($this->session->userdata['group'], 'datasets')):?>
					<li <?php if ($pagetype == 'datasets'):?>class="active"<?php endif;?>><a href="<?=site_url('datasets')?>" class="datasets" title="Datasets">Datasets</a></li>
				<?php endif;?>

				<?php if ($this->acl->can_read($this->session->userdata['group'], 'settings')):?>
					<li <?php if ($pagetype == 'settings'):?>class="active"<?php endif;?>><a href="<?=site_url('settings')?>" class="settings" title="Settings">Settings</a></li>
				<?php endif;?>

			</ul>

			<div id="user-block">
			  <ul>
			    <li><a href='<?=site_url('auth/edit/')?>' class='linkbtn profile'><span><?=$this->session->userdata('first_name')?></span></a></li>
			    <li><a href="<?=site_url('auth/logout/')?>" class="linkbtn logout"><span>Log Out</span></a></li>
			  </ul>
			</div>

		</div>

	</div> <!--/crmtop-->

	<div id="crmbodywrapper">

		<div id="crmbody">
			<?=$content?>
		</div><!--/crmbody-->

	</div> <!--/crmbodywrapper-->

	<div id="ModalWrapper" class="modal fade"></div>

</div> <!--/crmwrapper-->
</body>
</html>