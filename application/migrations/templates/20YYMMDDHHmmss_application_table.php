<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_application_table extends CI_Migration {


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
	 * Add application_table table
	 *
	 */
	public function up()
	{
		echo "\n Adding application_table table";
		
		Schema::create_table('application_table', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->integer('client_application_id', array('constraint' => 20));
			
			
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
			$table->integer('created_user_id', array('constraint' => 20));
			$table->integer('modified_user_id', array('constraint' => 20, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop application_table table
	 *
	 */
	public function down()
	{
		echo "\n Dropping application_table table";
		
		$this->dbforge->drop_table('application_table', TRUE);
		
		echo "\n Schema changes successful";
	}


}