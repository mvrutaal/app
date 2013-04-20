<script type="text/javascript" src="<?=base_url()?>assets/js/modules/budget.js"></script>
<div id="budget">

<div id="sidebar">
	<div id="logo"><img src="<?=base_url()?>assets/logos/logo_crm_vertical.png"></div>

	<h6>Filter By</h6>
	<div class="dtfilters">
		<div class="filter">
			<input type="text" name="item_name" placeholder="Item Label">
		</div>
		<div class="filter">
			<?=form_multiselect('item_categories[]', $item_categories, '', ' class="chosen" data-placeholder="Item Category" style="width:100%;"')?>
		</div>
	</div>

	<h6>Visible Columns</h6>
	<div class="columns">
		<?php foreach($standard_cols as $name => $col):?>
		<span class="label" rel="<?=$name?>"><?=$col['name']?></span>
		<?php endforeach;?>
	</div>

	<h6>Account Categories</h6>
	<div class="sidebarcats">
		<?php foreach($item_categories as $cat_id => $cat_label):?>
		<a href="#" class="linkbtn edit item_category-add" data-url="budget/ajax_new_item_category_modal/edit/<?=$cat_id?>"><span><?=$cat_label?></span></a>
		<?php endforeach;?>
	</div>

</div>

<div id="contentwrapper">
	<div id="title_block">
		<h2>Budget Items</h2>
		<a href="#" class="linkbtn add item-add" data-url="budget/ajax_new_item_modal"><span>New Item</span></a>
		<a href="#" class="linkbtn add item_category-add" data-url="budget/ajax_new_item_category_modal"><span>New Item Category</span></a>
	</div>

	<div id="content">

		<table id="BudgetItems" class="datatable CrmTable" cellspacing="0" cellpadding="0" border="0" width="100%" data-url="budget/ajax_datatable_budget_items/" data-name="budget_items">
			<thead>
				<tr>
					<th style="width:38px">ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<?php foreach($standard_cols as $col_name => $col):?>
					<th><?=$col['name']?></th>
					<?php endforeach;?>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>

		<div class="clear"></div>
	</div>


</div>
<div class="clear"></div>

</div>


<script type="text/javascript">
CRM.DatatablesCols['budget_items'] = [];
CRM.DatatablesCols['budget_items'].push({mDataProp:'item_id', bSortable: false, sWidth:'38px'});

<?php foreach ($standard_cols as $col_name => $col):?>
CRM.DatatablesCols['budget_items'].push({mDataProp:'<?=$col_name?>', bSortable: <?=$col['sortable']?>});
<?php endforeach;?>
</script>