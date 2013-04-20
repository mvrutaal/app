<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends CRM_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->model('groups_model');
	}

	// ********************************************************************************* //

	public function index()
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (!$this->acl->can_read($this->session->userdata['group'], 'groups')) {
			show_error("You do not have permissions to read this resource");
		}

		$data = array();

		//----------------------------------------
		// Columns
		//----------------------------------------
		$data['standard_cols']['group_name']	= array('name' => 'Group Name', 'sortable' => 'true');
		$data['standard_cols']['group_author']		= array('name' => 'Author', 'sortable' => 'true');
		$data['standard_cols']['group_item_types']	= array('name' => 'Items Type', 'sortable' => 'true');
		$data['standard_cols']['group_total_items']	= array('name' => 'Total Items', 'sortable' => 'true');

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
		$vData['title'] = 'Groups';
		$vData['pagetype'] = 'groups';
		$vData['content'] = $this->load->view('groups/index', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function view()
	{
		//----------------------------------------
		// Get all Groups data
		//----------------------------------------
		$group_id = $this->uri->segment(3);
		$query = $this->db->select('*')->from('crm_groups')->where('group_id', $group_id)->get();

		if ($query->num_rows() == 0)
		{
			show_error('Group cannot be found');
		}

		$data = $query->row_array();

		if ($data['group_item_types'] == 'contacts')
		{
			//----------------------------------------
			// Contacts
			//----------------------------------------
			$data['contacts'] = array();

			$this->db->select('c.*');
			$this->db->from('crm_groups_items gi');
			$this->db->join('crm_contacts c', 'c.contact_id = gi.contact_id', 'left');
			$this->db->where('gi.group_id', $group_id);
			$query = $this->db->get();

			$data['contacts'] = $query->result();
		}
		elseif ($data['group_item_types'] == 'companies')
		{
			//----------------------------------------
			// Companies
			//----------------------------------------
			$data['companies'] = array();

			$this->db->select('c.*, ct.company_type_label');
			$this->db->from('crm_groups_items gi');
			$this->db->join('crm_companies c', 'c.company_id = gi.company_id', 'left');
			$this->db->join('crm_dataset_company_types ct', 'ct.company_type_id = c.company_type_id', 'left');
			$this->db->where('gi.group_id', $group_id);
			$query = $this->db->get();

			$data['companies'] = $query->result();
		}



		$vData = array();
		$vData['title'] = $data['group_name'];
		$vData['pagetype'] = 'groups';
		$vData['content'] = $this->load->view('groups/view', $data, TRUE);
		$this->load->view('layout', $vData);

	}

	// ********************************************************************************* //

	public function add($group_id=0)
	{
		//----------------------------------------
		// Do we have access?
		//----------------------------------------
		if (! $this->acl->can_write($this->session->userdata['group'], 'groups'))
		{
			show_error('Access Denied!');
		}

		$data['item_types'] = array();
		$data['item_types']['contacts'] = 'Contacts Only';
		$data['item_types']['companies'] = 'Companies Only';

		//----------------------------------------
		// Grab the default fields
		//----------------------------------------
		$data['i'] = $this->groups_model->get_db_fields();
		$data['i']->group_item_types = 'contacts';

		//----------------------------------------
		// Get Users List
		//----------------------------------------
		$data['users'] = array();
		$users = $this->ion_auth->get_users_array();

		foreach ($users as $user)
		{
			$data['users'][ $user['group_description'] ][ $user['id'] ] = $user['username'];
		}

		//----------------------------------------
		// Grab all groups
		//----------------------------------------
		$data['user_roles'] = array();
		$query = $this->db->select('id, description')->from('crm_auth_roles')->order_by('description', 'asc')->get();
		foreach($query->result() as $row) $data['user_roles'][$row->id] = $row->description;

		//----------------------------------------
		// ACL
		//----------------------------------------
		$data['acl']['who'] = array('everyone');
		$data['acl']['exclude'] = array();
		$data['acl']['users'] = array();
		$data['acl']['user_roles'] = array();

		//----------------------------------------
		// If we are editing, grab group
		//----------------------------------------
		if ($group_id > 0)
		{
			$data['i'] = $this->groups_model->get_group($group_id);

			if ($data['i'] == FALSE) show_error('Group not found!');

			$acl = @unserialize($data['i']->group_acl);
			if ($acl != FALSE)
			{
				$data['acl'] = array_merge($data['acl'], $acl);
			}
		}

		//----------------------------------------
		// Grab Linked Items
		//----------------------------------------
		$data['i']->linked_items = array();

		if ($group_id > 0)
		{
			$items = $this->groups_model->get_group_items($group_id);

			if ($data['i']->group_item_types == 'contacts')
			{
				$this->load->model('contacts_model');

				$data['i']->linked_items = $this->contacts_model->get_simpledt_contacts($items);
			}
			elseif ($data['i']->group_item_types == 'companies')
			{
				$this->load->model('companies_model');

				$data['i']->linked_items = $this->companies_model->get_simpledt_companies($items);
			}
		}

		//----------------------------------------
		// Cols
		//----------------------------------------
		$data['dtcols'] = array();
		$data['dtcols']['companies']['company_title']	= array('name' => 'Company Name', 'sortable' => 'true');

		$data['dtcols']['contacts']['first_name']	= array('name' => 'First Name', 'sortable' => 'true');
		$data['dtcols']['contacts']['last_name']	= array('name' => 'Last Name', 'sortable' => 'true');



		$vData = array();
		$vData['title'] = ($group_id == 0) ? 'New Group' : 'Edit Group';
		$vData['pagetype'] = 'groups';
		$vData['content'] = $this->load->view('groups/add_edit', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function edit()
	{
		if (! $this->acl->can_modify($this->session->userdata['group'], 'groups'))
		{
			show_error('Access Denied!');
		}

		$this->add( $this->uri->segment(3) );
	}

	// ********************************************************************************* //

	public function update()
	{
		$this->load->helper('url');

		$this->groups_model->update_group();

		redirect('/groups/', 'location');
	}

	// ********************************************************************************* //

	public function delete()
	{
		if (! $this->acl->can_delete($this->session->userdata['group'], 'groups'))
		{
			show_error('Access Denied!');
		}

		// What is the group id?
		$group_id = $this->uri->segment(3);

		// Delete the group
		$this->groups_model->delete_group($group_id);

		redirect('/groups/', 'location');
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
		$sGroup_name = ($this->input->post('group_name') != FALSE) ? $_POST['group_name'] : FALSE;
		$sAuthors = ($this->input->post('authors') != FALSE) ? $_POST['authors'] : FALSE;
		$sItem_types = ($this->input->post('item_types') != FALSE) ? $_POST['item_types'] : FALSE;

		//----------------------------------------
		// Prepare Data Array
		//----------------------------------------
		$data = array();
		$data['aaData'] = array();
		$data['iTotalDisplayRecords'] = 0; // Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned in this result set)
		$data['sEcho'] = $this->input->get_post('sEcho');

		// Total records, before filtering (i.e. the total number of records in the database)
		$query = $this->db->select('COUNT(*) as total_records', FALSE)->from('crm_groups')->get();
		$data['iTotalRecords'] = $query->row('total_records');

		//----------------------------------------
		// Total after filter
		//----------------------------------------
		$this->db->select('COUNT(*) as total_records', FALSE);
		$this->db->from('crm_groups g');
		if ($sGroup_name) $this->db->like('g.group_name', $sGroup_name, 'both');
		if ($sAuthors) $this->db->where_in('g.group_author', $sAuthors);
		if ($sItem_types) $this->db->where_in('g.group_item_types', $sItem_types);
		$query = $this->db->get();
		$data['iTotalDisplayRecords'] = $query->row('total_records');
		$query->free_result();

		//----------------------------------------
		// Real Query
		//----------------------------------------
		$this->db->select('g.*, c.first_name, c.last_name');
		$this->db->from('crm_groups g');
		$this->db->join('crm_auth_users au', 'au.id = g.group_author', 'left');
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
				case 'group_name':
					$this->db->order_by('g.group_name', $sort);
					break;
				case 'group_author':
					$this->db->order_by('c.first_name', $sort);
					break;
				case 'group_item_types':
					$this->db->order_by('g.group_item_types', $sort);
					break;
				case 'group_total_items':
					$this->db->order_by('g.group_total_items', $sort);
					break;
				default:
					$this->db->order_by('g.group_name', 'ASC');
					break;
			}

		}

		//----------------------------------------
		// WHERE/LIKE
		//----------------------------------------
		if ($sGroup_name) $this->db->like('g.group_name', $sGroup_name, 'both');
		if ($sAuthors) $this->db->where_in('g.group_author', $sAuthors);
		if ($sItem_types) $this->db->where_in('g.group_item_types', $sItem_types);

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
		$can_edit = $this->acl->can_modify($this->session->userdata['group'], 'groups');

		//----------------------------------------
		// Loop Over all
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$trow = array();

			// Do we have access to see it?
			if ($this->global_model->process_item_acl($row->group_author, $row->group_acl) == FALSE)
			{
				$data['iTotalDisplayRecords']--;
				continue;
			}

			// Actions Block
			$actions = '<a href="'.site_url('groups/view/'.$row->group_id).'">'.$row->group_id.'</a>';
			if ($can_edit) $actions .= '<a href="'.site_url('groups/edit/'.$row->group_id).'" class="edit"></a>';

			$trow['group_id']    = $actions;
			$trow['group_name']  = $row->group_name;
			$trow['group_total_items'] = $row->group_total_items;
			$trow['group_author'] = $row->first_name . ' ' . $row->last_name;
			$trow['group_item_types'] = ucfirst($row->group_item_types);

			// Add to data
			$data['aaData'][] = $trow;
		}

		//print_r($this->db->queries);

		exit(json_encode($data));
	}

	// ********************************************************************************* //

	public function ajax_get_groups_dt()
	{
		$data = array();
		$data['item_type']	= $this->uri->segment(3);
		$data['groups'] 	= $this->groups_model->get_simpledt_groups($data['item_type']);
		$data['first_col']	= 'radio';

		$out = $this->load->view('groups/ajax/simple_dt', $data, TRUE);

		exit($out);
	}

	// ********************************************************************************* //

	public function ajax_filter_contacts()
	{
		$this->db->select('contact_id, first_name, last_name')->from('crm_contacts');
		if ($this->input->get_post('keywords') != FALSE && $this->input->get_post('keywords') != 'EMPTY')
		{
			$this->db->like('first_name', $this->input->get_post('keywords'), 'both');
			$this->db->or_like('last_name', $this->input->get_post('keywords'), 'both');
		}
		$this->db->order_by('last_name', 'ASC');
		$this->db->order_by('first_name', 'ASC');
		$this->db->limit(100);
		$query = $this->db->get();

		$list = '';
		foreach($query->result() as $row)
		{
			$list .= '<li class="PlayaItem"><a href="#" rel="'.$row->contact_id.'"><span>&bull;</span>'.$row->last_name.', '.$row->first_name.'</a></li>';
		}

		exit($list);
	}

	// ********************************************************************************* //

	public function ajax_add_items_to_group()
	{
		$group_id = $this->input->get_post('group_id');
		$ids = $_POST['ids'];

		if ($group_id == FALSE) exit('MISSING GROUP ID');

		// Get the group
		$group = $this->groups_model->get_group($group_id);
		$this->groups_model->items_to_group($group_id, $group->group_item_types, $ids);
	}

	// ********************************************************************************* //
}

/* End of file welcome.php */
/* Location: ./application/controllers/contacts.php */