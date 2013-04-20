<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CRM_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	// ********************************************************************************* //

	public function index()
	{
		$data = array();
		$data['preferences']['general_main_company'] = '';
		$data['preferences']['general_aff_companies'] = array();
		$data['preferences']['general_default_tel_cc'] = '';
		$data['preferences']['general_default_city'] = '';
		$data['preferences']['general_default_country'] = '';

		//----------------------------------------
		// Get all preferences
		//----------------------------------------
		$query = $this->db->query("SELECT preference, value FROM crm_preferences WHERE preference LIKE 'general_%' ");
		foreach ($query->result() as $row)
		{
			$val = @unserialize($row->value);
			$data['preferences'][ $row->preference ] = $val ? $val : $row->value;
		}

		//----------------------------------------
		// Grab all countries
		//----------------------------------------
		$data['countries'] = array();
		$query = $this->db->query("SELECT * FROM crm_dataset_countries ORDER BY country_label ASC");

		foreach($query->result() as $row)
		{
			$data['countries'][$row->country_id] = $row->country_label;
		}

		//----------------------------------------
		// Grab all Cities
		//----------------------------------------
		$data['cities'] = array();
		$query = $this->db->query("SELECT * FROM crm_dataset_cities ORDER BY city_label ASC");

		foreach($query->result() as $row)
		{
			$data['cities'][$row->city_id] = $row->city_label;
		}

		//----------------------------------------
		// Get all Companies
		//----------------------------------------
		$data['companies'] = array();
		$query = $this->db->select('company_id, company_title')->from('crm_companies')->order_by('company_title', 'ASC')->get();
		foreach ($query->result() as $row) $data['companies'][ $row->company_id ] = $row->company_title;

		//----------------------------------------
		// Get Users List
		//----------------------------------------
		$data['users'] = $this->ion_auth->get_users_array();

		//----------------------------------------
		// Get User Groups List
		//----------------------------------------
		$data['user_roles'] = array();
		$query = $this->db->select('*')->from('crm_auth_roles')->order_by('description', 'ASC')->get();
		foreach ($query->result() as $row)
		{
			// Group Count
			$q = $this->db->query("SELECT COUNT(*) as count FROM crm_auth_users WHERE group_id = {$row->id}");
			$count = $q->row('count');

			$row->count = $count;
			$data['user_roles'][] = $row;
		}

		//----------------------------------------
		// Get User Resources List
		//----------------------------------------
		$data['user_resources'] = array();
		$query = $this->db->select('*')->from('crm_auth_resources')->order_by('description', 'ASC')->get();
		foreach ($query->result() as $row)
		{
			$data['user_resources'][] = $row;
		}

		//----------------------------------------
		// Get User Resources List
		//----------------------------------------
		$data['notes_types'] = array();
		$this->db->select('*');
		$this->db->from('crm_notes_types nt');
		$this->db->join('crm_auth_users au', 'au.id = nt.note_type_author', 'left');
		$this->db->join('crm_contacts c', 'c.contact_id = au.contact_id', 'left');
		$query = $this->db->order_by('note_type_label', 'ASC')->get();
		foreach ($query->result() as $row)
		{
			$data['notes_types'][] = $row;
		}

		//----------------------------------------
		// Output
		//----------------------------------------
		$vData = array();
		$vData['title'] = 'Settings';
		$vData['pagetype'] = 'settings';
		$vData['content'] = $this->load->view('settings/index', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function update_settings()
	{
		//----------------------------------------
		// Preferences There?
		//----------------------------------------
		if (isset($_POST['preferences']) == TRUE)
		{
			foreach ($_POST['preferences'] as $key => $value)
			{
				// Does it already exist?
				$query = $this->db->select('pref_id')->from('crm_preferences')->where('preference', $key)->get();

				// Is it an array?
				if (is_array($value) == TRUE) $value = serialize($value);

				$this->db->set('preference', $key);
				$this->db->set('value', $value);

				if ($query->num_rows() > 0)
				{
					// Update
					$this->db->where('pref_id', $query->row('pref_id'));
					$this->db->update('crm_preferences');
				}
				else
				{
					// Insert!
					$this->db->insert('crm_preferences');
				}
			}
		}

		redirect('/settings/', 'location');
	}

	// ********************************************************************************* //

	public function acl()
	{
		$role_id = $this->uri->segment(3);

		//----------------------------------------
		// Grab the User Role
		//----------------------------------------
		$query = $this->db->select('*')->from('crm_auth_roles')->where('id', $role_id)->get();

		if ($query->num_rows() == 0)
		{
			show_error('Group cannot be found');
		}

		$data = $query->row_array();

		//----------------------------------------
		// Grab all Resources
		//----------------------------------------
		$query = $this->db->query("SELECT resource_id, description FROM crm_auth_resources ORDER BY description ASC");
		foreach($query->result() as $row) $data['resources'][$row->resource_id] = $row->description;

		//----------------------------------------
		// Grab all Permissions
		//----------------------------------------
		$data['perm'] = array();
		$query = $this->db->select('*')->from('crm_auth_permissions')->where('role', $role_id)->get();

		foreach($query->result_array() as $row)
		{
			$data['perm'][$row['resource']] = $row;
		}

		//----------------------------------------
		// Output
		//----------------------------------------
		$vData = array();
		$vData['title'] = 'Access Control List';
		$vData['pagetype'] = 'settings';
		$vData['content'] = $this->load->view('settings/acl', $data, TRUE);
		$this->load->view('layout', $vData);
	}

	// ********************************************************************************* //

	public function acl_update()
	{
		$role_id = $this->input->post('role_id');


		foreach($_POST['acl'] as $resource_id => $acl)
		{
			$query = $this->db->query("SELECT permission_id FROM crm_auth_permissions WHERE role = {$role_id} AND resource = {$resource_id}");

			$this->db->set('read', $acl['read']);
			$this->db->set('write', $acl['write']);
			$this->db->set('modify', $acl['modify']);
			$this->db->set('delete', $acl['del']);
			$this->db->set('role', $role_id);
			$this->db->set('resource', $resource_id);

			if ($query->num_rows() > 0)
			{
				$this->db->where('permission_id', $query->row('permission_id'));
				$this->db->update('crm_auth_permissions');
			}
			else
			{
				$this->db->insert('crm_auth_permissions');
			}
		}

		redirect('/settings/', 'location');
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

	private function ajax_add_user()
	{
		$vData = array();

		// What user id?
		$user_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['user_id'] = $user_id;
		$vData['username'] = '';
		$vData['contact_id'] = '';
		$vData['group_id'] = '';

		//----------------------------------------
		// Grab all groups
		//----------------------------------------
		$vData['groups'] = array();
		$query = $this->db->select('id, description')->from('crm_auth_roles')->order_by('description', 'asc')->get();
		foreach($query->result() as $row) $vData['groups'][$row->id] = $row->description;

		//----------------------------------------
		// Grab all contacts
		//----------------------------------------
		$vData['contacts'] = array();

		// What Main Company?
		$query = $this->db->select('value')->from('crm_preferences')->where('preference', 'general_main_company')->get();
		$main_company = $query->row('value');

		// What Company?
		$query = $this->db->select('value')->from('crm_preferences')->where('preference', 'general_aff_companies')->get();
		$company = unserialize($query->row('value'));
		$company[] = $main_company;

		// Grab the contacts from this company
		$query = $this->db->select('contact_id, first_name, last_name')->from('crm_contacts')->where_in('company_id', $company)->order_by('first_name', 'asc')->get();
		foreach($query->result() as $row) $vData['contacts'][ $row->contact_id ] = $row->first_name . ' ' . $row->last_name;

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($user_id > 0)
		{
			$user = $this->ion_auth->get_user_array($user_id);

			$vData['username'] = $user['username'];
			$vData['contact_id'] = $user['contact_id'];
			$vData['group_id'] = $user['group_id'];
		}

		$this->load->view('settings/ajax/add_user', $vData);
	}

	// ********************************************************************************* //

	private function ajax_add_user_role()
	{
		$vData = array();

		// What group id?
		$role_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['role_id'] = $role_id;
		$vData['name'] = '';
		$vData['description'] = '';

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($role_id > 0)
		{
			$query = $this->db->select('*')->from('crm_auth_roles')->where('id', $role_id)->get();

			$vData['name'] = $query->row('name');
			$vData['description'] = $query->row('description');
		}

		$this->load->view('settings/ajax/add_user_role', $vData);
	}

	// ********************************************************************************* //

	private function ajax_add_access_resource()
	{
		$vData = array();

		// What group id?
		$resource_id = $this->uri->segment(4);

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['resource_id'] = $resource_id;
		$vData['name'] = '';
		$vData['description'] = '';

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($resource_id > 0)
		{
			$query = $this->db->select('*')->from('crm_auth_resources')->where('resource_id', $resource_id)->get();

			$vData['name'] = $query->row('name');
			$vData['description'] = $query->row('description');
		}

		$this->load->view('settings/ajax/add_access_resource', $vData);
	}

	// ********************************************************************************* //

	private function ajax_add_note_type()
	{
		$vData = array();

		// What group id?
		$note_type_id = $this->uri->segment(4);

		$vData['modules'] = array();
		$vData['modules']['contacts'] = 'Contacts';
		$vData['modules']['companies'] = 'Companies';

		//----------------------------------------
		// Prepare Vars
		//----------------------------------------
		$vData['note_type_id'] = $note_type_id;
		$vData['note_type_label'] = '';
		$vData['note_type_module'] = '';

		//----------------------------------------
		// Get Users List
		//----------------------------------------
		$vData['users'] = array();
		$users = $this->ion_auth->get_users_array();

		foreach ($users as $user)
		{
			$vData['users'][ $user['group_description'] ][ $user['id'] ] = $user['username'];
		}

		//----------------------------------------
		// Grab all groups
		//----------------------------------------
		$vData['user_roles'] = array();
		$query = $this->db->select('id, description')->from('crm_auth_roles')->order_by('description', 'asc')->get();
		foreach($query->result() as $row) $vData['user_roles'][$row->id] = $row->description;

		//----------------------------------------
		// ACL
		//----------------------------------------
		$vData['acl']['who'] = array('everyone');
		$vData['acl']['exclude'] = array();
		$vData['acl']['users'] = array();
		$vData['acl']['user_roles'] = array();

		//----------------------------------------
		// Are We Editing?
		//----------------------------------------
		if ($note_type_id > 0)
		{
			$query = $this->db->select('*')->from('crm_notes_types')->where('note_type_id', $note_type_id)->get();

			$vData['note_type_label'] = $query->row('note_type_label');
			$vData['note_type_module'] = $query->row('note_type_module');

			$acl = @unserialize($query->row('note_type_acl'));
			if ($acl != FALSE)
			{
				$vData['acl'] = array_merge($vData['acl'], $acl);
			}
		}

		$this->load->view('settings/ajax/add_note_type', $vData);
	}

	// ********************************************************************************* //

	private function ajax_update_user()
	{
		$user_id = $this->input->post('user_id');
		$contact_id = $this->input->post('contact_id');

		//----------------------------------------
		// Do we need to delete?
		//----------------------------------------
		$delete = $this->uri->segment(4);
		if ($delete == 'delete')
		{
			$this->db->query("DELETE FROM crm_auth_users WHERE id = ".$user_id);
			exit();
		}

		// Data Array
		$data = array();
		$data['username'] = ucfirst($this->input->post('username'));
		$data['password'] = $this->input->post('password');
		$data['group_id'] = $this->input->post('group_id');
		$data['email'] = '';

		// Grab his Email
		$query = $this->db->select('email_work, email_personal')->from('crm_contacts')->where('contact_id', $contact_id)->get();
		if ($query->num_rows() > 0) $data['email'] = $query->row('email_work') ? $query->row('email_work') : $query->row('email_personal');

		// Do we need to register or update?
		if ($user_id == FALSE)
		{
			// Create the vars
			extract($data);

			// We can pass the group_id here! jeej!
			$additional_data = array('group_id' => $group_id);

			// Register The User!
			$user_id = $this->ion_auth->register($username, $password, $email, $additional_data, false);
		}
		else
		{
			if ($data['password'] == FALSE) unset($data['password']);
			$result = $this->ion_auth->update_user($user_id, $data);
		}

		// Update the contact_id column
		$this->db->set('contact_id', $contact_id);
		$this->db->where('id', $user_id);
		$this->db->update('crm_auth_users');

		exit();
	}

	// ********************************************************************************* //

	private function ajax_update_user_role()
	{
		//----------------------------------------
		// Do we need to delete?
		//----------------------------------------
		$delete = $this->uri->segment(4);
		if ($delete == 'delete')
		{
			$this->db->query("DELETE FROM crm_auth_roles WHERE id = ".$this->input->post('role_id'));
			$this->db->query("DELETE FROM crm_auth_permissions WHERE role = ".$this->input->post('role_id'));
			exit();
		}

		//----------------------------------------
		// Add/Update
		//----------------------------------------
		$this->db->set('name', $this->input->post('name'));
		$this->db->set('description', $this->input->post('description'));

		// New or Update?
		if ($this->input->post('role_id') != FALSE)
		{
			$this->db->where('id', $this->input->post('role_id'));
			$this->db->update('crm_auth_roles');
			$role_id = $this->input->post('group_id');
		}
		else
		{
			$this->db->insert('crm_auth_roles');
			$role_id = $this->db->insert_id();
		}

		exit();
	}

	// ********************************************************************************* //

	private function ajax_update_access_resource()
	{
		$this->db->set('name', $this->input->post('name'));
		$this->db->set('description', $this->input->post('description'));

		// New or Update?
		if ($this->input->post('resource_id') != FALSE)
		{
			$this->db->where('resource_id', $this->input->post('resource_id'));
			$this->db->update('crm_auth_resources');
			$resource_id = $this->input->post('resource_id');
		}
		else
		{
			$this->db->insert('crm_auth_resources');
			$resource_id = $this->db->insert_id();
		}

		exit();
	}

	// ********************************************************************************* //

	private function ajax_update_note_type()
	{
		$this->db->set('note_type_label', $this->input->post('note_type_label'));
		$this->db->set('note_type_module', $this->input->post('note_type_module'));
		$this->db->set('note_type_acl', serialize($this->input->post('acl')));

		// New or Update?
		if ($this->input->post('note_type_id') != FALSE)
		{
			$this->db->where('note_type_id', $this->input->post('note_type_id'));
			$this->db->update('crm_notes_types');
			$note_type_id = $this->input->post('note_type_id');
		}
		else
		{
			$this->db->set('note_type_author', $this->session->userdata['user_id']);
			$this->db->insert('crm_notes_types');
			$note_type_id = $this->db->insert_id();
		}

		exit();
	}

	// ********************************************************************************* //


}