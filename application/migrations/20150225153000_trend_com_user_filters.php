<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_trend_com_user_filters extends CI_Migration {


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
	 * Add trend_com_user_filters table
	 *
	 */
	public function up()
	{
		echo "\n Adding trend_com_user_filters table";
		
		Schema::create_table('trend_com_user_filters', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->integer('client_application_id', array('constraint' => 20));
			
			$table->integer('user_id', array('constraint' => 20));
			$table->integer('filter_id', array('constraint' => 20));
			$table->integer('filter_option_id', array('constraint' => 20));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop trend_com_user_filters table
	 *
	 */
	public function down()
	{
		echo "\n Dropping trend_com_user_filters table";
		
		$this->dbforge->drop_table('trend_com_user_filters', TRUE);
		
		echo "\n Schema changes successful";
	}


}