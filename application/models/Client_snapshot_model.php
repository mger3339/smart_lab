<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_snapshot_model extends Smartlab_model {


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
			'name'				=> NULL,
			'commence'			=> NULL,
			'expire'			=> NULL,
	);


	/**
	 * Cache container for row storage
	 */
	private $cache = array();


	/**
	 * Model validation rules
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
			'label'		=> 'snapshot name',
			'rules'		=> 'required|trim|max_length[60]|is_client_unique[client_snapshots.name]'
		),
		array(
			'field'		=> 'commence',
			'label'		=> 'snapshot commencement',
			'rules'		=> 'trim|max_length[12]'
		),
		array(
			'field'		=> 'expire',
			'label'		=> 'snapshot expiry',
			'rules'		=> 'trim|max_length[12]'
		),
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
	 * Get a client's snapshots
	 *
	 * @param	int
	 * @return	array
	 */
	public function get_client_snapshot($client_id)
	{
		if ( ! isset($this->cache[$client_id]) )
		{
			$this->order_by('sort_order', 'ASC');
			$snapshots_data = $this->get_many_by('client_id', $client_id);

			$this->cache[$client_id] = $snapshots_data;
		}

		return $this->cache[$client_id];
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
	public function create_prototype($client_id = NULL)
	{
		date_default_timezone_set('UTC');

		$this->prototype['client_id'] = $client_id;
		$this->prototype['commence'] = strtotime('today 0:00');
		$this->prototype['expire'] = strtotime('+1 week 0:00');

		return (object) $this->prototype;
	}

	/**
	 * Define extra snapshot row properties
	 *
	 * @param	object
	 * @return	object
	 */
	protected function add_default_properties($row)
	{
		// add the snapshot applications
		if (is_object($row) && ! property_exists($row, 'applications'))
		{
			$this->load->model('client_application_model');

			$row->applications = $this->client_application_model->get_snapshot_applications($row->id);
		}

        // add the snapshot sessions
        if(is_object($row) && !property_exists($row, 'snapshots'))
        {
            $this->load->model('client_snapshots_session_model');
            $row->sessions = $this->client_snapshots_session_model->get_snapshot_sessions($row->id);
        }

		return $row;
	}

	/**
	 * Set or modify post data on create
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

		// set the sort order
		if ( isset($data['client_id']) )
		{
			$data['sort_order'] = $this->count_by('client_id', $data['client_id']) + 1;
		}


		// check if expire time is smaller than the commence time, set $correct_time = false;
		$correct_time = TRUE;
		
		if ( isset($data['commence']) && isset($data['expire']) && $data['commence'] > $data['expire'] )
		{
			$correct_time = FALSE;
		}


		// set the expire date/time
		if ( ! isset($data['expire']) )
		{
			$data['expire'] = strtotime('tomorrow +10 years') - 1;
		}
		else
		{
			date_default_timezone_set('UTC');

			if ( ! $correct_time )
			{
				//make the expire time 1 hour greater than the commence time
				$data['expire'] = date("U",strtotime($data['commence']) + 3600);

			}
			else
			{
				$data['expire'] = date("U",strtotime($data['expire']));

			}
		}

		// set the commence date/time
		if ( ! isset($data['commence']) )
		{
			$data['commence'] = strtotime('today') + 1;
		}
		else
		{
			date_default_timezone_set('UTC');
			$data['commence'] = date("U",strtotime($data['commence']));
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
	public function update_sort_order($client_id, $snapshot_ids)
	{
		$snapshot_ids = explode('-', $snapshot_ids);
		
		$sort_order = 1;
		
		foreach ($snapshot_ids as $snapshot_id)
		{
			$this->_database->where('client_id', $client_id);
			
			$this->update($snapshot_id, array('sort_order' => $sort_order), TRUE);
			
			$sort_order++;
		}
	}

	/**
	 * Delete dependent data in other tables
	 *
	 * @param	int
	 * @return	int
	 */
	protected function delete_dependencies($id)
	{
		// delete the snapshot's applications
		$this->load->model('client_application_model');
		$this->client_application_model->delete_by('snapshot_id', $id);

		return $id;
	}


}