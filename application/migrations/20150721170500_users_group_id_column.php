<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_users_group_id_column extends CI_Migration {

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
     * Add group_id column to users table
     *
     */
    public function up()
    {
        echo "\n Adding group_id column to users table";

        $group_id = Schema::add_column('users', 'group_id', 'varchar', array('constraint' => 255, 'default' => NULL), 'client_id');

        echo "\n Schema changes successful";
    }


    /**
     * Remove group_id column from users table
     *
     */
    public function down()
    {
        echo "\n Removing group_id column from users table";

        $group_id = Schema::remove_column('users', 'group_id');

        echo "\n Schema changes successful";
    }


}