<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workstreams extends Timeline_context {


	/**
	 * Declare admin-only zone
	 */
	public $admin_area = TRUE;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method
	 *  - lists all workstreams with basic CRUD
	 *
	 * @return	function
	 */
	public function index()
	{
		// add the required page assets
		$this->css_assets[] = 'admin/admin.less';
		$this->css_assets[] = 'timeline/timeline_admin.less';
		
		$this->js_assets[] = 'timeline/timeline_admin.js';
		
		$this->js_assets[] = 'third_party/jquery.elastic.source.js';
		
		$this->css_assets[] = 'third_party/glDatePicker.smartlab.less';
		$this->js_assets[] = 'third_party/glDatePicker.min.js';
		$this->js_assets[] = 'default/date_picker.js';
		
		$this->content['timeline_settings'] = $this->application_settings;
		
		$this->content['calendar_division_options'] = array(
			'weeks'			=> 'Weeks',
			'months'		=> 'Months',
			'quarters'		=> 'Quarters',
			'years'			=> 'Years',
		);
		
		$this->content['workstreams'] = $this->workstream_model->get_workstreams();
		$this->content['max_workstreams'] = $this->workstream_model->get_max_workstreams();
		$this->content['workstream_colors'] = $this->workstream_model->get_default_colors();
		
		$this->wrap_views[]
			= $this->load->view('timeline/admin/workstreams/index', $this->content, TRUE);
		
		$this->render();
	}


	/**
	 * Workstream operation router method
	 *
	 * @return	function
	 */
	public function workstream_op()
	{
		$workstream_id = $this->input->post('workstream_id');
		
		switch ($this->input->post('action'))
		{
			case 'put':
			return $this->put_workstream();
			break;
			
			case 'update':
			return $this->update_workstream($workstream_id);
			break;
			
			case 'delete':
			return $this->delete_workstream($workstream_id);
			break;
			
			default:
			return $this->index();
			break;
		}
	}


	/**
	 * Insert workstream on add submission
	 *
	 * @return	function
	 */
	private function put_workstream()
	{
		$data = array(
			'name'			=> $this->input->post('name'),
			'description'	=> $this->input->post('description'),
			'color'			=> $this->input->post('color'),
			'sort_order'	=> $this->input->post('sort_order'),
		);
		
		$success = $this->workstream_model->insert($data);
		
		if ($success)
		{
			redirect('timeline/admin/workstreams');
		}
		
		return $this->index();
	}


	/**
	 * Update workstream on edit submission
	 *
	 * @param	int
	 * @return	function
	 */
	private function update_workstream($workstream_id)
	{
		$data = array(
			'name'			=> $this->input->post('name'),
			'description'	=> $this->input->post('description'),
		);
		
		$success = $this->workstream_model->update($workstream_id, $data);
		
		if ($success)
		{
			redirect('timeline/admin/workstreams');
		}
		
		return $this->index();
	}


	/**
	 * Delete workstream 
	 *
	 * @param	int
	 * @return	function
	 */
	private function delete_workstream($workstream_id)
	{
		$success = $this->workstream_model->delete($workstream_id);
		
		if ($success)
		{
			redirect('timeline/admin/workstreams');
		}
		
		return $this->index();
	}


	/**
	 * Update calendar options 
	 *
	 * @return	void
	 */
	public function update_calendar_options()
	{
		$this->load->model('client_application_option_model');
		
		$calendar_start_time = strtotime($this->input->post('calendar_start'));
		$calendar_end_time = strtotime($this->input->post('calendar_end'));
		
		if ($calendar_end_time < ($calendar_start_time + 7776000))
		{
			$calendar_end_time = $calendar_start_time + 7776000; // 90 days
		}
		
		$calendar_start = date('Y-m-d', $calendar_start_time);
		$calendar_end = date('Y-m-d', $calendar_end_time);
		
		$this->client_application_option_model->update_by('option', 'calendar_start', array(
			'option'	=> 'calendar_start',
			'value'		=> $calendar_start,
		));
		
		$this->client_application_option_model->update_by('option', 'calendar_end', array(
			'option'	=> 'calendar_end',
			'value'		=> $calendar_end,
		));
		
		$this->client_application_option_model->update_by('option', 'calendar_division', array(
			'option'	=> 'calendar_division',
			'value'		=> $this->input->post('calendar_division'),
		));
		
		redirect('timeline/admin/workstreams');
	}


}