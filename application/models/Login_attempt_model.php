<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_attempt_model extends Smartlab_model {


	/**
	 * Model internal vars
	 */
	protected $_max_login_attempts = 5;
	protected $_min_user_lock_time = 86400; // 24 hours


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
		
		// purge old login attempts
		$this->purge_login_attempts();
	}


	/**
	 * Register a login attempt,
	 * and return TRUE if max login attempts has been reached
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function register_login_attempt($login, $login_ip)
	{
		$data = array(
			'login'			=> $login,
			'login_ip'		=> $login_ip,
			'login_time'	=> time(),
		);
		
		$this->insert($data, TRUE);
		
		$login_attempts = $this->where_client_id()->count_by(array(
			'login'			=> $login,
			'login_ip'		=> $login_ip,
			'login_time >'	=> time() - $this->_min_user_lock_time,
		));
		
		if ($login_attempts >= $this->_max_login_attempts)
		{
			return TRUE;
		}
		
		return FALSE;
	}


	/**
	 * Detrmine whether a user can be unlocked
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function user_unlock($login, $login_ip)
	{
		$login_attempts = $this->where_client_id()->count_by(array(
			'login'			=> $login,
			'login_ip'		=> $login_ip,
			'login_time >'	=> time() - $this->_min_user_lock_time,
		));
		
		if ($login_attempts < $this->_max_login_attempts)
		{
			return TRUE;
		}
		
		return FALSE;
	}


	/**
	 * Purge out-of-date login attempts
	 *
	 * @return	void
	 */
	private function purge_login_attempts()
	{
		$this->delete_by(array(
			'login_time <'	=> time() - $this->_min_user_lock_time,
		));
	}


}