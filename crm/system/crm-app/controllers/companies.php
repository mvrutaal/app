<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies extends CRM_Controller
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
		if (! $this->acl->can_read($this->session->userdata['group'], 'companies'))
		{
			show_error('Access Denied!');
		}


		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		$data['standard_cols']['company_title']	= array('name' => 'Company Name', 'sortable' => 'true');
		$data['standard_cols']['company_type']	= array('name' => 'Type', 'sortable' => 'true');
		$data['standard_cols']['company_street']= array('name' => 'Street', 'sortable' => 'false');
		$data['standard_cols']['company_tel']	= array('name' => 'Tel. Number', 'sortable' => 'false');
		$data['standard_cols']['company_fax']	= array('name' => 'Fax Number', 'sortable' => 'false');
		$data['standard_cols']['company_email']	= array('name' => 'Email', 'sortable' => 'false');
		$data['standard_cols']['company_count']	= array('name' => 'Contacts', 'sortable' => 'false');

		$data['extra_cols']['company_employee_count'] = array('name' => 'Employees', 'sortable' => 'true');
		$data['extra_cols']['company_languages'] = array('name' => 'Languages', 'sortable' => 'false');
		$data['extra_cols']['company_website']	= array('name' => 'Website', 'sortable' => 'false');
		$data['extra_cols']['company_street2']	= array('name' => 'Street 2', 'sortable' => 'false');
		$data['extra_cols']['company_city']		= array('name' => 'City', 'sortable' => 'false');
		$data['extra_cols']['company_suburb']	= array('name' => 'Suburb', 'sortable' => 'false');
		$data['extra_cols']['company_zip']		= array('name' => 'Zip', 'sortable' => 'false');
		$data['extra_cols']['company_country']	= array('name' => 'Country', 'sortable' => 'false');
		$data['extra_cols']['company_tel2']		= array('name' => 'Tel. Number 2', 'sortable' => 'false');
		$data['extra_cols']['company_fax2']		= array('name' => 'Fax Number 2', 'sortable' => 'false');
		$data['extra_cols']['company_email2']	= array('name' => 'Email 2', 'sortable' => 'false');
		$data['extra_cols']['company_author']	= array('name' => 'Author', 'sortable' => 'true');

		//----------------------------------------
		// Grab all company_types
		//----------------------------------------
		$data['company_types'] = array('0' => '');
		$query = $this->db->query("SELECT * FROM crm_dataset_company_types ORDER BY company_type_label ASC");

		foreach($query->result() as $row)
		{
			$data['company_types'][$row->company_type_id] = $row->company_type_label;
		}

		//----------------------------------------
		// Get Users List
		//----------------------------------------
		$data['authors'] = array();
		$data['users'] = $this->ion_auth->get_users_array();

		foreach ($data['users'] as $user)
		{
			$data['authors'][ $user['group_description'] ][ $user['id'] ] = $user['username'];
		}

		//----------------------------------------
		// Grab all Suburbs
		//----------------------------------------
		$data['suburbs'] = array('0' => 'NONE');
		$query = $this->db->query("SELECT * FROM crm_dataset_suburbs ORDER BY suburb_label ASC");

		foreach($query->result() as $row)
		{
			$data['suburbs'][$row->suburb_id] = $row->suburb_label;
		}

		//----------------------------------------
		// Default Dropdowns!
		//----------------------------------------
		$data['dropdowns'] = $this->config->item('crm_dropdowns');

		$vData = array();
		$vData['title'] = 'Companies';
		$vData['pagetype'] = 'companies';
		$vData['content'] = $this->load->view('companies/index', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function view()
	{
		//----------------------------------------
		// Get All Company Data
		//----------------------------------------
		$company_id = $this->uri->segment(3);

		$this->db->select('c.*, sub.suburb_label, str.street_label, city.city_label, cc.country_label, ct.company_type_label, con.first_name, con.last_name');
		$this->db->from('crm_companies c');
		$this->db->join('crm_dataset_streets str', 'str.street_id = c.company_street_id', 'left');
		$this->db->join('crm_dataset_suburbs sub', 'sub.suburb_id = c.company_suburb_id', 'left');
		$this->db->join('crm_dataset_cities city', 'city.city_id = c.company_city_id', 'left');
		$this->db->join('crm_dataset_countries cc', 'cc.country_id = c.company_country_id', 'left');
		$this->db->join('crm_dataset_company_types ct', 'ct.company_type_id = c.company_type_id', 'left');
		$this->db->join('crm_auth_users au', 'au.id = c.company_author', 'left');
		$this->db->join('crm_contacts con', 'con.contact_id = au.contact_id', 'left');
		$this->db->where('c.company_id', $company_id);
		$query = $this->db->get();
		$data = $query->row_array();

		//----------------------------------------
		// Get all contacts
		//----------------------------------------
		$contacts = array();
		$query = $this->db->select('*')->from('crm_contacts')->where('company_id', $company_id)->order_by('first_name, last_name')->get();
		foreach($query->result() as $row)
		{
			$contacts[] = $row;
		}
		$data['contacts'] = $contacts;


		//----------------------------------------
		// Grab all Notes types
		//----------------------------------------
		$data['notes'] = array();
		$data['note_types'] = array();
		$query = $this->db->select('*')->from('crm_notes_types')->where('note_type_module', 'companies')->order_by('note_type_label')->get();

		foreach($query->result() as $row)
		{
			// Lets store notes for later
			$data['notes'][$row->note_type_id] = array();

			if ($this->global_model->process_item_acl($row->note_type_author, $row->note_type_acl) == FALSE) continue;
			$data['note_types'][ $row->note_type_id ] = $row->note_type_label;
		}

		//----------------------------------------
		// Notes Cols
		//----------------------------------------
		$data['notes_cols'] = array();
		$data['notes_cols']['note_author']	= array('name' => 'Author', 'sortable' => 'true');
		$data['notes_cols']['note_date']	= array('name' => 'Date', 'sortable' => 'true');
		$data['notes_cols']['note_text']	= array('name' => 'Note Text', 'sortable' => 'true');

		//----------------------------------------
		// Grab all notes!
		//----------------------------------------
		$data['notes'] = array();

		$this->db->select('n.*, c.first_name, c.last_name, ');
		$this->db->from('crm_notes n');
		$this->db->join('crm_auth_users au', 'au.id = n.note_author', 'left');
		$this->db->join('crm_contacts c', 'c.contact_id = au.contact_id', 'left');
		$this->db->where('note_item_type', 'companies')->where('note_item_id', $company_id);
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$data['notes'][$row->note_type_id][] = $row;
		}


		//----------------------------------------
		// Return
		//----------------------------------------
		$vData = array();
		$vData['title'] = $data['company_title'];
		$vData['pagetype'] = 'companies';
		$vData['content'] = $this->load->view('companies/view', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function add($company_id=0)
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_write($this->session->userdata['group'], 'companies'))
		{
			show_error('Access Denied!');
		}

		//----------------------------------------
		// Grab all default fields
		//----------------------------------------
		if ($company_id == 0)
		{
			$data = array();
			foreach ($this->db->list_fields('crm_companies') as $key => $val) $data[$val] = '';

			$data['company_tel_cc'] = $this->config->item('general_default_tel_cc');
			$data['company_tel2_cc'] = $this->config->item('general_default_tel_cc');
			$data['company_fax_cc'] = $this->config->item('general_default_tel_cc');
			$data['company_fax2_cc'] = $this->config->item('general_default_tel_cc');
			$data['company_country_id'] = $this->config->item('general_default_country');
			$data['company_city_id'] = $this->config->item('general_default_city');
		}
		else
		{
			$query = $this->db->select('*')->from('crm_companies')->where('company_id', $company_id)->get();

			if ($query->num_rows() == 0)
			{
				show_error('Company cannot be found');
			}

			$data = $query->row_array();

			if ($data['company_languages'] != FALSE) $data['company_languages'] = explode(',', $data['company_languages']);
		}

		//----------------------------------------
		// Grab all company_types
		//----------------------------------------
		$data['company_types'] = array('0' => '');
		$query = $this->db->query("SELECT * FROM crm_dataset_company_types ORDER BY company_type_label ASC");

		foreach($query->result() as $row)
		{
			$data['company_types'][$row->company_type_id] = $row->company_type_label;
		}

		//----------------------------------------
		// Grab all Suburbs
		//----------------------------------------
		$data['suburbs'] = array('0' => '');
		$query = $this->db->query("SELECT * FROM crm_dataset_suburbs ORDER BY suburb_label ASC");

		foreach($query->result() as $row)
		{
			$data['suburbs'][$row->suburb_id] = $row->suburb_label;
		}

		//----------------------------------------
		// Grab all Streets
		//----------------------------------------
		$data['streets'] = array('0' => '');
		$query = $this->db->query("SELECT * FROM crm_dataset_streets ORDER BY street_label ASC");

		foreach($query->result() as $row)
		{
			$data['streets'][$row->street_id] = $row->street_label;
		}

		//----------------------------------------
		// Grab all Cities
		//----------------------------------------
		$data['cities'] = array('0' => '');
		$query = $this->db->query("SELECT * FROM crm_dataset_cities ORDER BY city_label ASC");

		foreach($query->result() as $row)
		{
			$data['cities'][$row->city_id] = $row->city_label;
		}

		//----------------------------------------
		// Grab all countries
		//----------------------------------------
		$data['countries'] = array('0' => '');
		$query = $this->db->query("SELECT * FROM crm_dataset_countries ORDER BY country_label ASC");

		foreach($query->result() as $row)
		{
			$data['countries'][$row->country_id] = $row->country_label;
		}

		//----------------------------------------
		// Grab all Notes types
		//----------------------------------------
		$data['note_types'] = array();
		$query = $this->db->select('*')->from('crm_notes_types')->where('note_type_module', 'companies')->order_by('note_type_label')->get();

		foreach($query->result() as $row)
		{
			if ($this->global_model->process_item_acl($row->note_type_author, $row->note_type_acl) == FALSE) continue;
			$data['note_types'][ $row->note_type_id ] = $row->note_type_label;
		}

		//----------------------------------------
		// Notes Cols
		//----------------------------------------
		$data['notes_cols'] = array();
		$data['notes_cols']['note_author']	= array('name' => 'Author', 'sortable' => 'true');
		$data['notes_cols']['note_date']	= array('name' => 'Date', 'sortable' => 'true');
		$data['notes_cols']['note_text']	= array('name' => 'Note Text', 'sortable' => 'true');

		//----------------------------------------
		// Default Dropdowns!
		//----------------------------------------
		$data['dropdowns'] = $this->config->item('crm_dropdowns');

		//----------------------------------------
		// Output
		//----------------------------------------
		$vData = array();
		$vData['title'] = 'New Company';
		$vData['pagetype'] = 'companies';
		$vData['content'] = $this->load->view('companies/add_edit', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function edit()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_modify($this->session->userdata['group'], 'companies'))
		{
			show_error('Access Denied!');
		}

		$this->add( $this->uri->segment(3) );
	}

	// ********************************************************************************* //

	public function update()
	{
		$this->load->helper('url');

		$this->db->set('company_title', $this->input->post('company_title'));
		$this->db->set('company_type_id', $this->input->post('company_type_id'));
		$this->db->set('company_employee_count', $this->input->post('company_employee_count'));
		$this->db->set('company_description', $this->input->post('company_description'));
		$this->db->set('company_street_id', $this->input->post('company_street_id'));
		$this->db->set('company_street2', $this->input->post('company_street2'));
		$this->db->set('company_housenumber', $this->input->post('company_housenumber'));
		$this->db->set('company_city_id', $this->input->post('company_city_id'));
		$this->db->set('company_suburb_id', $this->input->post('company_suburb_id'));
		$this->db->set('company_zip', $this->input->post('company_zip'));
		$this->db->set('company_country_id', $this->input->post('company_country_id'));
		$this->db->set('company_tel_cc', $this->input->post('company_tel_cc'));
		$this->db->set('company_tel_number', $this->input->post('company_tel_number'));
		$this->db->set('company_tel2_cc', $this->input->post('company_tel2_cc'));
		$this->db->set('company_tel2_number', $this->input->post('company_tel2_number'));
		$this->db->set('company_fax_cc', $this->input->post('company_fax_cc'));
		$this->db->set('company_fax_number', $this->input->post('company_fax_number'));
		$this->db->set('company_fax2_cc', $this->input->post('company_fax2_cc'));
		$this->db->set('company_fax2_number', $this->input->post('company_fax2_number'));
		$this->db->set('company_email', $this->input->post('company_email'));
		$this->db->set('company_email2', $this->input->post('company_email2'));
		$this->db->set('company_website', $this->input->post('company_website'));

		$languages = $this->input->post('company_languages');
		if ($languages != FALSE && is_array($languages) == TRUE)
		{
			$languages = implode(',', $languages);
		} else $languages = '';

		$this->db->set('company_languages', $languages);

		// New or Update?
		if ($this->input->post('company_id') != FALSE)
		{
			$this->db->where('company_id', $this->input->post('company_id'));
			$this->db->update('crm_companies');
		}
		else
		{
			$this->db->set('company_author', $this->session->userdata['user_id']);
			$this->db->insert('crm_companies');
		}


		redirect('/companies/', 'location');
	}

	// ********************************************************************************* //

	public function delete()
	{
		if (! $this->acl->can_delete($this->session->userdata['group'], 'companies'))
		{
			show_error('Access Denied!');
		}

		$company_id = $this->uri->segment(3);

		//----------------------------------------
		// CRM_COMPANIES
		//----------------------------------------
		$this->db->where('company_id', $company_id);
		$this->db->delete('crm_companies');

		//----------------------------------------
		// CRM_CONTACTS
		//----------------------------------------
		$this->db->set('company_id', 0);
		$this->db->where('company_id', $company_id);
		$this->db->update('crm_contacts');

		redirect('/companies/', 'location');
	}

	// ********************************************************************************* //

	public function ajax_datatable()
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
		$sCompany_name = ($this->input->post('company_title') != FALSE) ? $_POST['company_title'] : FALSE;
		$sCompany_type = ($this->input->post('company_types')  != FALSE) ? $_POST['company_types'] : FALSE;
		$sCompany_authors = ($this->input->post('company_authors')  != FALSE) ? $_POST['company_authors'] : FALSE;
		$sCompany_employees = ($this->input->post('company_employee_count')  != FALSE) ? $_POST['company_employee_count'] : FALSE;
		$sSuburb = ($this->input->post('suburb')  != FALSE) ? $_POST['suburb'] : FALSE;

		$sCompany_lang = FALSE;
		if ($this->input->post('company_languages')  != FALSE)
		{
			$sCompany_lang = '(c.company_languages LIKE ';
			foreach ($this->input->post('company_languages') as $lang)
			{
				$sCompany_lang .= " '%{$lang}%' OR c.company_languages LIKE";
			}

			$sCompany_lang = rtrim($sCompany_lang, "OR c.company_languages LIKE");

			$sCompany_lang .= ')';
		}

		// Global search?
		$global_search = FALSE;

		if ($_POST['sSearch'] != FALSE)
		{
			$sCompany_name = FALSE;
			$global_search = $_POST['sSearch'];
		}

		//----------------------------------------
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_companies')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_companies c');
		$this->db->join('crm_auth_users au', 'au.id = c.company_author', 'left');
		$this->db->join('crm_contacts con', 'con.contact_id = au.contact_id', 'left');
		if ($sCompany_name) $this->db->like('c.company_title', $sCompany_name, 'both');
		if ($sCompany_type) $this->db->where_in('c.company_type_id', $sCompany_type);
		if ($sCompany_authors) $this->db->where_in('c.company_author', $sCompany_authors);
		if ($sSuburb) $this->db->where_in('c.company_suburb_id', $sSuburb);
		if ($sCompany_lang) $this->db->where($sCompany_lang, NULL, FALSE);
		if ($sCompany_employees) $this->db->where_in('c.company_employee_count', $sCompany_employees);
		if ($global_search)
		{
			$this->db->like('c.company_title', $global_search, 'both');
		}
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('c.*, sub.suburb_label, str.street_label, city.city_label, cc.country_label, ct.company_type_label, con.first_name, con.last_name');
		$this->db->from('crm_companies c');
		$this->db->join('crm_dataset_streets str', 'str.street_id = c.company_street_id', 'left');
		$this->db->join('crm_dataset_suburbs sub', 'sub.suburb_id = c.company_suburb_id', 'left');
		$this->db->join('crm_dataset_cities city', 'city.city_id = c.company_city_id', 'left');
		$this->db->join('crm_dataset_countries cc', 'cc.country_id = c.company_country_id', 'left');
		$this->db->join('crm_dataset_company_types ct', 'ct.company_type_id = c.company_type_id', 'left');
		$this->db->join('crm_auth_users au', 'au.id = c.company_author', 'left');
		$this->db->join('crm_contacts con', 'con.contact_id = au.contact_id', 'left');

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
				case 'company_title':
					$this->db->order_by('c.company_title', $sort);
					break;
				case 'company_type':
					$this->db->order_by('c.company_type', $sort);
					break;
				case 'company_employee_count':
					$this->db->order_by('c.company_employee_count', $sort);
					break;
				default:
					$this->db->order_by('c.company_title', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($sCompany_name) $this->db->like('c.company_title', $sCompany_name, 'both');
		if ($sCompany_type) $this->db->where_in('c.company_type_id', $sCompany_type);
		if ($sCompany_authors) $this->db->where_in('c.company_author', $sCompany_authors);
		if ($sSuburb) $this->db->where_in('c.company_suburb_id', $sSuburb);
		if ($sCompany_lang) $this->db->where($sCompany_lang, NULL, FALSE);
		if ($sCompany_employees) $this->db->where_in('c.company_employee_count', $sCompany_employees);
		if ($global_search)
		{
			$this->db->like('c.company_title', $global_search, 'both');
		}

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
		// DropDowns
		//----------------------------------------
		$dropdowns = $this->config->item('crm_dropdowns');
		$employees = $dropdowns['company_employees'];
		$languages = $dropdowns['languages'];

		//----------------------------------------
		// ACL
		//----------------------------------------
		$can_edit = $this->acl->can_modify($this->session->userdata['group'], 'companies');

		// Add Icon?
		$addicon = (isset($_POST['addicon']) == TRUE && $_POST['addicon'] == 'yes') ? TRUE : FALSE;

		//----------------------------------------
		// Loop Over all
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$trow = array();

			// Actions Block
			$actions = '<a href="'.site_url('companies/view/'.$row->company_id).'">'.$row->company_id.'</a>';
			if ($can_edit) $actions .= '<a href="'.site_url('companies/edit/'.$row->company_id).'" class="edit"></a>';

			// Group Count
			$q = $this->db->query("SELECT COUNT(*) as count FROM crm_contacts WHERE company_id = {$row->company_id}");
			$count = $q->row('count');

			// AddIcon?
			if ($addicon == TRUE)
			{
				$actions = $row->company_id . '<a href="#" class="add" data-id="'.$row->company_id.'"></a>';
			}

			$trow['DT_RowId']	= $row->company_id;

			$trow['company_id'] = $actions;
			$trow['company_title']	= $row->company_title;
			$trow['company_type']	= $row->company_type_label;
			$trow['company_street']	= $row->street_label .' '.$row->company_housenumber;
			$trow['company_tel']	= $row->company_tel_number;
			$trow['company_fax']	= $row->company_fax_number;
			$trow['company_email']	= $row->company_email;
			$trow['company_count']	= $count;



			$trow['company_website']= $row->company_website;
			$trow['company_street2']= '';
			$trow['company_city']	= $row->city_label;
			$trow['company_suburb']	= $row->suburb_label;
			$trow['company_zip']	= '';
			$trow['company_country']= $row->country_label;
			$trow['company_tel2']	= $row->company_tel2_number;
			$trow['company_fax2']	= $row->company_fax2_number;
			$trow['company_email2']	= $row->company_email2;
			$trow['company_author'] = $row->first_name . ' ' . $row->last_name;

			if ($row->company_employee_count == -1) $trow['company_employee_count'] = '';
			else $trow['company_employee_count'] = str_replace(' Employees', '', $employees[$row->company_employee_count]);

			if ($row->company_languages == FALSE) $trow['company_languages'] = '';
			else
			{
				$row->company_languages = explode(',', $row->company_languages);
				foreach ($row->company_languages as &$lang) $lang = $languages[$lang];
				$trow['company_languages'] = implode(', ', $row->company_languages);
			}

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //
}

/* End of file welcome.php */
/* Location: ./application/controllers/contacts.php */