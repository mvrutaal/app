<script type="text/javascript" src="<?=base_url()?>assets/js/modules/datasets.js"></script>

<div id="datasets">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Datasets</h6>
	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#lA">Streets</a></li>
		<li><a data-toggle="tab" href="#lB">Suburbs</a></li>
		<li><a data-toggle="tab" href="#lC">Cities</a></li>
		<li><a data-toggle="tab" href="#lD">Countries</a></li>
		<li><a data-toggle="tab" href="#lE">Company Types</a></li>
	</ul>

	<br />

</div> <!-- </sidebar> -->

<div id="contentwrapper">

	<div class="tab-content">
		<div id="lA" class="tab-pane active"> <?=$this->load->view('datasets/blocks/streets', array(), TRUE);?> </div>
		<div id="lB" class="tab-pane"> <?=$this->load->view('datasets/blocks/suburbs', array(), TRUE);?> </div>
		<div id="lC" class="tab-pane"> <?=$this->load->view('datasets/blocks/cities', array(), TRUE);?> </div>
		<div id="lD" class="tab-pane"> <?=$this->load->view('datasets/blocks/countries', array(), TRUE);?> </div>
		<div id="lE" class="tab-pane"> <?=$this->load->view('datasets/blocks/company_types', array(), TRUE);?> </div>
	</div>
	<div class="clear"></div>

</div>
<div class="clear"></div>

</div> <!-- </settings> -->

