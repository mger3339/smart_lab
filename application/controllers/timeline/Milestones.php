<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Milestones extends Timeline_context {


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
	 *
	 * @return	void
	 */
	public function index()
	{
		
	}


	/**
	 * Get milestones by workstream
	 * - called via AJAX
	 *
	 * @param	int
	 * @return	function
	 */
	public function get_milestones_by_workstream($workstream_id)
	{
		$this->json['data'] = $this->milestone_model->get_many_by('timeline_workstream_id', $workstream_id);
		
		return $this->ajax_response();
	}


	/**
	 * Get milestone by ID
	 * - called via AJAX
	 *
	 * @param	int
	 * @return	function
	 */
	public function get_milestone($milestone_id)
	{
		$this->content['row'] = $this->milestone_model->get($milestone_id);
		
		$this->json['content'] = $this->load->view('timeline/milestones/milestone', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Add milestone form
	 * - called via AJAX
	 *
	 * @param	int
	 * @return	function
	 */
	public function add_milestone($workstream_id = NULL)
	{
		$this->milestone_op('add', NULL, $workstream_id);
	}


	/**
	 * Edit milestone form
	 * - called via AJAX
	 *
	 * @param	int
	 * @return	function
	 */
	public function edit_milestone($milestone_id)
	{
		$this->milestone_op('edit', $milestone_id);
	}


	/**
	 * Build & return add / edit milestone form
	 * - called via AJAX
	 *
	 * @param	string
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function milestone_op($action = 'add', $milestone_id = NULL, $workstream_id = NULL)
	{
		$workstreams = $this->workstream_model->get_workstreams();
		
		if ($action === 'add')
		{
			$form_action = 'timeline/milestones/add';
			
			$milestone = $this->milestone_model->create_prototype();
			
			if ( ! is_null($workstream_id) )
			{
				$milestone->timeline_workstream_id = $workstream_id;
			}
			else
			{
				$milestone->timeline_workstream_id = $workstreams[0]->id;
			}
		}
		else // edit
		{
			$form_action = "timeline/milestones/edit/{$milestone_id}";
			
			$milestone = $this->milestone_model->get($milestone_id);
		}
		
		$milestone->workstream = $this->workstream_model->get($milestone->timeline_workstream_id);
		
		$this->content['action'] = $action;
		$this->content['form_action'] = $form_action;
		$this->content['row'] = $milestone;
		$this->content['workstreams'] = $workstreams;
		$this->content['timeline_settings'] = $this->application_settings;
		
		$this->json['content'] = $this->load->view('timeline/milestones/add_edit_milestone', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Insert milestone on add submission
	 * - called via AJAX
	 *
	 * @return	function
	 */
	public function put_milestone()
	{
		$data = $this->input->post();
		$insert_id = $this->milestone_model->insert($data);
		
		if ( ! $insert_id )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting to add the milestone.';
			return $this->add_milestone($data['timeline_workstream_id']);
		}
		
		$this->json['insert_id'] = $insert_id;
		
		return $this->ajax_response();
	}


	/**
	 * Update milestone on edit submission
	 * - called via AJAX
	 *
	 * @param	int
	 * @return	function
	 */
	public function update_milestone($milestone_id)
	{
		$data = $this->input->post();
		$update = $this->milestone_model->update($milestone_id, $data);
		
		if ( ! $update )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting to update the milestone.';
			return $this->edit_milestone($milestone_id);
		}
		
		return $this->ajax_response();
	}


	/**
	 * Update milestone start & end dates
	 * - called via AJAX
	 *
	 * @param	int
	 * @param	int
	 * @param	int
	 * @param	int		(optional)
	 * @return	function
	 */
	public function update_milestone_start_end($milestone_id, $start_offset, $end_offset, $y_position = FALSE)
	{
		$this->milestone_model->update_dates($milestone_id, $start_offset, $end_offset);
		
		if ($y_position)
		{
			$this->milestone_model->update_y_position($milestone_id, $y_position);
		}
		
		return $this->ajax_response();
	}


	/**
	 * Delete milestone 
	 * - called via AJAX
	 *
	 * @param	int
	 * @return	function
	 */
	public function delete_milestone($milestone_id)
	{
		if ($this->input->post())
		{
			$this->milestone_model->delete($milestone_id);
		}
		
		return $this->ajax_response();
	}


}