<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_user_groups extends CI_Migration {


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
     * Add client user groups table
     *
     */
    public function up()
    {
        echo "\n Adding client_user_groups table";
        Schema::create_table('client_user_groups', function($table) {

            $table->auto_increment_integer('id', array('constraint' => 20));


            $table->integer('user_id', array('constraint' => 20));
            $table->integer('group_id', array('constraint' => 20));

        });

        echo "\n Schema changes successful";
    }


    /**
     * Drop client user groups table
     *
     */
    public function down()
    {
        echo "\n Dropping client_user_groups table";
        $this->dbforge->drop_table('client_user_groups', TRUE);

        echo "\n Schema changes successful";
    }


}