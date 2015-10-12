<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_users extends CI_Migration {


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
	 * Add users table
	 *
	 */
	public function up()
	{
		echo "\n Adding users table";
		Schema::create_table('users', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			$table->boolean('active', array('default' => 1));
			
			$table->boolean('admin', array('default' => 0));
			$table->boolean('super_admin', array('default' => 0));
			$table->boolean('super_admin_admin', array('default' => 0));
			$table->integer('client_id', array('constraint' => 20));
			$table->string('user_role', 20, array('default' => 'user'));
			
			$table->string('email', 255);
			$table->string('username', 60);
			$table->string('password', 255);
			
			$table->string('firstname', 60);
			$table->string('lastname', 60);
			
			$table->integer('avatar_file_id', array('constraint' => 20, 'null' => TRUE));
			
			$table->string('country_iso', 2, array('null' => TRUE, 'default' => 'GB'));
			$table->string('time_zone', 60, array('null' => TRUE));
			$table->string('currency', 3, array('null' => TRUE, 'default' => 'GBP'));
			
			$table->string('last_ip', 60, array('null' => TRUE));
			$table->integer('last_login', array('constraint' => 11, 'null' => TRUE));
			$table->integer('last_activity', array('constraint' => 11, 'null' => TRUE));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
		});
		
		echo "\n Seeding users table with one super admin user";
		$this->load->library('phpass');
		$password = $this->phpass->hash('changeme');
		$super_admin = array(
			'admin'				=> 1,
			'super_admin'		=> 1,
			'super_admin_admin'	=> 1,
			'client_id'			=> 0,
			'user_role'			=> 'admin',
			'email'				=> 'graeme.coultrip@me.com',
			'username'			=> 'graeme.coultrip',
			'password'			=> $password,
			'firstname'			=> 'Graeme',
			'lastname'			=> 'Coultrip',
			'country_iso'		=> 'GB',
			'time_zone'			=> date_default_timezone_get(),
			'currency'			=> 'GBP',
		);
		$this->load->model('user_model');
		$user = $this->user_model->insert($super_admin);
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop users table
	 *
	 */
	public function down()
	{
		echo "\n Dropping users table";
		$this->dbforge->drop_table('users', TRUE);
		
		echo "\n Schema changes successful";
	}


}