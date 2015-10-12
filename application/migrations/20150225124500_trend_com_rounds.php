<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_trend_com_rounds extends CI_Migration {


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
	 * Add trend_com_rounds table
	 *
	 */
	public function up()
	{
		echo "\n Adding trend_com_rounds table";
		
		Schema::create_table('trend_com_rounds', function($table) {
			
			$table->auto_increment_integer('id', array('constraint' => 20));
			
			$table->boolean('deleted', array('default' => 0));
			
			$table->integer('client_id', array('constraint' => 20));
			$table->integer('client_application_id', array('constraint' => 20));
			
			$table->string('type', 30);
			
			$table->text('title');
			$table->text('description');
			
			$table->integer('sort_order', array('constraint' => 20, 'default' => 0));
			
			$table->integer('created', array('constraint' => 11, 'null' => TRUE));
			$table->integer('modified', array('constraint' => 11, 'null' => TRUE));
			
			$table->integer('created_user_id', array('constraint' => 20));
			$table->integer('modified_user_id', array('constraint' => 20, 'null' => TRUE));
			
		});
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop trend_com_rounds table
	 *
	 */
	public function down()
	{
		echo "\n Dropping trend_com_rounds table";
		
		$this->dbforge->drop_table('trend_com_rounds', TRUE);
		
		echo "\n Schema changes successful";
	}


}