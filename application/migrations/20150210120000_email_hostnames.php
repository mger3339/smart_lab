<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_email_hostnames extends CI_Migration {


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
	 * Add email_hostnames table
	 *
	 */
	public function up()
	{
		echo "\n Adding email_hostnames table";
		Schema::create_table('email_hostnames', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			
			$table->integer('client_id', array('constraint' => 20));
			
			$table->string('hostname', 255);
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop email_hostnames table
	 *
	 */
	public function down()
	{
		echo "\n Dropping email_hostnames table";
		$this->dbforge->drop_table('email_hostnames', TRUE);
		
		echo "\n Schema changes successful";
	}


}