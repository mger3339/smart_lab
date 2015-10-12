<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_login_attempts extends CI_Migration {


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
	 * Add login_attempts table
	 *
	 */
	public function up()
	{
		echo "\n Adding login_attempts table";
		Schema::create_table('login_attempts', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->integer('client_id', array('constraint' => 20));
			
			$table->string('login', 255);
			$table->string('login_ip', 60, array('null' => TRUE));
			$table->integer('login_time', array('constraint' => 11));
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop login_attempts table
	 *
	 */
	public function down()
	{
		echo "\n Dropping login_attempts table";
		$this->dbforge->drop_table('login_attempts', TRUE);
		
		echo "\n Schema changes successful";
	}


}