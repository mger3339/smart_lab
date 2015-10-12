<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_timeline_milestones extends CI_Migration {


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
	 * Add timeline_milestones table
	 *
	 */
	public function up()
	{
		echo "\n Adding timeline_milestones table";
		
		Schema::create_table('timeline_milestones', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->integer('client_application_id', array('constraint' => 20));
			
			$table->integer('timeline_workstream_id', array('constraint' => 20));
			
			$table->text('title');
			$table->text('description');
			
			$table->date('start_date');
			$table->date('end_date');
			
			$table->integer('y_position', array('constraint' => 5, 'null' => TRUE));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
			$table->integer('created_user_id', array('constraint' => 20));
			$table->integer('modified_user_id', array('constraint' => 20, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop timeline_milestones table
	 *
	 */
	public function down()
	{
		echo "\n Dropping timeline_milestones table";
		
		$this->dbforge->drop_table('timeline_milestones', TRUE);
		
		echo "\n Schema changes successful";
	}


}