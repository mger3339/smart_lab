<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_application_module_model extends Smartlab_model {


	/**
	 * Enable soft delete
	 */
	protected $soft_delete = TRUE;


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


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
			'field'		=> 'client_application_id',
			'label'		=> 'client application id',
			'rules'		=> 'required|trim|integer'
		),
		array(
			'field'		=> 'application',
			'label'		=> 'application',
			'rules'		=> 'required|trim|max_length[30]'
		),
		array(
			'field'		=> 'module',
			'label'		=> 'module',
			'rules'		=> 'required|trim|max_length[30]'
		),
		array(
			'field'		=> 'name',
			'label'		=> 'module name',
			'rules'		=> 'required|trim|max_length[60]'
		),
	);


	/**
	 * Model observers
	 */
	public $before_create		= array('purge_cache', 'set_created_time', 'set_modified_time', 'modify_post_data');
	public $before_update		= array('purge_cache', 'set_modified_time', 'modify_post_data');
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
	 * Get an application's modules
	 *
	 * @param	int
	 * @return	array
	 */
	public function get_application_modules($client_application_id)
	{
		if ( ! isset($this->cache[$client_application_id]))
		{
			$this->order_by('sort_order', 'ASC');
			
			$modules_data = $this->get_many_by('client_application_id', $client_application_id);
			
			$first_row = reset($modules_data);
			$client_application_id = $first_row->client_application_id;
			$application = $first_row->application;
			$registered_application_modules = array();
			$application_modules = array();
			
			// purge any application modules that may no longer exist in the system
			foreach ($modules_data as $row)
			{
				if (array_key_exists($row->module, $this->default_applications[$application]['modules']))
				{
					$registered_application_modules[] = $row->module;
					$application_modules[] = $row;
				}
				else
				{
					$this->delete($row->id);
				}
			}
			
			// add any modules that now exist in the system but are not in the DB
			foreach ($this->default_applications[$application]['modules'] as $module => $name)
			{
				if ( ! in_array($module, $registered_application_modules))
				{
					$sort_order = count($application_modules) + 1;
					
					$data = array(
						'active'					=> 0,
						'client_application_id'		=> $client_application_id,
						'application'				=> $application,
						'module'					=> $module,
						'name'						=> $name,
						'sort_order'				=> $sort_order,
					);
					
					$application_modules[] = (object) $data;
					
					$this->insert($data, TRUE);
				}
			}
			
			$this->cache[$client_application_id] = $application_modules;
		}
		
		return $this->cache[$client_application_id];
	}


	/**
	 * Sort application modules
	 *
	 * @param	string
	 * @return	void
	 */
	public function sort_application_modules($ids = FALSE)
	{
		if ($ids)
		{
			$ids = explode('-', $ids);
			
			$sort_order = 1;
			
			foreach ($ids as $application_module_id)
			{
				$this->_database->where('id', $application_module_id);
				$this->_database->update($this->_table, array('sort_order' => $sort_order));
				
				$sort_order++;
			}
		}
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
	 * Define extra client application row properties
	 *
	 * @param	object
	 * @return	object
	 */
	protected function add_default_properties($row)
	{
		if (is_object($row) && array_key_exists($row->module, $this->default_applications[$row->application]['modules']))
		{
			$row->original_name = $this->default_applications[$row->application]['modules'][$row->module];
		}
		
		return $row;
	}


	/**
	 * Set or modify post data
	 *
	 * @param	array
	 * @return	array
	 */
	protected function modify_post_data($data)
	{
		return $data;
	}


}