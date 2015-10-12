<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_applications_commence_expire_columns extends CI_Migration {


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
     * Add commence, expire columns to client_applications table
     *
     */
    public function up()
    {
        echo "\n Adding commence, expire columns to client_applications table";

        $commence = Schema::add_column('client_applications', 'commence', 'int', array('constraint' => 11, 'default' => 0, 'null' => FALSE), 'accent_color');
        $expire = Schema::add_column('client_applications', 'expire', 'int', array('constraint' => 11, 'default' => 0, 'null' => FALSE), 'commence');

        echo "\n Schema changes successful";
    }


    /**
     * Remove sort_order column from client_applications table
     *
     */
    public function down()
    {
        echo "\n Removing commence, expire columns from client_applications table";

        $commence = Schema::remove_column('client_applications', 'commence');
        $expire = Schema::remove_column('client_applications', 'expire');

        echo "\n Schema changes successful";
    }


}