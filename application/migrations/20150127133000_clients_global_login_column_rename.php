<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_clients_global_login_column_rename extends CI_Migration {


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
	 * Re-name global login columns in clients table
	 *
	 */
	public function up()
	{
		echo "\n Re-configuring clients table with auto register columns";
		
		$table = Schema::remove_column('clients', 'global_login');
		$table = Schema::remove_column('clients', 'global_login_password');
		
		$table = Schema::add_column('clients', 'user_auto_register', 'boolean', array('default' => 0, 'null' => FALSE), 'currency');
		$table = Schema::add_column('clients', 'auto_register_password', 'varchar', array('constraint' => 255, 'null' => TRUE), 'user_auto_register');
		
		echo "\n Schema changes successful";
	}


	/**
	 * Re-name auto register columns in clients table
	 *
	 */
	public function down()
	{
		echo "\n Re-configuring clients table with global login columns";
		
		$table = Schema::remove_column('clients', 'user_auto_register');
		$table = Schema::remove_column('clients', 'auto_register_password');
		
		$table = Schema::add_column('clients', 'global_login', 'boolean', array('default' => 0, 'null' => FALSE), 'currency');
		$table = Schema::add_column('clients', 'global_login_password', 'varchar', array('constraint' => 255, 'null' => TRUE), 'global_login');
		
		echo "\n Schema changes successful";
	}


}