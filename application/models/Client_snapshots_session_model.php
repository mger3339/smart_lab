<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_snapshots_session_model extends Smartlab_model {


    /**
     * Prototype for row creation
     */
    private $prototype = array(
        'id'                => 0,
        'client_id'			=> NULL,
        'snapshot_id'       => NULL,
        'group_id'          => NUll,
        'user_id'           => NULL,
        'name'				=> NULL,
        'commence'			=> NULL,
        'expire'			=> NULL,
    );


    /**
     * Enable soft delete
     */
    protected $soft_delete = TRUE;


    /**
     * Protected columns
     */
    public $protected_attributes = array( 'id' );


    /**
     * Default sessions container
     */
    private $default_sessions = array();


    /**
     * Cache container for row storage
     */
    private $cache = array();


    /**
     * Model validation rules
     */
    public $validate = array(
        array(
            'field'		=> 'client_id',
            'label'		=> 'client id',
            'rules'		=> 'required|trim|integer'
        ),
        array(
            'field'		=> 'name',
            'label'		=> 'session name',
            'rules'		=> 'required|trim|max_length[60]|is_client_unique[client_snapshots_sessions.name]'
        ),
        array(
            'field'		=> 'commence',
            'label'		=> 'session commencement',
            'rules'		=> 'required|trim|max_length[12]'
        ),
        array(
            'field'		=> 'expire',
            'label'		=> 'session expiry',
            'rules'		=> 'trim|max_length[12]'
        )

    );


    /**
     * Model observers
     */
    public $before_create		= array('purge_cache', 'set_created_time', 'set_modified_time', 'modify_post_data');
    public $before_update		= array('purge_cache', 'set_modified_time', 'modify_post_data');
    public $before_delete		= array('purge_cache', 'delete_dependencies');
    public $after_get			= array('purge_cache', 'add_default_properties');



    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

    }


    /**
     * Define extra snapshots session row properties
     *
     * @param	object
     * @return	object
     */
    protected function add_default_properties($row)
    {
        // add the session facilitators
        if (is_object($row) && !property_exists($row, 'facilitator'))
        {
            $this->load->model('user_model');
            if(isset($row->user_id)){
                $user_ids = explode(',',$row->user_id);
                $row->facilitator = $this->user_model->get_many_by('id',$user_ids);
            }else{
                $row->facilitator = [];
            }

        }

        // add the sessions groups
        if (is_object($row) && !property_exists($row, 'group_id'))
        {
            $this->load->model('client_snapshot_session_group_model');
            $group_ids = $this->client_snapshot_session_group_model->get_groups('session_id',$row->id);
            $row->group_id = implode(',',$group_ids);

        }

        // add the session groups
        if (is_object($row) && !property_exists($row, 'groups'))
        {
            $this->load->model('client_group_model');
            if(isset($row->group_id)){
                $group_ids = explode(',',$row->group_id);
                $row->groups = $this->client_group_model->get_many_by('id',$group_ids);
            }else{
                $row->groups = [];
            }

        }

        return $row;
    }

    /**
     * Get a client's sessions
     *
     * @param	int
     * @return	array
     */
    public function get_client_sessions($client_id)
    {
        if ( ! isset($this->cache[$client_id]) )
        {
            $this->order_by('sort_order', 'ASC');

            $sessions_data = $this->get_many_by('client_id', $client_id);

            $client_sessions = array();

            // purge any sessions that may no longer exist in the system
            foreach ($sessions_data as $row)
            {
                if ( array_key_exists($row->session, $this->default_sessions) )
                {
                    $client_sessions[$row->id] = $row;
                }
                else
                {
                    $this->delete($row->id);
                }
            }

            $this->cache[$client_id] = $client_sessions;
        }

        return $this->cache[$client_id];
    }

    /**
     * Get a snapshot's sessions
     *
     * @param	int
     * @return	array
     */
    public function get_snapshot_sessions($snapshot_id)
    {
        $this->order_by('sort_order', 'ASC');

        $sessions_data = $this->get_many_by('snapshot_id', $snapshot_id);

        return $sessions_data;
    }

    /**
     * Purge the cached rows
     *
     * @param	mixed
     * @return	mixed
     */
    public function purge_cache($data = NULL)
    {
        $this->cache = array();

        return $data;
    }


    /**
     * Return the model prototype
     *
     * @param	int
     * @return	object
     */
    public function create_prototype($client_id = NULL,$snapshot_id = NULL)
    {

        date_default_timezone_set('UTC');
        $this->prototype['client_id'] = $client_id;
        $this->prototype['snapshot_id'] = $snapshot_id;
        $this->prototype['name'] = "sessions";

        $this->load->model('client_snapshot_model');
        $snapshot = $this->client_snapshot_model->get_many_by('id', $snapshot_id);
        $this->prototype['commence'] = $snapshot[0]->commence;
        $this->prototype['expire'] = $snapshot[0]->commence;

        return (object) $this->prototype;
    }




    /**
     * Set or modify post data on create
     *
     * @param	array
     * @return	array
     */
    protected function modify_post_data($data)
    {
        $correct_time = true;
        // unset the unique_id if set
        if ( isset($data['unique_id']) )
        {
            unset($data['unique_id']);
        }

        //get snapshot commence/expire time
        $this->load->model('client_snapshot_model');
        $snapshot_id = $data['snapshot_id'];
        $snapshot = $this->client_snapshot_model->get($snapshot_id);
        $snapshot->commence = gmdate("Y-m-dH:i", $snapshot->commence);
        $snapshot->commence = preg_replace('/\D/', '', $snapshot->commence);

        //check if expire time is smaller than the commence time, set $correct_time = false;
        if(isset($data['commence']) && isset($data['expire']) && $data['commence'] > $data['expire'])
        {
            $correct_time = false;
        }


        //check if $data commence time is smaller than snapshots commence time
        if(isset($data['commence']) && isset($snapshot->commence) && $data['commence'] < $snapshot->commence)
        {
            $correct_time = false;
        }




        // set the expire date/time
        if ( ! isset($data['expire']) )
        {
            $data['expire'] = strtotime('tomorrow +10 years') - 1;
        }
        else
        {
            date_default_timezone_set('UTC');

            if(!$correct_time)
            {
                //make the expire time 1 hour greater than the commence time
                $data['expire'] = date("U",strtotime($snapshot->commence) + 3600);

            }else
            {
                $data['expire'] = date("U",strtotime($data['expire']));

            }
        }

        // set the commence date/time
        if ( ! isset($data['commence']) )
        {
            $data['commence'] = strtotime('today') + 1;
        }
        else{
            date_default_timezone_set('UTC');
            if(!$correct_time)
            {
                $data['commence'] = date("U",strtotime($snapshot->commence));
            }else
            {
                $data['commence'] = date("U",strtotime($data['commence']));
            }
        }

        //implode group_id from data
        if(isset($data['group_id']) && is_array($data['group_id']))
        {
            $data['group_id'] = implode(',',$data['group_id']);
        }

        //implode user_id from data
        if(isset($data['user_id']) && is_array($data['user_id']))
        {
            $data['user_id'] = implode(',',$data['user_id']);
        }

        return $data;
    }


    /**
     * Set or modify post data on create
     *
     * @param	int
     * @param	string
     * @return	void
     */
    public function update_sort_order($client_id, $session_ids)
    {
        $session_ids = explode('-', $session_ids);

        $sort_order = 1;

        foreach ($session_ids as $session_id)
        {
            $this->_database->where('client_id', $client_id);

            $this->update($session_id, array('sort_order' => $sort_order), TRUE);

            $sort_order++;
        }
    }



}