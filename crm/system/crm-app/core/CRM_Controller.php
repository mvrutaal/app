<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Code here is run before ALL controllers
class CRM_Controller extends CI_Controller {

	public function __construct()
	{
		setlocale(LC_ALL, "dutch_NETHERLANDS");
		
		parent::__construct();
		
		if (!$this->ion_auth->logged_in())
		{
			if ($this->uri->segment(1) != 'auth') redirect('auth/login');
		}
		else
		{
			$this->session->set_userdata($this->ion_auth->get_user());

			$query = $this->db->select('first_name, last_name')->from('crm_contacts')->where('contact_id', $this->session->userdata('contact_id'))->get();

			$this->session->set_userdata(array('first_name' => $query->row('first_name'), 'last_name' => $query->row('last_name')));
		}

		//----------------------------------------
		// Get all preferences
		//----------------------------------------
		$query = $this->db->query("SELECT preference, value FROM crm_preferences WHERE preference LIKE 'general_%' ");
		foreach ($query->result() as $row)
		{
			$val = @unserialize($row->value);
			$this->config->set_item($row->preference, (($val == TRUE) ? $val : $row->value));
		}
		
		
	}

	// ********************************************************************************* //

}

/**
 * Returns the CI object.
 *
 * Example: ci()->db->get('table');
 *
 * @staticvar	object	$ci
 * @return		object
 */
function ci()
{
	return get_instance();
}

