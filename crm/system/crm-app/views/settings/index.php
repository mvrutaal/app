<script type="text/javascript" src="<?=base_url()?>assets/js/modules/settings.js"></script>

<div id="settings">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>General</h6>
	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#lA">General</a></li>
	</ul>

	<h6>Users</h6>

	<ul class="tabs">
		<li><a data-toggle="tab" href="#2A">Users</a></li>
		<li><a data-toggle="tab" href="#2B">User Roles</a></li>
		<li><a data-toggle="tab" href="#2C">Access Resources</a></li>
	</ul>

	<h6>Modules</h6>

	<ul class="tabs">
		<li><a data-toggle="tab" href="#3A">Notes</a></li>
	</ul>

	<br />

</div> <!-- </sidebar> -->

<div id="contentwrapper">

	<div class="tab-content">
		<div id="lA" class="tab-pane active">
			<?=$this->load->view('settings/blocks/general', array(), TRUE);?>
		</div>

		<div id="2A" class="tab-pane">
			<?=$this->load->view('settings/blocks/users', array(), TRUE);?>
		</div>
		<div id="2B" class="tab-pane">
			<?=$this->load->view('settings/blocks/user_roles', array(), TRUE);?>
		</div>
		<div id="2C" class="tab-pane">
			<?=$this->load->view('settings/blocks/access_resources', array(), TRUE);?>
		</div>

		<div id="3A" class="tab-pane">
			<?=$this->load->view('settings/blocks/module_notes', array(), TRUE);?>
		</div>
	</div>
	<div class="clear"></div>

</div>
<div class="clear"></div>

</div> <!-- </settings> -->

<div id="ModalWrapper" class="modal fade"></div>