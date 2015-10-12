<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_users_locked_column extends CI_Migration {


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
	 * Add locked column to users table
	 *
	 */
	public function up()
	{
		echo "\n Adding locked column to users table";
		$table = Schema::add_column('users', 'locked', 'boolean', array('default' => 0), 'active');
		
		echo "\n Schema changes successful";
	}


	/**
	 * Remove locked column from users table
	 *
	 */
	public function down()
	{
		echo "\n Remove locked column from users table";
		$table = Schema::remove_column('users', 'locked');
		
		echo "\n Schema changes successful";
	}


}