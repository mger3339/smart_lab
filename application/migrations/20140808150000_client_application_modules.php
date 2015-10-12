<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_application_modules extends CI_Migration {


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
	 * Add client_application_modules table
	 *
	 */
	public function up()
	{
		echo "\n Adding client_application_modules table";
		Schema::create_table('client_application_modules', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			$table->boolean('active', array('default' => 1));
			$table->integer('client_application_id', array('constraint' => 20));
			
			$table->string('application', 30);
			$table->string('module', 30);
			$table->string('name', 60);
			
			$table->integer('sort_order', array('constraint' => 11, 'default' => 0));
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop client_application_modules table
	 *
	 */
	public function down()
	{
		echo "\n Dropping client_application_modules table";
		$this->dbforge->drop_table('client_application_modules', TRUE);
		
		echo "\n Schema changes successful";
	}


}