<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget extends CRM_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	// ********************************************************************************* //

	public function index()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_read($this->session->userdata['group'], 'budget'))
		{
			show_error('Access Denied!');
		}


		$data = array();


		$vData = array();
		$vData['title'] = 'Budget';
		$vData['pagetype'] = 'budget';
		$vData['content'] = $this->load->view('budget/index', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function grand_overview()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_read($this->session->userdata['group'], 'budget'))
		{
			show_error('Access Denied!');
		}


		$data = array();

		//----------------------------------------
		// Grab all Program Sectors
		//----------------------------------------
		$data['program_sectors'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_sectors')->order_by('program_sector_label')->get();

		foreach($query->result() as $row)
		{
			$data['program_sectors'][$row->program_sector_id] = $row->program_sector_label;
		}

		//----------------------------------------
		// Grab all Program Departments
		//----------------------------------------
		$this->db->select('*');
		$this->db->from('crm_feffik_budget_programs_departments');
		$this->db->order_by('program_department_label');
		$query = $this->db->get();

		foreach ($query->result() as $key => $dep)
		{
			$data['program_departments'][ $dep->program_sector_id ][ $dep->program_department_id ] = $dep->program_department_label;
		}

		//----------------------------------------
		// Grab all Programs
		//----------------------------------------
		$this->db->select('*');
		$this->db->from('crm_feffik_budget_programs');
		$this->db->order_by('program_label');
		$query = $this->db->get();

		foreach ($query->result() as $key => $dep)
		{
			$data['programs'][ $dep->program_department_id ][ $dep->program_id ] = $dep->program_label;
		}


		$vData = array();
		$vData['title'] = 'Gran Overview';
		$vData['pagetype'] = 'budget';
		$vData['content'] = $this->load->view('budget/grand_overview', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function programs()
	{
		$data = array();

		//----------------------------------------
		// Grab all Program Sectors
		//----------------------------------------
		$data['program_sectors'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_sectors')->order_by('program_sector_label')->get();

		foreach($query->result() as $row)
		{
			$data['program_sectors'][$row->program_sector_id] = $row->program_sector_label;
		}

		//----------------------------------------
		// Grab all items
		//----------------------------------------
		$data['items'] = array('0' =>  (object) array('item_id' => 0, 'item_price' => 0, 'item_label' => 'Select an Item'));
		$query = $this->db->select('i.*, ic.item_cat_label')->from('crm_feffik_budget_items i')->join('crm_feffik_budget_items_categories ic', 'i.item_cat_id = ic.item_cat_id', 'left')->get();

		foreach($query->result() as $row)
		{
			if ($row->item_cat_label) $data['items'][$row->item_cat_label][$row->item_id] = $row;
			else $data['items'][$row->item_id] = $row;
		}

		//----------------------------------------
		// Grab all accounts
		//----------------------------------------
		$data['accounts'] = array('0' => 'Select an Account');
		$query = $this->db->select('*')->from('crm_feffik_budget_accounts')->order_by('account_number', 'asc')->get();

		foreach($query->result() as $row)
		{
			$data['accounts'][$row->account_id] = $row->account_number . ' - ' .$row->account_label;
		}

		$vData = array();
		$vData['title'] = 'Qualifications';
		$vData['pagetype'] = 'budget';
		$vData['content'] = $this->load->view('budget/programs', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function items()
	{
		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		$data['standard_cols']['item_label']    = array('name' => 'Item Label', 'sortable' => 'true');
		$data['standard_cols']['item_price']    = array('name' => 'Item Price', 'sortable' => 'true');
		$data['standard_cols']['item_category'] = array('name' => 'Item Category', 'sortable' => 'true');

		//----------------------------------------
		// Grab all Item Categories
		//----------------------------------------
		$data['item_categories'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_items_categories')->order_by('item_cat_label')->get();

		foreach($query->result() as $row)
		{
			$data['item_categories'][$row->item_cat_id] = $row->item_cat_label;
		}

		$vData = array();
		$vData['title'] = 'Budget Items';
		$vData['pagetype'] = 'budget';
		$vData['content'] = $this->load->view('budget/items', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function accounts()
	{
		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		$data['standard_cols']['account_number']    = array('name' => 'Account Number', 'sortable' => 'true');
		$data['standard_cols']['account_label']    = array('name' => 'Account Label', 'sortable' => 'true');
		$data['standard_cols']['account_category']    = array('name' => 'Account Category', 'sortable' => 'true');

		//----------------------------------------
		// Grab all Item Categories
		//----------------------------------------
		$data['account_categories'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_accounts_categories')->order_by('account_category_label')->get();

		foreach($query->result() as $row)
		{
			$data['account_categories'][$row->account_cat_id] = $row->account_category_label;
		}

		$vData = array();
		$vData['title'] = 'Budget Accounts';
		$vData['pagetype'] = 'budget';
		$vData['content'] = $this->load->view('budget/accounts', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function manage_partials()
	{
		$data = array();
		$partial_id = $this->uri->segment(3);

		// Grab the Partial
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_partials')->where('program_partial_id', $partial_id)->get();
		$data['partial'] = $query->row();

		//----------------------------------------
		// Grab all accounts
		//----------------------------------------
		$data['accounts'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_accounts')->where('account_cat_id', 6)->get();

		foreach($query->result() as $row)
		{
			$data['accounts'][] = $row;
		}

		//----------------------------------------
		// Grab all items
		//----------------------------------------
		$data['items'] = array('0' =>  (object) array('item_id' => 0, 'item_price' => 0, 'item_label' => 'Select an Item'));
		$query = $this->db->select('i.*, ic.item_cat_label')->from('crm_feffik_budget_items i')->join('crm_feffik_budget_items_categories ic', 'i.item_cat_id = ic.item_cat_id', 'left')->get();

		foreach($query->result() as $row)
		{
			if ($row->item_cat_label) $data['items'][$row->item_cat_label][$row->item_id] = $row;
			else $data['items'][$row->item_id] = $row;
		}

		$vData = array();
		$vData['title'] = 'Manage Partial';
		$vData['pagetype'] = 'budget';
		$vData['content'] = $this->load->view('budget/manage_partial', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function ajax_get_sector()
	{
		$data = array();

		// What Sector?
		$sector_id = $this->uri->segment(3);

		$data['program_departments'] = array();

		//----------------------------------------
		// Grab all Program Departments
		//----------------------------------------
		$this->db->select('*');
		$this->db->from('crm_feffik_budget_programs_departments');
		$this->db->where('program_sector_id', $sector_id);
		$this->db->order_by('program_department_label');
		$query = $this->db->get();

		foreach ($query->result() as $key => $dep)
		{
			$data['program_departments'][ $dep->program_department_id ] = $dep->program_department_label;
		}

		exit( $this->load->view('budget/blocks/level_one', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_get_department()
	{
		$data = array();

		// What Sector?
		$dep_id = $this->uri->segment(3);

		$data['programs'] = array();

		//----------------------------------------
		// Grab all Programs
		//----------------------------------------
		$this->db->select('*');
		$this->db->from('crm_feffik_budget_programs');
		$this->db->where('program_department_id', $dep_id);
		$this->db->order_by('program_label');
		$query = $this->db->get();

		foreach ($query->result() as $key => $dep)
		{
			$data['programs'][ $dep->program_id ] = $dep->program_label;
		}

		exit( $this->load->view('budget/blocks/level_two', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_get_program()
	{
		$data = array();

		// What Sector?
		$prog_id = $this->uri->segment(3);

		$data['program_id'] = $prog_id;
		$data['programs_partials'] = array();

		//----------------------------------------
		// Grab all Programs partials
		//----------------------------------------
		$q4 = $this->db->select('*')->from('crm_feffik_budget_programs_partials')->order_by('program_partial_label')->get();

		foreach ($q4->result() as $key => $row)
		{
			$data['programs_partials'][$row->program_id][ $row->program_partial_id ] = $row;
		}

		//----------------------------------------
		// Grab all Programs
		//----------------------------------------
		$data['partials'] = array();
		$this->db->select('*');
		$this->db->from('crm_feffik_budget_programs_partials');
		$this->db->where('program_id', $prog_id);
		$this->db->order_by('program_partial_label');
		$query = $this->db->get();

		foreach ($query->result() as $key => $dep)
		{
			$data['partials'][ $dep->program_partial_id ] = $dep->program_partial_label;
		}

		//----------------------------------------
		// Grab all posts
		//----------------------------------------
		$data['posts'] = array();
		$this->db->select('p.*, i.item_label, a.account_label, a.account_number');
		$this->db->from('crm_feffik_budget_posts p');
		$this->db->join('crm_feffik_budget_items i', 'i.item_id = p.item_id', 'left');
		$this->db->join('crm_feffik_budget_accounts a', 'p.account_id = a.account_id', 'left');
		$this->db->where('p.program_id', $prog_id);
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$row->account_label = $row->account_number . ' - ' . $row->account_label;
			$data['posts'][] = $row;
		}

		exit( $this->load->view('budget/blocks/level_three', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_get_partial_program()
	{
		$data = array();

		// What Sector?
		$partial_id = $this->uri->segment(3);

		// Grab the Partial
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_partials')->where('program_partial_id', $partial_id)->get();
		$data['partial'] = $query->row();

		//----------------------------------------
		// Grab all accounts
		//----------------------------------------
		$data['accounts'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_accounts')->where('account_cat_id', 6)->order_by('account_number', 'ASC')->get();

		foreach($query->result() as $row)
		{
			$data['accounts'][] = $row;
		}

		//----------------------------------------
		// Grab all posts
		//----------------------------------------
		$data['posts'] = array();
		$this->db->select('p.*, i.item_label');
		$this->db->from('crm_feffik_budget_posts p');
		$this->db->join('crm_feffik_budget_items i', 'i.item_id = p.item_id', 'left');
		$this->db->where('p.program_partial_id', $partial_id);
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$data['posts'][$row->account_id][] = $row;
		}

		exit( $this->load->view('budget/blocks/level_four', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function update_manage_partials()
	{
		$out = array('success' => 'no', 'body' => '');

		// What partial ID?
		if ($this->input->post('program_partial_id') == FALSE)
		{
			exit( json_encode($out) );
		}

		$program_partial_id = $this->input->post('program_partial_id');

		$this->db->where('program_partial_id', $program_partial_id);
		$this->db->delete('crm_feffik_budget_posts');

		// Add all new ones
		if ($this->input->post('account') == FALSE)
		{
			exit( json_encode($out) );
		}

		foreach ($_POST['account'] as $account_id => $account)
		{
			foreach ($account['items'] as $item)
			{
				$this->db->set('program_partial_id', $program_partial_id);
				$this->db->set('account_id', $account_id);
				$this->db->set('item_id', $item['item_id']);
				$this->db->set('item_price', $item['price']);
				$this->db->set('item_desc', $item['desc']);
				$this->db->set('item_quantity', $item['quantity']);
				$this->db->set('row_total', $item['total']);
				$this->db->insert('crm_feffik_budget_posts');
			}
		}

		$out['success'] = 'yes';
		exit( json_encode($out) );
	}

	// ********************************************************************************* //

	public function update_manage_program()
	{
		$out = array('success' => 'no', 'body' => '');

		//print_r($_POST); exit();
		

		// What partial ID?
		if ($this->input->post('program_id') == FALSE)
		{
			exit( json_encode($out) );
		}

		$program_id = $this->input->post('program_id');

		$this->db->where('program_id', $program_id);
		$this->db->delete('crm_feffik_budget_posts');

		// Add all new ones
		if ($this->input->post('account') == FALSE)
		{
			exit( json_encode($out) );
		}

		foreach ($_POST['account'] as $items)
		{
			foreach ($items as $item)
			{
				$this->db->set('program_id', $program_id);
				$this->db->set('account_id', $item['account_id']);
				$this->db->set('account_alt_label', $item['account_desc']);
				$this->db->set('item_id', $item['item_id']);
				$this->db->set('item_price', $item['item_price']);
				$this->db->set('item_desc', $item['item_desc']);
				$this->db->set('item_quantity', $item['item_quantity']);
				$this->db->set('row_total', $item['row_total']);
				$this->db->insert('crm_feffik_budget_posts');
			}
		}

		$out['success'] = 'yes';
		exit( json_encode($out) );
	}

	// ********************************************************************************* //

	public function grandov_grand()
	{
		$data = array();


		exit( $this->load->view('budget/grandov/grand', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function grandov_sector()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['sector_id'] = $this->input->post('id');
		$data['departments'] = array();
		$data['programs'] = array();
		$data['partials'] = array();
		$data['accounts'] = array();
		$data['posts'] = array();

		// Grab all accounts
		$this->db->select('ba.account_id, ba.account_label, ba.account_number, bac.account_category_label');
		$this->db->from('crm_feffik_budget_accounts ba');
		$this->db->join('crm_feffik_budget_accounts_categories bac', 'ba.account_cat_id = bac.account_cat_id', 'left');
		$this->db->order_by('bac.account_category_label', 'asc');
		$this->db->order_by('ba.account_number', 'asc');
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$data['accounts'][$row->account_category_label][] = $row;
		}

		// Grab all departments
		$query = $this->db->select('program_department_id')->from('crm_feffik_budget_programs_departments')->where('program_sector_id', $data['sector_id'])->get();
		foreach ($query->result() as $row)
		{
			$data['departments'][] = $row->program_department_id;
		}

		// We need programs!
		if (empty($data['departments']) == TRUE)
		{
			exit('<h4>No Departments have been recorded!</h4>');
		}

		// Grab all programs
		$query = $this->db->select('program_id')->from('crm_feffik_budget_programs')->where_in('program_department_id', $data['departments'])->get();
		foreach ($query->result() as $row)
		{
			$data['programs'][] = $row->program_id;
		}

		// We need programs!
		if (empty($data['programs']) == TRUE)
		{
			exit('<h4>No Qualifications have been recorded!</h4>');
		}

		// Grab all partials
		$query = $this->db->select('program_partial_id')->from('crm_feffik_budget_programs_partials')->where_in('program_id', $data['programs'])->get();
		foreach ($query->result() as $row)
		{
			$data['partials'][] = $row->program_partial_id;
		}

		// Grab account totals
		$this->db->select('bp.row_total, bp.account_id');
		$this->db->from('crm_feffik_budget_posts bp');
		$this->db->join('crm_feffik_budget_accounts ba', 'ba.account_id = bp.account_id');
		$this->db->where_in('program_id', $data['programs']);
		if (empty($data['partials']) == FALSE) $this->db->or_where_in('program_partial_id', $data['partials']);
		$this->db->order_by('ba.account_label');
		$q = $this->db->get();

		foreach ($q->result() as $post)
		{
			if (isset($data['posts'][ $post->account_id ]) == FALSE)
			{
				$data['posts'][$post->account_id ] = $post->row_total;
			}
			else
			{
				$data['posts'][ $post->account_id ] += $post->row_total;
			}
		}

		// Lets see which ones we can kill
		foreach ($data['accounts'] as $cat => $accounts)
		{
			foreach ($accounts as $key => $account)
			{
				if (isset($data['posts'][ $account->account_id ]) == FALSE)
				{
					unset ($data['accounts'][$cat][$key]);
				}
			}

			if (empty($data['accounts'][$cat]) == TRUE) unset($data['accounts'][$cat]);
		}

		//print_r($data);


		exit( $this->load->view('budget/grandov/sector', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function grandov_department()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['department_id'] = $this->input->post('id');
		$data['programs'] = array();
		$data['partials'] = array();
		$data['accounts'] = array();
		$data['posts'] = array();

		// Grab all accounts
		$this->db->select('ba.account_id, ba.account_label, ba.account_number, bac.account_category_label');
		$this->db->from('crm_feffik_budget_accounts ba');
		$this->db->join('crm_feffik_budget_accounts_categories bac', 'ba.account_cat_id = bac.account_cat_id', 'left');
		$this->db->order_by('bac.account_category_label', 'asc');
		$this->db->order_by('ba.account_number', 'asc');
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$data['accounts'][$row->account_category_label][] = $row;
		}

		// Grab all programs
		$query = $this->db->select('program_id')->from('crm_feffik_budget_programs')->where('program_department_id', $data['department_id'])->get();
		foreach ($query->result() as $row)
		{
			$data['programs'][] = $row->program_id;
		}

		// We need programs!
		if (empty($data['programs']) == TRUE)
		{
			exit('<h4>No Qualifications have been recorded!</h4>');
		}

		// Grab all partials
		$query = $this->db->select('program_partial_id')->from('crm_feffik_budget_programs_partials')->where_in('program_id', $data['programs'])->get();
		foreach ($query->result() as $row)
		{
			$data['partials'][] = $row->program_partial_id;
		}

		// Grab account totals
		$this->db->select('bp.row_total, bp.account_id');
		$this->db->from('crm_feffik_budget_posts bp');
		$this->db->join('crm_feffik_budget_accounts ba', 'ba.account_id = bp.account_id');
		$this->db->where_in('program_id', $data['programs']);
		if (empty($data['partials']) == FALSE) $this->db->or_where_in('program_partial_id', $data['partials']);
		$this->db->order_by('ba.account_label');
		$q = $this->db->get();

		foreach ($q->result() as $post)
		{
			if (isset($data['posts'][ $post->account_id ]) == FALSE)
			{
				$data['posts'][$post->account_id ] = $post->row_total;
			}
			else
			{
				$data['posts'][ $post->account_id ] += $post->row_total;
			}
		}

		// Lets see which ones we can kill
		foreach ($data['accounts'] as $cat => $accounts)
		{
			foreach ($accounts as $key => $account)
			{
				if (isset($data['posts'][ $account->account_id ]) == FALSE)
				{
					unset ($data['accounts'][$cat][$key]);
				}
			}

			if (empty($data['accounts'][$cat]) == TRUE) unset($data['accounts'][$cat]);
		}

		//print_r($data);

		exit( $this->load->view('budget/grandov/department', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function grandov_program()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['program_id'] = $this->input->post('id');
		$data['partials'] = array();
		$data['accounts'] = array();
		$data['posts'] = array();

		// Grab all partials
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_partials')->where('program_id', $data['program_id'])->order_by('program_partial_label', 'ASC')->get();
		foreach ($query->result() as $row)
		{
			// Grab account totals
			$this->db->select('bp.row_total, bp.account_id');
			$this->db->from('crm_feffik_budget_posts bp');
			$this->db->join('crm_feffik_budget_accounts ba', 'ba.account_id = bp.account_id');
			$this->db->where('program_partial_id', $row->program_partial_id);
			$this->db->order_by('ba.account_label');
			$q = $this->db->get();

			$data['posts'][ $row->program_partial_id ] = (isset($data['posts'][ $row->program_partial_id ])) ? $data['posts'][ $row->program_partial_id ] : array();

			$data['partials'][] = $row;

			foreach ($q->result() as $post)
			{
				if (isset($data['posts'][ $row->program_partial_id ][ $post->account_id ]) == FALSE)
				{
					$data['posts'][ $row->program_partial_id ][$post->account_id ] = $post->row_total;
				}
				else
				{
					$data['posts'][ $row->program_partial_id ][ $post->account_id ] += $post->row_total;
				}
			}
		}

		// Grab all accounts
		$query = $this->db->select('account_id, account_label, account_number')->from('crm_feffik_budget_accounts')->get();
		foreach ($query->result() as $row)
		{
			$data['accounts'][$row->account_id] = $row;
		}

		exit( $this->load->view('budget/grandov/program', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function reports()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_read($this->session->userdata['group'], 'budget'))
		{
			show_error('Access Denied!');
		}


		$data = array();

		//----------------------------------------
		// Grab all Program Sectors
		//----------------------------------------
		$data['program_sectors'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_sectors')->order_by('program_sector_label')->get();

		foreach($query->result() as $row)
		{
			$data['program_sectors'][$row->program_sector_id] = $row->program_sector_label;
		}

		//----------------------------------------
		// Grab all Program Departments
		//----------------------------------------
		$this->db->select('*');
		$this->db->from('crm_feffik_budget_programs_departments');
		$this->db->order_by('program_department_label');
		$query = $this->db->get();

		foreach ($query->result() as $key => $dep)
		{
			$data['program_departments'][ $dep->program_sector_id ][ $dep->program_department_id ] = $dep->program_department_label;
		}


		$vData = array();
		$vData['title'] = 'Budget Reports';
		$vData['pagetype'] = 'budget';
		$vData['content'] = $this->load->view('budget/reports', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function ajax_datatable_budget_items()
	{
		$this->db->save_queries = TRUE;

		//----------------------------------------
		// Columns
		//----------------------------------------
		$cols = array();
		$cols_inv = array();

		for ($i = 0; $i < $_POST['iColumns']; $i++)
		{
			$cols[ $_POST['mDataProp_'.$i] ] = $i;
		}

		$cols_inv = array_flip($cols);

		//----------------------------------------
		// Filters
		//----------------------------------------
		$sItem_label = ($this->input->post('item_label') != FALSE) ? $_POST['item_label'] : FALSE;
		$sItem_cat = ($this->input->post('item_categories') != FALSE) ? $_POST['item_categories'] : FALSE;


		//----------------------------------------
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_feffik_budget_items')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_feffik_budget_items i');
		if ($sItem_label) $this->db->like('i.item_label', $sItem_label, 'both');
		if ($sItem_cat) $this->db->where_in('i.item_cat_id', $sItem_cat);
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('i.*, ic.item_cat_label');
		$this->db->from('crm_feffik_budget_items i');
		$this->db->join('crm_feffik_budget_items_categories ic', 'i.item_cat_id = ic.item_cat_id', 'left');

		//----------------------------------------
		// Sort By
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			// Translate to column name
			$col = $cols_inv[$col];

			switch ($col)
			{
				case 'item_label':
					$this->db->order_by('i.item_label', $sort);
					break;
				case 'item_price':
					$this->db->order_by('i.item_price', $sort);
					break;
				case 'item_category':
					$this->db->order_by('ic.item_cat_label', $sort);
					break;
				default:
					$this->db->order_by('i.item_label', 'DESC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($sItem_label) $this->db->like('i.item_label', $sItem_label, 'both');
		if ($sItem_cat) $this->db->where_in('i.item_cat_id', $sItem_cat);

		//----------------------------------------
		// OFFSET & LIMIT & EXECUTE!
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$query = $this->db->get();

		//----------------------------------------
		// Loop Over all
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$trow = array();

			// Actions Block
			$actions = $row->item_id;
			$actions .= '<a href="#" class="edit item-add" data-url="budget/ajax_new_item_modal/edit/'.$row->item_id.'"></a>';

			$trow['DT_RowId']    = $row->item_id;
			$trow['item_id']     = $actions;
			$trow['item_label'] = $row->item_label;
			$trow['item_price'] = $row->item_price;
			$trow['item_category'] = $row->item_cat_label;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	public function ajax_datatable_budget_accounts()
	{
		$this->db->save_queries = TRUE;

		//----------------------------------------
		// Columns
		//----------------------------------------
		$cols = array();
		$cols_inv = array();

		for ($i = 0; $i < $_POST['iColumns']; $i++)
		{
			$cols[ $_POST['mDataProp_'.$i] ] = $i;
		}

		$cols_inv = array_flip($cols);

		//----------------------------------------
		// Filters
		//----------------------------------------
		$sAccount_number = ($this->input->post('account_number') != FALSE) ? $_POST['account_number'] : FALSE;
		$sAccount_label = ($this->input->post('account_label') != FALSE) ? $_POST['account_label'] : FALSE;
		$sAccount_cat = ($this->input->post('account_categories') != FALSE) ? $_POST['account_categories'] : FALSE;


		//----------------------------------------
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_feffik_budget_accounts')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_feffik_budget_accounts a');
		if ($sAccount_number) $this->db->like('a.account_number', $sAccount_number, 'both');
		if ($sAccount_label) $this->db->like('a.account_label', $sAccount_label, 'both');
		if ($sAccount_cat) $this->db->where_in('a.account_cat_id', $sAccount_cat);
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('a.*, ac.account_category_label');
		$this->db->from('crm_feffik_budget_accounts a');
		$this->db->join('crm_feffik_budget_accounts_categories ac', 'a.account_cat_id = ac.account_cat_id', 'left');

		//----------------------------------------
		// Sort By
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			// Translate to column name
			$col = $cols_inv[$col];

			switch ($col)
			{
				case 'account_label':
					$this->db->order_by('a.account_label', $sort);
					break;
				case 'account_number':
					$this->db->order_by('a.account_number', $sort);
					break;
				case 'account_category':
					$this->db->order_by('a.account_category_label', $sort);
					break;
				default:
					$this->db->order_by('a.account_number', 'DESC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($sAccount_number) $this->db->like('a.account_number', $sAccount_number, 'both');
		if ($sAccount_label) $this->db->like('a.account_label', $sAccount_label, 'both');
		if ($sAccount_cat) $this->db->where_in('a.account_cat_id', $sAccount_cat);

		//----------------------------------------
		// OFFSET & LIMIT & EXECUTE!
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$query = $this->db->get();

		//----------------------------------------
		// Loop Over all
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$trow = array();

			// Actions Block
			$actions = $row->account_id;
			$actions .= '<a href="#" class="edit item-add" data-url="budget/ajax_new_account_modal/edit/'.$row->account_id.'"></a>';

			$trow['DT_RowId']    = $row->account_id;
			$trow['account_id']     = $actions;
			$trow['account_number'] = $row->account_number;
			$trow['account_label'] = $row->account_label;
			$trow['account_category'] = $row->account_category_label;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	public function ajax_new_item_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_items') as $key => $val) $data[$val] = '';

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_items')->where('item_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		//----------------------------------------
		// Grab all Item Categories
		//----------------------------------------
		$data['item_categories'] = array();
		$data['item_categories'][''] = 'Select A Category';
		$query = $this->db->select('*')->from('crm_feffik_budget_items_categories')->order_by('item_cat_label')->get();

		foreach($query->result() as $row)
		{
			$data['item_categories'][$row->item_cat_id] = $row->item_cat_label;
		}

		exit( $this->load->view('budget/ajax/new_item_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_item()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('item_id', $this->input->post('item_id'));
			$this->db->delete('crm_feffik_budget_items');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['item_label'] = trim($this->input->post('item_label'));
		$data['item_price'] = $this->input->post('item_price');
		$data['item_cat_id'] = $this->input->post('item_cat_id');

		$item_id = trim($this->input->post('item_id'));

		// Item Label
		if ($data['item_label'] == FALSE)
		{
			$out['body'] = 'An Item Label is required!';
			exit(json_encode($out));
		}

		// Item Price
		if ($data['item_price'] == FALSE)
		{
			$out['body'] = 'An Item Price is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('item_id')->from('crm_feffik_budget_items')->where('item_label', $data['item_label'])->get();

		// New or Update?
		if ($item_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('item_id') != $item_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('item_id', $item_id);
			$this->db->update('crm_feffik_budget_items', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_items', $data);
			$item_id = $this->db->insert_id();
		}

		$data['item_id'] = $item_id;
		$out['chosen'] = $data;
		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	public function ajax_new_item_category_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_items_categories') as $key => $val) $data[$val] = '';

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_items_categories')->where('item_cat_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		exit( $this->load->view('budget/ajax/new_item_category_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_item_category()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('item_cat_id', $this->input->post('item_cat_id'));
			$this->db->delete('crm_feffik_budget_items_categories');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['item_cat_label'] = trim($this->input->post('item_cat_label'));

		$item_cat_id = trim($this->input->post('item_cat_id'));

		// Item Label
		if ($data['item_cat_label'] == FALSE)
		{
			$out['body'] = 'An Item Category Label is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('item_cat_id')->from('crm_feffik_budget_items_categories')->where('item_cat_label', $data['item_cat_label'])->get();

		// New or Update?
		if ($item_cat_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('item_cat_id') != $item_cat_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('item_cat_id', $item_cat_id);
			$this->db->update('crm_feffik_budget_items_categories', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_items_categories', $data);
			$item_cat_id = $this->db->insert_id();
		}

		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	public function ajax_new_account_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_accounts') as $key => $val) $data[$val] = '';

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_accounts')->where('account_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		//----------------------------------------
		// Grab all Item Categories
		//----------------------------------------
		$data['account_categories'] = array();
		$data['account_categories'][''] = 'Select A Category';
		$query = $this->db->select('*')->from('crm_feffik_budget_accounts_categories')->order_by('account_category_label')->get();

		foreach($query->result() as $row)
		{
			$data['account_categories'][$row->account_cat_id] = $row->account_category_label;
		}

		exit( $this->load->view('budget/ajax/new_account_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_account()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('account_id', $this->input->post('account_id'));
			$this->db->delete('crm_feffik_budget_accounts');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['account_number'] = trim($this->input->post('account_number'));
		$data['account_label'] = $this->input->post('account_label');
		$data['account_cat_id'] = $this->input->post('account_cat_id');

		$account_id = trim($this->input->post('account_id'));

		// Item Label
		if ($data['account_number'] == FALSE)
		{
			$out['body'] = 'An Account Number is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('account_id')->from('crm_feffik_budget_accounts')->where('account_number', $data['account_number'])->get();

		// New or Update?
		if ($account_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('account_id') != $account_id))
			{
				$out['body'] = 'An entry already exists with that number';
				exit(json_encode($out));
			}

			$this->db->where('account_id', $account_id);
			$this->db->update('crm_feffik_budget_accounts', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that number';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_accounts', $data);
			$account_id = $this->db->insert_id();
		}

		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	public function ajax_new_account_category_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_accounts_categories') as $key => $val) $data[$val] = '';

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_accounts_categories')->where('account_cat_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		exit( $this->load->view('budget/ajax/new_account_category_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_account_category()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('account_cat_id', $this->input->post('account_cat_id'));
			$this->db->delete('crm_feffik_budget_accounts_categories');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['account_category_label'] = trim($this->input->post('account_category_label'));

		$account_cat_id = trim($this->input->post('account_cat_id'));

		// Item Label
		if ($data['account_category_label'] == FALSE)
		{
			$out['body'] = 'An Account Category Label is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('account_cat_id')->from('crm_feffik_budget_accounts_categories')->where('account_category_label', $data['account_category_label'])->get();

		// New or Update?
		if ($account_cat_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('account_cat_id') != $account_cat_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('account_cat_id', $account_cat_id);
			$this->db->update('crm_feffik_budget_accounts_categories', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_accounts_categories', $data);
			$account_cat_id = $this->db->insert_id();
		}

		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	public function ajax_new_program_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_programs') as $key => $val) $data[$val] = '';

		if ($this->uri->segment(3) != FALSE) $data['program_department_id'] = $this->uri->segment(3);

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_programs')->where('program_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		//----------------------------------------
		// Grab all Program Departments
		//----------------------------------------
		$data['program_departments'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_sectors')->order_by('program_sector_label')->get();

		foreach($query->result() as $row)
		{
			//----------------------------------------
			// Grab all Program Departments
			//----------------------------------------
			$q2 = $this->db->select('*')->from('crm_feffik_budget_programs_departments')->order_by('program_department_label')->where('program_sector_id', $row->program_sector_id)->get();

			foreach ($q2->result() as $key => $dep)
			{
				$data['program_departments'][ $row->program_sector_label ][ $dep->program_department_id ] = $dep->program_department_label;
			}
		}

		exit( $this->load->view('budget/ajax/new_program_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_program()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '', 'new_item'=> 'no');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('program_id', $this->input->post('program_id'));
			$this->db->delete('crm_feffik_budget_programs');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['program_label'] = trim($this->input->post('program_label'));
		$data['program_students'] = $this->input->post('program_students');
		$data['program_department_id'] = $this->input->post('program_department_id');

		$program_id = trim($this->input->post('program_id'));

		// Item Label
		if ($data['program_label'] == FALSE)
		{
			$out['body'] = 'A Label is required!';
			exit(json_encode($out));
		}

		// Item Price
		if ($data['program_students'] == FALSE)
		{
			$out['body'] = 'Amount of students is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('program_id')->from('crm_feffik_budget_programs')->where('program_label', $data['program_label'])->get();

		// New or Update?
		if ($program_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('program_id') != $program_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('program_id', $program_id);
			$this->db->update('crm_feffik_budget_programs', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_programs', $data);
			$item_id = $this->db->insert_id();
			$out['new_item'] = 'yes';
		}

		$out['item_label'] = $data['program_label'];
		$out['item_id'] = $program_id;
		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	public function ajax_new_program_partial_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_programs_partials') as $key => $val) $data[$val] = '';

		if ($this->uri->segment(3) != FALSE) $data['program_id'] = $this->uri->segment(3);

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_programs_partials')->where('program_partial_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		//----------------------------------------
		// Grab all Programs Departments
		//----------------------------------------
		$data['programs'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_departments')->order_by('program_department_label')->get();

		foreach($query->result() as $row)
		{
			//----------------------------------------
			// Grab all Programs
			//----------------------------------------
			$q2 = $this->db->select('*')->from('crm_feffik_budget_programs')->order_by('program_label')->where('program_department_id', $row->program_department_id)->get();

			foreach ($q2->result() as $key => $dep)
			{
				$data['programs'][ $row->program_department_label ][ $dep->program_id ] = $dep->program_label;
			}
		}

		exit( $this->load->view('budget/ajax/new_program_partial_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_program_partial()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '', 'new_item' => 'no');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('program_partial_id', $this->input->post('program_partial_id'));
			$this->db->delete('crm_feffik_budget_programs_partials');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['program_partial_label'] = trim($this->input->post('program_partial_label'));
		$data['program_id'] = $this->input->post('program_id');

		$program_partial_id = trim($this->input->post('program_partial_id'));

		// Item Label
		if ($data['program_partial_label'] == FALSE)
		{
			$out['body'] = 'A Label is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('program_partial_id')->from('crm_feffik_budget_programs_partials')->where('program_partial_label', $data['program_partial_label'])->where('program_id', $data['program_id'])->get();

		// New or Update?
		if ($program_partial_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('program_partial_id') != $program_partial_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('program_partial_id', $program_partial_id);
			$this->db->update('crm_feffik_budget_programs_partials', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_programs_partials', $data);
			$program_partial_id = $this->db->insert_id();
			$out['new_item'] = 'yes';
		}

		$out['item_label'] = $data['program_partial_label'];
		$out['item_id'] = $program_partial_id;
		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	public function ajax_new_program_sector_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_programs_sectors') as $key => $val) $data[$val] = '';

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_programs_sectors')->where('program_sector_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		exit( $this->load->view('budget/ajax/new_program_sector_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_program_sector()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('program_sector_id', $this->input->post('program_sector_id'));
			$this->db->delete('crm_feffik_budget_items_categories');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['program_sector_label'] = trim($this->input->post('program_sector_label'));

		$program_sector_id = trim($this->input->post('program_sector_id'));

		// Item Label
		if ($data['program_sector_label'] == FALSE)
		{
			$out['body'] = 'An Program Category Label is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('program_sector_id')->from('crm_feffik_budget_programs_sectors')->where('program_sector_label', $data['program_sector_label'])->get();

		// New or Update?
		if ($program_sector_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('program_sector_id') != $program_sector_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('program_sector_id', $program_sector_id);
			$this->db->update('crm_feffik_budget_programs_sectors', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_programs_sectors', $data);
			$item_cat_id = $this->db->insert_id();
		}

		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	public function ajax_new_program_department_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_feffik_budget_programs_departments') as $key => $val) $data[$val] = '';

		if ($this->uri->segment(3) != FALSE) $data['program_sector_id'] = $this->uri->segment(3);

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_feffik_budget_programs_departments')->where('program_department_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}

		//----------------------------------------
		// Grab all Program Sectors
		//----------------------------------------
		$data['program_sectors'] = array();
		$query = $this->db->select('*')->from('crm_feffik_budget_programs_sectors')->order_by('program_sector_label')->get();

		foreach($query->result() as $row)
		{
			$data['program_sectors'][$row->program_sector_id] = $row->program_sector_label;
		}

		exit( $this->load->view('budget/ajax/new_program_department_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_program_department()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '', 'new_item' => 'no');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('program_department_id', $this->input->post('program_department_id'));
			$this->db->delete('crm_feffik_budget_items_categories');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['program_department_label'] = trim($this->input->post('program_department_label'));
		$data['program_sector_id'] = trim($this->input->post('program_sector_id'));

		$program_department_id = trim($this->input->post('program_department_id'));

		// Item Label
		if ($data['program_department_label'] == FALSE)
		{
			$out['body'] = 'An Program Category Label is required!';
			exit(json_encode($out));
		}

		// Lets see if it already exists
		$exists = $this->db->select('program_department_id')->from('crm_feffik_budget_programs_departments')->where('program_department_label', $data['program_department_label'])->get();

		// New or Update?
		if ($program_department_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('program_department_id') != $program_department_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('program_department_id', $program_department_id);
			$this->db->update('crm_feffik_budget_programs_departments', $data);
		}
		else
		{
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->insert('crm_feffik_budget_programs_departments', $data);
			$program_department_id = $this->db->insert_id();
			$out['new_item'] = 'yes';
		}

		$out['item_label'] = $data['program_department_label'];
		$out['item_id'] = $program_department_id;
		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //






	public function report()
	{
		$this->load->library('mypdf');
		$type = $this->input->get('type');
		$id = $this->input->get('id');
		$method = 'pdf_report_' . $type;

		$this->{$method}($id);
	}

	// ********************************************************************************* //

	private function pdf_report_department($id)
	{
		$data = array();
		$data['department_id'] = $id;
		$data['programs'] = array();
		$data['partials'] = array();
		$data['accounts'] = array();
		$data['accounts_id'] = array();
		$data['posts'] = array();

		// Get Department
		$dep = $this->db->select('*')->from('crm_feffik_budget_programs_departments')->where('program_department_id', $data['department_id'])->get();
		$dep_label = $dep->row('program_department_label');

		// Grab all accounts
		$this->db->select('ba.account_id, ba.account_label, ba.account_number, bac.account_category_label');
		$this->db->from('crm_feffik_budget_accounts ba');
		$this->db->join('crm_feffik_budget_accounts_categories bac', 'ba.account_cat_id = bac.account_cat_id', 'left');
		$this->db->where_in('ba.account_cat_id', array(6,7));
		$this->db->order_by('bac.account_category_label', 'asc');
		$this->db->order_by('ba.account_number', 'asc');
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$data['accounts'][$row->account_category_label][] = $row;
			$data['accounts_id'][ $row->account_id ] = $row;
		}

		// Grab all programs
		$query = $this->db->select('program_id')->from('crm_feffik_budget_programs')->where('program_department_id', $data['department_id'])->get();
		foreach ($query->result() as $row)
		{
			$data['programs'][] = $row->program_id;
		}

		// We need programs!
		if (empty($data['programs']) == TRUE)
		{
			exit('<h4>No Qualifications have been recorded!</h4>');
		}

		// Grab all partials
		$query = $this->db->select('program_partial_id')->from('crm_feffik_budget_programs_partials')->where_in('program_id', $data['programs'])->get();
		foreach ($query->result() as $row)
		{
			$data['partials'][] = $row->program_partial_id;
		}

		// Grab account totals
		$this->db->select('bp.row_total, bp.account_id');
		$this->db->from('crm_feffik_budget_posts bp');
		$this->db->join('crm_feffik_budget_accounts ba', 'ba.account_id = bp.account_id');
		$this->db->where_in('program_id', $data['programs']);
		if (empty($data['partials']) == FALSE) $this->db->or_where_in('program_partial_id', $data['partials']);
		$this->db->order_by('ba.account_label');
		$q = $this->db->get();

		foreach ($q->result() as $post)
		{
			if (isset($data['posts'][ $post->account_id ]) == FALSE)
			{
				$data['posts'][$post->account_id ] = $post->row_total;
			}
			else
			{
				$data['posts'][ $post->account_id ] += $post->row_total;
			}
		}

		// Lets see which ones we can kill
		foreach ($data['accounts'] as $cat => $accounts)
		{
			foreach ($accounts as $key => $account)
			{
				if (isset($data['posts'][ $account->account_id ]) == FALSE)
				{
					$data['posts'][ $account->account_id ] = 0;
				}
			}

			//if (empty($data['accounts'][$cat]) == TRUE) unset($data['accounts'][$cat]);
		}

		//print_r($data);







		//----------------------------------------
		// HTML
		//----------------------------------------
		$css = '
		<style>
		table {text-align:left;}
		table th {font-size:30px; font-weight:bold;border-bottom:2px solid #000000; }
		table td {border-bottom:1px solid #CCCCCC;}
		.pagetitle {}
		.subtotal {text-align:right; color:darkblue;}
		.grandtotal {text-align:right; color:darkgreen; border-top:3px dotted darkgreen;}
		</style>
		';

		$html = $css;
		$html .= '<h2 class="pagetitle">Department Overview - '.$dep_label.'</h2>';

		$totaal = 0;

		foreach($data['accounts'] as $account_cat => $accounts)
		{
			$cat_total = 0;

			$html .= '
			<h4 style="color:darkgreen;">'.strtoupper($account_cat).'</h4>
			<table border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>Account</th>
					<th>Account Label</th>
					<th style="text-align:right;">Total</th>
				</tr>
			</thead>
			<tbody>';

			foreach($accounts as $account)
			{
				$cat_total += $data['posts'][$account->account_id];
				$totaal += $data['posts'][$account->account_id];
				$html .='
				<tr class="ItemRow">
		            <td>'.$account->account_number.'</td>
		            <td>'.$account->account_label.'</td>
		            <td style="text-align:right;">'.number_format($data['posts'][$account->account_id], 2).'</td>
		          </tr>
		        ';
			}

		   $html .= '
			</tbody>
			</table>

			<h4 class="subtotal">Subtotal: '.number_format($cat_total, 2).'</h4>
			<br>';

		}

		$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($totaal, 2).'</h3>';


		//----------------------------------------
		// Create PDF
		//----------------------------------------
		// create new PDF document
		$pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Page Titles
		$pdf->page_title = 'Budget Department (2012) - '.$dep_label;

		// set default header data
		$pdf->SetHeaderData(NULL, NULL, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set font
		$pdf->SetFont('helvetica', '', 10);

		$pdf->SetCellPadding(0);
		$pdf->setCellHeightRatio(1.70);
		$pdf->setImageScale(1);

		// add a page
		$pdf->AddPage();

		//$pdf->Image('@'.file_get_contents(FCPATH.'assets/logos/logo_crm_vertical.png'), 172, 1, 34, 0, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');


		// Grab all programs
		$programsquery = $this->db->select('*')->from('crm_feffik_budget_programs')->where('program_department_id', $data['department_id'])->get();



		//----------------------------------------
		// QUALIFICATIOSN OVERVIEW
		//----------------------------------------

		// add a page
		$pdf->AddPage();
		$dep_total = 0;

		$html = $css;
		$html .= '<h2 class="pagetitle">Qualifications Overview - '. $dep_label . '</h2>';
		$html .= '<table border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Qualification</th>
							<th>Students</th>
							<th style="text-align:right;">Total</th>
						</tr>
					</thead>
					<tbody>';

		if ($programsquery->num_rows() == 0)
		{
			$html .='<tr><td colspan="3">Nothing has been recorded..</td></tr>';
		}

		foreach ($programsquery->result() as $program)
		{
			// Grab all Posts & Items
			$this->db->select('SUM(bp.row_total) AS total');
			$this->db->from('crm_feffik_budget_posts bp');
			$this->db->join('crm_feffik_budget_programs_partials par', 'par.program_partial_id = bp.program_partial_id', 'left');
			$this->db->where('par.program_id', $program->program_id);
			$this->db->or_where('bp.program_id', $program->program_id);
			$query = $this->db->get();

			$html .= '
			    	<tr class="ItemRow">
			            <td>'.$program->program_label.'</td>
						<td>'.$program->program_students.'</td>
						<td style="text-align:right;">'.number_format($query->row('total'), 2).'</td>
					</tr>
			        ';

			$dep_total += $query->row('total');

		}

		$html .= '</tbody></table> <h4></h4>';
		$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($dep_total, 2).'</h3>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');


		//----------------------------------------
		// LOOP OVER ALL QUALIFICATIONS
		//----------------------------------------

		$data['posts'] = array();
		foreach ($programsquery->result() as $program)
		{
			// add a page
			$pdf->AddPage();

			$data['program_posts'] = array();
			//----------------------------------------
			// QUALIFICATION OVERVIEW
			//----------------------------------------
			$this->db->select('bp.row_total, bp.account_id');
			$this->db->from('crm_feffik_budget_posts bp');
			$this->db->join('crm_feffik_budget_programs_partials par', 'par.program_partial_id = bp.program_partial_id', 'left');
			$this->db->where('par.program_id', $program->program_id);
			$this->db->or_where('bp.program_id', $program->program_id);
			$q = $this->db->get();

			foreach ($q->result() as $post)
			{
				if (isset($data['program_posts'][ $post->account_id ]) == FALSE)
				{
					$data['program_posts'][$post->account_id ] = $post->row_total;
				}
				else
				{
					$data['program_posts'][ $post->account_id ] += $post->row_total;
				}
			}

			// Lets see which ones we can kill
			foreach ($data['accounts'] as $cat => $accounts)
			{
				foreach ($accounts as $key => $account)
				{
					if (isset($data['program_posts'][ $account->account_id ]) == FALSE)
					{
						$data['program_posts'][ $account->account_id ] = 0;
					}
				}
			}


			$html = $css;
			$html .= '<h2 class="pagetitle">Qualification Overview - '.$program->program_label.'</h2>';

			$qualtotaal = 0;

			foreach($data['accounts'] as $account_cat => $accounts)
			{
				$cat_total = 0;

				$html .= '
				<h4 style="color:darkgreen;">'.strtoupper($account_cat).'</h4>
				<table border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Account</th>
						<th>Account Label</th>
						<th style="text-align:right;">Total</th>
					</tr>
				</thead>
				<tbody>';

				foreach($accounts as $account)
				{
					$cat_total += $data['program_posts'][$account->account_id];
					$qualtotaal += $data['program_posts'][$account->account_id];
					$html .='
					<tr class="ItemRow">
			            <td>'.$account->account_number.'</td>
			            <td>'.$account->account_label.'</td>
			            <td style="text-align:right;">'.number_format($data['program_posts'][$account->account_id], 2).'</td>
			          </tr>
			        ';
				}

			   $html .= '
				</tbody>
				</table>

				<h4 class="subtotal">Subtotal: '.number_format($cat_total, 2).'</h4>
				<br>';

			}

			$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($qualtotaal, 2).'</h3>';

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');








			$data['partials'] = array();

			$program_total = 0;

			// add a page
			$pdf->AddPage();

			//----------------------------------------
			// LOOP OVER ALL PARTIALS
			//----------------------------------------
			$query = $this->db->select('*')->from('crm_feffik_budget_programs_partials')->where('program_id', $program->program_id)->order_by('program_partial_label', 'ASC')->get();
			foreach ($query->result() as $row)
			{
				// Grab account totals
				$this->db->select('bp.row_total, bp.account_id');
				$this->db->from('crm_feffik_budget_posts bp');
				$this->db->join('crm_feffik_budget_accounts ba', 'ba.account_id = bp.account_id');
				$this->db->where('program_partial_id', $row->program_partial_id);
				$this->db->order_by('ba.account_number', 'asc');
				$q = $this->db->get();

				$data['posts'][ $row->program_partial_id ] = (isset($data['posts'][ $row->program_partial_id ])) ? $data['posts'][ $row->program_partial_id ] : array();

				$data['partials'][] = $row;

				foreach ($q->result() as $post)
				{
					if (isset($data['posts'][ $row->program_partial_id ][ $post->account_id ]) == FALSE)
					{
						$data['posts'][ $row->program_partial_id ][$post->account_id ] = $post->row_total;
					}
					else
					{
						$data['posts'][ $row->program_partial_id ][ $post->account_id ] += $post->row_total;
					}
				}
			}



			$html = $css;
			$html .= '<h2 class="pagetitle">Qualification: '. $program->program_label . '</h2>';




			// Grab the GENERAL
			$this->db->select('bp.row_total, bp.account_id');
			$this->db->from('crm_feffik_budget_posts bp');
			$this->db->join('crm_feffik_budget_accounts ba', 'ba.account_id = bp.account_id');
			$this->db->where('bp.program_id', $program->program_id);
			$this->db->order_by('ba.account_number');
			$q = $this->db->get();

			$partial_total = 0;
			$html .= '<h4 style="color:#953B39;">GENERAL</h4>';
			$html .= '<table border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>Account</th>
									<th>Account Label</th>
									<th style="text-align:right;">Total</th>
								</tr>
							</thead>
							<tbody>';

			if ($q->num_rows() == 0)
			{
				$html .='<tr><td colspan="3">Nothing has been recorded..</td></tr>';
			}


			$general = array();
			foreach($q->result() as $gen)
			{
				if (isset($general[ $gen->account_id ]) == FALSE)
				{
					$general[ $gen->account_id ] = $gen->row_total;
				}
				else
				{
					$general[ $gen->account_id ] += $gen->row_total;
				}
			}

			foreach($general as $gen_account_id => $gen_total)
			{
		    	$partial_total += $gen_total;
		    	$program_total += $gen_total;

		    	$html .= '
		    	<tr class="ItemRow">
		            <td>'.$data['accounts_id'][$gen_account_id]->account_number.'</td>
					<td>'.$data['accounts_id'][$gen_account_id]->account_label.'</td>
					<td style="text-align:right;">'.number_format($gen_total, 2).'</td>
				</tr>
		        ';
			}

			$html .= '</tbody></table>';
			$html .= '<h4 class="subtotal">Subtotal: '.number_format($partial_total, 2).'</h4>';




			foreach ($data['partials'] as $partial)
			{
				$html .= '<h4 style="color:darkgreen;">'.strtoupper($partial->program_partial_label).'</h4>';
				$html .= '<table border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>Account</th>
									<th>Account Label</th>
									<th style="text-align:right;">Total</th>
								</tr>
							</thead>
							<tbody>';

			    if (empty($data['posts'][$partial->program_partial_id]) == TRUE)
			    {
			    	$html .='<tr><td colspan="3">Nothing has been recorded..</td></tr>';
			    }

			    $partial_total = 0;
			    foreach($data['posts'][$partial->program_partial_id] as $account_id => $account_total)
			    {
			    	$partial_total += $account_total;
			    	$program_total += $account_total;

			    	$html .= '
			    	<tr class="ItemRow">
			            <td>'.$data['accounts_id'][$account_id]->account_number.'</td>
						<td>'.$data['accounts_id'][$account_id]->account_label.'</td>
						<td style="text-align:right;">'.number_format($account_total, 2).'</td>
					</tr>
			        ';
			    }

			    $html .= '</tbody></table>';
			    $html .= '<h4 class="subtotal">Subtotal: '.number_format($partial_total, 2).'</h4>';
			}

			$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($program_total, 2).'</h3>';

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');


			//----------------------------------------
			// SUB-QUALIFICATION OVERVIEW - GENERAL!!
			//----------------------------------------
			// Grab all Posts & Items
			$this->db->select('bp.account_id, bp.item_price, bi.item_label, bp.item_desc, bp.item_quantity, bp.row_total, bp.account_alt_label, ba.account_label, ba.account_number');
			$this->db->from('crm_feffik_budget_posts bp');
			$this->db->join('crm_feffik_budget_accounts ba', 'bp.account_id = ba.account_id', 'left');
			$this->db->join('crm_feffik_budget_items bi', 'bi.item_id = bp.item_id', 'left');
			$this->db->where('bp.program_id', $program->program_id);
			$this->db->order_by('ba.account_number', 'asc');
			$query = $this->db->get();

			$html = $css;

			// add a page
			$pdf->AddPage();
			$partial_total = 0;

			$html .= '<h2 class="pagetitle">';
			$html .= 'Qualification Details: '. $program->program_label . '<br>';
			$html .= '<font size="10" style="color:#953B39;">GENERAL</font>';
			$html .= '</h2>';

			$html .= '<table border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="150">Account</th>
									<th width="30">Qty.</th>
									<th width="117">Item</th>
									<th width="150">Description</th>
									<th width="50" style="text-align:right;">Price</th>
									<th width="60" style="text-align:right;">Total</th>
								</tr>
							</thead>
							<tbody>';

			if ($query->num_rows() == 0)
		    {
		    	$html .='<tr><td colspan="6">Nothing has been recorded..</td></tr>';
		    }

			foreach ($query->result() as $row)
			{
				$partial_total += $row->row_total;

		    	if ($row->account_alt_label != FALSE) $row->account_label = $row->account_alt_label;

		    	$html .= '
		    	<tr class="ItemRow">
		    		<td width="150">'. $row->account_number .' - ' . $row->account_label .'</td>
		            <td width="30">'.$row->item_quantity.'</td>
					<td width="117">'.$row->item_label.'</td>
					<td width="150">'.$row->item_desc.'</td>
					<td width="50" style="text-align:right;">'.number_format($row->item_price, 2).'</td>
					<td width="60" style="text-align:right;">'.number_format($row->row_total, 2).'</td>
				</tr>
		        ';

			}

			$html .= '</tbody></table><h4></h4>';
			$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($partial_total, 2).'</h3>';

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');


			//----------------------------------------
			// SUB-QUALIFICATION OVERVIEW
			//----------------------------------------
			foreach ($data['partials'] as $partial)
			{
				$data['posts'] = array();

				// Grab all Posts & Items
				$this->db->select('bp.account_id, bp.item_price, bi.item_label, bp.item_desc, bp.item_quantity, bp.row_total, ba.account_label, ba.account_number');
				$this->db->from('crm_feffik_budget_posts bp');
				$this->db->join('crm_feffik_budget_accounts ba', 'bp.account_id = ba.account_id', 'left');
				$this->db->join('crm_feffik_budget_items bi', 'bi.item_id = bp.item_id', 'left');
				$this->db->where('ba.account_cat_id', 6);
				$this->db->where('bp.program_partial_id', $partial->program_partial_id);
				$this->db->order_by('ba.account_number', 'asc');
				$query = $this->db->get();

				foreach($query->result() as $row)
				{
					$data['posts'][ $row->account_number . ' - ' . $row->account_label ][] = $row;
				}

				if (empty($data['posts']) == TRUE) continue;

				$html = $css;

				// add a page
				$pdf->AddPage();
				$partial_total = 0;

				$html .= '<h2 class="pagetitle">';
				$html .= 'Qualification Details: '. $program->program_label . '<br>';
				$html .= '<font size="10">Sub-Qualification: '. $partial->program_partial_label.'</font>';
				$html .= '</h2>';

				foreach ($data['posts'] as $act_label => $act_posts)
				{
					$html .= '<h4 style="color:darkgreen;">'.strtoupper($act_label).'</h4>';
					$html .= '<table border="0" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th width="30">Qty.</th>
										<th width="167">Item</th>
										<th width="250">Description</th>
										<th width="50" style="text-align:right;">Price</th>
										<th width="60" style="text-align:right;">Total</th>
									</tr>
								</thead>
								<tbody>';

				    if (empty($act_posts) == TRUE)
				    {
				    	$html .='<tr><td colspan="5">Nothing has been recorded..</td></tr>';
				    }

				    $account_total = 0;
				    foreach($act_posts as $post_row)
				    {
				    	$account_total += $post_row->row_total;
				    	$partial_total += $post_row->row_total;

				    	$html .= '
				    	<tr class="ItemRow">
				            <td width="30">'.$post_row->item_quantity.'</td>
							<td width="167">'.$post_row->item_label.'</td>
							<td width="250">'.$post_row->item_desc.'</td>
							<td width="50" style="text-align:right;">'.number_format($post_row->item_price, 2).'</td>
							<td width="60" style="text-align:right;">'.number_format($post_row->row_total, 2).'</td>
						</tr>
				        ';
				    }

				    $html .= '</tbody></table>';
				    $html .= '<h4 class="subtotal">Subtotal: '.number_format($account_total, 2).'</h4>';
				}

				$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($partial_total, 2).'</h3>';

				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
			}


		}




		$pdf->Output('Budget Department - 2012'.date('d-m-Y Hi').'.pdf', 'I');
	}

	// ********************************************************************************* //

	private function pdf_report_grand($id)
	{
		//----------------------------------------
		// Create PDF
		//----------------------------------------
		// create new PDF document
		$pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Page Titles
		$pdf->page_title = 'Budget Overview';

		// set default header data
		$pdf->SetHeaderData(NULL, NULL, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set font
		$pdf->SetFont('helvetica', '', 10);

		$pdf->SetCellPadding(0);
		$pdf->setCellHeightRatio(1.70);
		$pdf->setImageScale(1);

		// add a page
		$pdf->AddPage();

		//----------------------------------------
		// HTML
		//----------------------------------------
		$css = '
		<style>
		table {text-align:left;}
		table th {font-size:30px; font-weight:bold;border-bottom:2px solid #000000; }
		table td {border-bottom:1px solid #CCCCCC;}
		.pagetitle {}
		.subtotal {text-align:right; color:darkblue;}
		.grandtotal {text-align:right; color:darkgreen; border-top:3px dotted darkgreen;}
		</style>
		';

		$html = $css;


		//----------------------------------------
		// Grab All Sectors
		//----------------------------------------
		$sectors = array();
		$sectors_id = array();

		$query = $this->db->select('*')->from('crm_feffik_budget_programs_sectors')->order_by('program_sector_label', 'ASC')->get();

		foreach ($query->result() as $row)
		{
			$sectors[ $row->program_sector_id ] = $row->program_sector_label;
			$sectors_id[] = $row->program_sector_label;
		}

		//----------------------------------------
		// Grab All Departments
		//----------------------------------------
		$departments = array();
		$departments_cat = array();
		$departments_id = array();

		$query = $this->db->select('*')->from('crm_feffik_budget_programs_departments')->order_by('program_department_label', 'ASC')->get();

		foreach ($query->result() as $row)
		{
			$departments[ $row->program_department_id ] = $row->program_department_label;
			$departments_id[] = $row->program_department_label;

			$departments_cat[ $row->program_sector_id ][ $row->program_department_id ] = $row->program_department_label;
		}

		//----------------------------------------
		// Grab All Programs
		//----------------------------------------
		$programs_cat = array();

		$query = $this->db->select('*')->from('crm_feffik_budget_programs')->order_by('program_label', 'ASC')->get();

		foreach ($query->result() as $row)
		{
			$programs_cat[ $row->program_department_id ][] = $row->program_id;
		}

		//----------------------------------------
		// Grab All Partials
		//----------------------------------------
		$partials_cat = array();

		$query = $this->db->select('*')->from('crm_feffik_budget_programs_partials')->order_by('program_partial_label', 'ASC')->get();

		foreach ($query->result() as $row)
		{
			$partials_cat[ $row->program_id ][] = $row->program_partial_id;
		}

		//----------------------------------------
		// Grab Grand Totals
		//----------------------------------------
		$sector_totals = array();
		$department_totals = array();
		$grandtotal = 0;
		foreach ($sectors as $sector_id => $sector_label)
		{
			if (isset($departments_cat[$sector_id]) == FALSE) $departments_cat[$sector_id] = array();
			foreach ($departments_cat[$sector_id] as $department_id => $department_label)
			{
				if (isset($programs_cat[$department_id]) == FALSE) $programs_cat[$department_id] = array();
				$progs = array();
				$parts = array();

				foreach ($programs_cat[$department_id] as $program_id)
				{
					if (isset($partials_cat[$program_id]) == FALSE) $partials_cat[$program_id] = array();
					$progs[] = $program_id;
					$parts = array_merge($parts, $partials_cat[$program_id]);
				}

				if (empty($progs) == TRUE) continue;

				$this->db->select('SUM(row_total) as total');
				$this->db->from('crm_feffik_budget_posts');
				$this->db->where_in('program_id', $progs);
				if (empty($parts) == FALSE) $this->db->or_where_in('program_partial_id', $parts);
				$query = $this->db->get();

				$department_totals[$department_id] = $query->row('total');

				if (isset($sector_totals[$sector_id]) == FALSE) $sector_totals[$sector_id] = $query->row('total');
				else $sector_totals[$sector_id] += $query->row('total');

				$grandtotal += $query->row('total');
			}
		}

		//$html .= '<h1 class="pagetitle">Budget Overview</h1>';


		foreach ($departments_cat as $sector_id => $deps)
		{
			$html .= '<h5 style="font-size:50px; color:darkgreen;">' . $sectors[$sector_id] . '</h5>';

			$html .= '
				<table border="0" cellpadding="0" cellspacing="0">
				<tbody>';

			$cat_total = 0;
			foreach ($deps as $department_id => $department_label)
			{
				if (isset($department_totals[$department_id]) == FALSE) $department_totals[$department_id] = 0;
				$cat_total += $department_totals[$department_id];

				$html .='
				<tr class="ItemRow">
		            <td style="font-size:40px;">'.$department_label.'</td>
		            <td style="font-size:40px; text-align:right;">'.number_format($department_totals[$department_id], 2).'</td>
		          </tr>
		        ';
			}

			$html .= '
				</tbody>
				</table>
				<h4 class="subtotal" style="font-size:40px;">Subtotal: '.number_format($cat_total, 2).'</h4>';
		}

		$html .= '<h1 style="font-size:50px;" class="grandtotal">TOTAL: ' . number_format($grandtotal, 2).'</h1>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');


		//----------------------------------------
		// Grab all accounts
		//----------------------------------------
		$query = $this->db->query("
			SELECT account_number, account_label, account_category_label,
			(
				SELECT SUM(row_total) FROM crm_feffik_budget_posts WHERE account_id = ba.account_id
			) as account_total
			FROM crm_feffik_budget_accounts AS `ba`
			LEFT JOIN crm_feffik_budget_accounts_categories AS `bac` ON bac.account_cat_id = ba.account_cat_id
			ORDER BY account_number ASC
		");

		$accounts = array();
		foreach ($query->result() as $row)
		{
			$accounts[ $row->account_category_label ][] = $row;
		}

		// add a page
		$pdf->AddPage();


		$html = $css;
		$html .= '<h2 class="pagetitle">Accounts Overview</h2>';
		$totaal = 0;

		foreach ($accounts as $account_cat => $cat)
		{
			$cat_total = 0;

			$html .= '
				<h4 style="color:darkgreen;">'.strtoupper($account_cat).'</h4>
				<table border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Account</th>
						<th>Account Label</th>
						<th style="text-align:right;">Total</th>
					</tr>
				</thead>
				<tbody>';

			foreach ($cat as $account)
			{
				$cat_total += $account->account_total;
				$totaal += $account->account_total;
				$html .='
				<tr class="ItemRow">
		            <td>'.$account->account_number.'</td>
		            <td>'.$account->account_label.'</td>
		            <td style="text-align:right;">'.number_format($account->account_total, 2).'</td>
		          </tr>
		        ';
			}

			 $html .= '
				</tbody>
				</table>

				<h4 class="subtotal">Subtotal: '.number_format($cat_total, 2).'</h4>';
		}

		$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($totaal, 2).'</h3>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');



		//----------------------------------------
		// Loop over all sectors
		//----------------------------------------
		foreach ($sectors as $sector_id => $sector_label)
		{
			// add a page
			$pdf->AddPage();

			$html = $css;
			$html .= '<h2 class="pagetitle">Sector Overview: '.$sector_label.'</h2>';
			$sector_totaal = 0;

			$progs = array();
			$parts = array();

			if (isset($departments_cat[$sector_id]) == FALSE) $departments_cat[$sector_id] = array();
			foreach ($departments_cat[$sector_id] as $department_id => $department_label)
			{
				if (isset($programs_cat[$department_id]) == FALSE) $programs_cat[$department_id] = array();
				

				foreach ($programs_cat[$department_id] as $program_id)
				{
					if (isset($partials_cat[$program_id]) == FALSE) $partials_cat[$program_id] = array();
					$progs[] = $program_id;
					$parts = array_merge($parts, $partials_cat[$program_id]);
				}

				if (empty($progs) == TRUE) continue;

			}


			$query = $this->db->query("
					SELECT account_number, account_label, account_category_label,
					(
						SELECT SUM(row_total)
						FROM crm_feffik_budget_posts
						WHERE account_id = ba.account_id
						AND (
						". ((empty($progs) != TRUE) ?  ' program_id IN ('.implode(',', $progs).') ' : ' program_id = 99999') ."
						". ((empty($parts) != TRUE) ?  ' OR program_partial_id IN ('.implode(',', $parts).') ' : '') ."
						)
					) as account_total
					FROM crm_feffik_budget_accounts AS `ba`
					LEFT JOIN crm_feffik_budget_accounts_categories AS `bac` ON bac.account_cat_id = ba.account_cat_id
					ORDER BY account_number ASC
				");

			$accounts = array();
			foreach ($query->result() as $row)
			{
				$accounts[ $row->account_category_label ][] = $row;
			}

			foreach ($accounts as $account_cat => $cat)
			{
				$cat_total = 0;

				$html .= '
					<h4 style="color:darkgreen;">'.strtoupper($account_cat).'</h4>
					<table border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Account</th>
							<th>Account Label</th>
							<th style="text-align:right;">Total</th>
						</tr>
					</thead>
					<tbody>';

				foreach ($cat as $account)
				{
					$cat_total += $account->account_total;
					$sector_totaal += $account->account_total;
					$html .='
					<tr class="ItemRow">
			            <td>'.$account->account_number.'</td>
			            <td>'.$account->account_label.'</td>
			            <td style="text-align:right;">'.number_format($account->account_total, 2).'</td>
			          </tr>
			        ';
				}

				 $html .= '
					</tbody>
					</table>

					<h4 class="subtotal">Subtotal: '.number_format($cat_total, 2).'</h4>';
			}

			$html .= '<h3 class="grandtotal">TOTAL: ' . number_format($sector_totaal, 2).'</h3>';

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');
		}


		$pdf->Output('Budget Grand Overview'.date('d-m-Y Hi').'.pdf', 'I');
	}

	// ********************************************************************************* //

	private function pdf_report_accounts($id)
	{
		//----------------------------------------
		// Create PDF
		//----------------------------------------
		// create new PDF document
		$pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Page Titles
		$pdf->page_title = 'Accounts List';

		// set default header data
		$pdf->SetHeaderData(NULL, NULL, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set font
		$pdf->SetFont('helvetica', '', 10);

		$pdf->SetCellPadding(0);
		$pdf->setCellHeightRatio(1.70);
		$pdf->setImageScale(1);

		// add a page
		$pdf->AddPage();

		//----------------------------------------
		// HTML
		//----------------------------------------
		$css = '
		<style>
		table {text-align:left;}
		table th {font-size:30px; font-weight:bold;border-bottom:2px solid #000000; }
		table td {border-bottom:1px solid #CCCCCC;}
		.pagetitle {}
		.subtotal {text-align:right; color:darkblue;}
		.grandtotal {text-align:right; color:darkgreen; border-top:3px dotted darkgreen;}
		</style>
		';

		$html = $css;


		//----------------------------------------
		// Grab all accounts
		//----------------------------------------
		$query = $this->db->query("
			SELECT account_number, account_label, account_category_label
			FROM crm_feffik_budget_accounts AS `ba`
			LEFT JOIN crm_feffik_budget_accounts_categories AS `bac` ON bac.account_cat_id = ba.account_cat_id
			ORDER BY account_number ASC
		");

		$accounts = array();
		foreach ($query->result() as $row)
		{
			$accounts[ $row->account_category_label ][] = $row;
		}

		foreach ($accounts as $account_cat => $cat)
		{
			$html .= '
				<h4 style="color:darkgreen;">'.strtoupper($account_cat).'</h4>
				<table border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Account</th>
						<th>Account Label</th>
					</tr>
				</thead>
				<tbody>';

			foreach ($cat as $account)
			{
				$html .='
				<tr class="ItemRow">
		            <td>'.$account->account_number.'</td>
		            <td>'.$account->account_label.'</td>
		          </tr>
		        ';
			}

			 $html .= '
				</tbody>
				</table> <h3></h3>';
		}


		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');


		$pdf->Output('Budget Grand Overview'.date('d-m-Y Hi').'.pdf', 'I');
	}

	// ********************************************************************************* //

	private function pdf_report_items($id)
	{
		//----------------------------------------
		// Create PDF
		//----------------------------------------
		// create new PDF document
		$pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Page Titles
		$pdf->page_title = 'Item List';

		// set default header data
		$pdf->SetHeaderData(NULL, NULL, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set font
		$pdf->SetFont('helvetica', '', 10);

		$pdf->SetCellPadding(0);
		$pdf->setCellHeightRatio(1.70);
		$pdf->setImageScale(1);

		// add a page
		$pdf->AddPage();

		//----------------------------------------
		// HTML
		//----------------------------------------
		$css = '
		<style>
		table {text-align:left;}
		table th {font-size:30px; font-weight:bold;border-bottom:2px solid #000000; }
		table td {border-bottom:1px solid #CCCCCC;}
		.pagetitle {}
		.subtotal {text-align:right; color:darkblue;}
		.grandtotal {text-align:right; color:darkgreen; border-top:3px dotted darkgreen;}
		</style>
		';

		$html = $css;


		//----------------------------------------
		// Grab all accounts
		//----------------------------------------
		$query = $this->db->query("
			SELECT item_label, item_price, item_cat_label
			FROM crm_feffik_budget_items AS `ba`
			LEFT JOIN crm_feffik_budget_items_categories AS `bac` ON bac.item_cat_id = ba.item_cat_id
			ORDER BY item_cat_label ASC, item_label ASC
		");

		$accounts = array();
		foreach ($query->result() as $row)
		{
			$accounts[ $row->item_cat_label ][] = $row;
		}

		foreach ($accounts as $account_cat => $cat)
		{
			if ($account_cat == FALSE) $account_cat = "UNCATEGORIZED";
			$html .= '
				<h4 style="color:darkgreen;">'.strtoupper($account_cat).'</h4>
				<table border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Item</th>
						<th>Default Price</th>
					</tr>
				</thead>
				<tbody>';

			foreach ($cat as $account)
			{
				$html .='
				<tr class="ItemRow">
		            <td>'.$account->item_label.'</td>
		            <td>'.number_format($account->item_price, 2).'</td>
		          </tr>
		        ';
			}

			 $html .= '
				</tbody>
				</table> <h3></h3>';
		}


		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');


		$pdf->Output('Budget Grand Overview'.date('d-m-Y Hi').'.pdf', 'I');
	}

	// ********************************************************************************* //
}

/* End of file welcome.php */
/* Location: ./application/controllers/contacts.php */