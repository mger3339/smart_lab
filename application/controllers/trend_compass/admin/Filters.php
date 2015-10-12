<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filters extends Trend_compass_context {


	/**
	 * Declare admin-only zone
	 */
	public $admin_area = TRUE;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// set the page title
		$this->page_title[] = 'Data filters';
	}


	/**
	 * Default class method
	 * - build admin filters CRUD page
	 *
	 */
	public function index()
	{
		
		
		$this->render();
	}


}