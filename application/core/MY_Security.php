<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Core Security Class override
 *
 */
class MY_Security extends CI_Security {


	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Show CSRF Error - now does redirect
	 *
	 * @return	void
	 */
	public function csrf_show_error()
	{
		header('Location: ' . $_SERVER['REQUEST_URI']);
		die();
	}


}