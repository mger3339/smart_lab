<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files_model extends Smartlab_model {

//    protected $soft_delete = TRUE;

    private $prototype = array(
        'id'				=> 0,
        'file_type'			=> NULL,
        'file_path'         => NULL,
        'orig_name'         => NULL,
        'client_name'       => NULL,
        'file_ext'          => NULL,
        'file_size'         => 0,
        'is_image'          => 0,
        'created'           => NULL
    );
    public function __construct()
    {
        parent::__construct();

    }

    public function get_user_image($id)
    {
        $query = $this->_database->get_where($this->_table, array('id' => $id, 'is_image' => 1));

        $image = $query->row();

        return $image;
    }
}