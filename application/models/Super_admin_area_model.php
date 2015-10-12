<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Super_admin_area_model extends CI_Model {


	/**
	 * Super admin areas data:
	 * Given that adding / deleting super admin areas means
	 * modifying the code base, the super admin areas list
	 * is stored here as hard data rather than in the DB.
	 *
	 */
	 private $areas = array(
	 	'clients'		=> array(
	 								'title'			=> 'Clients',
	 								'is_admin'		=> FALSE,
	 	),
	 	'users'			=> array(
	 								'title'			=> 'Super admin users',
	 								'is_admin'		=> TRUE,
	 	),
	 	'my-account'	=> array(
	 								'title'			=> 'My account',
	 								'is_admin'		=> FALSE,
	 	),
	 );


	/**
	 * Return the super admin areas data
	 *
	 * @return	array
	 */
	public function get_all()
	{
		return $this->areas;
	}


}