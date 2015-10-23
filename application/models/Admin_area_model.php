<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_area_model extends CI_Model {


	/**
	 * Client admin areas data:
	 * Given that adding / deleting client admin areas means
	 * modifying the code base, the client admin areas list
	 * is stored here as hard data rather than in the DB.
	 *
	 */
	 private $areas = array(
            'users'			=> array(
                'title'			=> 'Users',
            ),
            'applications'	=> array(
                'title'			=> 'Applications',
            ),
            'snapshots'		=> array(
                 'title'		=> 'Snapshots',
            ),
            'groups'		=> array(
                 'title'		=> 'Groups',
            ),
            'settings'		=> array(
                'title'			=> 'Settings',
            ),
     );


	/**
	 * Return the client admin areas data
	 *
	 * @return	array
	 */
	public function get_all()
	{
		return $this->areas;
	}

}