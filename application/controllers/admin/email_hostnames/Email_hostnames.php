<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_hostnames extends Admin_context {


	/**
	 * Internal vars
	 */
	private $error_id = NULL;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load required models
		$this->load->model('email_hostname_model');
		
		// load language files
		$this->lang->load('admin_settings');
	}


	/**
	 * Build list of client email hostnames
	 * as data list form & return as AJAX content
	 *
	 */
	public function index()
	{
		$this->content['email_hostnames'] = $this->email_hostname_model
			->order_by('hostname', 'ASC')
			->get_all();
		
		$this->content['error_id'] = $this->error_id;
		
		$this->json['content'] = $this->load->view('admin/email_hostnames/email_hostnames/index', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Insert an email hostname
	 *
	 * @return	function
	 */
	public function put_email_hostname()
	{
		$data = $this->input->post();
		
		$success = $this->email_hostname_model->insert($data);
		
		if ( $success )
		{
			$this->json['message'] = lang('admin_add_email_hostname_success_message');
		}
		else
		{
			$this->json['status'] = 'error';
			$this->json['message'] = lang('admin_add_email_hostname_error_message');
		}
		
		return $this->index();
	}


	/**
	 * Update an email hostname
	 *
	 * @param	int
	 * @return	function
	 */
	public function update_email_hostname($email_hostname_id)
	{
		$data = $this->input->post();
		
		unset($data['unique_id']);
		
		$success = $this->email_hostname_model->update($email_hostname_id, $data);
		
		if ( $success )
		{
			$this->json['message'] = lang('admin_update_email_hostname_success_message');
		}
		else
		{
			$this->error_id = $email_hostname_id;
			
			$this->json['status'] = 'error';
			$this->json['message'] = lang('admin_update_email_hostname_error_message');
		}
		
		return $this->index();
	}


	/**
	 * Delete an email hostname
	 *
	 * @param	int
	 * @return	function
	 */
	public function delete_email_hostname($email_hostname_id)
	{
		$success = $this->email_hostname_model->delete($email_hostname_id);
		
		if ( $success )
		{
			$this->json['message'] = lang('admin_delete_email_hostname_success_message');
		}
		else
		{
			$this->error_id = $email_hostname_id;
			
			$this->json['status'] = 'error';
			$this->json['message'] = lang('admin_delete_email_hostname_error_message');
		}
		
		// check to see if any email hostnames are left
		// for the client, and if not, disable user auto register
		$email_hostnames = $this->email_hostname_model->get_all();
		
		if ( count($email_hostnames) == 0 )
		{
			$this->client_model->update($this->client->id, array(
					'user_auto_register'		=> 0,
			), TRUE);
		}
		
		return $this->index();
	}


}