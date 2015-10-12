<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_hostname_model extends Smartlab_model {


	/**
	 * Enable soft delete
	 */
	protected $special_hostnames = array('ludicgroup.com');


	/**
	 * Enable soft delete
	 */
	protected $soft_delete = TRUE;


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


	/**
	 * Prototype for row creation
	 */
	private $prototype = array(
			'hostname'			=> NULL,
	);


	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> 'hostname',
			'label'		=> 'hostname',
			'rules'		=> 'required|strtolower|trim|valid_domain|is_client_unique[email_hostnames.hostname]'
		),
	);


	/**
	 * Model observers
	 */
	public $before_create		= array('set_client_id', 'set_created_time');
	public $before_update		= array('where_client_id');
	public $before_delete		= array('where_client_id');
	public $before_get			= array('where_client_id');


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Return the model prototype
	 *
	 * @return	object
	 */
	public function create_prototype()
	{
		return (object) $this->prototype;
	}


	/**
	 * Tests for a valid client email hostanme
	 * and returns TRUE or FALSE
	 * Optional bool param to enforce client hostnames
	 *
	 * @param	string
	 * @param	bool	(optional)
	 * @return	bool
	 */
	public function validate_hostname($email, $enforce_hostnames = FALSE)
	{
		if ( ! $enforce_hostnames )
		{
			$client_email_hostnames = $this->get_all();
			
			if ( ! $client_email_hostnames )
			{
				return TRUE;
			}
		}
		
		$hostname = substr(strrchr($email, "@"), 1);
		
		$client_email_hostname = $this->get_by(array('hostname' => $hostname));
		
		if ( $client_email_hostname )
		{
			return TRUE;
		}
		
		if ( in_array($hostname, $this->special_hostnames) )
		{
			return TRUE;
		}
		
		return FALSE;
	}


}