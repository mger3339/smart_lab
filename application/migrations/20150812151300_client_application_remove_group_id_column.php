<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_application_remove_group_id_column extends CI_Migration {


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
     * Remove group_id  column from client_applications table
     *
     */
    public function up()
    {
        echo "\n Removing group_id column from client_applications table";
        $group_id = Schema::remove_column('client_applications', 'group_id');


        echo "\n Schema changes successful";
    }


    /**
     * Add group_id column to client_applications table
     *
     */
    public function down()
    {
        echo "\n Adding group_id  column to client_applications table";

        $group_id = Schema::add_column('client_applications', 'group_id', 'varchar', array('constraint' => 255, 'default' => NULL), 'snapshot_id');


        echo "\n Schema changes successful";
    }


}