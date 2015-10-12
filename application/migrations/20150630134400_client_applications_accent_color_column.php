<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_client_applications_accent_color_column extends CI_Migration {

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
     * Add accent_color column to client_applications table
     *
     */
    public function up()
    {
        echo "\n Adding accent_color column to client_applications table";

        $accent_color = Schema::add_column('client_applications', 'accent_color', 'varchar', array('constraint' => 6, 'default' => NULL), 'modified');

        echo "\n Schema changes successful";
    }


    /**
     * Remove accent_color column from client_applications table
     *
     */
    public function down()
    {
        echo "\n Removing accent_color column from client_applications table";

        $accent_color = Schema::remove_column('client_applications', 'accent_color');

        echo "\n Schema changes successful";
    }


}