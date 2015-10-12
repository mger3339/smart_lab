<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_group extends CI_Migration {


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
     * Add client groups table
     *
     */
    public function up()
    {
        echo "\n Adding client groups table";
        Schema::create_table('client_groups', function($table) {

            $table->auto_increment_integer('id', array('constraint' => 20));

            $table->string('name', 255);

            $table->boolean('deleted', array('default' => 0));
            $table->integer('client_id', array('constraint' => 20));

            $table->string('name', 60);
            $table->integer('sort_order', array('constraint' => 20, 'default' => 0));

        });

        echo "\n Schema changes successful";
    }


    /**
     * Drop client groups table
     *
     */
    public function down()
    {
        echo "\n Dropping client groups table";
        $this->dbforge->drop_table('client_groups', TRUE);

        echo "\n Schema changes successful";
    }


}