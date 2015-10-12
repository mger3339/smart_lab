<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_application_model extends Smartlab_model {


	/**
	 * Enable soft delete
	 */
	protected $soft_delete = TRUE;


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


    /**
     * Prototype for row creation
     */
    private $prototype = array(
        'active'			=> 1,
        'client_id'			=> NULL,
        'application'		=> NULL,
        'name'				=> NULL,
        'commence'			=> NULL,
        'expire'			=> NULL,
        'accent_color'		=> NULL,
        'user_id'           => NULL,
        'group_id'          => NULL,
        'facilitator_id'    => NULL,
    );


    /**
	 * Default applications container
	 */
	private $default_applications = array();


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
			'field'		=> 'application',
			'label'		=> 'application',
			'rules'		=> 'required|trim|max_length[30]'
		),
		array(
			'field'		=> 'name',
			'label'		=> 'application name',
			'rules'		=> 'required|trim|max_length[60]|is_client_unique[client_applications.name]'
		),
        array(
            'field'		=> 'commence',
            'label'		=> 'application commencement',
            'rules'		=> 'trim|max_length[12]'
        ),
        array(
            'field'		=> 'expire',
            'label'		=> 'application expiry',
            'rules'		=> 'trim|max_length[12]'
        ),
        array(
            'field'		=> 'accent_color',
            'label'		=> 'application color',
            'rules'		=> 'trim|max_length[6]'
        ),
	);


	/**
	 * Model observers
	 */
	public $before_create		= array('purge_cache', 'set_created_time', 'set_modified_time', 'modify_post_data');
	public $after_create		= array('create_dependencies');
	public $before_update		= array('purge_cache', 'set_modified_time', 'modify_post_data');
	public $before_delete		= array('purge_cache', 'delete_dependencies');
	public $after_get			= array('add_default_properties');


	/**
	 * Constructor
	 */
	public function __construct()
    {
        parent::__construct();

        $this->load->model('application_model');
		$this->default_applications = $this->application_model->get_all();
    }


	/**
	 * Get a client's applications
	 *
	 * @param	int
	 * @return	array
	 */
	public function get_client_applications($client_id)
	{
		if ( ! isset($this->cache[$client_id]) )
		{
			$this->order_by('sort_order', 'ASC');

			$applications_data = $this->get_many_by('client_id', $client_id);

			$client_applications = array();

			// purge any applications that may no longer exist in the system
			foreach ($applications_data as $row)
			{
				if ( array_key_exists($row->application, $this->default_applications) )
				{
					$client_applications[$row->id] = $row;
				}
				else
				{
					$this->delete($row->id);
				}
			}

			$this->cache[$client_id] = $client_applications;
		}

		return $this->cache[$client_id];
	}

    /**
     * Get a snapshot's applications
     *
     * @param	int
     * @return	array
     */
    public function get_snapshot_applications($snapshot_id)
    {
            $this->order_by('sort_order', 'ASC');

            $applications_data = $this->get_many_by('snapshot_id', $snapshot_id);

            return $applications_data;
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

        $this->load->model('application_model');

        date_default_timezone_set('UTC');

        $applications = $this->application_model->get_all();
        $applications_colors = $this->application_model->get_application_colors();

        $this->prototype['client_id'] = $client_id;
        $this->prototype['snapshot_id'] = $snapshot_id;
		$this->prototype['application'] = key($applications);
        $this->prototype['application_color'] = key($applications_colors);
        $this->prototype['accent_color'] = $applications_colors[key($applications_colors)];
        $this->prototype['name'] = $applications[key($applications)]['name'];
        $this->prototype['commence'] = strtotime('today 0:00');
        $this->prototype['expire'] = strtotime('+1 week 0:00');

		return (object) $this->prototype;
	}


	/**
	 * Define extra client application row properties
	 *
	 * @param	object
	 * @return	object
	 */
	protected function add_default_properties($row)
	{
		if (is_object($row) && array_key_exists($row->application, $this->default_applications))
		{
			$row->original_name = $this->default_applications[$row->application]['name'];

			// add application modules if relevant
			$row->modules = NULL;

			if (is_array($this->default_applications[$row->application]['modules']))
			{
				$this->load->model('client_application_module_model');
				$modules = $this->client_application_module_model->get_application_modules($row->id);

				if ($modules)
				{
					$row->modules = $modules;
				}
			}
		}
        // add the applications groups
        if (is_object($row) && !property_exists($row, 'group_id'))
        {
            $this->load->model('client_application_group_model');
            $group_ids = $this->client_application_group_model->get_groups('application_id',$row->id);
            $row->group_id = implode(',',$group_ids);

        }
        // add the application groups
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

        // add the application facilitators
        if (is_object($row) && !property_exists($row, 'facilitators'))
        {
            $this->load->model('user_model');
            if(isset($row->facilitator_id)){
                $facilitator_ids = explode(',',$row->facilitator_id);
                $row->facilitators = $this->user_model->get_many_by('id',$facilitator_ids);
            }else{
                $row->facilitators = [];
            }

        }

		return $row;
	}


	/**
	 * Create dependent data in other tables
	 *
	 * @param	int
	 * @return	int
	 */
	protected function create_dependencies($id)
	{
		if (is_int($id))
		{
			$application = $this->_database->get_where($this->_table, array('id' => $id))->row();

			// add any (relevant) modules
			$application_modules = $this->default_applications[$application->application]['modules'];
			$default_modules = $this->default_applications[$application->application]['default_modules'];

			if (is_array($application_modules) && is_array($default_modules))
			{
				$this->load->model('client_application_module_model');

				$sort_order = 1;
				foreach ($application_modules as $module => $name)
				{
					$application_module_data = array(
						'active'					=> 0,
						'client_application_id'		=> $id,
						'application'				=> $application->application,
						'module'					=> $module,
						'name'						=> $name,
						'sort_order'				=> $sort_order,
						'commence'				    => $name->commence,
						'expire'				    => $name->expire,
						'accent_color'				=> $name->accent_color,
					);

					if (in_array($module, $default_modules))
					{
						$application_module_data['active'] = 1;
					}

					$this->client_application_module_model->insert($application_module_data, TRUE);

					$sort_order++;
				}
			}
		}

		return $id;
	}


	/**
	 * Delete dependent data in other tables
	 *
	 * @param	int
	 * @return	int
	 */
	protected function delete_dependencies($id)
	{
		$this->load->model('client_application_module_model');
		$this->client_application_module_model->delete_by('client_application_id', $id);

		return $id;
	}


    /**
     * Get users by search
     *
     * @param	string
     * @return	array
     */
    public function search_users($keyword,$ids)
    {
        $this->_database->where('client_id',$this->client->id);
        $this->_database->where('deleted',0);
        $this->_database->where_not_in('user_role','facilitator');
        if($ids)
            $this->_database->where_not_in('id',$ids);
        $this->_database->group_start();
        $this->_database->where("username LIKE '%$keyword%'");
        $this->_database->where_in("firstname LIKE '%$keyword%'");
        $this->_database->where_in("lastname LIKE '%$keyword%'");
        $this->_database->where_in("email LIKE '%$keyword%'");
        $this->_database->group_end();
        $query = $this->_database->get('users');
        return $query->result_array();
    }


    /**
     * Get groups by search
     *
     * @param	string
     * @return	array
     */
    public function search_groups($keyword,$ids)
    {
        $this->_database->where('client_id',$this->client->id);
        $this->_database->where('deleted',0);
        if($ids)
            $this->_database->where_not_in('id',$ids);
        $this->_database->where("name LIKE '%$keyword%'");
        $query = $this->_database->get('client_groups');
        return $query->result_array();
    }


    /**
     * Get facilitators by search
     *
     * @param	string
     * @return	array
     */
    public function search_facilitators($keyword,$ids)
    {
        $this->_database->where('client_id',$this->client->id);
        $this->_database->where('deleted',0);
        $this->_database->where('user_role','facilitator');
        if($ids)
            $this->_database->where_not_in('id',$ids);
        $this->_database->group_start();
        $this->_database->where("username LIKE '%$keyword%'");
        $this->_database->where_in("firstname LIKE '%$keyword%'");
        $this->_database->where_in("lastname LIKE '%$keyword%'");
        $this->_database->where_in("email LIKE '%$keyword%'");
        $this->_database->group_end();
        $query = $this->_database->get('users');
        return $query->result_array();
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

		// set the sort order
		if ( isset($data['client_id']) )
		{
			$data['sort_order'] = $this->count_by('client_id', $data['client_id']) + 1;
		}


        //check if expire time is smaller than the commence time, set $correct_time = false;
        if(isset($data['commence']) && isset($data['expire']) && $data['commence'] > $data['expire'])
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
                $data['expire'] = date("U",strtotime($data['commence']) + 3600);

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
            $data['commence'] = date("U",strtotime($data['commence']));
        }

        //implode group_id from data
        if(isset($data['group_id']) && is_array($data['group_id']))
        {
            $data['group_id'] = implode(',',$data['group_id']);
        }

        //implode facilitator_id from data
        if(isset($data['facilitator_id']) && is_array($data['facilitator_id']))
        {
            $data['facilitator_id'] = implode(',',$data['facilitator_id']);
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
	public function update_sort_order($client_id, $application_ids)
	{
		$application_ids = explode('-', $application_ids);
		
		$sort_order = 1;
		
		foreach ($application_ids as $application_id)
		{
			$this->_database->where('client_id', $client_id);
			
			$this->update($application_id, array('sort_order' => $sort_order), TRUE);
			
			$sort_order++;
		}
	}


}