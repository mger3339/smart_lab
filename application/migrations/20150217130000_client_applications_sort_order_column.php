<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_applications_sort_order_column extends CI_Migration {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('schema');
	}


	/**
	 * Add sort_order column to client_applications table
	 *
	 */
	public function up()
	{
		echo "\n Adding sort_order column to client_applications table";
		
		$table = Schema::add_column('client_applications', 'sort_order', 'int', array('constraint' => 20, 'default' => 0, 'null' => FALSE), 'name');
		
		echo "\n Schema changes successful";
	}


	/**
	 * Remove sort_order column from client_applications table
	 *
	 */
	public function down()
	{
		echo "\n Removing sort_order column from client_applications table";
		
		$table = Schema::remove_column('client_applications', 'sort_order');
		
		echo "\n Schema changes successful";
	}


}