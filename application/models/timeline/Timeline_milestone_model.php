<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timeline_milestone_model extends Smartlab_model {


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
			'timeline_workstream_id'	=> NULL,
			'title'						=> NULL,
			'description'				=> NULL,
	);


	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> 'timeline_workstream_id',
			'label'		=> 'workstream id',
			'rules'		=> 'required|trim|integer'
		),
		array(
			'field'		=> 'title',
			'label'		=> 'title',
			'rules'		=> 'required|trim'
		),
		array(
			'field'		=> 'description',
			'label'		=> 'description',
			'rules'		=> 'trim'
		),
		array(
			'field'		=> 'start_date',
			'label'		=> 'start date',
			'rules'		=> 'trim'
		),
		array(
			'field'		=> 'end_date',
			'label'		=> 'end date',
			'rules'		=> 'trim'
		),
		array(
			'field'		=> 'y_position',
			'label'		=> 'y position',
			'rules'		=> 'trim'
		),
	);


	/**
	 * Model observers
	 */
	public $before_create		= array(
											'set_client_id',
											'set_client_application_id',
											'set_created_time',
											'set_modified_time',
											'set_created_user_id',
											'validate_dates'
								);
	public $before_update		= array(
											'where_client_id',
											'where_client_application_id',
											'set_modified_time',
											'set_modified_user_id',
											'validate_dates'
								);
	public $before_delete		= array(
											'where_client_id',
											'where_client_application_id'
								);
	public $before_get			= array(
											'where_client_id',
											'where_client_application_id',
											'where_valid_dates',
											'order_by_start_date'
								);
	public $after_get			= array(
											'set_days_offsets',
											'add_workstream'
								);


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Return the model prototype
	 *
	 * @return	object
	 */
	public function create_prototype()
	{
		$data = $this->prototype;
		
		$data = $this->set_dates($data);
		
		return (object) $data;
	}


	/**
	 * Where valid date for get
	 *
	 * @param	array
	 * @return	array
	 */
	protected function where_valid_dates($data)
	{
		$calendar_start = $this->_CI->application_settings->calendar_start;
		$calendar_end = $this->_CI->application_settings->calendar_end;
		
		$this->_database->where('start_date >=', $calendar_start);
		$this->_database->where('end_date <=', $calendar_end);
		
		return $data;
	}


	/**
	 * Order by start date for get
	 *
	 * @param	array
	 * @return	array
	 */
	protected function order_by_start_date($data)
	{
		$this->_database->order_by('start_date', 'ASC');
		
		return $data;
	}


	/**
	 * Add workstream data on get
	 *
	 * @param	object
	 * @return	object
	 */
	protected function add_workstream($row)
	{
		$this->load->model('timeline_workstream_model', 'workstream_model');
		$row->workstream = $this->workstream_model->get_by('id', $row->timeline_workstream_id);
		
		return $row;
	}


	/**
	 * Set days offsets (start & end) on get
	 *
	 * @param	object
	 * @return	object
	 */
	protected function set_days_offsets($row)
	{
		$calendar_start = $this->_CI->application_settings->calendar_start;
		
		$calendar_start = new DateTime($calendar_start);
		
		$start_date = new DateTime($row->start_date);
		$end_date = new DateTime($row->end_date);
		
		$row->start_offset = $start_date->diff($calendar_start)->format("%a");
		$row->end_offset = $end_date->diff($calendar_start)->format("%a");
		
		return $row;
	}


	/**
	 * Set default dates for prototype
	 *
	 * @param	array
	 * @return	array
	 */
	protected function set_dates($data)
	{
		$calendar_start = $this->_CI->application_settings->calendar_start;
		
		$data['start_date'] = date("Y-m-d", strtotime('+7 days', strtotime($calendar_start)));
		$data['end_date'] = date("Y-m-d", strtotime('+1 month', strtotime($data['start_date'])));
		
		return $data;
	}


	/**
	 * Validate dates for create & update
	 *
	 * @param	array
	 * @return	array
	 */
	protected function validate_dates($data)
	{
		$start_time = strtotime($data['start_date']);
		$end_time = strtotime($data['end_date']);
		
		$calendar_start = strtotime($this->_CI->application_settings->calendar_start);
		$calendar_end = strtotime($this->_CI->application_settings->calendar_end);
		
		if ($end_time < $start_time)
		{
			$end_time = $start_time + 86400;
		}
		
		if ($start_time < $calendar_start)
		{
			$start_time = $calendar_start;
		}
		
		if ($end_time > $calendar_end)
		{
			$end_time = $calendar_end;
		}
		
		$data['start_date'] = date("Y-m-d", $start_time);
		$data['end_date'] = date("Y-m-d", $end_time);
		
		return $data;
	}


	/**
	 * Update dates based on new start & end days offsets
	 *
	 * @param	int
	 * @param	int
	 * @param	int
	 * @return	void
	 */
	public function update_dates($milestone_id, $start_offset, $end_offset)
	{
		$year_start = $this->_CI->application_settings->calendar_start;
		
		$data = array();
		
		if ($start_offset !== FALSE)
		{
			$data['start_date'] = date("Y-m-d", strtotime("+{$start_offset} days", strtotime("{$year_start}-1-1")));
		}
		
		if ($end_offset !== FALSE)
		{
			$data['end_date'] = date("Y-m-d", strtotime("+{$end_offset} days", strtotime("{$year_start}-1-1")));
		}
		
		$this->update($milestone_id, $data, TRUE);
	}


	/**
	 * Update y position
	 *
	 * @param	int
	 * @param	int
	 * @return	void
	 */
	public function update_y_position($milestone_id, $y_position)
	{
		$this->_database->where('id', $milestone_id);
		$this->_database->update($this->_table, array('y_position' => $y_position));
	}


}