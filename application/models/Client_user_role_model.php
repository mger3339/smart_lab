<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_user_role_model extends Smartlab_model {


	/**
	 * Set a client's user roles
	 *
	 * @param	int
	 * @param	array
	 * @return	void
	 */
	public function set_client_user_roles($client_id, $client_user_roles)
	{
		if ( ! is_array($client_user_roles))
		{
			$client_user_roles = array();
		}
		
		// get the base user roles
		$this->load->model('user_role_model');
		$user_roles = $this->user_role_model->get_roles();
		
		// get the client's current user roles
		$current_client_user_roles = $this->get_many_by('client_id', $client_id);
		$current_client_user_roles_array = array();
		
		foreach ($current_client_user_roles as $role)
		{
			$current_client_user_roles_array[] = $role->user_role;
		}
		
		foreach ($user_roles as $role => $attr)
		{
			// remove any unselected user roles
			if ( ! in_array($role, $client_user_roles) && $attr['default'] !== TRUE)
			{
				$this->delete_by(array(
					'client_id'		=> $client_id,
					'user_role'		=> $role,
				));
			}
			
			// add any selected and missing in DB user roles
			if ( ! in_array($role, $current_client_user_roles_array) && in_array($role, $client_user_roles))
			{
				$this->insert(array(
					'client_id'		=> $client_id,
					'user_role'		=> $role,
				), TRUE);
			}
		}
	}


}