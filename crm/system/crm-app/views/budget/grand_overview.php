<script type="text/javascript" src="<?=base_url()?>assets/js/modules/budget.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.dcdrilldown.1.2.js"></script>
<div id="budget">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>


</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Grand Overview</h2>
	</div>

	<div id="content">

		<div class="grandover clear">

			<div class="left dd-wrapper">
				<div class="dcjq-drilldown" id="grandov_menu">
					<ul id="menu-drill-down-1" class="menu">
						<?php foreach ($program_sectors as $sector_id => $sector):?>
						<li class="menu-item">
							<a href="#" data-type="sector" data-id="<?=$sector_id?>"><?=$sector?></a>
							<ul class="sub-menu">
							<?php foreach($program_departments[$sector_id] as $department_id => $dep_label):?>
								<li class="menu-item">
									<a href="#" data-type="department" data-id="<?=$department_id?>"><?=$dep_label?></a>
									<ul class="sub-menu">
										<?php if (isset($programs[ $department_id ]) == FALSE) $programs[ $department_id ] = array();?>
										<?php foreach($programs[ $department_id ] as $program_id => $program_label):?>
										<li class="menu-item">
											<a href="#" data-type="program" data-id="<?=$program_id?>"><?=$program_label?></a>
										</li>
										<?php endforeach;?>
									</ul>
								</li>
							<?php endforeach;?>
							</ul>
						</li>
						<?php endforeach;?>
					</ul>
				</div>
			</div>

			<div class="right">

				RIGHT
			</div>

		</div>


		<div class="clear"></div>
	</div>


</div>
<div class="clear"></div>

</div>




	