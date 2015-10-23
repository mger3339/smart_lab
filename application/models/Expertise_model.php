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

    public function get_expertise($text)
    {
        if($text == '')
        {
            $this->order_by('expertise', 'ASC');
            $results = $this->get_all();
        }
        else
        {
            $this->_database->like("expertise", $text);
            $this->order_by('expertise', 'ASC');
            $query = $this->_database->get('expertises');
            $results = $query->result_object();
        }
        return $results;
    }

    public function merge_expertise($expertise_id)
    {
        $this->_database->where_in('id', $expertise_id);
        $this->_database->delete('expertises');
    }
}