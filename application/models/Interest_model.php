<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Interest_model extends Smartlab_model {

//    protected $soft_delete = TRUE;

    private $prototype = array(
        'id'				=> 0,
        'interests'			=> NULL,
    );
    public function __construct()
    {
        parent::__construct();

    }
    public function get_interests_options()
    {
        $results = $this->get_all();

        return $results;
    }
}