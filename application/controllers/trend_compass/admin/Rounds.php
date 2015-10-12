<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rounds extends Trend_compass_context {


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
		
		// set the page title
		$this->page_title[] = 'Question rounds';
	}


	/**
	 * Default class method
	 * - build admin rounds-list page
	 *
	 */
	public function index()
	{
		// add required page assets
		$this->css_assets[] = 'admin/admin.less';
		$this->js_assets[] = 'admin/data_rows.js';
		$this->js_assets[] = 'default/sortable.js';
		
		// get the rounds data
		$this->content['rounds'] = $this->round_model->get_all();
		
		$this->wrap_views[]
			= $this->load->view('trend_compass/admin/rounds/index', $this->content, TRUE);
		
		$this->render();
	}


	/**
	 * Build add round page
	 *
	 * @return	function
	 */
	public function add_round()
	{
		return $this->round_op();
	}


	/**
	 * Build edit round page
	 *
	 * @param	int
	 * @return	function
	 */
	public function edit_round($round_id)
	{
		return $this->round_op('edit', $round_id);
	}


	/**
	 * Build add/edit round page
	 *
	 * @param	string
	 * @param	int
	 * @return	function
	 */
	public function round_op($action = 'add', $round_id = NULL)
	{
		if ($action === 'add')
		{
			$round = $this->round_model->create_prototype();
			
			$this->content['action_title'] = 'Add new question round';
		}
		else // edit
		{
			$round = $this->round_model->get(intval($round_id));
			
			$this->content['action_title'] = 'Edit question round';
		}
		
		// add required page assets
		$this->css_assets[] = 'admin/admin.less';
		$this->js_assets[] = 'admin/data_rows.js';
		$this->js_assets[] = 'default/sortable.js';
		
		$this->wrap_views[]
			= $this->load->view('trend_compass/admin/rounds/round_add_edit', $this->content, TRUE);
		
		$this->render();
	}


	/**
	 * Insert a new round on add form submission
	 *
	 * @return	function
	 */
	public function put_round()
	{
		$data = $this->input->post();
		
		// attempt to insert the new round
		$insert_id = $this->round_model->insert($data);
		
		if ( ! $insert_id )
		{
			$this->set_flash_message('error', 'A problem occurred attempting to add the question round, please try again.');
			
			return $this->add_round();
		}
		
		$this->set_flash_message('success', 'Question added successfully');
		
		return $this->index();
	}


	/**
	 * Update a round on edit form submission
	 *
	 * @param	int
	 * @return	function
	 */
	public function update_round($round_id)
	{
		$data = $this->input->post();
		
		// attempt to update the round
		$update = $this->round_model->update($round_id, $data);
		
		if ( ! $update )
		{
			$this->set_flash_message('error', 'A problem occurred attempting to update the question round, please try again.');
			
			return $this->edit_round($round_id);
		}
		
		$this->set_flash_message('success', 'Question round successfully updated');
		
		return $this->index();
	}


	/**
	 * Delete a round
	 *
	 * @param	int
	 * @return	function
	 */
	public function delete_round($round_id)
	{
		$delete = $this->round_model->delete($round_id);
		
		if ( ! $delete )
		{
			$this->set_flash_message('error', 'A problem occurred attempting to delete the question round, please try again.');
		}
		else
		{
			$this->set_flash_message('success', 'Question round successfully deleted');
		}
		
		return $this->index();
	}


}