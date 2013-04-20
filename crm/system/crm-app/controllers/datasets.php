<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datasets extends CRM_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	// ********************************************************************************* //

	public function index()
	{
		$data = array();

		//----------------------------------------
		// Output
		//----------------------------------------
		$vData = array();
		$vData['title'] = 'Datasets';
		$vData['pagetype'] = 'datasets';
		$vData['content'] = $this->load->view('datasets/index', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function ajax()
	{
		// What is the AJAX Method
		$ajax_method = $this->uri->segment(3);

		// Execute method
		$method = 'ajax_' . $ajax_method;
		$this->{$method}();
	}

	// ********************************************************************************* //

	private function ajax_street_add()
	{
		$vData = array();

		// What group id?
		$street_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['street_id'] = $street_id;
		$vData['street_label'] = '';

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($street_id > 0)
		{
			$query = $this->db->select('*')->from('crm_dataset_streets')->where('street_id', $street_id)->get();

			$vData['street_label'] = $query->row('street_label');
		}

		$this->load->view('datasets/ajax/add_street', $vData);
	}

	// ********************************************************************************* //

	private function ajax_street_update()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		$street_id = $this->input->post('street_id');

		//----------------------------------------
		// Do we need to delete?
		//----------------------------------------
		$delete = $this->uri->segment(4);
		if ($delete == 'delete')
		{
			$this->db->set('street_id', 0);
			$this->db->where('street_id', $street_id);
			$this->db->update('crm_contacts');

			$this->db->query("DELETE FROM crm_dataset_streets WHERE street_id = ".$street_id);

			exit();
		}

		//----------------------------------------
		// Add/Update
		//----------------------------------------
		$data = array();
		$data['street_label'] = trim($this->input->post('street_label'));

		// Lets see if it already exists
		$exists = $this->db->select('street_id')->from('crm_dataset_streets')->where('street_label', $data['street_label'])->get();

		// New or Update?
		if ($street_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('street_id') != $street_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('street_id', $street_id);
			$this->db->update('crm_dataset_streets', $data);
		}
		else
		{
			//----------------------------------------
			// Does it already exist?
			//----------------------------------------
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'That Street already exists!';
				exit(json_encode($out));
			}

			$this->db->insert('crm_dataset_streets', $data);
			$street_id = $this->db->insert_id();
		}

		//----------------------------------------
		// Return
		//----------------------------------------
		$out['success'] = 'yes';
		$out['chosen'] = "<option value='{$street_id}'>{$data['street_label']}</option>";

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	private function ajax_street_datatable()
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
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_dataset_streets')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Global Filter
		//----------------------------------------
		$global_filter = FALSE;
		if ($this->input->post('sSearch') != FALSE)
		{
			$global_filter = $this->input->post('sSearch');
		}

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_dataset_streets s');
		if ($global_filter) $this->db->like('s.street_label', $global_filter, 'both');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('s.*');
		$this->db->from('crm_dataset_streets s');

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
				case 'street_id':
					$this->db->order_by('s.street_id', $sort);
					break;
				case 'street_label':
					$this->db->order_by('s.street_label', $sort);
					break;
				default:
					$this->db->order_by('s.street_label', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($global_filter) $this->db->like('s.street_label', $global_filter, 'both');

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
			$actions = $row->street_id;
			$actions .= '<a href="#" class="edit dataset-add" data-url="datasets/ajax/street_add/'.$row->street_id.'"></a>';

			// Group Count
			$q = $this->db->query("SELECT COUNT(*) as count FROM crm_contacts WHERE street_id = {$row->street_id}");
			$count = $q->row('count');

			$trow['street_id']    = $actions;
			$trow['street_label'] = $row->street_label;
			$trow['street_count'] = $count;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	private function ajax_suburb_add()
	{
		$vData = array();

		// What group id?
		$suburb_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['suburb_id'] = $suburb_id;
		$vData['suburb_label'] = '';

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($suburb_id > 0)
		{
			$query = $this->db->select('*')->from('crm_dataset_suburbs')->where('suburb_id', $suburb_id)->get();

			$vData['suburb_label'] = $query->row('suburb_label');
		}

		$this->load->view('datasets/ajax/add_suburb', $vData);
	}

	// ********************************************************************************* //

	private function ajax_suburb_update()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		$suburb_id = $this->input->post('suburb_id');

		//----------------------------------------
		// Do we need to delete?
		//----------------------------------------
		$delete = $this->uri->segment(4);
		if ($delete == 'delete')
		{
			$this->db->set('suburb_id', 0);
			$this->db->where('suburb_id', $suburb_id);
			$this->db->update('crm_contacts');

			$this->db->query("DELETE FROM crm_dataset_suburbs WHERE suburb_id = ".$this->input->post('suburb_id'));

			exit();
		}

		//----------------------------------------
		// Add/Update
		//----------------------------------------
		$data = array();
		$data['suburb_label'] = trim($this->input->post('suburb_label'));

		// Lets see if it already exists
		$exists = $this->db->select('suburb_id')->from('crm_dataset_suburbs')->where('suburb_label', $data['suburb_label'])->get();

		// New or Update?
		if ($suburb_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('suburb_id') != $suburb_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('suburb_id', $suburb_id);
			$this->db->update('crm_dataset_suburbs', $data);
		}
		else
		{
			//----------------------------------------
			// Does it already exist?
			//----------------------------------------
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'That Suburb already exists!';
				exit(json_encode($out));
			}

			$this->db->insert('crm_dataset_suburbs', $data);
			$suburb_id = $this->db->insert_id();
		}

		//----------------------------------------
		// Return
		//----------------------------------------
		$out['success'] = 'yes';
		$out['chosen'] = "<option value='{$suburb_id}'>{$data['suburb_label']}</option>";

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	private function ajax_suburb_datatable()
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
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_dataset_suburbs')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Global Filter
		//----------------------------------------
		$global_filter = FALSE;
		if ($this->input->post('sSearch') != FALSE)
		{
			$global_filter = $this->input->post('sSearch');
		}

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_dataset_suburbs s');
		if ($global_filter) $this->db->like('s.suburb_label', $global_filter, 'both');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('s.*');
		$this->db->from('crm_dataset_suburbs s');

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
				case 'suburb_id':
					$this->db->order_by('s.suburb_id', $sort);
					break;
				case 'suburb_label':
					$this->db->order_by('s.suburb_label', $sort);
					break;
				default:
					$this->db->order_by('s.suburb_label', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($global_filter) $this->db->like('s.suburb_label', $global_filter, 'both');

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
			$actions = $row->suburb_id;
			$actions .= '<a href="#" class="edit dataset-add" data-url="datasets/ajax/suburb_add/'.$row->suburb_id.'"></a>';

			// Group Count
			$q = $this->db->query("SELECT COUNT(*) as count FROM crm_contacts WHERE suburb_id = {$row->suburb_id}");
			$count = $q->row('count');

			$trow['suburb_id']    = $actions;
			$trow['suburb_label'] = $row->suburb_label;
			$trow['suburb_count'] = $count;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	private function ajax_city_add()
	{
		$vData = array();

		// What group id?
		$city_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['city_id'] = $city_id;
		$vData['city_label'] = '';

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($city_id > 0)
		{
			$query = $this->db->select('*')->from('crm_dataset_cities')->where('city_id', $city_id)->get();

			$vData['city_label'] = $query->row('city_label');
		}

		$this->load->view('datasets/ajax/add_city', $vData);
	}

	// ********************************************************************************* //

	private function ajax_city_update()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		$city_id = $this->input->post('city_id');

		//----------------------------------------
		// Do we need to delete?
		//----------------------------------------
		$delete = $this->uri->segment(4);
		if ($delete == 'delete')
		{
			$this->db->set('city_id', 0);
			$this->db->where('city_id', $city_id);
			$this->db->update('crm_contacts');

			$this->db->query("DELETE FROM crm_dataset_cities WHERE city_id = ".$city_id);

			exit();
		}

		//----------------------------------------
		// Add/Update
		//----------------------------------------
		$data = array();
		$data['city_label'] = trim($this->input->post('city_label'));

		// Lets see if it already exists
		$exists = $this->db->select('city_id')->from('crm_dataset_cities')->where('city_label', $data['city_label'])->get();

		// New or Update?
		if ($city_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('city_id') != $city_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('city_id', $city_id);
			$this->db->update('crm_dataset_cities', $data);
		}
		else
		{
			//----------------------------------------
			// Does it already exist?
			//----------------------------------------
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'That City already exists!';
				exit(json_encode($out));
			}

			$this->db->insert('crm_dataset_cities', $data);
			$city_id = $this->db->insert_id();
		}

		//----------------------------------------
		// Return
		//----------------------------------------
		$out['success'] = 'yes';
		$out['chosen'] = "<option value='{$city_id}'>{$data['city_label']}</option>";

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	private function ajax_city_datatable()
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
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_dataset_cities')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Global Filter
		//----------------------------------------
		$global_filter = FALSE;
		if ($this->input->post('sSearch') != FALSE)
		{
			$global_filter = $this->input->post('sSearch');
		}

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_dataset_cities s');
		if ($global_filter) $this->db->like('s.city_label', $global_filter, 'both');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('s.*');
		$this->db->from('crm_dataset_cities s');

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
				case 'city_id':
					$this->db->order_by('s.city_id', $sort);
					break;
				case 'city_label':
					$this->db->order_by('s.city_label', $sort);
					break;
				default:
					$this->db->order_by('s.city_label', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($global_filter) $this->db->like('s.city_label', $global_filter, 'both');

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
			$actions = $row->city_id;
			$actions .= '<a href="#" class="edit dataset-add" data-url="datasets/ajax/city_add/'.$row->city_id.'"></a>';

			// Group Count
			$q = $this->db->query("SELECT COUNT(*) as count FROM crm_contacts WHERE city_id = {$row->city_id}");
			$count = $q->row('count');

			$trow['city_id']    = $actions;
			$trow['city_label'] = $row->city_label;
			$trow['city_count'] = $count;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	private function ajax_country_add()
	{
		$vData = array();

		// What group id?
		$country_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['country_id'] = $country_id;
		$vData['country_label'] = '';
		$vData['country_code'] = '';

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($country_id > 0)
		{
			$query = $this->db->select('*')->from('crm_dataset_countries')->where('country_id', $country_id)->get();

			$vData['country_label'] = $query->row('country_label');
			$vData['country_code'] = $query->row('country_code');
		}

		$this->load->view('datasets/ajax/add_country', $vData);
	}

	// ********************************************************************************* //

	private function ajax_country_update()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		$country_id = $this->input->post('country_id');

		//----------------------------------------
		// Do we need to delete?
		//----------------------------------------
		$delete = $this->uri->segment(4);
		if ($delete == 'delete')
		{
			$this->db->set('country_id', 0);
			$this->db->where('country_id', $country_id);
			$this->db->update('crm_contacts');

			$this->db->query("DELETE FROM crm_dataset_countries WHERE country_id = ".$country_id);

			exit();
		}

		//----------------------------------------
		// Add/Update
		//----------------------------------------
		$data = array();
		$data['country_label'] = $this->input->post('country_label');
		$data['country_code'] = $this->input->post('country_code');

		// Lets see if it already exists
		$exists = $this->db->select('country_id')->from('crm_dataset_countries')->where('country_label', $data['country_label'])->get();

		// New or Update?
		if ($country_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ($exists->num_rows() > 0 && ($exists->row('country_id') != $country_id))
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('country_id', $country_id);
			$this->db->update('crm_dataset_countries', $data);
		}
		else
		{
			//----------------------------------------
			// Does it already exist?
			//----------------------------------------
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'That Country already exists!';
				exit(json_encode($out));
			}

			$this->db->insert('crm_dataset_countries', $data);
			$country_id = $this->db->insert_id();
		}

		//----------------------------------------
		// Return
		//----------------------------------------
		$out['success'] = 'yes';
		$out['chosen'] = "<option value='{$country_id}'>{$data['country_label']}</option>";

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	private function ajax_country_datatable()
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
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_dataset_countries')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Global Filter
		//----------------------------------------
		$global_filter = FALSE;
		if ($this->input->post('sSearch') != FALSE)
		{
			$global_filter = $this->input->post('sSearch');
		}

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_dataset_countries c');
		if ($global_filter) $this->db->like('c.country_label', $global_filter, 'both');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('c.*');
		$this->db->from('crm_dataset_countries c');

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
				case 'country_id':
					$this->db->order_by('c.country_id', $sort);
					break;
				case 'country_label':
					$this->db->order_by('c.country_label', $sort);
					break;
				case 'country_code':
					$this->db->order_by('c.country_code', $sort);
					break;
				default:
					$this->db->order_by('c.country_label', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($global_filter) $this->db->like('c.country_label', $global_filter, 'both');

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
			$actions = $row->country_id;
			$actions .= '<a href="#" class="edit dataset-add" data-url="datasets/ajax/country_add/'.$row->country_id.'"></a>';

			// Group Count
			$q = $this->db->query("SELECT COUNT(*) as count FROM crm_contacts WHERE country_id = {$row->country_id}");
			$count = $q->row('count');

			$trow['country_id']    = $actions;
			$trow['country_label'] = $row->country_label;
			$trow['country_code'] = $row->country_code;
			$trow['country_count'] = $count;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	private function ajax_company_types_add()
	{
		$vData = array();

		// What group id?
		$company_type_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['company_type_id'] = $company_type_id;
		$vData['company_type_label'] = '';

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($company_type_id > 0)
		{
			$query = $this->db->select('*')->from('crm_dataset_company_types')->where('company_type_id', $company_type_id)->get();

			$vData['company_type_label'] = $query->row('company_type_label');
		}

		$this->load->view('datasets/ajax/add_company_type', $vData);
	}

	// ********************************************************************************* //

	private function ajax_company_types_update()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		$company_type_id = $this->input->post('company_type_id');

		//----------------------------------------
		// Do we need to delete?
		//----------------------------------------
		$delete = $this->uri->segment(4);

		if ($delete == 'delete')
		{
			$this->db->set('company_type_id', 0);
			$this->db->where('company_type_id', $company_type_id);
			$this->db->update('crm_contacts');

			$this->db->query("DELETE FROM crm_dataset_company_types WHERE company_type_id = ".$company_type_id);

			exit();
		}

		//----------------------------------------
		// Add/Update
		//----------------------------------------
		$data = array();
		$data['company_type_label'] = trim($this->input->post('company_type_label'));

		// Lets see if it already exists
		$exists = $this->db->select('company_type_id')->from('crm_dataset_company_types')->where('company_type_label', $data['company_type_label'])->get();

		// New or Update?
		if ($company_type_id != FALSE)
		{
			// Does the entry already exist with another id?
			if ( $exists->num_rows() > 0 && ($exists->row('company_type_id') != $company_type_id) )
			{
				$out['body'] = 'An entry already exists with that name';
				exit(json_encode($out));
			}

			$this->db->where('company_type_id', $company_type_id);
			$this->db->update('crm_dataset_company_types', $data);
		}
		else
		{
			//----------------------------------------
			// Does it already exist?
			//----------------------------------------
			if ($exists->num_rows() > 0)
			{
				$out['body'] = 'That Company Type already exists!';
				exit(json_encode($out));
			}

			$this->db->insert('crm_dataset_company_types', $data);
			$company_type_id = $this->db->insert_id();
		}

		//----------------------------------------
		// Return
		//----------------------------------------
		$out['success'] = 'yes';
		$out['chosen'] = "<option value='{$company_type_id}'>{$data['company_type_label']}</option>";

		exit(json_encode($out));
	}

	// ********************************************************************************* //

	private function ajax_company_types_datatable()
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
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_dataset_company_types')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Global Filter
		//----------------------------------------
		$global_filter = FALSE;
		if ($this->input->post('sSearch') != FALSE)
		{
			$global_filter = $this->input->post('sSearch');
		}

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_dataset_company_types s');
		if ($global_filter) $this->db->like('s.company_type_label', $global_filter, 'both');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('s.*');
		$this->db->from('crm_dataset_company_types s');

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
				case 'company_type_id':
					$this->db->order_by('s.company_type_id', $sort);
					break;
				case 'company_type_label':
					$this->db->order_by('s.company_type_label', $sort);
					break;
				default:
					$this->db->order_by('s.company_type_label', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($global_filter) $this->db->like('s.company_type_label', $global_filter, 'both');

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
			$actions = $row->company_type_id;
			$actions .= '<a href="#" class="edit dataset-add" data-url="datasets/ajax/company_types_add/'.$row->company_type_id.'"></a>';

			// Group Count
			$q = $this->db->query("SELECT COUNT(*) as count FROM crm_companies WHERE company_type_id = {$row->company_type_id}");
			$count = $q->row('count');

			$trow['company_type_id']    = $actions;
			$trow['company_type_label'] = $row->company_type_label;
			$trow['company_type_count'] = $count;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //
}