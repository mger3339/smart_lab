<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_role_model extends CI_Model {


	/**
	 * User roles data:
	 * Given that adding / deleting user roles means
	 * modifying the code base, the user roles list is
	 * stored here as hard data rather than in the DB.
	 *
	 */
	private $roles = array(
			'admin'			=> array(
						'name'			=> 'Admin',
						'admin'			=> TRUE,
						'guest'			=> FALSE,
						'default'		=> TRUE,
			),
			'user'			=> array(
						'name'			=> 'User',
						'admin'			=> FALSE,
						'guest'			=> FALSE,
						'default'		=> TRUE,
			),
            'facilitator'	=> array(
                        'name'			=> 'Facilitator',
                        'admin'			=> FALSE,
                        'guest'			=> FALSE,
                        'default'		=> TRUE,
            ),
            'faculty'		=> array(
                        'name'			=> 'Faculty',
                        'admin'			=> FALSE,
                        'guest'			=> FALSE,
                        'default'		=> TRUE,
            ),
			'test-user'		=> array(
						'name'			=> 'Test user',
						'admin'			=> FALSE,
						'guest'			=> FALSE,
						'default'		=> FALSE,
			),
	);


	/**
	 * Return the default user roles
	 *
	 * @return	array
	 */
	public function get_default_roles()
	{
		$default_roles = array();
		
		foreach ($this->roles as $role => $attr)
		{
			if ($role['default'] === TRUE)
			{
				$default_roles[] = $role;
			}
		}
		
		return $default_roles;
	}


	/**
	 * Return the all user roles
	 *
	 * @return	array
	 */
	public function get_roles()
	{
		return $this->roles;
	}


	/**
	 * Return the all user roles as an array
	 * for a form select element
	 *
	 * @return	array
	 */
	public function get_roles_select_options()
	{
		$options = array();
		
		foreach ($this->roles as $role => $attr)
		{
			$options[$role] = $attr['name'];
		}
		
		return $options;
	}


}