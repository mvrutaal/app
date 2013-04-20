<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CRM_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('javascript');
	}

	// ********************************************************************************* //

	public function index()
	{
		exit('AJAX METHOD?');
	}

	// ********************************************************************************* //

	public function ac_streets()
	{
		$items = array();

		$query = $this->db->query("SELECT DISTINCT(street) FROM crm_contacts WHERE street LIKE '%".$this->input->get_post('term')."%' ORDER BY street ASC");

		foreach ($query->result() as $row)
		{
			$items[] = array('label' => $row->street, 'value' => $row->street, 'id' => '');
		}

		$items = $this->javascript->generate_json($items, TRUE);
		exit($items);
	}

	// ********************************************************************************* //

	public function ac_cities()
	{
		$items = array();

		$query = $this->db->query("SELECT DISTINCT(city) FROM crm_contacts WHERE city LIKE '%".$this->input->get_post('term')."%' ORDER BY city ASC");

		foreach ($query->result() as $row)
		{
			$items[] = array('label' => $row->city, 'value' => $row->city, 'id' => '');
		}

		$items = $this->javascript->generate_json($items, TRUE);
		exit($items);
	}

	// ********************************************************************************* //

	public function ac_suburbs()
	{
		$items = array();

		$query = $this->db->query("SELECT DISTINCT(suburb) FROM crm_contacts WHERE suburb LIKE '%".$this->input->get_post('term')."%' ORDER BY state ASC");

		foreach ($query->result() as $row)
		{
			$items[] = array('label' => $row->suburb, 'value' => $row->suburb, 'id' => '');
		}

		$items = $this->javascript->generate_json($items, TRUE);
		exit($items);
	}

	// ********************************************************************************* //

	public function ac_countries()
	{
		$items = array();

		$query = $this->db->query("SELECT DISTINCT(country) FROM crm_contacts WHERE country LIKE '%".$this->input->get_post('term')."%' ORDER BY country ASC");

		foreach ($query->result() as $row)
		{
			$items[] = array('label' => $row->country, 'value' => $row->country, 'id' => '');
		}

		$items = $this->javascript->generate_json($items, TRUE);
		exit($items);
	}

	// ********************************************************************************* //

	public function ac_job_titles()
	{
		$items = array();

		$query = $this->db->query("SELECT DISTINCT(job_title) FROM crm_contacts WHERE job_title LIKE '%".$this->input->get_post('term')."%' ORDER BY job_title ASC");

		foreach ($query->result() as $row)
		{
			$items[] = array('label' => $row->job_title, 'value' => $row->job_title, 'id' => '');
		}

		$items = $this->javascript->generate_json($items, TRUE);
		exit($items);
	}

	// ********************************************************************************* //

	public function ac_companytypes()
	{
		$items = array();

		$query = $this->db->query("SELECT DISTINCT(company_type) FROM crm_companies WHERE company_type LIKE '%".$this->input->get_post('term')."%' ORDER BY company_type ASC");

		foreach ($query->result() as $row)
		{
			$items[] = array('label' => $row->company_type, 'value' => $row->company_type, 'id' => '');
		}

		$items = $this->javascript->generate_json($items, TRUE);
		exit($items);
	}

	// ********************************************************************************* //

	public function ac_programcats()
	{
		$items = array();

		$query = $this->db->query("SELECT DISTINCT(program_category) FROM crm_programs WHERE program_category LIKE '%".$this->input->get_post('term')."%' ORDER BY program_category ASC");

		foreach ($query->result() as $row)
		{
			$items[] = array('label' => $row->program_category, 'value' => $row->program_category, 'id' => '');
		}

		$items = $this->javascript->generate_json($items, TRUE);
		exit($items);
	}



	public function dt_companies()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_companies');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/
		$company_title_search = FALSE;
		if ($this->input->get_post('sSearch_0') != FALSE)
		{
			$company_title_search = $this->input->get_post('sSearch_0');
		}

		$company_type_search = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$company_type_search = $this->input->get_post('sSearch_1');
		}

		$company_city_search = FALSE;
		if ($this->input->get_post('sSearch_2') != FALSE)
		{
			$company_city_search = $this->input->get_post('sSearch_2');
		}

		$company_country_search = FALSE;
		if ($this->input->get_post('sSearch_3') != FALSE)
		{
			$company_country_search = $this->input->get_post('sSearch_3');
		}

		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_companies gp');
		if ($company_title_search) $this->db->like('gp.company_title', $company_title_search, 'both');
		if ($company_type_search) $this->db->like('gp.company_type', $company_type_search, 'both');
		if ($company_city_search) $this->db->like('gp.company_city', $company_city_search, 'both');
		if ($company_country_search) $this->db->like('gp.company_country', $company_country_search, 'both');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('*');
		$this->db->from('crm_companies gp');
		if ($company_title_search) $this->db->like('gp.company_title', $company_title_search, 'both');
		if ($company_type_search) $this->db->like('gp.company_type', $company_type_search, 'both');
		if ($company_city_search) $this->db->like('gp.company_city', $company_city_search, 'both');
		if ($company_country_search) $this->db->like('gp.company_country', $company_country_search, 'both');
		//----------------------------------------
		// Sort By
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			switch ($col)
			{
				case 1: // Company Name
					$this->db->order_by('gp.company_title', $sort);
					break;
				case 2: // Company Type
					$this->db->order_by('gp.company_type', $sort);
					break;
				case 3: // Street
					$this->db->order_by('gp.company_street', $sort);
					break;
				case 4: // City
					$this->db->order_by('gp.company_city', $sort);
					break;
				case 5: // Tel
					$this->db->order_by('gp.company_tel_number', $sort);
					break;
				case 6: // Tel 2
					$this->db->order_by('gp.company_tel2_number', $sort);
					break;
				case 7: // Fax
					$this->db->order_by('gp.company_fax_number', $sort);
					break;
				case 8: // Email
					$this->db->order_by('gp.company_email', $sort);
					break;
			}
		}

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$query = $this->db->get();



		foreach ($query->result() as $row)
		{
			$actions = '
			<a href="'.base_url().'companies/view/'.$row->company_id.'" class="quick_company QuickIcon" title="Quick View Company">&nbsp;</a>
			<a href="'.base_url().'companies/view/'.$row->company_id.'" class="view_company ViewIcon" title="View Company">&nbsp;</a>
			<a href="'.base_url().'companies/edit/'.$row->company_id.'" class="edit_company EditIcon" title="Edit Company">&nbsp;</a>
			';

			$trow = array();
			$trow['actions'] = $actions;
			$trow['company_name'] = $row->company_title;
			$trow['company_type'] = $row->company_type;
			$trow['street'] = $row->company_street .' '.  $row->company_housenumber;
			$trow['suburb'] = $row->company_suburb;
			$trow['city'] = $row->company_city;
			$trow['country'] = $row->company_country;
			$trow['tel'] = $row->company_tel_number;
			$trow['tel2'] = $row->company_tel2_number;
			$trow['fax'] = $row->company_fax_number;
			$trow['email'] = $row->company_email;
			$trow['email2'] = $row->company_email2;
			$data['aaData'][] = $trow;
		}

		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	public function dt_programs()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_programs');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/

		$program_category_search = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$program_category_search = $this->input->get_post('sSearch_1');
		}

		$program_code_search = FALSE;
		if ($this->input->get_post('sSearch_2') != FALSE)
		{
			$program_code_search = $this->input->get_post('sSearch_2');
		}

		$program_name_search = FALSE;
		if ($this->input->get_post('sSearch_3') != FALSE)
		{
			$program_name_search = $this->input->get_post('sSearch_3');
		}

		$year_search = FALSE;
		if ($this->input->get_post('sSearch_4') != FALSE)
		{
			$year_search = $this->input->get_post('sSearch_4');
		}

		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_programs pg');
		if ($program_category_search) $this->db->like('pg.program_category', $program_category_search, 'both');
		if ($program_code_search) $this->db->like('pg.program_code', $program_code_search, 'both');
		if ($program_name_search) $this->db->like('pg.program_name', $program_name_search, 'both');
		if ($year_search) $this->db->like('pg.year', $year_search, 'both');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('pg.*, g.group_name, fc1.first_name AS fc1_first_name, fc1.last_name AS fc1_last_name, fc2.first_name AS fc2_first_name, fc2.last_name AS fc2_last_name');
		$this->db->from('crm_programs pg');
		$this->db->join('crm_contacts fc1', 'pg.facilitator1 = fc1.contact_id', 'left');
		$this->db->join('crm_contacts fc2', 'pg.facilitator2 = fc2.contact_id', 'left');
		$this->db->join('crm_groups g', 'pg.group_id = g.group_id', 'left');

		if ($program_category_search) $this->db->like('pg.program_category', $program_category_search, 'both');
		if ($program_code_search) $this->db->like('pg.program_code', $program_code_search, 'both');
		if ($program_name_search) $this->db->like('pg.program_name', $program_name_search, 'both');
		if ($year_search) $this->db->like('pg.year', $year_search, 'both');

		//----------------------------------------
		// Sort By
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			switch ($col)
			{
				case 1: // Program Category
					$this->db->order_by('pg.program_category', $sort);
					break;
				case 2: // Program Code
					$this->db->order_by('pg.program_code', $sort);
					break;
				case 3: // Program Name
					$this->db->order_by('pg.program_name', $sort);
					break;
				case 4: // Year
					$this->db->order_by('pg.duration', $sort);
					break;
				case 5: // Linked Group
					$this->db->order_by('g.group_name', $sort);
					break;
				case 6: // Facilitator 1
					$this->db->order_by('fc1.first_name', $sort);
					break;
				case 6: // Facilitator 2
					$this->db->order_by('fc2.first_name', $sort);
					break;
				default:
					//$this->db->order_by('fc2.first_name', 'asc');
					$this->db->order_by('pg.program_category', 'asc');
					$this->db->order_by('pg.program_code', 'asc');
					$this->db->order_by('pg.program_name', 'asc');
			}
		}

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$query = $this->db->get();



		foreach ($query->result() as $row)
		{
			$actions = '
			<a href="'.base_url().'programs/view/'.$row->program_id.'" class="quick_program QuickIcon" title="Quick View Program">&nbsp;</a>
			<a href="'.base_url().'programs/view/'.$row->program_id.'" class="view_program ViewIcon" title="View Program">&nbsp;</a>
			<a href="'.base_url().'programs/edit/'.$row->program_id.'" class="edit_program EditIcon" title="Edit Program">&nbsp;</a>
			';

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->program_category;
			$trow[] = $row->program_code;
			$trow[] = $row->program_name;
			$trow[] = $row->year;
			$trow[] = $row->group_name;
			$trow[] = $row->fc1_first_name . ' ' . $row->fc1_last_name;
			$trow[] = $row->fc2_first_name . ' ' . $row->fc2_last_name;
			$trow[] = $row->notes;
			$data['aaData'][] = $trow;
		}

		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	public function dt_program_trainees()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_programs_links');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/
		$program_search = -1;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$program_search = $this->input->get_post('sSearch_1');
		}

		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_groups_links gl');
		$this->db->join('crm_contacts c', 'gl.contact_id = c.contact_id', 'left');
		$this->db->join('crm_groups g', 'gl.group_id = g.group_id', 'left');
		$this->db->join('crm_programs p', 'g.group_id = p.group_id', 'left');

		$this->db->where('p.program_id', $program_search, false);

		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('c.contact_id, c.first_name, c.last_name');
		$this->db->from('crm_groups_links gl');
		$this->db->join('crm_contacts c', 'gl.contact_id = c.contact_id', 'left');
		$this->db->join('crm_groups g', 'gl.group_id = g.group_id', 'left');
		$this->db->join('crm_programs p', 'p.group_id = g.group_id', 'left');

		$this->db->where('p.program_id', $program_search, false);

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);

		$this->db->save_queries = TRUE;

		$query = $this->db->get();

		//exit($this->db->last_query());



		foreach ($query->result() as $row)
		{
			$actions = '<input name="trainees[]" id="trainees" value="' . $row->contact_id  .'" type="checkbox" />';

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->first_name;
			$trow[] = $row->last_name;
			$data['aaData'][] = $trow;
		}

		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	public function dt_messages()
	{
		$this->db->save_queries = TRUE;

		/** ----------------------------------------
		/** Get all statuses
		/** ----------------------------------------*/
		$query = $this->db->select('*')->from('crm_document_statuses')->get();
		$statuses = $query->num_rows() == 0 ? array() : $query->result();
		$query->free_result();

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_documents d');
		$this->db->join('crm_contacts sc', 'd.sender_contact_id = sc.contact_id', 'left');
		$this->db->join('crm_document_templates dt', 'd.document_template_id = dt.document_template_id', 'left');
		$this->db->join('crm_document_statuses ds', 'd.document_status_id = ds.document_status_id', 'left');
		$this->db->join('crm_template_types dtt', 'dt.template_type_id = dtt.template_type_id', 'left');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/
		$sender_first_name_search = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$sender_first_name_search = $this->input->get_post('sSearch_1');
		}

		$sender_last_name_search = FALSE;
		if ($this->input->get_post('sSearch_2') != FALSE)
		{
			$sender_last_name_search = $this->input->get_post('sSearch_2');
		}

		$reciever_first_name_search = FALSE;
		if ($this->input->get_post('sSearch_3') != FALSE)
		{
			$reciever_first_name_search = $this->input->get_post('sSearch_3');
		}

		$reciever_last_name_search = FALSE;
		if ($this->input->get_post('sSearch_4') != FALSE)
		{
			$reciever_last_name_search = $this->input->get_post('sSearch_4');
		}

		$subject_search = FALSE;
		if ($this->input->get_post('sSearch_5') != FALSE)
		{
			$subject_search = $this->input->get_post('sSearch_5');
		}

		$template_search = FALSE;
		if ($this->input->get_post('sSearch_6') != FALSE)
		{
			$template_search = $this->input->get_post('sSearch_6');
		}
		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_documents d');
		$this->db->join('crm_contacts sc', 'd.sender_contact_id = sc.contact_id', 'left');
		$this->db->join('crm_document_templates dt', 'd.document_template_id = dt.document_template_id', 'left');
		$this->db->join('crm_document_statuses ds', 'd.document_status_id = ds.document_status_id', 'left');
		$this->db->join('crm_template_types dtt', 'dt.template_type_id = dtt.template_type_id', 'left');

		if ($sender_first_name_search) $this->db->like('sc.first_name', $sender_first_name_search, 'both');
		if ($sender_last_name_search) $this->db->like('sc.last_name', $sender_last_name_search, 'both');
		/*
		if ($reciever_first_name_search) $this->db->like('rc.first_name', $reciever_first_name_search, 'both');
		if ($reciever_last_name_search) $this->db->like('rc.last_name', $reciever_last_name_search, 'both');
		*/
		if ($subject_search) $this->db->like('d.subject', $subject_search, 'both');
		if ($template_search) $this->db->where('d.document_template_id', $template_search, false);

		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('d.*, sc.first_name sc_first_name, sc.last_name sc_last_name, dt.template_name dt_template_name, ds.status_name ds_status_name, dtt.type_name dtt_type_name');
		$this->db->from('crm_documents d');
		$this->db->join('crm_contacts sc', 'd.sender_contact_id = sc.contact_id', 'left');
		$this->db->join('crm_document_templates dt', 'd.document_template_id = dt.document_template_id', 'left');
		$this->db->join('crm_document_statuses ds', 'd.document_status_id = ds.document_status_id', 'left');
		$this->db->join('crm_template_types dtt', 'dt.template_type_id = dtt.template_type_id', 'left');

		if ($sender_first_name_search) $this->db->like('sc.first_name', $sender_first_name_search, 'both');
		if ($sender_last_name_search) $this->db->like('sc.last_name', $sender_last_name_search, 'both');
		/*
		if ($reciever_first_name_search) $this->db->like('rc.first_name', $reciever_first_name_search, 'both');
		if ($reciever_last_name_search) $this->db->like('rc.last_name', $reciever_last_name_search, 'both');
		*/
		if ($subject_search) $this->db->like('d.subject', $subject_search, 'both');
		if ($template_search) $this->db->where('d.document_template_id', $template_search, false);

		//----------------------------------------
		// Order Clause
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			switch ($col)
			{
				case 1: // Reference Id
					$this->db->order_by('d.reference_id', $sort);
					break;
				case 2: // Template Name
					$this->db->order_by('dt.template_name', $sort);
					break;
				case 3: // Template Type
					$this->db->order_by('dtt.type_name', $sort);
					break;
				case 4: // Created
					$this->db->order_by('d.created_date', $sort);
				case 5: // Sender
					$this->db->order_by('sc.last_name', $sort);
					$this->db->order_by('sc.first_name', $sort);
					break;
				/*
				case 6: // Reciever
					$this->db->order_by('rc.last_name', $sort);
					$this->db->order_by('rc.first_name', $sort);
					break;
				*/
				case 7: // Subject
					$this->db->order_by('d.subject', $sort);
					break;
				case 8: // Status Name
					$this->db->order_by('ds.status_name', $sort);
					break;
				default:
					break;
			}
		}

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		$this->load->model('Documents');

		foreach ($query->result() as $row)
		{
			$result = $this->db->select('c.last_name, c.first_name')->from('crm_document_recipients dr')->join('crm_contacts c', 'dr.contact_id=c.contact_id', 'left')->where('dr.document_id', $row->document_id, false)->get();
			$recipients= $result->num_rows();
			$recipient_text = '<a href="' . base_url() . 'messages/recipients/' . $row->document_id .'" class="quick_message">See All ' . $recipients . ' recipients</a>';
			if($recipients <= 1)
			{
				$records = $result->result_array();
				$recipient_text = $records[0]['last_name'] . ', ' .$records[0]['first_name'];
			}

			$actions = '
			<a href="'.base_url().'messages/view/'.$row->document_id.'" class="quick_message QuickIcon" title="Quick View document">&nbsp;</a>
			<a href="'.base_url(). $this->Documents->Path . $row->reference_id.'.docx" class="view_message ViewIcon" title="Download document">&nbsp;</a>
			';

			if($recipients <= 1)
			{
				foreach($statuses as $status){
					if($row->document_status_id < $status->document_status_id && $status->document_status_id > 1){
						$actions .= '<a href="'.base_url().'messages/process/'.$row->document_id.'/' . $status->document_status_id .'" class="edit_message ' . $status->css_class . '" document_id="' . $row->document_id. '" title="' . $status->status_option .'">&nbsp;</a>';
					}
				}
			}

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->reference_id;
			$trow[] = $row->dt_template_name;
			$trow[] = $row->dtt_type_name;
			$trow[] = date("Y-m-d", strtotime($row->created_date));
			$trow[] = $row->sc_last_name . ', ' . $row->sc_first_name;
			$trow[] = $recipient_text;
			$trow[] = $row->subject;
			$trow[] = $row->ds_status_name . (!empty($row->processed_date) ? ' (' . date("Y-m-d", strtotime($row->processed_date)) . ')' : '');
			$data['aaData'][] = $trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	public function dt_facilities()
	{
		$this->db->save_queries = TRUE;

		/** ----------------------------------------
		/** Get all statuses
		/** ----------------------------------------*/
		$query = $this->db->select('*')->from('crm_facility_options')->get();
		$statuses = $query->num_rows() == 0 ? array() : $query->result();
		$query->free_result();

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_facility_options');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/
		$option_name = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$option_name = $this->input->get_post('sSearch_1');
		}
		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_facility_options fo');
		$this->db->join('crm_facility_option_groups fog', 'fo.facility_option_group_id=fog.facility_option_group_id', 'left');
		$this->db->where('fog.is_fixed_price',1,false);

		if ($option_name) $this->db->like('fo.option_name', $option_name, 'both');

		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('fo.*');
		$this->db->from('crm_facility_options fo');
		$this->db->join('crm_facility_option_groups fog', 'fo.facility_option_group_id=fog.facility_option_group_id', 'left');
		$this->db->where('fog.is_fixed_price',1,false);

		if ($option_name) $this->db->like('fo.option_name', $option_name, 'both');

		//----------------------------------------
		// Order Clause
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			switch ($col)
			{
				case 1: // Option Name
					$this->db->order_by('fo.option_name', $sort);
					break;
				case 2: // Price
					$this->db->order_by('fo.price', $sort);
					break;
				default:
					break;
			}
		}

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$actions = '';
			$actions .='<a href="'.base_url().'administration/facilities/view/'.$row->facility_option_id.'" class="quick_view QuickIcon" title="Quick View Facility Option">&nbsp;</a>';
			$actions .='<a href="'.base_url().'administration/facilities/view/'.$row->facility_option_id.'" class="full_view ViewIcon" title="View Facility Option">&nbsp;</a>';
			$actions .='<a href="'.base_url().'administration/facilities/edit/'.$row->facility_option_id.'" class="edit_contact EditIcon" title="Edit Facility Option">&nbsp;</a>';

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->option_name;
			$trow[] = $row->price;
			$data['aaData'][] = $trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}
	// ********************************************************************************* //


	public function dt_rooms()
	{
		$this->db->save_queries = TRUE;

		/** ----------------------------------------
		/** Get all statuses
		/** ----------------------------------------*/
		$query = $this->db->select('*')->from('crm_rooms')->get();
		$statuses = $query->num_rows() == 0 ? array() : $query->result();
		$query->free_result();

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_rooms');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/
		$room_code = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$room_code = $this->input->get_post('sSearch_1');
		}

		$room_title = FALSE;
		if ($this->input->get_post('sSearch_2') != FALSE)
		{
			$room_title = $this->input->get_post('sSearch_2');
		}

		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_rooms');

		if ($room_code) $this->db->like('room_code', $room_code, 'both');
		if ($room_title) $this->db->like('room_title', $room_title, 'both');

		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('*');
		$this->db->from('crm_rooms');

		if ($room_code) $this->db->like('room_code', $room_code, 'both');
		if ($room_title) $this->db->like('room_title', $room_title, 'both');

		//----------------------------------------
		// Order Clause
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			switch ($col)
			{
				case 1: // Room Code
					$this->db->order_by('room_code', $sort);
					break;
				case 2: // Room Title
					$this->db->order_by('room_title', $sort);
					break;
				case 3: // Morning Price
					$this->db->order_by('morning_price', $sort);
					break;
				case 4: // Afternoon Price
					$this->db->order_by('afternoon_price', $sort);
					break;
				case 5: // Evening Price
					$this->db->order_by('evening_price', $sort);
					break;
				case 6: // Wholeday Price
					$this->db->order_by('wholeday_price', $sort);
					break;
				default:
					break;
			}
		}

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$actions = '';
			$actions .='<a href="'.base_url().'administration/rooms/view/'.$row->room_id.'" class="quick_view QuickIcon" title="Quick View Room">&nbsp;</a>';
			$actions .='<a href="'.base_url().'administration/rooms/view/'.$row->room_id.'" class="full_view ViewIcon" title="View Room">&nbsp;</a>';
			$actions .='<a href="'.base_url().'administration/rooms/edit/'.$row->room_id.'" class="edit_contact EditIcon" title="Edit Room">&nbsp;</a>';

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->room_code;
			$trow[] = $row->room_title;
			$trow[] = $row->morning_price;
			$trow[] = $row->afternoon_price;
			$trow[] = $row->evening_price;
			$trow[] = $row->wholeday_price;
			$data['aaData'][] = $trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	public function dt_message_contacts()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_contacts c');
		$this->db->join('crm_companies cc', 'c.company_id = cc.company_id', 'left');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/
		$sender_first_name_search = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$sender_first_name_search = $this->input->get_post('sSearch_1');
		}

		$sender_last_name_search = FALSE;
		if ($this->input->get_post('sSearch_2') != FALSE)
		{
			$sender_last_name_search = $this->input->get_post('sSearch_2');
		}

		$company_search = FALSE;
		if ($this->input->get_post('sSearch_3') != FALSE)
		{
			$company_search = explode('|', $this->input->get_post('sSearch_3'));
		}
		else{
			$company_search[0] = -1;
		}

		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_contacts c');
		$this->db->join('crm_companies cc', 'c.company_id = cc.company_id', 'left');

		if ($sender_first_name_search) $this->db->like('c.first_name', $sender_first_name_search, 'both');
		if ($sender_last_name_search) $this->db->like('c.last_name', $sender_last_name_search, 'both');
		if ($company_search) $this->db->where_in('c.company_id', $company_search, NULL, FALSE);

		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('c.*, cc.*');
		$this->db->from('crm_contacts c');
		$this->db->join('crm_companies cc', 'c.company_id = cc.company_id', 'left');

		if ($sender_first_name_search) $this->db->like('c.first_name', $sender_first_name_search, 'both');
		if ($sender_last_name_search) $this->db->like('c.last_name', $sender_last_name_search, 'both');
		if ($company_search) $this->db->where_in('c.company_id', $company_search, NULL, FALSE);

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$actions = '<input name="message_contacts[]" id="message_contact_select" value="' . $row->contact_id  .'" type="checkbox" />';

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->first_name;
			$trow[] = $row->last_name;
			$trow[] = $row->company_title;
			$data['aaData'][] = $trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	public function dt_templates()
	{
		$this->db->save_queries = TRUE;

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_document_templates dt');
		$this->db->join('crm_template_types tt', 'dt.template_type_id=tt.template_type_id', 'left');
		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/
		$template_name_search = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$template_name_search = $this->input->get_post('sSearch_1');
		}

		$template_type_search = FALSE;
		if ($this->input->get_post('sSearch_2') != FALSE)
		{
			$template_type_search = explode('|', $this->input->get_post('sSearch_2'));
		}

		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_document_templates dt');
		$this->db->join('crm_template_types tt', 'dt.template_type_id=tt.template_type_id', 'left');

		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		if ($template_name_search) $this->db->like('dt.template_name', $template_name_search, 'both');
		if ($template_type_search) $this->db->where_in('dt.template_type_id', $template_type_search, NULL, FALSE);

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('dt.created_date, dt.document_template_id, dt.template_name, tt.type_name');
		$this->db->from('crm_document_templates dt');
		$this->db->join('crm_template_types tt', 'dt.template_type_id=tt.template_type_id', 'left');

		if ($template_name_search) $this->db->like('dt.template_name', $template_name_search, 'both');
		if ($template_type_search) $this->db->where_in('dt.template_type_id', $template_type_search, NULL, FALSE);

		//----------------------------------------
		// Order Clause
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			switch ($col)
			{
				case 1: // Template Name
					$this->db->order_by('dt.template_name', $sort);
					break;
				case 2: // Template Name
					$this->db->order_by('tt.type_name', $sort);
					break;
				case 3: // Created
					$this->db->order_by('dt.created_date', $sort);
					break;
				default:
					break;
			}
		}

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		$this->load->model('Templates');
		foreach ($query->result() as $row)
		{
			$actions = '
			<a href="'.base_url().'messages/template_view/' . $row->document_template_id . '" class="quick_template QuickIcon" title="Quick View Template">&nbsp;</a>
			<a href="'.base_url(). $this->Templates->Path . 'templates_' . $row->document_template_id . '.docx" class="view_template ViewIcon" title="Download Template">&nbsp;</a>
			<a href="'.base_url().'messages/template_edit/' . $row->document_template_id . '" class="edit_template EditIcon" title="Edit Template">&nbsp;</a>
			';

			$extra_actions = '<a href="'.base_url().'messages/add/' . $row->document_template_id . '" class="edit_template EditIcon" title="Create Message">&nbsp;</a>';

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->template_name;
			$trow[] = $row->type_name;
			$trow[] = date("Y-m-d", strtotime($row->created_date));
			$trow[] = $extra_actions;
			$data['aaData'][] = $trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	public function dt_invoices(){
		$this->db->save_queries = TRUE;

		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		/** ----------------------------------------
		/** Total records, before filtering
		/** (i.e. the total number of records in the database)
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_invoices');

		$query = $this->db->get();
		$data['iTotalRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Column Search
		/** ----------------------------------------*/

		$invoice_id = FALSE;
		if ($this->input->get_post('sSearch_1') != FALSE)
		{
			$invoice_id = $this->input->get_post('sSearch_1');
		}

		$first_name = FALSE;
		if ($this->input->get_post('sSearch_2') != FALSE)
		{
			$first_name = $this->input->get_post('sSearch_2');
		}

		$last_name = FALSE;
		if ($this->input->get_post('sSearch_3') != FALSE)
		{
			$last_name = $this->input->get_post('sSearch_3');
		}

		$company_search = FALSE;
		if ($this->input->get_post('sSearch_4') != FALSE)
		{
			$company_search = explode('|', $this->input->get_post('sSearch_4'));
		}

		$status_serch = FALSE;
		if ($this->input->get_post('sSearch_5') != FALSE)
		{
			$status_serch = explode('|', $this->input->get_post('sSearch_5'));
		}

		/** ----------------------------------------
		/** Total after filter
		/** ----------------------------------------*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_invoices i');
		$this->db->join('crm_contacts crc', 'i.billing_contact_id=crc.contact_id', 'left');
		$this->db->join('crm_contacts bc', 'i.billing_contact_id=bc.contact_id', 'left');
		$this->db->join('crm_companies bcc', 'bc.company_id=bcc.company_id', 'left');
		$this->db->join('crm_invoice_statuses iss', 'i.status_id=iss.status_id', 'left');

		if ($invoice_id) $this->db->where('i.invoice_id', $invoice_id, false);
		if ($first_name) $this->db->like('bc.first_name', $first_name, 'both');
		if ($last_name) $this->db->like('bc.last_name', $last_name, 'both');
		if ($company_search) $this->db->where_in('bc.company_id', $company_search);
		if ($status_serch) $this->db->where_in('i.status_id', $status_serch);

		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		/** ----------------------------------------
		/** Real query
		/** ----------------------------------------*/
		$this->db->select('i.*, crc.first_name crc_first_name, crc.last_name crc_last_name, bcc.company_title, bc.first_name, bc.last_name, iss.status_name');
		$this->db->from('crm_invoices i');
		$this->db->join('crm_contacts crc', 'i.billing_contact_id=crc.contact_id', 'left');
		$this->db->join('crm_contacts bc', 'i.billing_contact_id=bc.contact_id', 'left');
		$this->db->join('crm_companies bcc', 'bc.company_id=bcc.company_id', 'left');
		$this->db->join('crm_invoice_statuses iss', 'i.status_id=iss.status_id', 'left');

		if ($invoice_id) $this->db->where('i.invoice_id', $invoice_id, false);
		if ($first_name) $this->db->like('bc.first_name', $first_name, 'both');
		if ($last_name) $this->db->like('bc.last_name', $last_name, 'both');
		if ($company_search) $this->db->where_in('bc.company_id', $company_search);
		if ($status_serch) $this->db->where_in('i.status_id', $status_serch);

		//----------------------------------------
		// Order Clause
		//----------------------------------------
		$sort_cols = $this->input->get_post('iSortingCols');

		for ($i = 0; $i < $sort_cols; $i++)
		{
			$col = $this->input->get_post('iSortCol_'.$i);
			$sort =  $this->input->get_post('sSortDir_'.$i);

			switch ($col)
			{
				case 1: // Invoice ID
					$this->db->order_by('i.invoice_id', $sort);
					break;
				case 2: // Contact
					$this->db->order_by('bc.last_name', $sort);
					$this->db->order_by('bc.first_name', $sort);
					break;
				case 3: // Company
					$this->db->order_by('bcc.company_title', $sort);
					break;
				case 3: // Status
					$this->db->order_by('iss.status_name', $sort);
					break;
				case 5: // Creator
					$this->db->order_by('crc.last_name', $sort);
					$this->db->order_by('crc.first_name', $sort);
					break;
				case 6: // Created
					$this->db->order_by('i.created_date', $sort);
					break;
				case 7: // Price
					$this->db->order_by('i.total', $sort);
					break;
				default:
					break;
			}
		}

		//----------------------------------------
		// Limit
		//----------------------------------------
		$limit = 10;
		if ($this->input->get_post('iDisplayLength') !== FALSE)
		{
			$limit = $this->input->get_post('iDisplayLength');
			if ($limit < 1) $limit = 999999;
		}

		//----------------------------------------
		// Offset
		//----------------------------------------
		$offset = 0;
		if ($this->input->get_post('iDisplayStart') !== FALSE)
		{
			$offset = $this->input->get_post('iDisplayStart');
		}

		$this->db->limit($limit, $offset);
		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$actions = '
			<a href="'.base_url().'administration/invoices/view/'.$row->invoice_id.'" class="quick_view QuickIcon" title="Quick View Invoice">&nbsp;</a>
			<a href="'.base_url().'administration/invoices/view/'.$row->invoice_id.'" class="view_contact ViewIcon" title="View Invoice">&nbsp;</a>
			<a href="'.base_url().'administration/invoices/pay/'. $row->invoice_id . '" class="pay_invoice PayIcon" title="Pay Invoice">&nbsp;</a>
			';

			$trow = array();
			$trow[] = $actions;
			$trow[] = $row->invoice_id;
			$trow[] = $row->last_name . ', ' . $row->first_name;
			$trow[] = $row->company_title;
			$trow[] = $row->crc_last_name . ', ' . $row->crc_first_name;
			$trow[] = $row->status_name;
			$trow[] = $row->created_date;
			$trow[] = $row->total;
			$data['aaData'][] = $trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	function dg_template_content(){

		$data = '';
		if($this->input->get_post('template_id') !== FALSE){
			$this->load->model('PhpDocX');
			$data = $this->PhpDocX->GetTemplate('./documents/templates/templates_' . $this->input->get_post('template_id') . '.docx');
			//$data = $this->javascript->generate_json($data, TRUE);
		}
		exit($data);
	}

	// ********************************************************************************* //

	function dl_contacts(){
		$data = array();
		$this->db->select('contact_id, first_name, last_name');
		$this->db->from('crm_contacts');
		if ($this->input->get_post('company_id') !== FALSE)
		{
			$this->db->where('company_id',$this->input->get_post('company_id'));
		}

		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		foreach ($query->result() as $row){
			$trow = array();
			$trow['id'] = $row->contact_id;
			$trow['revfullname'] = $row->last_name . ', ' . $row->first_name;
			$data[]=$trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	function dl_program_contacts(){
		$data = array();
		$this->db->select('c.contact_id, c.first_name, c.last_name');
		$this->db->from('crm_programs p');
		$this->db->join('crm_groups_links gl', 'p.group_id=gl.group_id');
		$this->db->join('crm_contacts c', 'gl.contact_id=c.contact_id', 'left');

		if ($this->input->get_post('program_id') !== FALSE)
		{
			$this->db->where('p.program_id',$this->input->get_post('program_id'));
		}

		$this->db->save_queries = TRUE;
		$query = $this->db->get();

		foreach ($query->result() as $row){
			$trow = array();
			$trow['id'] = $row->contact_id;
			$trow['revfullname'] = $row->last_name . ', ' . $row->first_name;
			$data[]=$trow;
		}
		$data = $this->javascript->generate_json($data, TRUE);
		exit($data);
	}

	// ********************************************************************************* //

	public function new_note()
	{
		$this->db->set('contact_id', $this->input->get_post('contact_id'));
		$this->db->set('note', $this->input->get_post('note'));
		$this->db->set('date', $this->input->get_post('date'));
		$this->db->set('creator_id', $this->session->userdata('contact_id'));
		$this->db->insert('crm_contacts_notes');

		exit($this->session->userdata('first_name'));
	}

	// ********************************************************************************* //

	public function new_event_note()
	{
		$this->db->set('event_id', $this->input->get_post('event_id'));
		$this->db->set('content', $this->input->get_post('content'));
		$this->db->set('created_date', date('Y-m-d H:i:s'));
		$this->db->set('contact_id', $this->session->userdata('contact_id'));
		$this->db->insert('crm_calendar_event_notes');
	}

	// ********************************************************************************* //

	public function sms_send(){
		$contact_id = $this->input->get_post('contact_id');
		$message = $this->input->get_post('message');
		$group_id = $this->input->get_post('group_id');

		$contacts = array();
		if($message == false) return false;
		if($group_id == false){
			if($contact_id == false){
				return false;
			}
			else{
				$contacts[] = $contact_id;
			}
		}
		else{
			$query = $this->db->select('contact_id')->from('crm_groups_links')->where('group_id', $group_id, false)->get();
			foreach ($query->result() as $row)
				if(!in_array($row->contact_id, $contacts))
					$contacts[] = $row->contact_id;
			$query->free_result();
		}

		foreach($contacts as $contact_id){
			$query = $this->db->select('tel_mobile_cc, tel_mobile_number, tel_mobile2_cc, tel_mobile2_number')->from('crm_contacts')->where('contact_id', $contact_id, false)->get();
			$contact_details = $query->num_rows() == 0 ? array() : $query->row_array();

			$mobile1 = $contact_details['tel_mobile_cc'] . $contact_details['tel_mobile_number'];
			$mobile2 = $contact_details['tel_mobile2_cc'] . $contact_details['tel_mobile2_number'];

			$target = '';
			if(strlen(trim($mobile2)) >= 10)
				$target = trim($mobile2);
			if(strlen(trim($mobile1)) >= 10)
				$target = trim($mobile1);

			$this->db->set('status', 'ERROR');
			if(strlen($target) >= 10){
				$this->load->library('clickatel');
				$confirmation_code = $this->clickatel->send($target,$message);
				if($confirmation_code !== false){
					$this->db->set('status', 'SUCCESS');
					$this->db->set('cost', 0.13);
					$this->db->set('confirmation_code', $confirmation_code);
				}
				else{
					$this->db->set('status', 'FAILED');
				}
			}
			$this->db->set('reciever_contact_id', $contact_id, false);
			$this->db->set('sender_contact_id', $this->session->userdata('contact_id'), false);
			$this->db->set('message', $message);

			$this->db->insert('crm_mobile_communications');
		}
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */