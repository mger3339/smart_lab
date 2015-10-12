<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_applications_facilitator_column extends CI_Migration {


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
     * Add facilitator_id column to client_applications table
     *
     */
    public function up()
    {
        echo "\n Adding facilitator_id  column to client_applications table";

        $group_id = Schema::add_column('client_applications', 'facilitator_id', 'varchar', array('constraint' => 255, 'default' => 0, 'null' => FALSE), 'user_id');

        echo "\n Schema changes successful";
    }


    /**
     * Remove facilitator_id  column from client_applications table
     *
     */
    public function down()
    {
        echo "\n Removing facilitator_id column from client_applications table";

        $facilitator_id = Schema::remove_column('client_applications', 'facilitator_id');

        echo "\n Schema changes successful";
    }


}