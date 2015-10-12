<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workstreams extends Timeline_context {


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
		$this->css_assets[] = 'timeline/admin.less';
		
		$this->content['workstreams'] = $this->workstream_model->get_workstreams();
		$this->content['max_workstreams'] = $this->workstream_model->get_max_workstreams();
		
		$this->wrap_views[]
			= $this->load->view('timeline/admin/workstreams/index', $this->content, TRUE);
		
		$this->render();
	}


	/**
	 * Get workstream by ID
	 * - called via AJAX
	 *
	 * @param	int
	 * @return	function
	 */
	public function get_workstream($workstream_id)
	{
		$this->content['row'] = $this->workstream_model->get($workstream_id);
		
		$this->json['content'] = $this->load->view('timeline/workstreams/workstream', $this->content, TRUE);
		
		return $this->ajax_response();
	}


}