<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_info_model extends Smartlab_model {

//    protected $soft_delete = TRUE;

    private $prototype = array(
        'id'				=> 0,
        'job_title'			=> NULL,
        'department'         => NULL,
        'biography'         => NULL,
        'user_id'       => 0
    );
    public function __construct()
    {
        parent::__construct();

    }

    public function get_user_info($user_id)
    {
        $query = $this->_database->get_where($this->_table, array('user_id' => $user_id));

        $image = $query->row();

        return $image;
    }
}