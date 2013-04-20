<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dooraccess extends CRM_Controller
{

	function __construct()
	{
		parent::__construct();

		//$this->load->model('groups_model');
		//$this->load->model('companies_model');
	}

	// ********************************************************************************* //

	public function index()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (!$this->acl->can_read($this->session->userdata['group'], 'dooraccess')) {
			show_error("You do not have permissions to read this resource");
		}

		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		
		$data['standard_cols']['first_name']	= array('name' => 'First Name', 'sortable' => 'true');
		$data['standard_cols']['last_name']		= array('name' => 'Last Name', 'sortable' => 'true');
		$data['standard_cols']['in_time']	= array('name' => 'In Date', 'sortable' => 'false');
		$data['standard_cols']['start_time']   = array('name' => 'In Time', 'sortable' => 'false');
		$data['standard_cols']['out_time']   = array('name' => 'Out Date', 'sortable' => 'false');
		$data['standard_cols']['end_time']   = array('name' => 'Out Time', 'sortable' => 'false');

		/*
		$data['extra_cols']['first_access_type']	= array('name' => 'In Time', 'sortable' => 'false');
		$data['extra_cols']['second_access_type']   = array('name' => 'Out Time', 'sortable' => 'false');
		
		$data['extra_cols']['birthplace'] = array('name' => 'Birthplace', 'sortable' => 'false');
		$data['extra_cols']['street2']	= array('name' => 'Street 2', 'sortable' => 'false');
		$data['extra_cols']['city']		= array('name' => 'City', 'sortable' => 'false');
		$data['extra_cols']['suburb']	= array('name' => 'Suburb', 'sortable' => 'false');
		$data['extra_cols']['zip']		= array('name' => 'Zip Code', 'sortable' => 'false');
		*/
		//----------------------------------------
		// Grab all companies
		//----------------------------------------
		$data['dooraccess'] = array('0' => 'NONE');
		$query = $this->db->query("SELECT crm_dooraccess.*, crm_contacts.*  FROM crm_dooraccess JOIN crm_contacts ON crm_dooraccess.contact_id = crm_contacts.contact_id");
		
		foreach($query->result() as $row)
		{
			
			$row->contact_id = array();
			$row->contact_id['inTime'] = $row->first_access_type;
			$row->contact_id['outTime'] = $row->second_access_type;
			$row->contact_id['first_name'] = $row->first_name;
			$row->contact_id['last_name'] = $row->last_name;
			$row->contact_id['start_time'] = $row->first_access_time;
			$row->contact_id['end_time'] = $row->second_access_time;
			
			$data['contact'][] = $row->contact_id; 
			//$data['dooraccess'][$row->contact_id] = $row->first_access_type;
			
		}
		$vData = array();
		$vData['title'] = 'Door Access';
		$vData['pagetype'] = 'dooraccess';
		$vData['content'] = $this->load->view('dooraccess/index', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function view()
	{
		//----------------------------------------
		// Get Contacts Data
		//----------------------------------------
		$contact_id = $this->uri->segment(3);
		$this->db->select('c.*, str.street_label, sub.suburb_label, city.city_label, cc.country_label');
		$this->db->from('crm_contacts c');
		$this->db->join('crm_dataset_streets str', 'c.street_id = str.street_id', 'left');
		$this->db->join('crm_dataset_suburbs sub', 'c.suburb_id = sub.suburb_id', 'left');
		$this->db->join('crm_dataset_cities city', 'c.city_id = city.city_id', 'left');
		$this->db->join('crm_dataset_countries cc', 'c.country_id = cc.country_id', 'left');
		$this->db->where('contact_id', $contact_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0)
		{
			show_error('Program cannot be found');
		}

		$data = $query->row_array();

		//----------------------------------------
		// Grab Company Info
		//----------------------------------------
		if ($data['company_id'] > 0)
		{
			$data['company'] = $this->companies_model->get_company($data['company_id']);
		}

		//----------------------------------------
		// Grab all Notes types
		//----------------------------------------
		$data['notes'] = array();
		$data['note_types'] = array();
		$query = $this->db->select('*')->from('crm_notes_types')->where('note_type_module', 'contacts')->order_by('note_type_label')->get();

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
		$this->db->where('note_item_type', 'contacts')->where('note_item_id', $contact_id);
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$data['notes'][$row->note_type_id][] = $row;
		}

		//----------------------------------------
		// Return
		//----------------------------------------
		if (IS_AJAX)
		{
			$this->load->view('contacts/view', $data);
		}
		else
		{
			$vData = array();
			$vData['title'] = $data['first_name'] . ' ' . $data['last_name'];
			$vData['pagetype'] = 'contacts';
			$vData['content'] = $this->load->view('contacts/view', $data, TRUE);
			$this->load->view('layout', $vData);
		}
	}

	// ********************************************************************************* //

	public function add($contact_id=0)
	{
		if($_POST){

			if(isset($_POST['in_user_id'])){
				
				$contact_id = $this->input->post('in_user_id');
				$inDate = $this->input->post('in_date');
				$inTime = $this->input->post('in_time');
				
				
				$date_str = date("Y-m-d",strtotime($inDate));
				
				$this->db->set('contact_id', $contact_id);
				$this->db->set('first_access_type', $date_str);
				$this->db->set('first_access_time', $inTime);
				$this->db->set('second_access_type', NULL);
				$this->db->set('second_access_time', NULL);
				$this->db->insert('crm_dooraccess');
				
				redirect('/dooraccess/', 'index');
				
			}else if(isset($_POST['out_user_id'])){
				
				$contact_id = $this->input->post('out_user_id');
				$outDate = $this->input->post('out_date');
				$outTime = $this->input->post('out_time');
				
				
				$date_str = date("Y-m-d",strtotime($outDate));
				
				$sql = "SELECT * FROM crm_dooraccess WHERE contact_id = $contact_id AND second_access_type IS NULL ORDER BY id DESC LIMIT 1";
				$query = $this->db->query($sql);
				
				foreach($query->result() as $row)
				{
					$row_id = $row->id;
				}
				
				if($row_id){
					
					$this->db->set('second_access_type', $date_str);
					$this->db->set('second_access_time', $outTime);
					$this->db->where('id', $row_id);
					$this->db->update('crm_dooraccess');
				}
				
				redirect('/dooraccess/', 'index');
			}
		}
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_write($this->session->userdata['group'], 'dooraccess'))
		{
			show_error('Access Denied!');
		}

		//----------------------------------------
		// Grab all default fields
		//----------------------------------------
		if ($contact_id == 0)
		{
			$data = array();
			foreach ($this->db->list_fields('crm_contacts') as $key => $val) $data[$val] = '';

			$data['tel_work_cc'] = $this->config->item('general_default_tel_cc');
			$data['tel_home_cc'] = $this->config->item('general_default_tel_cc');
			$data['tel_mobile_cc'] = $this->config->item('general_default_tel_cc');
			$data['tel_mobile2_cc'] = $this->config->item('general_default_tel_cc');
			$data['city_id'] = $this->config->item('general_default_city');
			$data['country_id'] = $this->config->item('general_default_country');
			$data['groups'] = array();
		}
		else
		{
			$query = $this->db->select('*')->from('crm_contacts')->where('contact_id', $contact_id)->get();

			if ($query->num_rows() == 0)
			{
				show_error('Contact cannot be found');
			}

			$data = $query->row_array();
			$data['groups'] = array();

		}
		
		//----------------------------------------
		// Grab all USER FOR IN TIME
		//----------------------------------------
		$data['employee'] = array('0' => '');
		$sql = "SELECT *  FROM crm_dooraccess WHERE second_access_type IS NULL";
		$query = $this->db->query($sql);
		
		$dataTemp = array();
		foreach($query->result() as $row)
		{
			$contact_id = $row->contact_id;
			//$data['employee'][$row->contact_id] = $name;
			$dataTemp[] = $contact_id;
		}
		
		
		$query = $this->db->query("SELECT * FROM crm_contacts ORDER BY first_name ASC");

		foreach($query->result() as $row)
		{
			if(count($dataTemp)>0){
				if(!in_array($row->contact_id,$dataTemp)){
					$name = $row->first_name. " " .$row->last_name;
					$data['employee'][$row->contact_id] = $name;
				}
			}
			else{
				$name = $row->first_name. " " .$row->last_name;
				$data['employee'][$row->contact_id] = $name;
			}
		}
		//----------------------------------------
		// Grab all USER FOR OUT TIME
		//----------------------------------------
		$data['employee1'] = array('0' => '');
		$sql = "SELECT C_CONTACTS.*  FROM crm_contacts as C_CONTACTS JOIN crm_dooraccess as C_DOOR ON C_DOOR.contact_id = 			 		
				C_CONTACTS.contact_id
				WHERE C_DOOR.second_access_type IS NULL 
				ORDER BY C_CONTACTS.first_name ASC";
		$query = $this->db->query($sql);

		foreach($query->result() as $row)
		{
			$name = $row->first_name. " " .$row->last_name;
			$data['employee1'][$row->contact_id] = $name;
		}
		
		
		//----------------------------------------
		// Grab all Notes types
		//----------------------------------------
		$data['note_types'] = array();
		$query = $this->db->select('*')->from('crm_notes_types')->where('note_type_module', 'contacts')->order_by('note_type_label')->get();

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
		// Output
		//----------------------------------------
		$vData = array();
		$vData['title'] = 'New Entry';
		$vData['pagetype'] = 'dooraccess';
		$vData['content'] = $this->load->view('dooraccess/add_edit', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function edit()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_modify($this->session->userdata['group'], 'contacts'))
		{
			show_error('Access Denied!');
		}

		$this->add( $this->uri->segment(3) );
	}

	// ********************************************************************************* //

	public function update()
	{
		$this->load->helper('url');

		$this->db->set('first_name', $this->input->post('first_name'));
		$this->db->set('initials', $this->input->post('initials'));
		$this->db->set('last_name', $this->input->post('last_name'));
		$this->db->set('nickname', $this->input->post('nickname'));
		$this->db->set('sex', $this->input->post('sex'));
		if ($this->input->post('birthday') != FALSE) $this->db->set('birthday', $this->input->post('birthday'));
		$this->db->set('birthplace', $this->input->post('birthplace'));
		$this->db->set('street_id', $this->input->post('street_id'));
		$this->db->set('street2', $this->input->post('street2'));
		$this->db->set('housenumber', $this->input->post('housenumber'));
		$this->db->set('city_id', $this->input->post('city_id'));
		$this->db->set('suburb_id', $this->input->post('suburb_id'));
		$this->db->set('zip', $this->input->post('zip'));
		$this->db->set('country_id', $this->input->post('country_id'));

		$this->db->set('tel_work_cc', $this->input->post('tel_work_cc'));
		$this->db->set('tel_work_number', $this->input->post('tel_work_number'));
		$this->db->set('tel_work_ext', $this->input->post('tel_work_ext'));

		$this->db->set('tel_home_cc', $this->input->post('tel_home_cc'));
		$this->db->set('tel_home_number', $this->input->post('tel_home_number'));

		$this->db->set('tel_mobile_cc', $this->input->post('tel_mobile_cc'));
		$this->db->set('tel_mobile_number', $this->input->post('tel_mobile_number'));

		$this->db->set('tel_mobile2_cc', $this->input->post('tel_mobile2_cc'));
		$this->db->set('tel_mobile2_number', $this->input->post('tel_mobile2_number'));

		$this->db->set('email_work', $this->input->post('email_work'));
		$this->db->set('email_work2', $this->input->post('email_work2'));
		$this->db->set('email_personal', $this->input->post('email_personal'));
		$this->db->set('email_personal2', $this->input->post('email_personal2'));

		$this->db->set('job_title', $this->input->post('job_title'));
		$this->db->set('id_number', $this->input->post('id_number'));
		if ($this->input->post('shirt_size') != FALSE) $this->db->set('shirt_size', $this->input->post('shirt_size'));
		else $this->db->set('shirt_size', '');
		$this->db->set('last_study', $this->input->post('last_study'));
		$this->db->set('bankaccount_number', $this->input->post('bankaccount_number'));
		$this->db->set('day_care', $this->input->post('day_care'));

		$this->db->set('skj_entitled', $this->input->post('skj_entitled'));

		$this->db->set('company_id', $this->input->post('company_id'));


		// New or Update?
		if ($this->input->post('contact_id') != FALSE)
		{
			$this->db->where('contact_id', $this->input->post('contact_id'));
			$this->db->update('crm_contacts');
			$contact_id = $this->input->post('contact_id');
		}
		else
		{
			$this->db->insert('crm_contacts');
			$contact_id = $this->db->insert_id();
		}

		//----------------------------------------
		// Update Groups
		//----------------------------------------
		$this->groups_model->group_to_items($contact_id, 'contacts', $_POST['groups']);

		//----------------------------------------
		// Media Uploads?
		//----------------------------------------
		$path_photo = FCPATH."images/contact_photo/{$contact_id}.jpg";
		$path_id = FCPATH."images/contact_id/{$contact_id}.jpg";

		//----------------------------------------
		// Photo Upload?
		//----------------------------------------
		if (isset($_FILES['upload_photo']['error']) == TRUE && $_FILES['upload_photo']['error'] == 0)
		{
			if (@move_uploaded_file($_FILES['upload_photo']['tmp_name'], $path_photo) !== FALSE)
	    	{
				$this->db->set('has_photo', 1);
				$this->db->where('contact_id', $contact_id);
				$this->db->update('crm_contacts');
	    	}
		}

		//----------------------------------------
		// ID Upload?
		//----------------------------------------
		if (isset($_FILES['upload_id']['error']) == TRUE && $_FILES['upload_photo']['error'] == 0)
		{
			if (@move_uploaded_file($_FILES['upload_id']['tmp_name'], $path_id) !== FALSE)
	    	{
				$this->db->set('has_id', 1);
				$this->db->where('contact_id', $contact_id);
				$this->db->update('crm_contacts');
	    	}
		}

		redirect('/contacts/', 'location');
	}

	// ********************************************************************************* //

	public function delete()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_delete($this->session->userdata['group'], 'contacts'))
		{
			show_error('Access Denied!');
		}

		$contact_id = $this->uri->segment(3);

		//----------------------------------------
		// CRM_GROUPS_LINKS
		//----------------------------------------
		$this->db->where('contact_id', $contact_id);
		$this->db->delete('crm_groups_items');

		//----------------------------------------
		// CRM_CONTACTS
		//----------------------------------------
		$this->db->where('contact_id', $contact_id);
		$this->db->delete('crm_contacts');

		redirect('/contacts/', 'location');
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
		$sFirst_name = ($this->input->post('first_name') != FALSE) ? $_POST['first_name'] : FALSE;
		$sLast_name = ($this->input->post('last_name') != FALSE) ? $_POST['last_name'] : FALSE;
		$sDate = ($this->input->post('date')  != FALSE) ? $_POST['date'] : FALSE;
		$sInTime = ($this->input->post('start_time')  != FALSE) ? $_POST['start_time'] : FALSE;
		$sOutTime = ($this->input->post('end_time')  != FALSE) ? $_POST['end_time'] : FALSE;
		
		$sJob_title = ($this->input->post('job_title')  != FALSE) ? $_POST['job_title'] : FALSE;
		$sCompany = ($this->input->post('company')  != FALSE) ? $_POST['company'] : FALSE;
		$sSuburb = ($this->input->post('suburb')  != FALSE) ? $_POST['suburb'] : FALSE;
		$sTel_number = ($this->input->post('tel_number')  != FALSE) ? $_POST['tel_number'] : FALSE;

		$sGroups = FALSE;
		if ($this->input->post('groups') != FALSE)
		{
			$sGroups = "(SELECT contact_id FROM crm_groups_items WHERE group_id IN (".implode(',', $this->input->post('groups')).") )";
		}

		// Global search?
		$global_search = FALSE;

		if ($_POST['sSearch'] != FALSE)
		{
			$sLast_name = FALSE; $sFirst_name = FALSE;
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
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_dooraccess')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		/*
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_contacts c');
		$this->db->join('crm_dataset_streets str', 'str.street_id = c.street_id', 'left');
		if ($sFirst_name) $this->db->like('first_name', $sFirst_name, 'both');
		if ($sLast_name) $this->db->like('last_name', $sLast_name, 'both');
		if ($sStreet) $this->db->like('str.street_label', $sStreet, 'both');
		if ($sJob_title) $this->db->like('job_title', $sJob_title, 'both');
		if ($sCompany) $this->db->where_in('c.company_id', $sCompany);
		if ($sSuburb) $this->db->where_in('c.suburb_id', $sSuburb);
		if ($sGroups) $this->db->where('c.contact_id IN '.$sGroups, NULL, FALSE);
		if ($sTel_number) $this->db->where(" (tel_work_number LIKE '%{$sTel_number}%' OR tel_home_number LIKE '%{$sTel_number}%' OR tel_mobile_number LIKE '%{$sTel_number}%' OR tel_mobile2_number LIKE '%{$sTel_number}%') ", NULL, FALSE);
		if ($global_search)
		{
			$this->db->like('c.first_name', $global_search, 'both');
			$this->db->or_like('c.last_name', $global_search, 'both');
		}
		*/
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_dooraccess str');
		$this->db->join('crm_contacts c', 'str.contact_id = c.contact_id', 'inner');
		if ($sFirst_name) $this->db->like('first_name', $sFirst_name, 'both');
		if ($sLast_name) $this->db->like('last_name', $sLast_name, 'both');
		if ($sDate){ 
			$this->db->like('str.first_access_type', $sDate, 'both');
			$this->db->or_like('str.second_access_type', $sDate, 'both');
		}
		if ($sInTime) $this->db->where('str.first_access_time >', $sInTime);
		if ($sOutTime) $this->db->where('str.second_access_time <', $sOutTime);
		
		if ($global_search)
		{
			$this->db->like('c.first_name', $global_search, 'both');
			$this->db->or_like('c.last_name', $global_search, 'both');
			$this->db->or_like('str.first_access_type', date('Y-m-d',strtotime($global_search)), 'both');
			$this->db->or_like('str.second_access_type', date('Y-m-d',strtotime($global_search)), 'both');
			//$this->db->or_like('str.first_access_time', $global_search, 'both');
			//$this->db->or_like('str.second_access_time', $global_search, 'both');
			$this->db->where('str.first_access_time >', $sInTime);
			$this->db->where('str.second_access_time <', $sOutTime);
		}
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//print_r($this->db->queries);

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('c.*, da.*');
		$this->db->from('crm_dooraccess da');
		$this->db->join('crm_contacts c', 'da.contact_id = c.contact_id', 'inner');
		
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
				case 'first_name':
					$this->db->order_by('c.first_name', $sort);
					break;
				case 'last_name':
					$this->db->order_by('c.last_name', $sort);
					break;
				case 'date':
					$this->db->order_by('da.first_access_type', date('Y-m-d',strtotime($sort)));
					$this->db->order_by('da.second_access_type', date('Y-m-d',strtotime($sort)));
					break;
				case 'start_time':
					$this->db->order_by('da.first_access_time', $sort);
					break;
				case 'end_time':
					$this->db->order_by('da.second_access_time', $sort);
					break;
				default:
					$this->db->order_by('c.first_name', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($sFirst_name) $this->db->like('first_name', $sFirst_name, 'both');
		if ($sLast_name) $this->db->like('last_name', $sLast_name, 'both');
		if ($sDate) { 
			$this->db->like('da.first_access_type', $sDate, 'both'); 
			$this->db->or_like('da.second_access_type', $sDate, 'both');
		}
		if($sInTime) $this->db->where('da.first_access_time > ', $sInTime); 
		if($sOutTime) $this->db->where('da.second_access_time < ', $sOutTime); 
		
		/*
		if ($sJob_title) $this->db->like('job_title', $sJob_title, 'both');
		if ($sTel_number) $this->db->where(" (tel_work_number LIKE '%{$sTel_number}%' OR tel_home_number LIKE '%{$sTel_number}%' OR tel_mobile_number LIKE '%{$sTel_number}%' OR tel_mobile2_number LIKE '%{$sTel_number}%') ", NULL, FALSE);
		*/
		if ($global_search)
		{
			$this->db->like('c.first_name', $global_search, 'both');
			$this->db->or_like('c.last_name', $global_search, 'both');
			$this->db->or_like('da.first_access_type', $global_search, 'both');
			$this->db->or_like('da.second_access_type', $global_search, 'both');
			//$this->db->or_like('da.first_access_time', $global_search, 'both');
			//$this->db->or_like('da.second_access_time', $global_search, 'both');
			$this->db->where('da.first_access_time > ', $global_search); 
			$this->db->where('da.second_access_time < ', $global_search); 

		}

		//----------------------------------------
		// OFFSET & LIMIT & EXECUTE!
		//----------------------------------------
		$limit = 15;
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
		// ACL
		//----------------------------------------
		$can_edit = $this->acl->can_modify($this->session->userdata['group'], 'contacts');

		// Add Icon?
		$addicon = (isset($_POST['addicon']) == TRUE && $_POST['addicon'] == 'yes') ? TRUE : FALSE;
		
		//print_r($this->db->queries);
		//----------------------------------------
		// Loop Over all
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$trow = array();

			// Actions Block
			//$actions = '<a href="'.site_url('dooraccess/view/'.$row->contact_id).'" class="view">'.$row->contact_id.'</a>';
			$actions = '<a href="#">'.$row->contact_id.'</a>';
			if ($can_edit) $actions .= '<a href="'.site_url('dooraccess/edit/'.$row->contact_id).'" class="edit"></a>';

			// AddIcon?
			if ($addicon == TRUE)
			{
				$actions = $row->contact_id . '<a href="#" class="add" data-id="'.$row->contact_id.'"></a>';
			}
			
			if($row->first_access_type != NULL || $row->first_access_type != ""){
				$in_time = date('d-m-Y', strtotime($row->first_access_type));
			}
			if($row->second_access_type != NULL || $row->second_access_type != "" ){
				$out_time = date('d-m-Y', strtotime($row->second_access_type));
			}else{
				$out_time = "";
			}
			
			$trow['DT_RowId']	 = $row->contact_id;
			$trow['contact_id']   = $actions;
			$trow['first_name']   = $row->first_name;
			$trow['last_name']	= $row->last_name;
			$trow['in_time']	  = $in_time;
			$trow['out_time']	 = $out_time;
			$trow['start_time']	 = $row->first_access_time;
			$trow['end_time']	= $row->second_access_time;
			/*
			$trow['street']		= $row->street_label . ' ' . $row->housenumber;
			$trow['tel_work']	= $row->tel_work_number;
			$trow['tel_mobile']	= $row->tel_mobile_number;
			$trow['email_work']	= $row->email_work;
			$trow['job_title']	= $row->job_title;
			$trow['company']	= $row->company_title;

			$trow['nickname']	= '';
			$trow['sex']		= '';
			$trow['birthday']	= '';
			$trow['birthplace'] = '';
			$trow['street2']	= $row->street2;
			$trow['city']		= $row->city_label;
			$trow['suburb']		= $row->suburb_label;
			$trow['zip']		= $row->zip;
			$trow['country']	= $row->country_label;
			$trow['tel_home']	= $row->tel_home_number;
			$trow['tel_mobile2']= $row->tel_mobile2_number;
			$trow['email_work2']= $row->email_work2;
			$trow['email_personal']		= $row->email_personal;
			$trow['email_personal2']	= $row->email_personal2;
			$trow['id_number']			= $row->id_number;
			$trow['bankaccount_number'] = $row->bankaccount_number;
			*/
			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	public function ajax_check_contact_emails()
	{
		$contacts = array('errors' => array(), 'ok'=>array());
		$query = $this->db->select('contact_id, email_work, email_work2, email_personal, email_personal2')->from('crm_contacts')->where_in('contact_id', $_POST['ids'])->get();

		foreach ($query->result() as $row)
		{
			if ($row->email_work != FALSE){ $contacts['ok'][] = $row->contact_id; continue; }
			if ($row->email_work2 != FALSE){ $contacts['ok'][] = $row->contact_id; continue; }
			if ($row->email_personal != FALSE){ $contacts['ok'][] = $row->contact_id; continue; }
			if ($row->email_personal2 != FALSE){ $contacts['ok'][] = $row->contact_id; continue; }

			// Error then
			$contacts['errors'][] = $row->contact_id;
		}

		exit(json_encode($contacts));
	}

	// ********************************************************************************* //

	public function ajax_get_contact_emails()
	{
		// Lets let them wait a while
		sleep(1);

		$emails = array();

		// Grab all emails and their names!
		$query = $this->db->select('first_name, last_name, email_work, email_work2, email_personal, email_personal2')->from('crm_contacts')->where_in('contact_id', $_POST['ids'])->get();

		// Loop over them all
		foreach ($query->result() as $row)
		{
			$namestr = str_replace(',', '', $row->first_name . ' ' .$row->last_name);

			if ($row->email_work != FALSE){ $emails[] = "{$namestr} <{$row->email_work}>"; continue; }
			if ($row->email_work2 != FALSE){ $emails[] = "{$namestr} <{$row->email_work2}>"; continue; }
			if ($row->email_personal != FALSE){ $emails[] = "{$namestr} <{$row->email_personal}>"; continue; }
			if ($row->email_personal2 != FALSE){ $emails[] = "{$namestr} <{$row->email_personal2}>"; continue; }

		}

		exit( implode(';', $emails) );
	}

	// ********************************************************************************* //


	public function ajax_generate_vcard()
	{
		if ($this->input->get('ids') == FALSE) show_error('No Contact ID\'s!!');
		$this->load->library('vcard');

		//----------------------------------------
		// Grab Contacts from DB
		//----------------------------------------
		$this->db->select('c.*, cp.company_title, sub.suburb_label, str.street_label');
		$this->db->from('crm_contacts c');
		$this->db->join('crm_companies cp', 'cp.company_id = c.company_id', 'left');
		$this->db->join('crm_dataset_streets str', 'str.street_id = c.street_id', 'left');
		$this->db->join('crm_dataset_suburbs sub', 'sub.suburb_id = c.suburb_id', 'left');
		$this->db->where_in('contact_id', explode('|',$this->input->get('ids')));
		$query = $this->db->get();

		// Init vCard
		$vCard = '';
		$this->vcard->filename = 'CRM vCard Export '.time();
		$this->vcard->class = "PUBLIC";

		//----------------------------------------
		// Loop over all contacts!
		//----------------------------------------
		foreach ($query->result() as $contact)
		{
			$this->vcard->vcard();

			//----------------------------------------
			// Contact Name Data
			// (If you leave display_name blank, it will be built using the first and last name)
			//----------------------------------------

			//$this->vcard->data['display_name'] = $contact->first_name . ' ' . $contact->last_name;
			$this->vcard->data['first_name'] = $contact->first_name;
			$this->vcard->data['last_name'] = $contact->last_name;
			#$this->vcard->data['additional_name'] = ""; //Middle name
			#$this->vcard->data['name_prefix'] = "";  //Mr. Mrs. Dr.
			#$this->vcard->data['name_suffix'] = ""; //DDS, MD, III, other designations.
			$this->vcard->data['nickname'] = $contact->nickname;

			//----------------------------------------
			// Contact's company, department, title, profession
			//----------------------------------------
			$this->vcard->data['company'] = $contact->company_title;
			#$this->vcard->data['department'] = "";
			$this->vcard->data['title'] = $contact->job_title;
			#$this->vcard->data['role'] = "";

			//----------------------------------------
			// Contact's work address
			//----------------------------------------
			//$this->vcard->data['work_po_box'] = "";
			//$this->vcard->data['work_extended_address'] = "";
			//$this->vcard->data['work_address'] = "7027 N. Hickory";
			//$this->vcard->data['work_city'] = "Kansas City";
			//$this->vcard->data['work_state'] = "MO";
			//$this->vcard->data['work_postal_code'] = "64118";
			//$this->vcard->data['work_country'] = "United States of America";

			//----------------------------------------
			// Contact's home address
			//----------------------------------------
			//$this->vcard->data['home_po_box'] = "";
			//$this->vcard->data['home_extended_address'] = "";
			$this->vcard->data['home_address'] = $contact->street_label;
			$this->vcard->data['home_city'] = $contact->city;
			//$this->vcard->data['home_state'] = $contact->state;
			$this->vcard->data['home_postal_code'] = $contact->zip;
			$this->vcard->data['home_country'] = $contact->country;

			//----------------------------------------
			// Contact's telephone numbers.
			//----------------------------------------
			if ($contact->tel_work_number) $this->vcard->data['office_tel'] = "+{$contact->tel_work_cc}{$contact->tel_work_number}";
			if ($contact->tel_home_number) $this->vcard->data['home_tel'] = "+{$contact->tel_home_cc}{$contact->tel_home_number}";
			if ($contact->tel_mobile_number) $this->vcard->data['cell_tel'] = "+{$contact->tel_mobile_cc}{$contact->tel_mobile_number}";
			//$this->vcard->data['fax_tel'] = "";
			//$this->vcard->data['pager_tel'] = "";

			//----------------------------------------
			// Contact's email addresses
			//----------------------------------------
			$this->vcard->data['email1'] = $contact->email_work;
			$this->vcard->data['email2'] = $contact->email_personal;
			$this->vcard->data['email3'] = $contact->email_work2;
			$this->vcard->data['email4'] = $contact->email_personal2;

			//----------------------------------------
			// Some other contact data.
			//----------------------------------------
			//$this->vcard->data['url'] = "http://www.troywolf.com";
			//$this->vcard->data['photo'] = "";  //Enter a URL.
			$this->vcard->data['birthday'] = $contact->birthday;
			$this->vcard->data['timezone'] = "-04:00";
			#$this->vcard->data['note'] = "Troy is an amazing guy!";

			// If you leave this blank, the class will default to using last_name or company.
			#$this->vcard->data['sort_string'] = "";

			// Build!
			$this->vcard->build();
			$vCard .= $this->vcard->card;
		}

		// Generate card and send as a .vcf file to user's browser for download.
		$this->vcard->card = $vCard;
		$this->vcard->download();
	}

	// ********************************************************************************* //

	public function ajax_check_duplicate()
	{
		$out = array('dupe' => 'yes');

		$first_name = $this->db->escape(strtolower(trim($_POST['first_name'])));
		$last_name = $this->db->escape(strtolower(trim($_POST['last_name'])));

		$query = $this->db->query("SELECT contact_id FROM crm_contacts WHERE LOWER(first_name) = {$first_name} AND LOWER(last_name) = {$last_name}");

		if ($query->num_rows() == 0)
		{
			$out['dupe'] = 'no';
		}

		exit(json_encode($out, TRUE));
	}

	// ********************************************************************************* //

	public function ajax_check_contact_mobiles()
	{
		$data = array('success'=>array(), 'errors'=>array());
		$query = $this->db->select('contact_id, tel_mobile_cc, tel_mobile_number, tel_mobile2_cc, tel_mobile2_number')->from('crm_contacts')->where_in('contact_id', $this->input->get_post('ids'), false)->get();

		foreach ($query->result() as $row)
		{
			if ($row->tel_mobile_cc != FALSE && $row->tel_mobile_number != FALSE){ $data['success'][$row->contact_id] = $row->tel_mobile_cc . $row->tel_mobile_number; continue; }
			if ($row->tel_mobile2_cc != FALSE && $row->tel_mobile2_number != FALSE){ $data['success'][$row->contact_id] = $row->tel_mobile2_cc . $row->tel_mobile2_number; continue; }

			// Error then
			$data['errors'][] = $row->contact_id;
		}
		exit(json_encode($data));
	}

	// ********************************************************************************* //

	public function ajax_open_mailto()
	{
		echo "<html><head><meta http-equiv='refresh' content=\"0;url=mailto:{$_GET['email']}?subject={$_GET['subject']}\"></head></html>";
		exit();
	}

	// ********************************************************************************* //
}

/* End of file welcome.php */
/* Location: ./application/controllers/contacts.php */