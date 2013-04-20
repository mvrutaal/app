<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Contacts Model
 */
class Contacts_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	// ********************************************************************************* //

	/**
	 * Get DB Fields
	 *
	 * @return object - List of db fields
	 */
	public function get_db_fields()
	{
		$data = new stdClass();

		$fields = $this->db->list_fields('crm_contacts');

		foreach ($fields as $key => $val)
		{
			$data->{$val} = '';
		}

		return $data;
	}

	// ********************************************************************************* //

	/**
	 * Get Contact from DB
	 *
	 * @param  integer $contact_id=0 - Contact ID
	 * @return mixed - FALSE when not found, db object if found
	 */
	public function get_contact($contact_id=0)
	{
		$query = $this->db->select('*')->from('crm_contacts')->where('contact_id', $contact_id)->get();

		if ($query->num_rows() == 0) return FALSE;
		else return $query->result();
	}

	// ********************************************************************************* //

	public function get_simpledt_contacts($arr=array())
	{
		if (empty($arr)) return array();

		$this->db->select('contact_id, first_name, last_name');
		$this->db->from('crm_contacts');
		$this->db->where_in('contact_id', $arr);
		$query = $this->db->get();

		return $query->result();
	}

	// ********************************************************************************* //

	public function update_company($company_id=0)
	{

	}

	// ********************************************************************************* //

	public function delete_company($company_id=0)
	{

	}

	// ********************************************************************************* //


}