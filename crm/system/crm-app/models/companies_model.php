<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Companies Model
 */
class Companies_model extends CI_Model
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

		$fields = $this->db->list_fields('crm_companies');

		foreach ($fields as $key => $val)
		{
			$data->{$val} = '';
		}

		return $data;
	}

	// ********************************************************************************* //

	/**
	 * Get Company from DB
	 *
	 * @param  integer $company_id=0 - Company ID
	 * @return mixed - FALSE when not found, db object if found
	 */
	public function get_company($company_id=0)
	{
		$query = $this->db->select('*')->from('crm_companies')->where('company_id', $company_id)->get();

		if ($query->num_rows() == 0) return FALSE;
		else return $query->row();
	}

	// ********************************************************************************* //

	public function get_simpledt_companies($arr=array())
	{
		if (empty($arr)) return array();

		$this->db->select('company_title, company_id, company_tel_number');
		$this->db->from('crm_companies');
		$this->db->where_in('company_id', $arr);
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