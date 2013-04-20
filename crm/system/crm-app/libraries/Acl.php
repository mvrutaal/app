<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'libraries/Zend/Acl.php';

class Acl extends Zend_Acl
{

	function __construct()
	{
		$CI = &get_instance();
		$this->acl = new Zend_Acl();
		$this->acl->deny();


		//----------------------------------------
		// Get Roles
		//----------------------------------------
		$roles = array();
		$CI->db->order_by('parent_id', 'ASC'); //Get the roles
		$query = $CI->db->get('crm_auth_roles');
		foreach ($query->result() as $row) $roles[$row->id] = $row->name;

		//----------------------------------------
		// Get Resources
		//----------------------------------------
		$resources = array();
		$CI->db->order_by('parent_id', 'ASC'); //Get the resources
		$query = $CI->db->get('crm_auth_resources');
		foreach ($query->result() as $row) $resources[$row->resource_id] = $row->name;

		//----------------------------------------
		// Get permissions
		//----------------------------------------
		$query = $CI->db->get('crm_auth_permissions'); //Get the permissions
		$permissions = $query->result();

		//----------------------------------------
		// Loop Over all Roles and Add
		//----------------------------------------
		foreach ($roles as $role) { //Add the roles to the ACL
			$role = new Zend_Acl_Role($role);
			$this->acl->addRole($role);
			/*$roles->parentId != null ?
				$this->acl->addRole($role,$roles->parentId):
				$this->acl->addRole($role);*/
		}

		//----------------------------------------
		// Get permissions
		//----------------------------------------
		foreach($resources as $resource) { //Add the resources to the ACL
			$resource = new Zend_Acl_Resource($resource);
			$this->acl->addResource($resource);
			/*$resources->parentId != null ?
				$this->acl->addResource($resource, $resources->parentId):
				$this->acl->addResource($resource);*/
		}

		//----------------------------------------
		// Assign Permissions
		//----------------------------------------
		foreach($permissions as $perms) { //Add the permissions to the ACL
			$perms->read == '1' ?
				$this->acl->allow($roles[$perms->role], $resources[$perms->resource], 'read') :
				$this->acl->deny($roles[$perms->role], $resources[$perms->resource], 'read');
			$perms->write == '1' ?
				$this->acl->allow($roles[$perms->role], $resources[$perms->resource], 'write') :
				$this->acl->deny($roles[$perms->role], $resources[$perms->resource], 'write');
			$perms->modify == '1' ?
				$this->acl->allow($roles[$perms->role], $resources[$perms->resource], 'modify') :
				$this->acl->deny($roles[$perms->role], $resources[$perms->resource], 'modify');
			$perms->publish == '1' ?
				$this->acl->allow($roles[$perms->role], $resources[$perms->resource], 'publish') :
				$this->acl->deny($roles[$perms->role], $resources[$perms->resource], 'publish');
			$perms->delete == '1' ?
				$this->acl->allow($roles[$perms->role], $resources[$perms->resource], 'delete') :
				$this->acl->deny($roles[$perms->role], $resources[$perms->resource], 'delete');
		}

		$this->acl->allow('admin'); //Change this to whatever id your adminstrators group is
	}

	/*
	 * Methods to query the ACL.
	 */
	function can_read($role, $resource) {
		return $this->acl->isAllowed($role, $resource, 'read')? TRUE : FALSE;
	}
	function can_write($role, $resource) {
		return $this->acl->isAllowed($role, $resource, 'write')? TRUE : FALSE;
	}
	function can_modify($role, $resource) {
		return $this->acl->isAllowed($role, $resource, 'modify')? TRUE : FALSE;
	}
	function can_delete($role, $resource) {
		return $this->acl->isAllowed($role, $resource, 'delete')? TRUE : FALSE;
	}
        function can_publish($role, $resource) {
		return $this->acl->isAllowed($role, $resource, 'publish')? TRUE : FALSE;
	}
}