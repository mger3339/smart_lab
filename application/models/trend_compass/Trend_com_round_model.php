<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trend_com_round_model extends Smartlab_model {


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
			
	);


	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> '',
			'label'		=> '',
			'rules'		=> 'required|trim'
		),
	);


	/**
	 * Model observers
	 */
	public $before_create		= array(
											'set_client_id',
											'set_client_application_id',
											'set_created_time',
											'set_modified_time',
											'set_created_user_id',
								);
	public $before_update		= array(
											'where_client_id',
											'where_client_application_id',
											'set_modified_time',
											'set_modified_user_id',
								);
	public $before_delete		= array(
											'where_client_id',
											'where_client_application_id'
								);
	public $before_get			= array(
											'where_client_id',
											'where_client_application_id',
								);


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
		$data = $this->prototype;
		
		return (object) $data;
	}


}