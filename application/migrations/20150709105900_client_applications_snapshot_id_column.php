<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_applications_snapshot_id_column extends CI_Migration {

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
     * Add snapshot_id column to client_applications table
     *
     */
    public function up()
    {
        echo "\n Adding snapshot_id column to client_applications table";

        $snapshot_id = Schema::add_column('client_applications', 'snapshot_id', 'int', array('constraint' => 20, 'default' => 0), 'expire');

        echo "\n Schema changes successful";
    }


    /**
     * Remove snapshot_id column from client_applications table
     *
     */
    public function down()
    {
        echo "\n Removing snapshot_id column from client_applications table";

        $snapshot_id = Schema::remove_column('client_applications', 'snapshot_id');

        echo "\n Schema changes successful";
    }


}