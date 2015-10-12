<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_applications extends Super_admin_context {


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
	}


	/**
	 * Default class method - lists client applications
	 *
	 * @param	int
	 * @return	function
	 */
	public function index($client_id = NULL)
	{
		$this->confirm_valid_client($client_id);
		
		// add the required JS assets
		$this->js_assets[] = 'admin/data_rows.js';
		$this->js_assets[] = 'super_admin/client_applications.js';
		
		$this->content['client'] = $this->client_model->get($client_id);
		$this->content['applications'] = $this->application_model->get_all();
        $this->content['applications_colors'] = $this->application_model->get_application_colors_options();

        // check if start or end date/time not set , make start - today, end - today + 1 week;

        date_default_timezone_set($this->content['client']->time_zone);

        foreach($this->content['client']->applications as $value)
        {
            $value->commence = $value->commence ? $value->commence : strtotime('today 0:00');
            $value->expire = $value->expire ? $value->expire :  strtotime('+1 week 0:00');
        }

        $this->wrap_views[] = $this->load->view('super_admin/client_applications/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Build add client application form
	 *
	 * @param	int
	 * @return	function
	 */
	public function add_client_application($client_id)
	{
		$this->confirm_valid_client($client_id);
		
		return $this->client_application_op('add', $client_id);
	}


	/**
	 * Build add/edit client application form
	 *
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function edit_client_application($client_id, $client_application_id)
	{
		$this->confirm_valid_client($client_id);
		$this->confirm_valid_client_application($client_id, $client_application_id);
		
		return $this->client_application_op('edit', $client_id, $client_application_id);
	}


	/**
	 * Build add/edit client application form
	 *
	 * @param	string
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function client_application_op($action = 'add', $client_id = NULL, $client_application_id = NULL)
	{
		if ($action === 'add')
		{
			$client_application = $this->client_application_model->create_prototype($client_id);
			
			$this->content['action_title'] = 'Add a new client application';
		}
		else // edit
		{
			$client_application = $this->client_application_model->get($client_application_id);
			
			$this->content['action_title'] = "Edit <span>{$client_application->name}</span>";
		}
		
		$client = $this->client_model->get($client_id);
		
		$this->content['action'] = $action;
		$this->content['client'] = $client;
		$this->content['row'] = $client_application;
		$this->content['applications'] = $this->application_model->get_application_options();

        //getting $applications_colors options
        $this->content['applications_colors'] = $this->application_model->get_application_colors_options();

        // check if start or end date/time not set , make start - today, end - today + 1 week;
        date_default_timezone_set($client->time_zone);
        
        $this->content['row']->commence = $this->content['row']->commence ? $this->content['row']->commence : strtotime('today 0:00');
        $this->content['row']->expire = $this->content['row']->expire ? $this->content['row']->expire :  strtotime('+1 week 0:00');

		$this->json['content'] = $this->load->view('super_admin/client_applications/partials/client_application_add_edit_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Insert a new client application on add form submission
	 *
	 * @param	int
	 * @return	function
	 */
	public function put_client_application($client_id)
	{
		$this->confirm_valid_client($client_id);
		
		$data = $this->input->post();
		$insert = $this->client_application_model->insert($data);
		
		if ( ! $insert )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There errors when attempting to add the client application.';
			return $this->add_client_application($client_id);
		}
		
		$this->set_flash_message('success', 'Client application successfully added.');
		
		$this->json['redirect'] = 'super-admin/client-applications/' . $client_id;
		
		return $this->ajax_response();
	}


	/**
	 * Update a client application on edit form submission
	 *
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function update_client_application($client_id, $client_application_id)
	{
		$this->confirm_valid_client($client_id);
		$this->confirm_valid_client_application($client_id, $client_application_id);
		
		$data = $this->input->post();
		$update = $this->client_application_model->update($client_application_id, $data);
		
		if ( ! $update )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There errors when attempting to update the client application.';
			return $this->edit_client_application($client_id, $client_application_id);
		}
		
		$this->content['row'] = $this->client_application_model->get($client_application_id);
		$this->content['applications'] = $this->application_model->get_all();

        //getting $applications_colors options
        $this->content['applications_colors'] = $this->application_model->get_application_colors_options();

		$this->json['message'] = 'Client application successfully updated.';
		$this->json['content'] = $this->load->view('super_admin/client_applications/partials/client_application_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Delete a client application
	 *
	 * @param	int
	 * @param	int
	 * @return	function
	 */
	public function delete_client_application($client_id, $client_application_id)
	{
		$this->confirm_valid_client($client_id);
		$this->confirm_valid_client_application($client_id, $client_application_id);
		
		$delete = $this->client_application_model->delete($client_application_id);
		
		if ( ! $delete )
		{
			$this->set_flash_message('error', 'An error occurred whilst attempting to delete the client application.');
		}
		else
		{
			$this->set_flash_message('success', 'Client application successfully deleted.');
		}
		
		return redirect('super-admin/client-applications/' . $client_id);
	}


	/**
	 * Sort client applications
	 *
	 * @param	int
	 * @param	string
	 * @return	function
	 */
	public function sort_client_applications($client_id, $client_application_ids)
	{
		$this->confirm_valid_client($client_id);
		
		$this->client_application_model->update_sort_order($client_id, $client_application_ids);
		
		$this->json['message'] = 'Client applications sort-order updated.';
		
		return $this->ajax_response();
	}


}