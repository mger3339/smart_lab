<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_expertise_model extends Smartlab_model {


    private $prototype = array(
        'id'				=> 0,
        'expertise'			=> NULL,
        'client_id'         => NULL,
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
    public function delete_expertise($expertise_id)
    {
        return $expertise_id;
    }
}