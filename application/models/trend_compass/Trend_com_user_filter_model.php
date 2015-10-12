<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trend_com_user_filter_model extends Smartlab_model {


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


	/**
	 * Model observers
	 */
	public $before_create		= array(
											'set_client_id',
											'set_client_application_id',
											'set_created_time',
								);
	public $before_update		= array(
											'where_client_id',
											'where_client_application_id',
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


}