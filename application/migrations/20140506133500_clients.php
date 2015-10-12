<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_clients extends CI_Migration {


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
	 * Add clients table
	 *
	 */
	public function up()
	{
		echo "\n Adding clients table";
		Schema::create_table('clients', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			$table->boolean('active', array('default' => 1));
			
			$table->string('name', 60);
			$table->string('slug', 60);
			$table->string('hostname', 255, array('null' => TRUE));
			
			$table->integer('user_id', array('constraint' => 20, 'null' => TRUE));
			$table->integer('admin_user_id', array('constraint' => 20, 'null' => TRUE));
			
			$table->string('language', 60, array('null' => TRUE, 'default' => 'english'));
			$table->string('country_iso', 2, array('null' => TRUE, 'default' => 'GB'));
			$table->string('time_zone', 60, array('null' => TRUE));
			$table->string('currency', 3, array('null' => TRUE, 'default' => 'GBP'));
			
			$table->boolean('global_login', array('default' => 0));
			$table->string('global_login_password', 255);
			$table->integer('session_timeout', array('constraint' => 10, 'default' => 3600));
			
			$table->integer('commence', array('constraint' => 11, 'null' => TRUE));
			$table->integer('expire', array('constraint' => 11, 'null' => TRUE));
			
			$table->boolean('enable_ssl', array('default' => 0));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop clients table
	 *
	 */
	public function down()
	{
		echo "\n Dropping clients table";
		$this->dbforge->drop_table('clients', TRUE);
		
		echo "\n Schema changes successful";
	}


}