<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_CI_sessions extends CI_Migration {


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
	 * Add ci_sessions table
	 *
	 */
	public function up()
	{
		echo "\n Adding ci_sessions table";
		$this->db->query("
			CREATE TABLE IF NOT EXISTS  `ci_sessions` (
				session_id varchar(40) DEFAULT '0' NOT NULL,
				ip_address varchar(45) DEFAULT '0' NOT NULL,
				user_agent varchar(120) NOT NULL,
				last_activity int(10) unsigned DEFAULT 0 NOT NULL,
				user_data text NOT NULL,
				PRIMARY KEY (session_id, ip_address, user_agent),
				KEY `last_activity_idx` (`last_activity`)
			);
		");
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop ci_sessions table
	 *
	 */
	public function down()
	{
		echo "\n Dropping ci_sessions table";
		$this->dbforge->drop_table('ci_sessions', TRUE);
		
		echo "\n Schema changes successful";
	}


}