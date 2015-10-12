<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_applications_groups_users_columns extends CI_Migration {


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
     * Add group_id, user_id columns to client_applications table
     *
     */
    public function up()
    {
        echo "\n Adding group_id, user_id  columns to client_applications table";

        $group_id = Schema::add_column('client_applications', 'group_id', 'varchar', array('constraint' => 255, 'default' => 0, 'null' => FALSE), 'snapshot_id');
        $user_id = Schema::add_column('client_applications', 'user_id', 'varchar', array('constraint' => 255, 'default' => 0, 'null' => FALSE), 'group_id');

        echo "\n Schema changes successful";
    }


    /**
     * Remove group_id, user_id  columns from client_applications table
     *
     */
    public function down()
    {
        echo "\n Removing group_id, user_id columns from client_applications table";

        $group_id = Schema::remove_column('client_applications', 'group_id');
        $user_id = Schema::remove_column('client_applications', 'user_id');

        echo "\n Schema changes successful";
    }


}