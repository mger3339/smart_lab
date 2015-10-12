<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* --------------------------------------------------------------
 *
 * SMARTLAB MODEL
 * Contains commonly used model vars & methods for
 * identifying and dealing with CLIENTS, USERS
 * and APPLICATION instances.
 * 
 * ------------------------------------------------------------ */

class Smartlab_model extends MY_Model {


	/* --------------------------------------------------------------
     * SMARTLAB MODEL SPECIFIC VARIABLES
     * ------------------------------------------------------------ */


	protected $_CI						= NULL;
	protected $_client_id				= NULL;
	protected $_user_id					= NULL;
	protected $_client_application_id	= NULL;
	protected $_active_only				= TRUE;


    /**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->_CI =& get_instance();
	}


	/* --------------------------------------------------------------
     * SMARTLAB MODEL METHODS
     * ------------------------------------------------------------ */


	/**
	 * Determine & set the current client ID
	 *
	 * @return	void
	 */
	private function define_client_id()
	{
		if ( ! $this->_client_id && isset($this->_CI->client) )
		{
			$this->_client_id = $this->_CI->client->id;
		}
	}


	/**
	 * Determine & set the current client application ID
	 *
	 * @return	void
	 */
	private function define_client_application_id()
	{
		if ( ! $this->_client_application_id && isset($this->_CI->application) )
		{
			$this->_client_application_id = $this->_CI->application->id;
		}
	}


	/**
	 * Determine & set the current user ID
	 *
	 * @return	void
	 */
	private function define_user_id()
	{
		if ( ! $this->_user_id && isset($this->_CI->user) )
		{
			$this->_user_id = $this->_CI->user->id;
		}
	}


	/**
	 * Set client
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_client_id($data)
	{
		$this->define_client_id();
		
		$data['client_id'] = $this->_client_id;
		
		return $data;
	}


	/**
	 * Set application instance ID
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_client_application_id($data)
	{
		$this->define_client_application_id();
		
		$data['client_application_id'] = $this->_client_application_id;
		
		return $data;
	}


	/**
	 * Set the user ID
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_user_id($data)
	{
		$this->define_user_id();
		
		$data['user_id'] = $this->_user_id;
		
		return $data;
	}


    /**
	 * Set created time
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_created_time($data)
	{
		$data['created'] = time();
		
		return $data;
	}


	/**
	 * Set modified time
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_modified_time($data)
	{
		$data['modified'] = time();
		
		return $data;
	}


	/**
	 * Set the created user ID
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_created_user_id($data)
	{
		$this->define_user_id();
		
		$data['created_user_id'] = $this->_user_id;
		
		return $data;
	}


	/**
	 * Set the modified user ID
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_modified_user_id($data)
	{
		$this->define_user_id();
		
		$data['modified_user_id'] = $this->_user_id;
		
		return $data;
	}


	/**
	 * Where client ID for get, update & delete
	 *
	 * @param	array	(optional)
	 * @return	array or this
	 */
	protected function where_client_id($data = NULL)
	{
		$this->define_client_id();
		
		$this->_database->where('client_id', $this->_client_id);
		
		if ( $data )
		{
			return $data;
		}
		
		return $this;
	}


	/**
	 * Where client application ID for get, update & delete
	 *
	 * @param	array	(optional)
	 * @return	array or this
	 */
	protected function where_client_application_id($data = NULL)
	{
		$this->define_client_application_id();
		
		$this->_database->where('client_application_id', $this->_client_application_id);
		
		if ( $data )
		{
			return $data;
		}
		
		return $this;
	}


	/**
	 * Where created user ID for get, update & delete
	 *
	 * @param	array	(optional)
	 * @return	array or this
	 */
	protected function where_created_user_id($data = NULL)
	{
		$this->define_user_id();
		
		$this->_database->where('created_user_id', $this->_client_id);
		
		if ( $data )
		{
			return $data;
		}
		
		return $this;
	}


	/**
	 * Setter for active only get parameter
	 *
	 * @param	bool
	 * @return	this
	 */
	public function set_active_only($active_only = TRUE)
	{
		$this->_active_only = TRUE;
		
		if ( $active_only === FALSE )
		{
			$this->_active_only = FALSE;
		}
		
		return $this;
	}


	/**
	 * Where active for get
	 *
	 * @param	array	(optional)
	 * @return	array or this
	 */
	protected function where_active($data = NULL)
	{
		if ( $this->_active_only === TRUE )
		{
			$this->_database->where('active', 1);
		}
		
		if ( $data )
		{
			return $data;
		}
		
		return $this;
	}


}