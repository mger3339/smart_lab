<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_application_modules extends Super_admin_context {


	public $area = 'clients';


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load models
		$this->load->model('client_model');
		$this->load->model('application_model');
		$this->load->model('client_application_model');
		$this->load->model('client_application_module_model');
	}


	/**
	 * Default class method - lists client application modules
	 *
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function index($client_id = NULL, $client_application_id = NULL)
	{
		$this->confirm_valid_client($client_id);
		$this->confirm_valid_client_application($client_id, $client_application_id);
		
		// add the required JS assets
		$this->js_assets[] = 'admin/data_rows.js';
		
		$this->content['client'] = $this->client_model->get($client_id);
		
		$this->content['application'] = $this->client_application_model->get($client_application_id);
		
		$this->content['modules'] = $this->client_application_module_model->get_application_modules($client_application_id);
		
		$this->wrap_views[] = $this->load->view('super_admin/client_application_modules/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Sort client application modules
	 *
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function sort_client_application_modules($client_id, $client_application_id, $application_module_ids)
	{
		$this->confirm_valid_client($client_id);
		$this->confirm_valid_client_application($client_id, $client_application_id);
		
		$this->client_application_module_model->sort_application_modules($application_module_ids);
		
		return $this->ajax_response();
	}


	/**
	 * Build edit client application module form
	 *
	 * @param	int
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function edit_client_application_module($client_id, $client_application_id, $client_application_module_id)
	{
		$this->confirm_valid_client($client_id);
		$this->confirm_valid_client_application($client_id, $client_application_id);
		
		$client_application_module = $this->client_application_module_model->get($client_application_module_id);
			
		$this->content['client_id'] = $client_id;
		$this->content['row'] = $client_application_module;
		
		$this->json['content'] = $this->load->view('super_admin/client_application_modules/partials/client_application_module_edit_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Update a client application module on edit form submission
	 *
	 * @param	int
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function update_client_application_module($client_id, $client_application_id, $client_application_module_id)
	{
		$this->confirm_valid_client($client_id);
		$this->confirm_valid_client_application($client_id, $client_application_id);
		
		$data = $this->input->post();
		$update = $this->client_application_module_model->update($client_application_module_id, $data);
		
		if ( ! $update)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There errors when attempting to update the application module.';
			return $this->edit_client_application_module($client_id, $client_application_id, $client_application_module_id);
		}
		
		$this->content['client_id'] = $client_id;
		$this->content['row'] = $this->client_application_module_model->get($client_application_module_id);
		
		$this->json['message'] = 'Application module successfully updated.';
		$this->json['content'] = $this->load->view('super_admin/client_application_modules/partials/client_application_module_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


}