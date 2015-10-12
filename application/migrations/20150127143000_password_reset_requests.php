<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_password_reset_requests extends CI_Migration {


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
	 * Add password_reset_requests table
	 *
	 */
	public function up()
	{
		echo "\n Adding password_reset_requests table";
		Schema::create_table('password_reset_requests', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->integer('client_id', array('constraint' => 20));
			
			$table->string('email', 255);
			$table->string('request_ip', 60, array('null' => TRUE));
			$table->integer('request_time', array('constraint' => 11));
			$table->string('request_hash', 255, array('null' => TRUE));
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop password_reset_requests table
	 *
	 */
	public function down()
	{
		echo "\n Dropping password_reset_requests table";
		$this->dbforge->drop_table('password_reset_requests', TRUE);
		
		echo "\n Schema changes successful";
	}


}