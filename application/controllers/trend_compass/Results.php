<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends Trend_compass_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// set the page title
		$this->page_title[] = 'Results';
	}


	/**
	 * Default class method - build results page
	 *
	 */
	public function index()
	{
		
		
		$this->render();
	}


}