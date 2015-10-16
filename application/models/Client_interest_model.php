<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_interest_model extends Smartlab_model {


    private $prototype = array(
        'id'				=> 0,
        'interests'			=> NULL,
        'client_id'         => NULL,
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
    public function delete_interests($interests_id)
    {
        return $interests_id;
    }
}