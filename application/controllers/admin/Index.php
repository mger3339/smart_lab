<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - redirects to default client admin area
	 *
	 */
	public function index()
	{
		redirect('admin/' . key($this->admin_areas));
	}


}