<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - display client account data
	 *
	 */
	public function index()
	{
		$this->wrap_views[] = $this->load->view('admin/account/index', $this->content, TRUE);
		$this->render();
	}


}