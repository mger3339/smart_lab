<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Password_reset_request_model extends Smartlab_model {


	/**
	 * Internal vars
	 */
	private $_reset_request_time_window = 86400; // 24 hours


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


	/**
	 * Model observers
	 */
	public $before_create		= array('set_client_id');
	public $before_get			= array('where_client_id');


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// purge out-of-date password reset requests
		$this->purge_reset_requests();
	}


	/**
	 * Check whether a password reset has been requested
	 * within the reset request time window
	 *
	 * @param	string
	 * @return	bool
	 */
	public function password_reset_requested($email)
	{
		$num_rows = $this->where_client_id()->count_by(array(
			'email'				=> $email,
			'request_time >'	=> time() - $this->_reset_request_time_window,
		));
		
		if ( $num_rows > 0 )
		{
			return TRUE;
		}
		
		return FALSE;
	}


	/**
	 * Register a password reset request
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	int or FALSE
	 */
	public function register_password_reset_request($email, $request_ip, $request_hash)
	{
		$data = array(
			'email'			=> $email,
			'request_ip'	=> $request_ip,
			'request_time'	=> time(),
			'request_hash'	=> $request_hash,
		);
		
		return $this->insert($data, TRUE);
	}


	/**
	 * Validate a password reset request
	 *
	 * @param	string
	 * @return	bool
	 */
	public function validate_password_reset_request($request_hash)
	{
		$num_rows = $this->where_client_id()->count_by(array(
			'request_hash'		=> $request_hash,
		));
		
		if ( $num_rows > 0 )
		{
			return TRUE;
		}
		
		return FALSE;
	}


	/**
	 * Purge out-of-date password reset requests
	 *
	 * @return	void
	 */
	private function purge_reset_requests()
	{
		$this->delete_by(array(
			'request_time <'	=> time() - $this->_reset_request_time_window,
		));
	}


}