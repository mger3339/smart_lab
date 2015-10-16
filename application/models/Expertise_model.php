<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expertise_model extends Smartlab_model {

//    protected $soft_delete = TRUE;

    private $prototype = array(
        'id'				=> 0,
        'expertise'			=> NULL,
    );
    public function __construct()
    {
        parent::__construct();

    }
    public function get_expertise_options()
    {
        $results = $this->get_all();

        return $results;
    }
}