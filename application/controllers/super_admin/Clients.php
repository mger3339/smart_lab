<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends Super_admin_context {


	/**
	 * Container for custom form errors
	 */
	private $_form_errors = array();


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load models
		$this->load->model('client_model');
	}


	/**
	 * Default class method - lists clients
	 *
	 * @return	void
	 */
	public function index()
	{
		$clients = $this->client_model->order_by('created', 'DESC')->get_all();
		
		// add the required JS assets
		$this->js_assets[] = 'admin/data_rows.js';
		$this->js_assets[] = 'super_admin/clients.js';
		
		$this->content['clients'] = $clients;
		
		$this->load->model('country_model');
		$this->content['country_options'] = $this->country_model->get_country_options();
		
		$this->load->model('time_zone_model');
		$this->content['time_zone_options'] = $this->time_zone_model->get_time_zone_options();
		
		$this->load->model('currency_model');
		$this->content['currency_options'] = $this->currency_model->get_currency_options();
		
		$this->load->model('user_role_model');
		$this->content['user_roles'] = $this->user_role_model->get_roles();
		
		$this->wrap_views[] = $this->load->view('super_admin/clients/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Build add client form
	 *
	 * @return	function
	 */
	public function add_client()
	{
		return $this->client_op();
	}


	/**
	 * Build add/edit client form
	 *
	 * @param	int
	 * @return	function
	 */
	public function edit_client($client_id)
	{
		$this->confirm_valid_client($client_id);
		
		return $this->client_op('edit', $client_id);
	}


	/**
	 * Build add/edit client form
	 *
	 * @param	string
	 * @param	int
	 * @return	function
	 */
	public function client_op($action = 'add', $client_id = NULL)
	{
		if ($action === 'add')
		{
			$client = $this->client_model->create_prototype();
			
			$this->content['action_title'] = 'Add a new client';
		}
		else // edit
		{
			$client = $this->client_model->get($client_id);
			
			$this->content['action_title'] = "Edit <span>{$client->name}</span>";
		}
		
		$this->load->helper('directory');
		$language_directories = list_directories(APPPATH . 'language');
		$language_options = array();
		foreach ($language_directories as $language)
		{
			$language_options[$language] = 'language/' . $language;
		}
		
		$this->content['action'] = $action;
		$this->content['form_errors'] = $this->_form_errors;
		$this->content['form_admin_user_choice'] = $this->input->post('admin_user');
		$this->content['row'] = $client;
		
		$this->content['language_options'] = $language_options;
		
		$this->load->model('country_model');
		$this->content['country_options'] = $this->country_model->get_country_options();
		
		$this->load->model('time_zone_model');
		$this->content['time_zone_options'] = $this->time_zone_model->get_time_zone_options();
		
		$this->load->model('currency_model');
		$this->content['currency_options'] = $this->currency_model->get_currency_options();
		
		$this->json['content'] = $this->load->view('super_admin/clients/partials/client_add_edit_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Insert a new client on add form submission
	 *
	 * @return	function
	 */
	public function put_client()
	{
		$success = TRUE;
		$data = $this->input->post();
		
		// compile the client POST data
		$client_data = $data;
		unset(
			$client_data['client_id'],
			$client_data['admin_user'],
			$client_data['firstname'],
			$client_data['lastname'],
			$client_data['email'],
			$client_data['existing_admin_user_fullname'],
			$client_data['existing_admin_user_email'],
			$client_data['this_admin_user_fullname'],
			$client_data['this_admin_user_email']
		);
		
		// before creating the new client
		// we attempt to create the client admin user
		$client_data['admin_user_id'] = $this->set_client_admin_user_id('put', $data);
		
		if ( ! $client_data['admin_user_id'])
		{
			$success = FALSE;
		}
		
		// attempt to insert the new client
		$insert_id = $this->client_model->insert($client_data);
		
		if ( ! $insert_id)
		{
			$success = FALSE;
		}
		
		if ( ! $success)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting to add the client.';
			return $this->add_client();
		}
		
		// if a new user was created as client admin
		// we need to update their client ID
		if ($data['admin_user'] === 'new_admin_user')
		{
			$this->user_model->update($client_data['admin_user_id'], array('client_id' => $insert_id), TRUE);
		}
		
		$this->set_flash_message('success', 'Client successfully added.');
		
		$this->json['redirect'] = 'super-admin/clients';
		
		return $this->ajax_response();
	}


	/**
	 * Update a client on edit form submission
	 *
	 * @param	int
	 * @return	function
	 */
	public function update_client($client_id)
	{
		$this->confirm_valid_client($client_id);
		
		$success = TRUE;
		$data = $this->input->post();
		
		// compile the client POST data
		$client_data = $data;
		unset(
			$client_data['client_id'],
			$client_data['admin_user'],
			$client_data['firstname'],
			$client_data['lastname'],
			$client_data['email'],
			$client_data['existing_admin_user_fullname'],
			$client_data['existing_admin_user_email'],
			$client_data['this_admin_user_fullname'],
			$client_data['this_admin_user_email']
		);
		
		// before updating the client
		// we attempt to update the client admin user
		$client_data['admin_user_id'] = $this->set_client_admin_user_id('update', $data);
		
		if ( ! $client_data['admin_user_id'])
		{
			$success = FALSE;
		}
		
		// attempt to update the client
		$update = $this->client_model->update($client_id, $client_data);
		
		if ( ! $update)
		{
			$success = FALSE;
		}
		
		if ( ! $success)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There errors when attempting to update the client.';
			return $this->edit_client($client_id);
		}
		
		$this->content['row'] = $this->client_model->get($client_id);
		
		$this->load->model('country_model');
		$this->content['country_options'] = $this->country_model->get_country_options();
		
		$this->load->model('time_zone_model');
		$this->content['time_zone_options'] = $this->time_zone_model->get_time_zone_options();
		
		$this->load->model('currency_model');
		$this->content['currency_options'] = $this->currency_model->get_currency_options();
		
		$this->json['message'] = 'Client successfully updated.';
		$this->json['content'] = $this->load->view('super_admin/clients/partials/client_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Set (and verify) a client's admin user ID when putting / updating a client
	 *
	 * @param	string
	 * @param	array
	 * @return	int or FALSE
	 */
	private function set_client_admin_user_id($action, $data)
	{
		$client_admin_user_id = FALSE;
		
		switch ($data['admin_user'])
		{
			case 'new_admin_user':
			
			$user_data = array(
				'admin'				=> 1,
				'user_role'			=> 'admin',
				'client_id'			=> $data['client_id'],
				'email'				=> $data['email'],
				'firstname'			=> $data['firstname'],
				'lastname'			=> $data['lastname'],
				'country_iso'		=> $data['country_iso'],
				'time_zone'			=> $data['time_zone'],
				'currency'			=> $data['currency'],
			);
			
			$this->load->model('user_model');
			$insert_user_id = $this->user_model->insert($user_data);
			
			if ($insert_user_id)
			{
				$client_admin_user_id = $insert_user_id;
				
				$this->form_validation->reset_validation();
			}
			
			break;
			
			case 'existing_admin_user':
			
			$this->load->model('user_model');
			
			if ($action === 'put')
			{
				$super_admin_user = $this->user_model->get_super_admin_user($data['existing_admin_user_email']);
				
				if ($super_admin_user)
				{
					$client_admin_user_id = $super_admin_user->id;
				}
				else
				{
					$this->_form_errors['existing_admin_user_email'] = 'Please enter a valid super admin user email.';
				}
			}
			else // update
			{
				$client_admin_user = $this->user_model->get_client_admin_user($data['client_id'], $data['existing_admin_user_email']);
				
				if ($client_admin_user)
				{
					$client_admin_user_id = $client_admin_user->id;
				}
				else
				{
					$this->_form_errors['existing_admin_user_email'] = 'Please enter a valid super admin or client admin user email.';
				}
			}
			
			break;
			
			default: // this (super admin) user
			$client_admin_user_id = $this->user->id;
			break;
		}
		
		return $client_admin_user_id;
	}


	/**
	 * Delete a client
	 *
	 * @param	int
	 * @return	function
	 */
	public function delete_client($client_id)
	{
		$this->confirm_valid_client($client_id);
		
		$delete = $this->client_model->delete($client_id);
		
		if ( ! $delete)
		{
			$this->set_flash_message('error', 'An error occurred whilst attempting to delete the client.');
		}
		else
		{
			$this->set_flash_message('success', 'Client successfully deleted.');
		}
		
		return redirect('super-admin/clients');
	}


}