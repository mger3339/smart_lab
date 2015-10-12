<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - builds welcome page
	 *
	 */
	public function index()
	{
		$this->wrap_views[] = $this->load->view('welcome/index', FALSE, TRUE);
		$this->render();
	}


}