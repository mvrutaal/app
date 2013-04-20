<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hrm extends CRM_Controller
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
		if (!$this->acl->can_read($this->session->userdata['group'], 'hrm')) {
			show_error("You do not have permissions to read this resource");
		}

		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		$colLabelForInitialHoliday = "Holidays starting - ". date('Y');
		$data['standard_cols']['last_name']  = array('name' => 'Last Name', 'sortable' => 'true');
		$data['standard_cols']['first_name'] = array('name' => 'First Name', 'sortable' => 'true');
		$data['standard_cols']['initialHours'] 	= array('name' => $colLabelForInitialHoliday, 'sortable' => 'false');
		$data['standard_cols']['balanceHours'] 	= array('name' => 'Holidays Left', 'sortable' => 'false');
		$data['standard_cols']['sickHours']  = array('name' => 'Sick Hours', 'sortable' => 'false');
		$data['standard_cols']['holidayHours'] 	= array('name' => 'Holiday Hours', 'sortable' => 'false');
		
		
		$sessionUserId = $this->session->userdata['contact_id'];
		/*echo "<pre>";
		print_r($this->session->userdata);
		die;*/
		$currentYear = date('Y');
		
		$data['holidayData'] = array('0' => 'NONE');
		//If the user is Administrator
		if($this->session->userdata['group'] == 'admin'){
			$sqlQuery = "SELECT crm_hrm.*, crm_contacts.first_name, crm_contacts.last_name FROM crm_hrm JOIN crm_contacts ON crm_hrm.contact_id = crm_contacts.contact_id WHERE crm_hrm.created_date LIKE '%$currentYear%'";
		}
		//else if the user is member
		else if($this->session->userdata['group'] == 'members'){
			$sqlQuery = "SELECT crm_hrm.*, crm_contacts.first_name, crm_contacts.last_name FROM crm_hrm JOIN crm_contacts ON crm_hrm.contact_id = crm_contacts.contact_id WHERE crm_hrm.created_date LIKE '%$currentYear%' AND crm_hrm.contact_id = ".$sessionUserId;

		}//else if($this->session->userdata['group']){
		//}
		
		
		$query = $this->db->query($sqlQuery);
		
		foreach($query->result() as $row)
		{
			$row->contact_id = array();
			if($row->sickHours == ""){
				$row->contact_id['sickHours'] = 0;
			}else{
				$row->contact_id['sickHours'] = $row->sickHours;
			}
			
			if($row->holidayHours == ""){
				$row->contact_id['holidayHours'] = 0;	
			}else{
				$row->contact_id['holidayHours'] = $row->holidayHours;
			}
			
			$row->contact_id['first_name'] = $row->first_name;
			$row->contact_id['last_name'] = $row->last_name;
			$row->contact_id['balanceHours'] = $row->balanceHours;
			$row->contact_id['initialHours'] = $row->initialHours; 
			
			$data['contact'][] = $row->contact_id; 
		}

		$vData = array();
		$vData['title'] = 'HRM';
		$vData['pagetype'] = 'hrm';
		if($this->session->userdata('error')){
			$vData['error'] = $this->session->userdata('error');
		}
		$vData['content'] = $this->load->view('hrm/index', $data, TRUE);
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
	
	public function summary(){
		
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (!$this->acl->can_read($this->session->userdata['group'], 'hrm')) {
			show_error("You do not have permissions to read this resource");
		}

		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		$data['standard_cols']['holiday_type'] = array('name' => 'Holiday Type', 'sortable' => 'true');
		$data['standard_cols']['holidayHours'] = array('name' => 'Holiday Hours', 'sortable' => 'false');
		$data['standard_cols']['from_date']  = array('name' => 'From Date', 'sortable' => 'false');
		$data['standard_cols']['to_date'] 	= array('name' => 'To Date', 'sortable' => 'false');
		$data['standard_cols']['attachment'] 	= array('name' => 'Attachment', 'sortable' => 'false');
		

		$contact_id = $this->uri->segment(3);
		//---------------------------------------------
		// Grab all Leave Details for requested contact
		//---------------------------------------------
		$this->db->select('c.first_name, c.last_name, HRD.*');
		$this->db->from('crm_contacts c');
		$this->db->join('crm_hrm_details HRD', 'HRD.contact_id = c.contact_id', 'left');
		$this->db->where('HRD.contact_id', $contact_id);
		$query = $this->db->get();
		
		if ($query->num_rows() == 0)
		{
			$error = 'Summary cannot be found';
			if($this->session->userdata('error')){
				$this->session->unset_userdata('error');
			}
			$this->session->set_userdata('error', $error);
			redirect('/hrm/index');
		}
		else{
			$data['leaveDetails'] = array();
			$i = 0;
			
			foreach($query->result() as $row)
			{
				$data['employee_fname'] = $row->first_name;
				$data['employee_lname'] = $row->last_name;
				$data['contact_id'] = $row->contact_id;
				
				$data['leaveDetails'][$i]['first_name'] = $row->first_name;
				$data['leaveDetails'][$i]['last_name'] = $row->last_name;
				if($row->holiday_type == 1) $holiday_type = 'Sick leave';
				if($row->holiday_type == 2) $holiday_type = 'Holiday leave';
				$data['leaveDetails'][$i]['holiday_type'] = $holiday_type;
				$data['leaveDetails'][$i]['holiday_taken'] = $row->holiday_taken;
				$data['leaveDetails'][$i]['attachment'] = $row->attachment;
				$data['leaveDetails'][$i]['from_date'] = $row->from_date;
				$data['leaveDetails'][$i]['to_date'] = $row->to_date;
				$i++;
			}
		
		}
		//$data = $query->row_array();
		

		//----------------------------------------
		// Return
		//----------------------------------------
		$vData = array();
		$vData['title'] = $data['employee_fname'] . ' ' . $data['employee_lname'];
		$vData['pagetype'] = 'HRM Details ';
		$vData['content'] = $this->load->view('hrm/summary', $data, TRUE);
		$this->load->view('layout', $vData);
		
	}

	// ********************************************************************************* //
	public function add($contact_id=0)
	{
		if (! $this->acl->can_write($this->session->userdata['group'], 'hrm'))
		{
			show_error('Access Denied!');
		}
		
		if($_POST){
			
			if($this->session->userdata('error')){
				$this->session->unset_userdata('error');
			}
			$contact_id = $this->input->post('contacts_id');
			$holiday_type = $this->input->post('holiday_type');
			$holiday_hours = $this->input->post('holiday_hours');
			$userfile  = $_FILES['userfile'];
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			$date_str = date("Y-m-d");
			
			if($userfile['name']!= ""){
				$upload_path = realpath(APPPATH.'\\uploads\\');
				$config = array(
					'allowed_types' => 'jpg|jpeg|gif|png',
					'upload_path' => $upload_path,
					'encrypt_name' => true
				);

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload()){
					$error = $this->upload->display_errors();
					$this->session->set_userdata('error', $error);
					redirect('/hrm/add');		
				}else{
					$image_data = $this->upload->data();
					$filename = $image_data['file_name'];
					$this->db->set('attachment', $filename);
				}
			}
			
					
			$this->db->set('contact_id', $contact_id);
			$this->db->set('holiday_type', $holiday_type);
			$this->db->set('holiday_taken', $holiday_hours);
			$this->db->set('from_date', $from_date);
			$this->db->set('to_date', $to_date);
			$this->db->set('created_date', $date_str);
			$this->db->insert('crm_hrm_details');
			
			//Update the new balance of leave in crm_hrm_holidays table.
			$query = $this->db->select('*')->from('crm_hrm')->where('contact_id', $contact_id)->get();
			$EmployeeLeaveData = $query->row_array();
			
				
				
			if($holiday_type == 1){
				if($EmployeeLeaveData['balanceHours'] != ""){
					$newBalance = $EmployeeLeaveData['balanceHours'] - $holiday_hours;
				}else{
					$newBalance = $EmployeeLeaveData['initialHours'] - $holiday_hours;
				}				
				$this->db->set('sickHours', $holiday_hours);
				$this->db->set('balanceHours', $newBalance);	
				
			}elseif($holiday_type == 2){
				
				if($EmployeeLeaveData['balanceHours'] != ""){
					$newBalance = $EmployeeLeaveData['balanceHours'] - $holiday_hours;
				}else{
					$newBalance = $EmployeeLeaveData['initialHours'] - $holiday_hours;
				}
				
				$this->db->set('holidayHours', $holiday_hours);
				$this->db->set('balanceHours', $newBalance);	
				
			}
			$this->db->where('contact_id', $contact_id);
			$this->db->update('crm_hrm');
			
			
			
			redirect('/hrm/', 'index');
			
		}
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_write($this->session->userdata['group'], 'hrm'))
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
		// Grab all USER To add new holiday Record
		//----------------------------------------
		$data['employee'] = array('0' => '');
		
		// Join on crm_hrm_holidays table to make sure employee with out balance leave so not appear on list
		$this->db->select('C.*', FALSE);
		$this->db->from('crm_hrm CH');
		$this->db->join('crm_contacts C', 'CH.contact_id = C.contact_id', 'inner');
		$this->db->where('CH.balanceHours >', 0);
		$query = $this->db->get();

		
		//$query = $this->db->query("SELECT * FROM crm_contacts ORDER BY first_name ASC");

		foreach($query->result() as $row)
		{
			$name = $row->first_name. " " .$row->last_name;
			$data['employee'][$row->contact_id] = $name;
		}

		//----------------------------------------
		// Output
		//----------------------------------------
		$vData = array();
		$vData['title'] = 'New Entry';
		$vData['pagetype'] = 'hrm';
		$vData['error'] = $this->session->userdata('error');
		$vData['content'] = $this->load->view('hrm/add_edit', $data, TRUE);
		$this->load->view('layout', $vData);
	}
	
	public function allocate($contact_id=0)
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_write($this->session->userdata['group'], 'hrm'))
		{
			show_error('Access Denied!');
		}

		if($_POST){

			$contact_id = $this->input->post('contacts_id');
			$initial_holidays = $this->input->post('initial_holidays');
			
			$date_str = date("Y-m-d");
			
			$this->db->set('contact_id', $contact_id);
			$this->db->set('balanceHours', $initial_holidays);
			$this->db->set('initialHours', $initial_holidays);
			$this->db->set('created_date', $date_str);
			$this->db->insert('crm_hrm');
			
			
			redirect('/hrm/', 'index');

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
		// Grab all USER FOR Holiday Allocation for first time
		//----------------------------------------
		$data['employee'] = array('0' => '');
		$sql = "SELECT * FROM crm_hrm";
		$query = $this->db->query($sql);
		
		$dataTemp = array();
		foreach($query->result() as $row)
		{
			$contact_id = $row->contact_id;
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
		// Output
		//----------------------------------------
		$vData = array();
		$vData['title'] = 'New Entry';
		$vData['pagetype'] = 'hrm';
		$vData['content'] = $this->load->view('hrm/allocate', $data, TRUE);
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
		$sFromDate = ($this->input->post('from_date')  != FALSE) ? $_POST['from_date'] : FALSE;
		$sToDate = ($this->input->post('to_date')  != FALSE) ? $_POST['to_date'] : FALSE;


		$sGroups = FALSE;

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
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_hrm')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$sessionUserId = $this->session->userdata['contact_id'];
		
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_hrm HR');
		$this->db->join('crm_contacts c', 'HR.contact_id = c.contact_id', 'inner');
		if($sFromDate || $sToDate) $this->db->join('crm_hrm_details HRD', 'HRD.contact_id = c.contact_id', 'inner');
		if ($sFirst_name) $this->db->like('first_name', $sFirst_name, 'both');
		if ($sLast_name) $this->db->like('last_name', $sLast_name, 'both');
		if ($sFromDate){ 
			$this->db->like('HRD.from_date', $sFromDate, 'both');
			$this->db->or_like('HRD.to_date', $sToDate, 'both');
		}
		if ($sToDate){ 
			$this->db->like('HRD.to_date', $sToDate, 'both');
			$this->db->or_like('HRD.from_date', $sFromDate, 'both');
		}
		if($this->session->userdata['group'] == 'members'){
			$this->db->where('HR.contact_id', $sessionUserId);
		}
		
		if ($global_search)
		{
			$this->db->like('c.first_name', $global_search, 'both');
			$this->db->or_like('c.last_name', $global_search, 'both');
			if($sFromDate || $sToDate){
				$this->db->or_like('HRD.from_date', date('Y-m-d',strtotime($global_search)), 'both');
				$this->db->or_like('HRD.to_date', date('Y-m-d',strtotime($global_search)), 'both');
			}
			if($this->session->userdata['group'] == 'members'){
				$this->db->where('HR.contact_id', $sessionUserId);
			}
			$this->db->where('HR.created_date LIKE ', date('Y'));
			

		}
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//print_r($this->db->queries);

		//----------------------------------------
		// Real Query
		//----------------------------------------
		//$this->db->select('c.*, HR.*, HRD.*');
		$this->db->select('c.*, HR.*');
		$this->db->from('crm_hrm HR');
		$this->db->join('crm_contacts c', 'HR.contact_id = c.contact_id', 'inner');
		if($sFromDate || $sToDate)$this->db->join('crm_hrm_details HRD', 'HRD.contact_id = c.contact_id', 'left');
		
		
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
				case 'from_date':
					$this->db->order_by('HRD.from_date', date('Y-m-d',strtotime($sort)));
					break;
				case 'to_date':
					$this->db->order_by('HRD.to_date', $sort);
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
		if ($sFromDate) { 
			$this->db->like('HRD.from_date', $sFromDate, 'both'); 
		}
		if ($sToDate) { 
			$this->db->like('HRD.to_date', $sToDate, 'both');
		}
		if($this->session->userdata['group'] == 'members'){
			$this->db->where('HR.contact_id', $sessionUserId);
		}

		/*
		if ($sJob_title) $this->db->like('job_title', $sJob_title, 'both');
		if ($sTel_number) $this->db->where(" (tel_work_number LIKE '%{$sTel_number}%' OR tel_home_number LIKE '%{$sTel_number}%' OR tel_mobile_number LIKE '%{$sTel_number}%' OR tel_mobile2_number LIKE '%{$sTel_number}%') ", NULL, FALSE);
		*/
		if ($global_search)
		{
			$this->db->like('c.first_name', $global_search, 'both');
			$this->db->or_like('c.last_name', $global_search, 'both');
			if($sFromDate || $sToDate){
				$this->db->or_like('HRD.from_date', $global_search, 'both');
				$this->db->or_like('HRD.to_date', $global_search, 'both');
			}
			if($this->session->userdata['group'] == 'members'){
				$this->db->where('HR.contact_id', $sessionUserId);
			}
			$this->db->where('WHERE HR.created_date LIKE ', date('Y'));
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
			$actions = '<a href="'.site_url('hrm/summary/'.$row->contact_id).'">'.$row->contact_id.'</a>';
			if ($can_edit) $actions .= '<a href="'.site_url('hrm/summary/'.$row->contact_id).'" class="view"></a>';

			// AddIcon?
			if ($addicon == TRUE)
			{
				$actions = $row->contact_id . '<a href="#" class="add" data-id="'.$row->contact_id.'"></a>';
			}
			
			if($row->holidayHours == NULL || $row->holidayHours == ""){
				$holidayHours = 0;
			}else{
				$holidayHours = $row->holidayHours;
			}
			if($row->sickHours == NULL || $row->sickHours == "" ){
				$sickHours = 0;
			}else{
				$sickHours = $row->sickHours;
			}
			
			$trow['DT_RowId']	  = $row->id;
			$trow['contact_id']    = $actions;
			$trow['first_name']    = $row->first_name;
			$trow['last_name']	 = $row->last_name;
			$trow['holidayHours']  = $holidayHours;
			$trow['sickHours']	 = $sickHours;
			$trow['initialHours']	 = $row->initialHours;
			$trow['balanceHours']	 = $row->balanceHours;
			
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
	
	public function ajax_leavedatatable($contact_id)
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
		$sHolidayTypeArray = ($this->input->post('holiday_type') != FALSE) ? $_POST['holiday_type'] : FALSE;
		$sHolidayType = $sHolidayTypeArray[0];
		$sFromDate = ($this->input->post('from_date')  != FALSE) ? $_POST['from_date'] : FALSE;
		$sToDate = ($this->input->post('to_date')  != FALSE) ? $_POST['to_date'] : FALSE;


		$sGroups = FALSE;

		// Global search?
		$global_search = FALSE;

		if ($_POST['sSearch'] != FALSE)
		{
			$sHolidayType = FALSE; $sFromDate = FALSE; $sToDate = FALSE;
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
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_hrm_details')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_hrm_details HRD');
		//$this->db->join('crm_contacts c', 'HR.contact_id = c.contact_id', 'inner');
		//if($sFromDate || $sToDate) $this->db->join('crm_hrm_details HRD', 'HRD.contact_id = c.contact_id', 'inner');
		if ($sHolidayType){ $this->db->where('holiday_type', $sHolidayType, 'both'); }
		if ($sFromDate){ 
			$this->db->like('HRD.from_date', $sFromDate, 'both');
			$this->db->or_like('HRD.to_date', $sToDate, 'both');
		}
		if ($sToDate){ 
			$this->db->like('HRD.to_date', $sToDate, 'both');
			$this->db->or_like('HRD.from_date', $sFromDate, 'both');
		}

		if ($global_search)
		{
			
			if($sFromDate || $sToDate){
				$this->db->or_like('HRD.from_date', date('Y-m-d',strtotime($global_search)), 'both');
				$this->db->or_like('HRD.to_date', date('Y-m-d',strtotime($global_search)), 'both');
			}
			$this->db->where('HRD.holiday_type', $global_search, 'both');
			$this->db->where('HRD.created_date LIKE ', date('Y'));
			$this->db->where('HRD.contact_id = ', $contact_id );

		}
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//print_r($this->db->queries);

		//----------------------------------------
		// Real Query
		//----------------------------------------
		//$this->db->select('c.*, HR.*, HRD.*');
		$this->db->select('HRD.*');
		$this->db->from('crm_hrm_details HRD');
		//$this->db->join('crm_contacts c', 'HR.contact_id = c.contact_id', 'inner');
		$this->db->where('HRD.contact_id', $contact_id, 'both'); 
		
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
				case 'holiday_type':
					$this->db->order_by('HRD.holiday_type', $sort);
					break;
				case 'from_date':
					$this->db->order_by('HRD.from_date', date('Y-m-d',strtotime($sort)));
					break;
				case 'to_date':
					$this->db->order_by('HRD.to_date', $sort);
					break;
				default:
					$this->db->order_by('HRD.created_date', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		
		if ($sFromDate) { 
			$this->db->like('HRD.from_date', $sFromDate, 'both'); 
		}
		if ($sToDate) { 
			$this->db->like('HRD.to_date', $sToDate, 'both');
		}
		if ($sHolidayType) $this->db->where('HRD.holiday_type', $sHolidayType, 'both');
		/*
		if ($sJob_title) $this->db->like('job_title', $sJob_title, 'both');
		if ($sTel_number) $this->db->where(" (tel_work_number LIKE '%{$sTel_number}%' OR tel_home_number LIKE '%{$sTel_number}%' OR tel_mobile_number LIKE '%{$sTel_number}%' OR tel_mobile2_number LIKE '%{$sTel_number}%') ", NULL, FALSE);
		*/
		if ($global_search)
		{
			
			if($sFromDate || $sToDate){
				$this->db->or_like('HRD.from_date', $global_search, 'both');
				$this->db->or_like('HRD.to_date', $global_search, 'both');
			}
			$this->db->where('WHERE HRD.holiday_type', $global_search, 'both');
			$this->db->where('WHERE HRD.created_date LIKE ', date('Y'));
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
		$can_edit = $this->acl->can_modify($this->session->userdata['group'], 'hrm');

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
			//if ($can_edit) $actions .= '<a href="'.site_url('hrm/summary/'.$row->contact_id).'" class="view"></a>';

			// AddIcon?
			if ($addicon == TRUE)
			{
				$actions = $row->contact_id . '<a href="#" class="add" data-id="'.$row->contact_id.'"></a>';
			}
			
			$trow['DT_RowId']	  = $row->hrm_id;
			$trow['contact_id']    = $actions;
			if($row->holiday_type == 1) 
				$holiday_type    = 'Sick Leave';
			if($row->holiday_type == 2) 
				$holiday_type    = 'Holiday Leave';
				
			$trow['holiday_type']	= $holiday_type;		
			$trow['holidayHours']  = $row->holiday_taken;
			if($row->attachment == "" || $row->attachment == NULL){
				$attachment = '-';
			}else{
				$attachment = $row->attachment;
			}
			$trow['attachment']	 = $attachment;
			$trow['from_date']	 = $row->from_date;
			$trow['to_date']	 = $row->to_date;
			
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

	public function ajax_generate_excel()
	{
		if ($this->input->get('ids') == FALSE) show_error('No Contact ID\'s!!');
		
		
		//----------------------------------------
		// Grab Contacts from DB
		//----------------------------------------
		$this->db->select('c.*, da.* ');
		$this->db->from('crm_contacts c');
		$this->db->join('crm_dooraccess da', 'da.contact_id = c.contact_id', 'inner');
		
		//$this->db->where_in('da.contact_id', explode('|',$this->input->get('ids')));
		$this->db->where_in('da.id', explode('|',$this->input->get('ids')));
		$this->db->order_by('da.first_access_type');
		//$this->db->group_by('da.contact_id');
		$query = $this->db->get();
		
		//----------------------------------------
		// Loop over all contacts!
		//----------------------------------------
		$i = 0;
		foreach ($query->result() as $contact)
		{
			//----------------------------------------
			// Contact Name Data
			//----------------------------------------
			$date_in_print = date("d-m-Y",strtotime($contact->first_access_type));
			$date_out_print = date("d-m-Y",strtotime($contact->second_access_type));

			$date_in = $contact->first_access_type;
			$date_out = $contact->second_access_type;
			
			$time_in = $contact->first_access_time;
			$time_out = $contact->second_access_time;
			
			$temp_in = $date_in ." ". $time_in ;
			$temp_out = $date_out ." ". $time_out ;
			
			$temp_in = date("d-m-Y H:i:s", strtotime($temp_in));
			$temp_out = date("d-m-Y H:i:s", strtotime($temp_out));
			
			$difference = (strtotime($temp_out) - strtotime($temp_in));
		 	
			$hours = $difference / 3600;
			$minutes = ($hours - floor($hours)) * 60;

			$final_hours = round($hours,0);
			$final_minutes = round($minutes);
		
			$spent_time = $final_hours . "h " . $final_minutes . "m";
			
			$EmpDetails[$contact->contact_id][$i]['contact_id'] = $contact->contact_id;
			$EmpDetails[$contact->contact_id][$i]['name'] = $contact->first_name." ".$contact->last_name;
			$EmpDetails[$contact->contact_id][$i]['in_date'] = $date_in;
			$EmpDetails[$contact->contact_id][$i]['in_time'] = $time_in;
			$EmpDetails[$contact->contact_id][$i]['out_date'] = $date_out;
			$EmpDetails[$contact->contact_id][$i]['out_time'] = $time_out;
			$EmpDetails[$contact->contact_id][$i]['time_spent'] = $spent_time;
			
			
			/*$details_1[$i]['contact_id'] = $contact->contact_id;
			$details_1[$i]['first_name'] = $contact->first_name;
			$details_1[$i]['last_name'] = $contact->last_name;
			$details_1[$i]['in_date'] = $contact->first_access_type;
			$details_1[$i]['in_time'] = $contact->first_access_time;
			$details_1[$i]['out_date'] = $contact->second_access_type;
			$details_1[$i]['out_time'] = $contact->second_access_time;*/

			$i++;
			
		}
		
		/*echo "<pre>";
		//print_r($details_2);
		//die;*/
		
		//load our new PHPExcel library
		$this->load->library('excel');
		
		$this->excel->setActiveSheetIndex(0);
		$sheetname = 'Door_Access_'.date('d-m-Y');
		$this->excel->getActiveSheet()->setTitle($sheetname);
		
		$this->excel->getActiveSheet()->setCellValue('A1', 'Contact ID');
		$this->excel->getActiveSheet()->setCellValue('B1', 'Contact Name');
		$this->excel->getActiveSheet()->setCellValue('C1', 'InDate');
		$this->excel->getActiveSheet()->setCellValue('D1', 'InTime');
		$this->excel->getActiveSheet()->setCellValue('E1', 'OutDate');
		$this->excel->getActiveSheet()->setCellValue('F1', 'OutTime');
		$this->excel->getActiveSheet()->setCellValue('G1', 'Hours Spent Inside');
		
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
		
		$j=2;
		foreach($EmpDetails as $_EmpDetails):
			
			foreach($_EmpDetails as $__EmpDetails):
				
				$this->excel->getActiveSheet()->setCellValue('A'.$j, $__EmpDetails['contact_id']);
				$this->excel->getActiveSheet()->setCellValue('B'.$j, $__EmpDetails['name']);
				$this->excel->getActiveSheet()->setCellValue('C'.$j, $__EmpDetails['in_date']);
				$this->excel->getActiveSheet()->setCellValue('D'.$j, $__EmpDetails['in_time']);
				$this->excel->getActiveSheet()->setCellValue('E'.$j, $__EmpDetails['out_date']);
				$this->excel->getActiveSheet()->setCellValue('F'.$j, $__EmpDetails['out_time']);
				$this->excel->getActiveSheet()->setCellValue('G'.$j, $__EmpDetails['time_spent']);
				
				$j++;	
			endforeach;
					
		endforeach;
		
		
		//change the font size
		//$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		//make the font become bold
		//$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		//merge cell A1 until D1
		//$this->excel->getActiveSheet()->mergeCells('A1:D1');
		//set aligment to center for that merged cell (A1 to D1)
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 
		$filename= 'EXCEL_'.strtotime(date('H:i:s')).'.xlsx'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
					 
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
		
	}
	
	public function pdf_report_dooraccess()
	{
		if ($this->input->get('ids') == FALSE) show_error('No Contact ID\'s!!');
		
		
		//----------------------------------------
		// Grab Contacts from DB
		//----------------------------------------
		$this->db->select('c.*, da.* ');
		$this->db->from('crm_contacts c');
		$this->db->join('crm_dooraccess da', 'da.contact_id = c.contact_id', 'inner');
		
		//$this->db->where_in('da.contact_id', explode('|',$this->input->get('ids')));
		$this->db->where_in('da.id', explode('|',$this->input->get('ids')));
		$this->db->order_by('da.first_access_type');
		//$this->db->group_by('da.contact_id');
		$query = $this->db->get();
		
		//----------------------------------------
		// Loop over all contacts!
		//----------------------------------------
		$i = 0;
		foreach ($query->result() as $contact)
		{
			//----------------------------------------
			// Contact Name Data
			//----------------------------------------
			$date_in_print = date("d-m-Y",strtotime($contact->first_access_type));
			$date_out_print = date("d-m-Y",strtotime($contact->second_access_type));

			$date_in = $contact->first_access_type;
			$date_out = $contact->second_access_type;
			
			$time_in = $contact->first_access_time;
			$time_out = $contact->second_access_time;
			
			$temp_in = $date_in ." ". $time_in ;
			$temp_out = $date_out ." ". $time_out ;
			
			$temp_in = date("d-m-Y H:i:s", strtotime($temp_in));
			$temp_out = date("d-m-Y H:i:s", strtotime($temp_out));
			
			$difference = (strtotime($temp_out) - strtotime($temp_in));
		 	
			$hours = $difference / 3600;
			$minutes = ($hours - floor($hours)) * 60;

			$final_hours = round($hours,0);
			$final_minutes = round($minutes);
		
			$spent_time = $final_hours . "h " . $final_minutes . "m";
			
			$EmpDetails[$contact->contact_id][$i]['contact_id'] = $contact->contact_id;
			$EmpDetails[$contact->contact_id][$i]['name'] = $contact->first_name." ".$contact->last_name;
			$EmpDetails[$contact->contact_id][$i]['in_date'] = $date_in;
			$EmpDetails[$contact->contact_id][$i]['in_time'] = $time_in;
			$EmpDetails[$contact->contact_id][$i]['out_date'] = $date_out;
			$EmpDetails[$contact->contact_id][$i]['out_time'] = $time_out;
			$EmpDetails[$contact->contact_id][$i]['time_spent'] = $spent_time;
			
			
			/*$details_1[$i]['contact_id'] = $contact->contact_id;
			$details_1[$i]['first_name'] = $contact->first_name;
			$details_1[$i]['last_name'] = $contact->last_name;
			$details_1[$i]['in_date'] = $contact->first_access_type;
			$details_1[$i]['in_time'] = $contact->first_access_time;
			$details_1[$i]['out_date'] = $contact->second_access_type;
			$details_1[$i]['out_time'] = $contact->second_access_time;*/

			$i++;
			
		}
		
		/*echo "<pre>";
		//print_r($details_2);
		//die;*/
		$this->load->library('mypdf');
		
		//----------------------------------------
		// Create PDF
		//----------------------------------------
		// create new PDF document
		$pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Page Titles
		$pdf->page_title = 'Door Access Report - '.date('d-m-Y');
		
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
		
		//code the html for pdf
		
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
		$html .= '<h2 class="pagetitle">Door Access Report - '.date('d-m-Y').'</h2>';
		$html .= '
			<h4 style="color:darkgreen;">'.strtoupper('dooraccess report').'</h4>
			<table border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>Contact ID</th>
					<th>Contact Name</th>
					<th>InDate</th>
					<th>InTime</th>
					<th>OutDate</th>
					<th>OutTime</th>
					<th style="text-align:right;">Hours Spent Inside</th>
				</tr>
			</thead>
			<tbody>';
			
					
		
		//$j=2;
		foreach($EmpDetails as $_EmpDetails):
			
			foreach($_EmpDetails as $__EmpDetails):
				$html .= 		'<tr class="ItemRow">
									<td>'.$__EmpDetails['contact_id'].'</td>
									<td>'.$__EmpDetails['name'].'</td>
									<td>'.$__EmpDetails['in_date'].'</td>
									<td>'.$__EmpDetails['in_time'].'</td>
									<td>'.$__EmpDetails['out_date'].'</td>
									<td>'.$__EmpDetails['out_time'].'</td>
									<td style="text-align:right;">'.$__EmpDetails['time_spent'].'</td>
								  </tr>
								';

				/*
				$this->excel->getActiveSheet()->setCellValue('A'.$j, $__EmpDetails['contact_id']);
				$this->excel->getActiveSheet()->setCellValue('B'.$j, $__EmpDetails['name']);
				$this->excel->getActiveSheet()->setCellValue('C'.$j, $__EmpDetails['in_date']);
				$this->excel->getActiveSheet()->setCellValue('D'.$j, $__EmpDetails['in_time']);
				$this->excel->getActiveSheet()->setCellValue('E'.$j, $__EmpDetails['out_date']);
				$this->excel->getActiveSheet()->setCellValue('F'.$j, $__EmpDetails['out_time']);
				$this->excel->getActiveSheet()->setCellValue('G'.$j, $__EmpDetails['time_spent']);
				
				$j++;	
				*/
			endforeach;
					
		endforeach;

		$pdf->writeHTML($html, true, false, true, false, '');
		
		$pdf->Output('Door Access Department - 2012'.date('d-m-Y Hi').'.pdf', 'I');
		//$filename= 'PDF-'.strtotime(date('H:i:s')).'.pdf'; 
		//$pdf->Output($filename, 'I');
		//$pdf->save('php://output');
		/*
		
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 
		$objWriter2 = new PHPExcel_Writer_PDF($this->excel);
		$objWriter2->save($filename);

		$objWriter2->save('php://output');
		*/
		
	}
	
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