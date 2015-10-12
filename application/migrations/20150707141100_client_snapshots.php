<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_snapshots extends CI_Migration {


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
	 * Add client_snapshots table
	 *
	 */
	public function up()
	{
		echo "\n Adding client_snapshots table";
		Schema::create_table('client_snapshots', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			$table->integer('client_id', array('constraint' => 20));
			
			$table->string('name', 60);
			$table->integer('sort_order', array('constraint' => 20, 'default' => 0));
			
			$table->integer('commence', array('constraint' => 11, 'null' => TRUE));
			$table->integer('expire', array('constraint' => 11, 'null' => TRUE));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop client_snapshots table
	 *
	 */
	public function down()
	{
		echo "\n Dropping client_snapshots table";
		$this->dbforge->drop_table('client_snapshots', TRUE);
		
		echo "\n Schema changes successful";
	}


}