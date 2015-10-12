<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_timeline_workstreams extends CI_Migration {


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
	 * Add timeline_workstreams table
	 *
	 */
	public function up()
	{
		echo "\n Adding timeline_workstreams table";
		
		Schema::create_table('timeline_workstreams', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->integer('client_application_id', array('constraint' => 20));
			
			$table->string('name', 60);
			$table->text('description');
			$table->string('color', 6);
			
			$table->integer('sort_order', array('constraint' => 20, 'default' => 0));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
			$table->integer('created_user_id', array('constraint' => 20));
			$table->integer('modified_user_id', array('constraint' => 20, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop timeline_workstreams table
	 *
	 */
	public function down()
	{
		echo "\n Dropping timeline_workstreams table";
		
		$this->dbforge->drop_table('timeline_workstreams', TRUE);
		
		echo "\n Schema changes successful";
	}


}