<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_application_settings extends CI_Migration {


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
	 * Add client_application_settings table
	 *
	 */
	public function up()
	{
		echo "\n Adding client_application_settings table";
		
		Schema::create_table('client_application_settings', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->integer('client_application_id', array('constraint' => 20));
			
			$table->string('setting', 30);
			$table->text('value', array('null' => TRUE));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop client_application_settings table
	 *
	 */
	public function down()
	{
		echo "\n Dropping client_application_settings table";
		
		$this->dbforge->drop_table('client_application_settings', TRUE);
		
		echo "\n Schema changes successful";
	}


}