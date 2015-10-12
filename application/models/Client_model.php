<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_model extends Smartlab_model {


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
			'id'					=> 0,
			'active'				=> 1,
			'name'					=> NULL,
			'slug'					=> NULL,
			'hostname'				=> NULL,
			'admin_user_id'			=> NULL,
			'language'				=> NULL,
			'country_iso'			=> NULL,
			'time_zone'				=> NULL,
			'currency'				=> NULL,
			'user_auto_register'	=> 0,
			'session_timeout'		=> 3600,
			'commence'				=> NULL,
			'expire'				=> NULL,
			'enable_ssl'			=> 0,
	);


	/**
	 * Cache container for row storage
	 */
	private $cache = array();


	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> 'name',
			'label'		=> 'client name',
			'rules'		=> 'required|trim|max_length[60]'
		),
		array(
			'field'		=> 'slug',
			'label'		=> 'client slug',
			'rules'		=> 'required|trim|alpha_dash|max_length[60]|is_unique[clients.slug]'
		),
		array(
			'field'		=> 'hostname',
			'label'		=> 'client hostname',
			'rules'		=> 'trim|max_length[255]|is_unique[clients.hostname]'
		),
		array(
			'field'		=> 'admin_user_id',
			'label'		=> 'client admin user ID',
			'rules'		=> 'required|trim|integer'
		),
		array(
			'field'		=> 'language',
			'label'		=> 'client language',
			'rules'		=> 'required|trim|max_length[60]'
		),
		array(
			'field'		=> 'country_iso',
			'label'		=> 'client country',
			'rules'		=> 'required|trim|max_length[2]'
		),
		array(
			'field'		=> 'time_zone',
			'label'		=> 'client time zone',
			'rules'		=> 'required|trim|max_length[60]'
		),
		array(
			'field'		=> 'currency',
			'label'		=> 'client currency',
			'rules'		=> 'required|trim|max_length[3]'
		),
		array(
			'field'		=> 'user_auto_register',
			'label'		=> 'client global login',
			'rules'		=> 'trim'
		),
		array(
			'field'		=> 'session_timeout',
			'label'		=> 'client session timeout',
			'rules'		=> 'trim|integer|max_length[11]'
		),
		array(
			'field'		=> 'commence',
			'label'		=> 'client commencement',
			'rules'		=> 'trim|max_length[11]'
		),
		array(
			'field'		=> 'expire',
			'label'		=> 'client expiry',
			'rules'		=> 'trim|max_length[11]'
		),
	);


	/**
	 * TLD for client slug validation and URL definition
	 */
	private $top_level_domain;


	/**
	 * Model observers
	 */
	public $before_create		= array('purge_cache', 'set_created_time', 'set_modified_time', 'set_user_id', 'modify_post_data');
	public $after_create		= array('create_dependencies');
	public $before_update		= array('purge_cache', 'set_modified_time', 'modify_post_data');
	public $before_delete		= array('purge_cache', 'delete_dependencies');
	public $after_get			= array('remove_protected_data', 'add_default_properties');


	/**
	 * Return the model prototype
	 *
	 * @return	object
	 */
	public function create_prototype()
	{
		$this->prototype['id'] = intval(microtime(TRUE) * 10000);
		
		$this->prototype['country_iso'] = DEFAULT_COUNTRY_ISO;
		$this->prototype['time_zone'] = DEFAULT_TIMEZONE;
		$this->prototype['currency'] = DEFAULT_CURRENCY;
		
		$this->prototype['commence'] = strtotime('today');
		$this->prototype['expire'] = strtotime('+5 years');
		
		return (object) $this->prototype;
	}


	/**
	 * Get row - redefined to include cache functionality
	 *
	 * @param	int
	 * @return	object or false
	 */
	public function get($id)
	{
		if ( ! isset($this->cache[$id]) )
		{
			$this->cache[$id] = parent::get($id);
		}
		
		return $this->cache[$id];
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
	 * Build the current client
	 *
	 * @param	string
	 * @param	string	optional
	 * @return	object or null
	 */
	public function get_current($hostname, $slug = FALSE)
	{
		$client = $this->get_by('hostname', $hostname);
		
		if ( ! $client && $slug !== FALSE )
		{
			$client = $this->get_by('slug', $slug);
		}
		
		// if we still have no client check for the super admin subdomain
		if ( ! $client && ($slug == 'superadmin' || $slug == 'super-admin') )
		{
			$client = (object) array(
					'id'					=> 0,
					'active'				=> 1,
					'name'					=> APPLICATION_NAME . ' Super Admin',
					'slug'					=> $slug,
					'hostname'				=> NULL,
					'admin_user_id'			=> 1,
					'language'				=> 'english',
					'country_iso'			=> DEFAULT_COUNTRY_ISO,
					'time_zone'				=> DEFAULT_TIMEZONE,
					'currency'				=> DEFAULT_CURRENCY,
					'user_auto_register'	=> 0,
					'session_timeout'		=> 3600,
					'commence'				=> NULL,
					'expire'				=> NULL,
					'enable_ssl'			=> 0,
			);
			
			$client = $this->add_default_properties($client);
		}
		
		// set the client ID in the parent class (Smartlab_model)
		$this->_client_id = $client->id;
		
		return $client;
	}


	/**
	 * Look up & return a client's auto register password
	 *
	 * @param	int
	 * @return	string
	 */
	public function get_auto_register_password($id)
	{
		$query = $this->_database->get_where($this->_table, array('id' => $id));
		
		$client = $query->row();
		
		$hash = $client->auto_register_password;
		
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
		
		if ( property_exists($data, 'auto_register_password') )
		{
			unset($data->auto_register_password);
		}
		
		return $data;
	}


	/**
	 * Define extra client row properties
	 *
	 * @param	object
	 * @return	object
	 */
	protected function add_default_properties($row)
	{
		// define the client base URL
		// based on slug, hostname and if SSL is enabled
		if (is_object($row) && ! property_exists($row, 'url'))
		{
			$protocol = 'http://';
			
			if ($row->enable_ssl == 1)
			{
				$protocol = 'https://';
			}
			
			$row->url = $protocol;
			
			if ($row->hostname)
			{
				$row->url.= $row->hostname;
			}
			else
			{
				$row->url.= $row->slug . '.' . $this->get_top_level_domain();
			}
		}
		
		// add the client admin user
		if (is_object($row) && ! property_exists($row, 'admin_user'))
		{
			$this->load->model('user_model');
			
			$row->admin_user = $this->user_model->get_client_admin_user($row->id, intval($row->admin_user_id));
		}
		
		// add the client applications
		if (is_object($row) && ! property_exists($row, 'applications'))
		{
			$this->load->model('client_application_model');
			
			$row->applications = $this->client_application_model->get_client_applications($row->id);

            //get only those applications that are not assigned to snapshots
            foreach($row->applications as $key => $value)
            {
                if($value->snapshot_id)
                {
                    unset($row->applications[$key]);
                }
            }

        }
		
		// add the client settings
		if (is_object($row) && ! property_exists($row, 'settings'))
		{
			$this->load->model('client_setting_model');
			
			$row->settings = $this->client_setting_model->get_client_settings($row->id);
		}

        // add the client snapshot
        if (is_object($row) && ! property_exists($row, 'snapshots'))
        {
            $this->load->model('client_snapshot_model');

            $row->snapshots = $this->client_snapshot_model->get_client_snapshot($row->id);
        }

        // add the client group
        if (is_object($row) && ! property_exists($row, 'groups'))
        {
            $this->load->model('client_group_model');

            $row->groups = $this->client_group_model->get_client_group($row->id);
        }


        // add the client user roles
		if (is_object($row) && ! property_exists($row, 'user_roles'))
		{
			$this->load->model('client_user_role_model');
			
			$user_roles = array();
			
			$client_user_roles = $this->client_user_role_model->get_many_by('client_id', $row->id);
			
			foreach ($client_user_roles as $role)
			{
				$user_roles[] = $role->user_role;
			}
			
			$row->user_roles = $user_roles;
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
		// get the newly created client row object
		$new_client = $this->get($id);
		
		// add the default client options
		$this->load->model('client_setting_model');
		$this->client_setting_model->set_client_default_settings($id);
		
		// add the default user roles for the new client
		$this->load->model('user_role_model');
		$default_roles = $this->user_role_model->get_default_roles();
		
		$this->load->model('client_user_role_model');
		foreach ($default_roles as $role => $attr)
		{
			$this->client_user_role_model->insert(array(
				'client_id'			=> $id,
				'user_role'			=> $role,
			), TRUE);
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
		// delete the client's users
		$this->load->model('user_model');
		$this->user_model->delete_by('client_id', $id);
		
		return $id;
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
		if ( isset($data['unique_id']) )
		{
			unset($data['unique_id']);
		}
		
		// correctly format the hostname if set
		if ( isset($data['hostname']) )
		{
			$hostname = $data['hostname'];
			$hostname = strtolower($hostname);
			$hostname = str_replace('http://', '', $hostname);
			$hostname = str_replace('https://', '', $hostname);
			$data['hostname'] = $hostname;
		}
		
		// correctly set the commence and expire values
		if ( isset($data['time_zone']) )
		{
			date_default_timezone_set($data['time_zone']);
			
			if ( ! isset($data['commence']) )
			{
				$data['commence'] = strtotime('today') + 1;
			}
			else
			{
				$data['commence'] = strtotime('today', strtotime($data['commence'])) + 1;
			}
			
			if ( ! isset($data['expire']) )
			{
				$data['expire'] = strtotime('tomorrow +10 years') - 1;
			}
			else
			{
				$data['expire'] = strtotime('tomorrow', strtotime($data['expire'])) - 1;
			}
		}
		
		return $data;
	}


	/**
	 * Get top level domain from HTTP host
	 *
	 * @return	void
	 */
	private function get_top_level_domain()
	{
		if ($this->top_level_domain)
		{
			return $this->top_level_domain;
		}
		
		if ( ! is_cli() )
		{
			$domain = $_SERVER['HTTP_HOST'];
		}
		else
		{
			if (isset($_ENV["HOSTNAME"]))
			{
				$domain = $_ENV["HOSTNAME"];
			}
			else if (isset($_ENV["COMPUTERNAME"]))
			{
				$domain = $_ENV["COMPUTERNAME"];
			}
			else
			{
				$domain = '';
			}
		}
		
		$domain_parts = explode('.', $domain);
		$sub_domain = array_shift($domain_parts);
		
		$client = $this->_database->get_where($this->_table, array('slug' => $sub_domain), 1);
		
		if ($client->result())
		{
			$domain = str_ireplace($sub_domain . '.', '', $domain);
		}
		
		$domain = str_ireplace(array('super-admin.', 'superadmin.'), '', $domain);
		
		$this->top_level_domain = $domain;
		
		return $domain;
	}


}