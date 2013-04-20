<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Groups Model
 */
class Groups_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	// ********************************************************************************* //

	/**
	 * Get Groups DB Fields
	 *
	 * @return object - List of db fields
	 */
	public function get_db_fields()
	{
		$data = new stdClass();

		$fields = $this->db->list_fields('crm_groups');

		foreach ($fields as $key => $val)
		{
			$data->{$val} = '';
		}

		return $data;
	}

	// ********************************************************************************* //

	/**
	 * Get Group from DB
	 *
	 * @param  integer $group_id=0 - Group ID
	 * @return mixed - FALSE when not found, db object if found
	 */
	public function get_group($group_id=0)
	{
		$query = $this->db->select('*')->from('crm_groups')->where('group_id', $group_id)->get();

		if ($query->num_rows() == 0) return FALSE;
		else return $query->row();
	}

	// ********************************************************************************* //

	/**
	 * Get Groups from DB
	 *
	 * @return mixed - FALSE when not found, db object if found
	 */
	public function get_groups($item_types=FALSE)
	{
		$this->db->select('*');
		$this->db->from('crm_groups');
		if ($item_types != FALSE) $this->db->where('group_item_types', $item_types);
		$this->db->order_by('group_name', 'ASC');
		$query = $this->db->get();

		// Any Results?
		if ($query->num_rows() == 0) return FALSE;

		$groups = array();

		// Loop Party
		foreach ($query->result() as $row)
		{
			if ($this->global_model->process_item_acl($row->group_author, $row->group_acl) == FALSE) continue;

			$groups[] = $row;
		}

		$query->free_result();

		return $groups;
	}

	// ********************************************************************************* //

	/**
	 * Get Linked Group Items
	 * @param  integer $group_id=0 - The Group ID
	 * @return array
	 */
	public function get_group_items($group_id=0)
	{
		$items = array();

		// Grab the group
		$group = $this->get_group($group_id);
		if ($group == FALSE) return $items;

		// Group all group items
		$query = $this->db->select('*')->from('crm_groups_items')->where('group_id', $group_id)->get();

		if ($query->num_rows() == 0) return $items;

		//----------------------------------------
		// What is the ID Column?
		//----------------------------------------
		switch ($group->group_item_types)
		{
			case 'contacts':
				$id_col = 'contact_id';
				break;
			case 'companies':
				$id_col = 'company_id';
				break;
			default:
				return $items;
		}

		//----------------------------------------
		// Loop over all items
		//----------------------------------------
		foreach ($query->result() as $row)
		{
			$items[] = $row->{$id_col};
		}

		// Resources are not free
		$query->free_result();

		return $items;
	}

	// ********************************************************************************* //

	/**
	 * Get groups linked to item
	 *
	 * @return mixed - FALSE when not found, db object if found
	 */
	public function get_linked_groups($contact_id=0, $company_id=0)
	{
		$items = array();

		$this->db->select('group_id');
		$this->db->from('crm_groups_items');
		if ($contact_id !=FALSE) $this->db->where('contact_id', $contact_id);
		if ($company_id !=FALSE) $this->db->where('company_id', $company_id);
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			$items[] = $row->group_id;
		}

		// Resources are not free
		$query->free_result();

		return $items;
	}

	// ********************************************************************************* //

	public function get_simpledt_groups($item_type=FALSE, $arr=array())
	{
		$this->db->select('group_id, group_name, group_total_items');
		$this->db->from('crm_groups');
		if ($item_type != FALSE) $this->db->where('group_item_types', $item_type);
		if (empty($arr) == FALSE) $this->db->where_in('contact_id', $arr);
		$this->db->order_by('group_name', 'ASC');
		$query = $this->db->get();

		return $query->result();
	}

	// ********************************************************************************* //

	public function update_group()
	{
		$this->db->set('group_name', $this->input->post('group_name'));
		$this->db->set('group_item_types', $this->input->post('group_item_types'));
		$this->db->set('group_acl', serialize($this->input->post('acl')));

		if ($this->input->post('group_id') != FALSE)
		{
			$this->db->where('group_id', $this->input->post('group_id'));
			$this->db->update('crm_groups');
			$group_id = $this->input->post('group_id');
		}
		else
		{
			$this->db->set('group_author', $this->session->userdata['user_id']);
			$this->db->insert('crm_groups');
			$group_id = $this->db->insert_id();
		}

		$this->items_to_group($group_id);
	}

	// ********************************************************************************* //

	public function delete_group($group_id=0)
	{
		//----------------------------------------
		// CRM_GROUPS_ITEMS
		//----------------------------------------
		$this->db->where('group_id', $group_id);
		$this->db->delete('crm_groups_items');

		//----------------------------------------
		// CRM_GROUPS
		//----------------------------------------
		$this->db->where('group_id', $group_id);
		$this->db->delete('crm_groups');
	}

	// ********************************************************************************* //

	public function items_to_group($group_id, $item_type=FALSE, $post_items=array())
	{
		// What item type?
		if ($item_type == FALSE) $item_type = $this->input->post('group_item_types');

		// What column?
		$column = ($item_type == 'contacts') ? 'contact_id' : 'company_id';

		// Any Post Items?
		if (empty($post_items) == TRUE) $post_items = ($item_type == 'contacts') ? $this->input->post('linked_contacts') : $this->input->post('linked_companies');

		$items = array();

		// Grab them all
		$query = $this->db->select('*')->from('crm_groups_items')->where('group_id', $group_id)->get();

		// Loop over them all
		foreach ($query->result() as $row)
		{
			$items[ $row->{$column} ] = $row;
		}

		// Loop over all posted items
		foreach ($post_items as $item_id)
		{
			// Already in DB?
			if (isset($items[ $item_id ]) == TRUE)
			{
				unset($items[ $item_id ]);
			}
			else
			{
				// Add it!
				$this->db->set($column, $item_id);
				$this->db->set('group_id', $group_id);
				$this->db->insert('crm_groups_items');
			}
		}

		// Delete the rest
		foreach ($items as $item_id => $row)
		{
			$this->db->where('rel_id', $row->rel_id);
			$this->db->delete('crm_groups_items');
		}

		// Get total
		$this->calculate_totals($group_id);
	}

	// ********************************************************************************* //

	public function group_to_items($item_id, $item_type=FALSE, $groups=array())
	{
		// What item type?
		if ($item_type == FALSE) $item_type = $this->input->post('group_item_types');

		// What column?
		$column = ($item_type == 'contacts') ? 'contact_id' : 'company_id';

		$items = array();

		// Grab them all
		$query = $this->db->select('*')->from('crm_groups_items')->where($column, $item_id)->get();

		// Loop over them all
		foreach ($query->result() as $row)
		{
			$items[ $row->group_id ] = $row;
		}

		// Loop over all posted items
		foreach ($groups as $group_id)
		{
			// Already in DB?
			if (isset($items[ $group_id ]) == TRUE)
			{
				unset($items[ $group_id ]);
			}
			else
			{
				// Add it!
				$this->db->set($column, $item_id);
				$this->db->set('group_id', $group_id);
				$this->db->insert('crm_groups_items');
			}
		}

		// Delete the rest
		foreach ($items as $group_id => $row)
		{
			$this->db->where('rel_id', $row->rel_id);
			$this->db->delete('crm_groups_items');
		}

		// Calculate Stats
		foreach ($query->result() as $row)
		{
			$this->calculate_totals($row->group_id);
		}

	}

	// ********************************************************************************* //

	public function calculate_totals($group_id=0)
	{
		// Get total
		$query = $this->db->select('COUNT(*) as total')->from('crm_groups_items')->where('group_id', $group_id)->get();

		// Set total
		$this->db->set('group_total_items', $query->row('total'));
		$this->db->where('group_id', $group_id);
		$this->db->update('crm_groups');
	}

	// ********************************************************************************* //

}