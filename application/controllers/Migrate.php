<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migrate extends CI_Controller {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Restrict database migration actions to cli requests
		if ( ! is_cli() )
		{
			redirect();
		}
		
		$this->load->library('migration');
	}


	// --------------------------------------------------------------------


	/**
	 * Default class method - runs current migration
	 *
	 */
	public function index()
	{
		if ($this->migration->current() === FALSE)
		{
			echo $this->migration->error_string();
		}
		else
		{
			echo "\nNow running the latest database schema." . PHP_EOL;
		}
	}


}