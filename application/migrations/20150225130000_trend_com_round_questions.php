<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_trend_com_round_questions extends CI_Migration {


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
	 * Add trend_com_round_questions table
	 *
	 */
	public function up()
	{
		echo "\n Adding trend_com_round_questions table";
		
		Schema::create_table('trend_com_round_questions', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->integer('client_application_id', array('constraint' => 20));
			
			$table->integer('round_id', array('constraint' => 20));
			
			$table->text('title');
			
			$table->integer('sort_order', array('constraint' => 20, 'default' => 0));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
			$table->integer('created_user_id', array('constraint' => 20));
			$table->integer('modified_user_id', array('constraint' => 20, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop trend_com_round_questions table
	 *
	 */
	public function down()
	{
		echo "\n Dropping trend_com_round_questions table";
		
		$this->dbforge->drop_table('trend_com_round_questions', TRUE);
		
		echo "\n Schema changes successful";
	}


}