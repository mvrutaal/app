<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CRM_Controller
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
		if (! $this->acl->can_read($this->session->userdata['group'], 'notes'))
		{
			show_error('Access Denied!');
		}


		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		$data['standard_cols']['note_author']    = array('name' => 'Author', 'sortable' => 'true');
		$data['standard_cols']['note_date']      = array('name' => 'Date', 'sortable' => 'true');
		$data['standard_cols']['note_type']      = array('name' => 'Note Type', 'sortable' => 'true');
		$data['standard_cols']['note_item']      = array('name' => 'Item Name', 'sortable' => 'false');
		$data['standard_cols']['note_item_type'] = array('name' => 'Item Type', 'sortable' => 'true');
		$data['standard_cols']['note_text']      = array('name' => 'Note Text', 'sortable' => 'true');

		//----------------------------------------
		// Grab all Notes types
		//----------------------------------------
		$data['note_types'] = array();
		$query = $this->db->select('*')->from('crm_notes_types')->order_by('note_type_label')->get();

		foreach($query->result() as $row)
		{
			if ($this->global_model->process_item_acl($row->note_type_author, $row->note_type_acl) == FALSE) continue;
			$data['note_types'][ ucfirst($row->note_type_module) ][$row->note_type_id] = $row->note_type_label;
		}

		//----------------------------------------
		// Grab all Notes Item types
		//----------------------------------------
		$data['item_types']['contacts'] = 'Contacts';
		$data['item_types']['companies'] = 'Companies';

		//----------------------------------------
		// Get Users List
		//----------------------------------------
		$data['authors'] = array();
		$data['users'] = $this->ion_auth->get_users_array();

		foreach ($data['users'] as $user)
		{
			$data['authors'][ $user['group_description'] ][ $user['id'] ] = $user['username'];
		}

		$vData = array();
		$vData['title'] = 'Notes';
		$vData['pagetype'] = 'notes';
		$vData['content'] = $this->load->view('notes/index', $data, TRUE);
		$this->load->view('layout', $vData);
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
		$sAuthors = ($this->input->post('authors') != FALSE) ? $_POST['authors'] : FALSE;
		$sNote_types = ($this->input->post('note_types') != FALSE) ? $_POST['note_types'] : FALSE;
		$sItem_types = ($this->input->post('note_item_types') != FALSE) ? $_POST['note_item_types'] : FALSE;
		$sNote_text = ($this->input->post('note_text') != FALSE) ? $_POST['note_text'] : FALSE;
		$sItem_name = ($this->input->post('item_name') != FALSE) ? $_POST['item_name'] : FALSE;
		$sDate_from = ($this->input->post('date_from') != FALSE) ? $_POST['date_from'] . ' 00:01:00' : FALSE;
		$sDate_to = ($this->input->post('date_to') != FALSE) ? $_POST['date_to'] . ' 23:59:00' : FALSE;

		//----------------------------------------
		// Grab all Notes types
		//----------------------------------------
		$excluded_note_types = array(0);
		$query = $this->db->select('*')->from('crm_notes_types')->get();

		foreach($query->result() as $row)
		{
			if ($this->global_model->process_item_acl($row->note_type_author, $row->note_type_acl) == FALSE)
			{
				$excluded_note_types[] = $row->note_type_id;
			}
		}


		//----------------------------------------
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_notes')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_notes n');
		if ($sAuthors) $this->db->where_in('n.note_author', $sAuthors);
		if ($sNote_types) $this->db->where_in('n.note_type_id', $sNote_types);
		if ($sItem_types) $this->db->where_in('n.note_item_type', $sItem_types);
		if ($sNote_text) $this->db->like('n.note_text', $sNote_text, 'both');
		if ($sItem_name)
		{
			$this->db->where("
				(cp.company_title LIKE \"%{$sItem_name}%\" OR ct.first_name LIKE \"%{$sItem_name}%\" OR ct.last_name LIKE \"%{$sItem_name}%\")
			", NULL, FALSE);
		}
		if ($sDate_from) $this->db->where('n.note_date >', $sDate_from);
		if ($sDate_to) $this->db->where('n.note_date <', $sDate_to);
		$this->db->where_not_in('n.note_type_id', $excluded_note_types);
		$this->db->join('crm_companies cp', 'cp.company_id = n.note_item_id', 'left');
		$this->db->join('crm_contacts ct', 'ct.contact_id = n.note_item_id', 'left');
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('n.*, nt.note_type_label, c.first_name, c.last_name, cp.company_title, ct.first_name AS item_first_name, ct.last_name AS item_last_name');
		$this->db->from('crm_notes n');
		$this->db->join('crm_notes_types nt', 'nt.note_type_id = n.note_type_id', 'left');
		$this->db->join('crm_auth_users au', 'au.id = n.note_author', 'left');
		$this->db->join('crm_contacts c', 'c.contact_id = au.contact_id', 'left');
		$this->db->join('crm_companies cp', 'cp.company_id = n.note_item_id', 'left');
		$this->db->join('crm_contacts ct', 'ct.contact_id = n.note_item_id', 'left');

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
				case 'note_author':
					$this->db->order_by('c.first_name', $sort);
					break;
				case 'note_date':
					$this->db->order_by('n.note_date', $sort);
					break;
				case 'note_text':
					$this->db->order_by('n.note_text', $sort);
					break;
				default:
					$this->db->order_by('n.note_date', 'DESC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($sAuthors) $this->db->where_in('n.note_author', $sAuthors);
		if ($sNote_types) $this->db->where_in('n.note_type_id', $sNote_types);
		if ($sItem_types) $this->db->where_in('n.note_item_type', $sItem_types);
		if ($sNote_text) $this->db->like('n.note_text', $sNote_text, 'both');
		if ($sDate_from) $this->db->where('n.note_date >', $sDate_from);
		if ($sDate_to) $this->db->where('n.note_date <', $sDate_to);
		if ($sItem_name)
		{
			$this->db->where("
				(cp.company_title LIKE \"%{$sItem_name}%\" OR ct.first_name LIKE \"%{$sItem_name}%\" OR ct.last_name LIKE \"%{$sItem_name}%\")
			", NULL, FALSE);
		}
		$this->db->where_not_in('n.note_type_id', $excluded_note_types);

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
		// ACL
		//----------------------------------------
		$can_edit = TRUE;


		//----------------------------------------
		// Loop Over all
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$trow = array();

			// Actions Block
			$actions = $row->note_id;
			if ($can_edit) $actions .= '<a href="#" class="edit note-add" data-url="notes/ajax_new_note_modal/edit/'.$row->note_id.'"></a>';

			$trow['DT_RowId']    = $row->note_id;
			$trow['note_id']     = $actions;
			$trow['note_author'] = $row->first_name . ' ' . $row->last_name;
			$trow['note_date']   = date('l, d-M-Y H:i', strtotime($row->note_date));
			$trow['note_text']   = $row->note_text;

			$trow['note_type'] = $row->note_type_label;
			$trow['note_item'] = ($row->note_item_type == 'contacts') ? $row->item_first_name . ' ' . $row->item_last_name : $row->company_title;
			$trow['note_item_type'] = ucfirst($row->note_item_type);

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	public function ajax_datatable_simple()
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
		$sType_id = ($this->uri->segment(3) != FALSE) ? $this->uri->segment(3) : FALSE;
		$sItem_id = ($this->uri->segment(4) != FALSE) ? $this->uri->segment(4) : FALSE;
		$sNote_text = ($this->input->post('note_text') != FALSE) ? $_POST['note_text'] : FALSE;

		// Global search?
		$global_search = FALSE;

		if ($_POST['sSearch'] != FALSE)
		{
			$sNote_text = FALSE;
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
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_notes')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_notes n');
		if ($sType_id) $this->db->where('n.note_type_id', $sType_id);
		if ($sItem_id) $this->db->where('n.note_item_id', $sItem_id);
		if ($sNote_text) $this->db->like('n.note_text', $sNote_text, 'both');
		if ($global_search)
		{
			$this->db->like('n.note_text', $global_search, 'both');
		}
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('n.*, c.first_name, c.last_name');
		$this->db->from('crm_notes n');
		$this->db->join('crm_auth_users au', 'au.id = n.note_author', 'left');
		$this->db->join('crm_contacts c', 'c.contact_id = au.contact_id', 'left');

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
				case 'note_author':
					$this->db->order_by('c.first_name', $sort);
					break;
				case 'note_date':
					$this->db->order_by('n.note_date', $sort);
					break;
				case 'note_text':
					$this->db->order_by('n.note_text', $sort);
					break;
				default:
					$this->db->order_by('n.note_date', 'DESC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($sType_id) $this->db->where('n.note_type_id', $sType_id);
		if ($sItem_id) $this->db->where('n.note_item_id', $sItem_id);
		//if ($sCompany_name) $this->db->like('c.company_title', $sCompany_name, 'both');

		if ($global_search)
		{
			$this->db->like('n.note_text', $global_search, 'both');
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
		// ACL
		//----------------------------------------
		$can_edit = TRUE;


		//----------------------------------------
		// Loop Over all
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$trow = array();

			// Actions Block
			$actions = $row->note_id;
			if ($can_edit) $actions .= '<a href="#" class="edit note-add" data-url="notes/ajax_new_note_modal/edit/'.$row->note_id.'"></a>';

			$trow['DT_RowId']    = $row->note_id;
			$trow['note_id']     = $actions;
			$trow['note_author'] = $row->first_name . ' ' . $row->last_name;
			$trow['note_date']   = date('l, d-M-Y H:i', strtotime($row->note_date));
			$trow['note_text']   = $row->note_text;

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	public function ajax_new_note_modal()
	{
		$data = array();
		foreach ($this->db->list_fields('crm_notes') as $key => $val) $data[$val] = '';

		//----------------------------------------
		// Are we editing?
		//----------------------------------------
		if ($this->uri->segment(3) == 'edit')
		{
			$query = $this->db->select('*')->from('crm_notes')->where('note_id', $this->uri->segment(4))->get();
			foreach ($query->row() as $name => $val)
			{
				$data[ $name ] = $val;
			}
		}
		else
		{
			$data['note_type_id'] = $this->uri->segment(3);
			$data['note_item_id'] = $this->uri->segment(4);
			$data['note_item_type'] = $this->uri->segment(5);
		}

		exit( $this->load->view('notes/add_note_modal', $data, TRUE) );
	}

	// ********************************************************************************* //

	public function ajax_save_note()
	{
		$out = array('success' => 'no', 'body' => '', 'chosen' => '');

		//----------------------------------------
		// Delete?
		//----------------------------------------
		if ($this->uri->segment(3) == 'delete')
		{
			$this->db->where('note_id', $this->input->post('note_id'));
			$this->db->delete('crm_notes');
			$out['success'] = 'yes';
			exit(json_encode($out));
		}

		//----------------------------------------
		// Fields
		//----------------------------------------
		$data = array();
		$data['note_text'] = trim($this->input->post('note_text'));
		$data['note_type_id'] = $this->input->post('note_type_id');
		$data['note_item_id'] = $this->input->post('note_item_id');
		$data['note_item_type'] = $this->input->post('note_item_type');
		$data['note_author'] = $this->input->post('note_author');

		$note_id = trim($this->input->post('note_id'));
		$note_date = $this->input->post('note_date');
		$note_time = $this->input->post('note_time');

		if ($note_date == FALSE) $note_date = date('Y-m-d');
		if ($note_time == FALSE) $note_time = date('G:i');
		$data['note_date'] = $note_date . ' ' . $note_time . ':00';

		if ($data['note_author'] == FALSE) $data['note_author'] = $this->session->userdata['user_id'];

		// Check TEXT
		if ($data['note_text'] == FALSE)
		{
			$out['body'] = 'A Note text is required';
			exit(json_encode($out));
		}

		// New or Update?
		if ($note_id != FALSE)
		{
			$this->db->where('note_id', $note_id);
			$this->db->update('crm_notes', $data);
		}
		else
		{
			$this->db->insert('crm_notes', $data);
			$country_id = $this->db->insert_id();
		}

		$out['success'] = 'yes';

		exit(json_encode($out));
	}

	// ********************************************************************************* //
}

/* End of file welcome.php */
/* Location: ./application/controllers/contacts.php */