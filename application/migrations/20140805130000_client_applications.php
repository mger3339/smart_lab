<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_applications extends CI_Migration {


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
	 * Add client_applications table
	 *
	 */
	public function up()
	{
		echo "\n Adding client_applications table";
		Schema::create_table('client_applications', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			$table->boolean('active', array('default' => 1));
			$table->integer('client_id', array('constraint' => 20));
			
			$table->string('application', 30);
			$table->string('name', 60);
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop client_applications table
	 *
	 */
	public function down()
	{
		echo "\n Dropping client_applications table";
		$this->dbforge->drop_table('client_applications', TRUE);
		
		echo "\n Schema changes successful";
	}


}