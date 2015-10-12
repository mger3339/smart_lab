<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_notifications extends CI_Migration {


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
	 * Add notifications table
	 *
	 */
	public function up()
	{
		echo "\n Adding notifications table";
		Schema::create_table('notifications', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));

			$table->integer('client_id', array('constraint' => 11));
			$table->integer('user_id', array('constraint' => 11));

			$table->string('subject');
			$table->text('message');
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
		});

		echo "\n Adding notification users table";
		Schema::create_table('notification_users', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));

			$table->integer('notification_id', array('constraint' => 11));
			$table->integer('user_id', array('constraint' => 11));
			$table->integer('through_group_id', array('constraint' => 11, 'null' => TRUE));

			$table->integer('read_on', array('constraint' => 11, 'null' => TRUE));
		});

		echo "\n Adding notification groups table";
		Schema::create_table('notification_groups', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));

			$table->integer('notification_id', array('constraint' => 11));
			$table->integer('group_id', array('constraint' => 11));
			
			$table->integer('read_on', array('constraint' => 11, 'null' => TRUE));
		});
	}


	/**
	 * Drop notifications table
	 *
	 */
	public function down()
	{
		echo "\n Dropping notifications table";
		$this->dbforge->drop_table('notifications', TRUE);

		echo "\n Dropping notification users table";
		$this->dbforge->drop_table('notification_users', TRUE);

		echo "\n Dropping notification groups table";
		$this->dbforge->drop_table('notification_groups', TRUE);
		
		echo "\n Schema changes successful";
	}

}