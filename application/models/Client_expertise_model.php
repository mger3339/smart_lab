<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_expertise_model extends Smartlab_model {


    private $prototype = array(
        'id'				=> 0,
        'expertise_id'			=> 0,
        'client_id'         => NULL,
    );
    public function __construct()
    {
        parent::__construct();

    }

    public function get_expertise_options()
    {
        $this->db->select('client_expertises.id,client_expertises.expertise_id,client_expertises.user_id, expertises.expertise');
        $this->db->join('expertises','client_expertises.expertise_id = expertises.id', 'left');
        $results = $this->get_all();

        return $results;
    }

        public function delete_expertise($expertise_id)
    {
        return $expertise_id;
    }

    public  function get_expertises($expertise_ids)
    {
        $this->_database->where_in('expertise_id', $expertise_ids);
        $results = $this->get_all();

        return $results;
    }

    public  function get_expertises_by_id($expertise_ids)
    {
        $this->_database->where_in('expertise_id', $expertise_ids);
        $this->_database->distinct();
        $this->_database->group_by('user_id');
        $results = $this->get_all();

        return $results;
    }


    public function delete_expertises($expertise_ids)
    {
        $this->_database->where_in('expertise_id', $expertise_ids);
        $results = $this->_database->delete('client_expertises');

        return $results;
    }


}