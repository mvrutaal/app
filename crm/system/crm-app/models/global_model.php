<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Global Model
 */
class global_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	// ********************************************************************************* //

	public function process_item_acl($author=0, $acl='')
	{
		// Unserialize it!
		$acl = @unserialize($acl);

		// No ACL defined? (Always Visible!)
		if ($acl == FALSE) return TRUE;

		// Author Can always see it!
		if ($author == $this->session->userdata['user_id']) return TRUE;

		//----------------------------------------
		// Exclude
		//----------------------------------------
		if (isset($acl['exclude']) == TRUE && is_array($acl['exclude']) == TRUE)
		{
			if (in_array($this->session->userdata['user_id'], $acl['exclude']) == TRUE) return FALSE;
		}

		//----------------------------------------
		// Who?
		//----------------------------------------
		if (isset($acl['who']) == FALSE OR is_array($acl['who']) == FALSE) return TRUE;
		if (empty($acl['who']) == TRUE) return FALSE;

		//----------------------------------------
		// Everyone?
		//----------------------------------------
		if (in_array('everyone', $acl['who']) == TRUE) return TRUE;

		//----------------------------------------
		// Only Original Author?
		//----------------------------------------
		if (in_array('author', $acl['who']) == TRUE)
		{
			if ($author == $this->session->userdata['user_id']) return TRUE;
			else return FALSE;
		}

		//----------------------------------------
		// Users
		//----------------------------------------
		if (in_array('users', $acl['who']) == TRUE)
		{
			if (isset($acl['users']) == FALSE) $acl['users'] = array();

			if (in_array($this->session->userdata['user_id'], $acl['users']) == TRUE) return TRUE;
		}

		//----------------------------------------
		// User Roles?
		//----------------------------------------
		if (in_array('user_roles', $acl['who']) == TRUE)
		{
			if (isset($acl['user_roles']) == FALSE) $acl['user_roles'] = array();

			if (in_array($this->session->userdata['group_id'], $acl['user_roles']) == TRUE) return TRUE;
		}

		return FALSE;
	}

	// ********************************************************************************* //


}