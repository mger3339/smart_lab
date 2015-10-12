<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_user_roles extends CI_Migration {


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
	 * Add client_user_roles table
	 *
	 */
	public function up()
	{
		echo "\n Adding client_user_roles table";
		Schema::create_table('client_user_roles', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->string('user_role', 20);
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop client_user_roles table
	 *
	 */
	public function down()
	{
		echo "\n Dropping client_user_roles table";
		$this->dbforge->drop_table('client_user_roles', TRUE);
		
		echo "\n Schema changes successful";
	}


}