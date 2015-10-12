<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_auto_register extends Admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load language files
		$this->lang->load('admin_settings');
	}


	/**
	 * Build user auto register settings form
	 * & return as AJAX content
	 *
	 */
	public function index()
	{
		$this->content['user_auto_register'] = $this->client->user_auto_register;
		
		$this->json['content'] = $this->load->view('admin/settings/auto_register/index', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Update user auto register settings
	 *
	 * @return	function
	 */
	public function update_user_auto_register()
	{
		// set up form validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_auto_register', 'user auto register', 'required|trim');
		
		$user_auto_register = intval($this->input->post('user_auto_register'));
		
		// if user auto register has been set to ON...
		if ( $user_auto_register == 1 )
		{
			// check that client email hostnames exist
			$this->load->model('email_hostname_model');
			$client_email_hostnames = $this->email_hostname_model->get_all();
			
			if ( count($client_email_hostnames) == 0 )
			{
				$this->json['status'] = 'error';
				$this->json['message'] = 'User self-registration cannot be enabled because at least one valid email hostname must be set.';
				
				return $this->index();
			}
			
			// check that the client auto register password has been set
			$auto_register_password = $this->input->post('auto_register_password');
			
			// set up form validation for the password fields
			$this->form_validation->set_rules('auto_register_password', 'password', 'required|trim|min_length[8]|valid_password');
			$this->form_validation->set_rules('auto_register_password_confirm', 're-type password', 'required|matches[auto_register_password]');
			
			$valid_form = $this->form_validation->run();
			
			// if no auto register password is present
			// or is not set, return a 'password must be set' message
			if ( ! $auto_register_password )
			{
				$this->json['status'] = 'error';
				$this->json['message'] = 'A self-registration password must be set to enable self registration.';
				
				return $this->index();
			}
			
			// if form validation fails...
			if ( ! $valid_form )
			{
				$this->json['status'] = 'error';
				$this->json['message'] = 'There were errors when attempting to update user self-registration settings.';
				
				return $this->index();
			}
			
			// update the client auto registration settings
			$this->load->library('phpass');
			$auto_register_password = $this->phpass->hash($auto_register_password);
			
			$this->client_model->update($this->client->id, array(
					'user_auto_register'		=> 1,
					'auto_register_password'	=> $auto_register_password,
			), TRUE);
		}
		else
		{
			// set the client auto register to OFF
			$this->client_model->update($this->client->id, array(
					'user_auto_register'		=> 0,
					'auto_register_password'	=> '',
			), TRUE);
		}
		
		$this->json['message'] = 'The self-registration settings have been updated successfully.';
		
		// update the client object
		$this->client = $this->client_model->get($this->client->id);
		
		return $this->index();
	}


}