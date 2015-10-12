<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Super_admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - redirects to default super admin area
	 *
	 */
	public function index()
	{
		redirect('super-admin/' . key($this->super_admin_areas));
	}


}