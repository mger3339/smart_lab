<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Trend_compass_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - build questionnaire page
	 *
	 */
	public function index()
	{
		
		
		$this->render();
	}


}