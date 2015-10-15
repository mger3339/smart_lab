<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends Smartlab_model {


	/**
	 * Model internal vars
	 */
	private $_base_user_roles;
	private $_filters;


	/**
	 * Enable soft delete
	 */
	protected $soft_delete = TRUE;

	/**
	 * Relationships
	 */
	protected $has_many = array('notifications');

	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


	/**
	 * Prototype for row creation
	 */
	private $prototype = array(
			'id'				=> 0,
			'active'			=> 1,
			'locked'			=> 0,
			'admin'				=> 0,
			'super_admin'		=> 0,
			'super_admin_admin'	=> 0,
			'client_id'			=> NULL,
			'user_role'			=> 'user',
			'email'				=> NULL,
			'username'			=> NULL,
            'password'          => '',
			'firstname'			=> '',
			'lastname'			=> '',
			'country_iso'		=> NULL,
			'time_zone'			=> NULL,
			'currency'			=> NULL,
            'group_id'          => NUll,
            'city'              => '',
	);


	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> 'client_id',
			'label'		=> 'client ID',
			'rules'		=> 'required|trim|integer'
		),
		array(
			'field'		=> 'email',
			'label'		=> 'email',
			'rules'		=> 'required|trim|valid_email|valid_email_hostname|is_client_unique[users.email]'
		),
		array(
			'field'		=> 'username',
			'label'		=> 'username',
			'rules'		=> 'trim|max_length[60]|is_client_unique[users.username]'
		),
        array(
            'field'   => 'password',
            'label'   => 'password',
            'rules'   => 'trim'
        ),
		array(
			'field'		=> 'firstname',
			'label'		=> 'firstname',
			'rules'		=> 'required|trim|max_length[60]'
		),
		array(
			'field'		=> 'lastname',
			'label'		=> 'lastname',
			'rules'		=> 'required|trim|max_length[60]'
		),
		array(
			'field'		=> 'country_iso',
			'label'		=> 'country',
			'rules'		=> 'required|trim|max_length[2]'
		),
		array(
			'field'		=> 'time_zone',
			'label'		=> 'time zone',
			'rules'		=> 'required|trim|max_length[60]'
		),
		array(
			'field'		=> 'currency',
			'label'		=> 'currency',
			'rules'		=> 'required|trim|max_length[3]'
		),
        array(
            'field'		=> 'city',
            'label'		=> 'city',
            'rules'		=> 'trim|max_length[60]'
        ),

	);


	/**
	 * Model observers
	 */
	public $before_create		= array(
										'add_default_data',
										'set_created_time',
										'set_modified_time',
										'modify_post_data',
								);
	public $after_create		= array('create_dependencies');
	public $before_update		= array('set_modified_time', 'modify_post_data');
	public $before_delete		= array('delete_dependencies');
	public $before_get			= array('where_client_id', 'where_active');
	public $after_get			= array('remove_protected_data', 'add_default_properties');


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// get and set the base user roles
		$this->load->model('user_role_model');
        $this->_base_user_roles = $this->user_role_model->get_roles();
	}


	/**
	 * Return the model prototype
	 *
	 * @param	bool	(optional)
	 * @return	object
	 */
	public function create_prototype($return_as_array = FALSE)
	{
		$this->prototype['country_iso'] = DEFAULT_COUNTRY_ISO;
		$this->prototype['time_zone'] = DEFAULT_TIMEZONE;
		$this->prototype['currency'] = DEFAULT_CURRENCY;
		
		if ( $return_as_array )
		{
			return $this->prototype;
		}
		
		return (object) $this->prototype;
	}


	/**
	 * Look up & return a user by email or username
	 *
	 * @param	string
	 * @return	object or null
	 */
	public function get_by_email_or_username($value)
	{
		$user = $this->get_by('email', $value);
		
		if ( ! $user)
		{
			$user = $this->get_by('username', $value);
		}
		
		return $user;
	}


	/**
	 * Look up & return a super admin user given an ID, email or username
	 *
	 * @param	mixed
	 * @return	object or null
	 */
	public function get_super_admin_user($value)
	{
		if (is_int($value))
		{
			$query = $this->_database->get_where($this->_table, array(
				'id'			=> $value,
				'deleted !='	=> 1,
				'active'		=> 1,
				'super_admin'	=> 1,
			));
		}
		else
		{
			$query = $this->_database->get_where($this->_table, array(
				'email'			=> $value,
				'deleted !='	=> 1,
				'active'		=> 1,
				'super_admin'	=> 1,
			));

			if ($query->num_rows() == 0)
			{
				$query = $this->_database->get_where($this->_table, array(
					'username'		=> $value,
					'deleted !='	=> 1,
					'active'		=> 1,
					'super_admin'	=> 1,
				));
			}
		}

		$user = $query->row();

		$this->remove_protected_data($user);
		$this->add_default_properties($user);

		return $user;
	}


	/**
	 * Look up & return a client admin user given an ID, email or username
	 * - will also query for super admin users
	 *
	 * @param	int
	 * @param	mixed
	 * @return	object or null
	 */
	public function get_client_admin_user($client_id, $value)
	{
		if (is_int($value))
		{
			$query = $this->_database
				->get_where($this->_table, array(
					'id'			=> $value,
					'client_id'		=> $client_id,
					'admin'			=> 1,
				));
		}
		else
		{
			$query = $this->_database
				->get_where($this->_table, array(
					'email'			=> $value,
					'client_id'		=> $client_id,
					'admin'			=> 1,
				));

			if ($query->num_rows() == 0)
			{
				$query = $this->_database
					->get_where($this->_table, array(
						'username'		=> $value,
						'client_id'		=> $client_id,
						'admin'			=> 1,
					));
			}
		}

		if ($query->num_rows() == 0)
		{
			return $this->get_super_admin_user($value);
		}

		$user = $query->row();

		$this->remove_protected_data($user);
		$this->add_default_properties($user);

		return $user;
	}


	/**
	 * Look up & return a user's password
	 *
	 * @param	int
	 * @return	string
	 */
	public function get_user_password($id)
	{
		$query = $this->_database->get_where($this->_table, array('id' => $id));

		$user = $query->row();

		$hash = $user->password;

		return $hash;
	}


	/**
	 * Remove any sensitive data from row
	 *
	 * @param	object
	 * @return	object
	 */
	protected function remove_protected_data($data)
	{
		if ( ! is_object($data) )
		{
			return $data;
		}

		if ( property_exists($data, 'password') )
		{
			unset($data->password);
		}

		return $data;
	}


	/**
	 * Add default properties to row on get
	 *
	 * @param	object
	 * @return	object
	 */
	protected function add_default_properties($data)
	{
		if ( is_object($data) )
		{
			// set the user's fullname
			$data->fullname = $data->firstname . ' ' . $data->lastname;

			// set up the user's user role properties
			$data->user_role_properties = (object) array(
					'user_role'		=> $data->user_role,
					'name'			=> $this->_base_user_roles[$data->user_role]['name'],
					'admin'			=> $this->_base_user_roles[$data->user_role]['admin'],
					'guest'			=> $this->_base_user_roles[$data->user_role]['guest'],
					'default'		=> $this->_base_user_roles[$data->user_role]['default'],
			);
		}

        // add the users groups
        if (is_object($data) && !property_exists($data, 'group_id'))
        {
            $this->load->model('client_user_group_model');
            $group_ids = $this->client_user_group_model->get_groups('user_id',$data->id);
            $data->group_id = implode(',',$group_ids);

        }

		return $data;
	}


	/**
	 * Add any reqiured default data on create
	 *
	 * @param	array
	 * @return	array
	 */
	protected function add_default_data($data)
	{

		if ( ! isset($data['client_id']) )
		{
			$data = $this->set_client_id($data);
		}

		if ( ! isset($data['username']) )
		{
			$data['username'] = $data['email'];
		}

		if ( ! isset($data['password']) )
		{
			// create a dummy password
			$this->load->helper('string');
			$this->load->library('phpass');
			$data['password'] = $this->phpass->hash(random_string('alnum', 8));
		}

		return $data;
	}


	/**
	 * Set or modify post data
	 *
	 * @param	array
	 * @return	array
	 */
	protected function modify_post_data($data)
	{

		// unset the unique_id if set
		if (isset($data['unique_id']))
		{
			unset($data['unique_id']);
		}

		// set the admin boolean and user role
		if ( isset($data['user_role']) )
		{
			if ( $this->_base_user_roles[$data['user_role']]['admin'] == TRUE )
			{
				$data['admin'] = 1;
			}
			else
			{
				$data['admin'] = 0;
			}
		}
		//implode group_id from data
        if(isset($data['group_id']) && is_array($data['group_id']))
        {
            $data['group_id'] = implode(',',$data['group_id']);
        }

		return $data;
	}


	/**
	 * Wrapper for update method which removes or sets
	 * any properties that users should not set themselves
	 *
	 * @param	int
	 * @param	array
	 * @return	function
	 */
	public function safe_update($user_id, $data)
	{
		unset($data['admin']);
		unset($data['super_admin']);
		unset($data['super_admin_admin']);
		unset($data['user_role']);

		$data = $this->set_client_id($data);

		return $this->update($user_id, $data);
	}


	/**
	 * Update a user's last activity and IP address
	 *
	 * @param	int
	 * @param	int
	 * @param	string	(optional)
	 * @return	void
	 */
	public function update_last_activity($user_id, $time, $ip_address = NULL)
	{
		$data = array(
			'last_activity'		=> $time,
		);

		if ( $ip_address )
		{
			$data['last_ip'] = $ip_address;
		}

		$this->_database
			->where('id', $user_id)
			->update($this->_table, $data);
	}


	/**
	 * Create dependent data in other tables
	 *
	 * @param	int
	 * @return	int
	 */
	protected function create_dependencies($id)
	{
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
		return $id;
	}

    /**
     * Search users
     *
     * @param	array
     * @return	object
     */
    public function search_users($data,$num = '', $offset = '')
    {
        $is_empty = true;
        foreach($data as $value){
            if(!empty($value))
                $is_empty = false;
        }
        if(!$is_empty){
            $this->_database->where('client_id',$this->client->id);
            $this->_database->where('deleted',0);
            if($data['free_text'])
            {
                $this->_database->group_start();
                $free_text = $data['free_text'];
                $this->_database->like("username", $free_text);
                $this->_database->or_like("firstname", $free_text);
                $this->_database->or_like("lastname", $free_text);
                $this->_database->or_like("email", $free_text);

                $this->_database->group_end();

            }
            if($data['group_id'])
            {
                $group_id = $data['group_id'];
                $this->_database->join('client_user_groups', 'users.id = client_user_groups.user_id', 'left');
                $this->_database->where("client_user_groups.group_id", $group_id);
            }

            if($data['time_zone'])
            {
                $time_zone = $data['time_zone'];
                $this->_database->where("time_zone", $time_zone);
            }

            if($data['user_role'])
            {
                $user_role = $data['user_role'];
                $this->_database->where("user_role", $user_role);
            }

            if($data['country_iso'])
            {
                $country_iso = $data['country_iso'];
                $this->_database->join('countries', 'countries.country_iso = users.country_iso', 'left');
                $this->_database->where("users.country_iso",$country_iso);
            }

            if($data['city'])
            {
                $city = $data['city'];
                $this->_database->like("city", $city);

            }
            $data_time = array();
            $this->db->select('created');
            $this->db->from('users');
            $data = $this->db->get()->result();
            foreach($data as $value)
            {
                $date = date('d-m-Y', $value->created);
                array_push($data_time, $date);
            }
            if($data['commence'])
            {
                $commence = $data['commence'];
                $this->db->where('created >=', $commence);
            }

            $this->_database->select('users.*');

            if(!empty($num) || !empty($offset)){
                $this->_database->limit($offset,$num);
            }
            $this->_database->where("users.id !=",$_SESSION["user_id"]);

            $query = $this->_database->get('users');
            $result = $query->result_object();
        }else{
            $this->order_by('lastname', 'ASC');
            $this->_database->where("users.id !=",$_SESSION["user_id"]);
            $query = $this->set_active_only(FALSE);
            if(!empty($num) || !empty($offset)){
                $this->_database->limit($offset,$num);
            }
            $result = $query->get_all();
        }

        return $result;
    }

    public function update_user_role($user_id,$data)
    {
        $this->_database->where('id', $user_id);
        $this->_database->where('client_id', $data['client_id']);
        $update = array('user_role'=>$data['user_role']);

        return $this->_database->update('users', $update);

    }

    public function get_users($num, $offset)
    {
//        $query = $this->_database->query("SELECT * FROM `users` WHERE `deleted` =0 ORDER BY `lastname` ASC LIMIT " . $num . ", " . $offset);
        $this->_database->order_by('lastname','ASC');
        $this->_database->where('deleted',0);
        $this->_database->where("users.id !=",$_SESSION["user_id"]);
        $this->_database->limit($offset,$num);

        $query = $this->_database->get('users',$offset, $num);

        return $query->result_object();
    }

    public function get_users_by_id($data)
    {
            $this->_database->order_by('lastname','ASC');
            $this->_database->where('deleted',0);
            $this->_database->where("users.id !=",$_SESSION["user_id"]);
            $this->_database->where_in('users.id',$data);
            $query = $this->_database->get('users');
            return $query->result_object();
    }

}