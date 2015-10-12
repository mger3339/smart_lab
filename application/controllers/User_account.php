<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_account extends User_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load relevant lang files
		$this->lang->load('user_account');
	}


	/**
	 * Default class method - build user account page
	 *
	 * @return	void
	 */
	public function index()
	{
		// load assets
		$this->css_assets[] = 'default/user_account.less';
		$this->js_assets[] = 'default/user_account.js';
		
		$this->content['update_account_content'] = $this->update_account_content();
		
		$this->content['update_password_content'] = $this->update_password_content();
		
		$this->wrap_views[] = $this->load->view('user_account/page/header', FALSE, TRUE);
		
		$this->wrap_views[] = $this->load->view('user_account/index', $this->content, TRUE);
		
		$this->render();
	}


	/**
	 * Update user account details on update form submission
	 *
	 * @return	function
	 */
	public function update_account()
	{
		$data = $this->input->post();
		
		$success = $this->user_model->safe_update($this->user->id, $data);
		
		if ( $success )
		{
			$this->json['message'] = 'Your account details have been successfully updated.';
		}
		else
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting update your account.';
		}
		
		$this->json['content'] = $this->update_account_content();
		
		return $this->ajax_response();
	}


	/**
	 * Build update user account form
	 *
	 * @return	view
	 */
	private function update_account_content()
	{
		$content = array();
		$content['user'] = $this->user_model->get($this->user->id);
		$this->load->model('country_model');
		$content['country_options'] = $this->country_model->get_country_options();
		
		$this->load->model('time_zone_model');
		$content['time_zone_options'] = $this->time_zone_model->get_time_zone_options();
		
		$this->load->model('currency_model');
		$content['currency_options'] = $this->currency_model->get_currency_options();

		return $this->load->view('user_account/partials/user_account_edit', $content, TRUE);
	}


	/**
	 * Update user password on update password form submission
	 *
	 * @return	function
	 */
	public function update_password()
	{
		// set up form validation for password submission
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'password', 'required|trim');
		$this->form_validation->set_rules('new_password', 'new password', 'required|trim|min_length[8]|valid_password');
		$this->form_validation->set_rules('new_password_confirm', 're-type password', 'required|matches[new_password]');
		
		// return the AJAX response if form validation fails
		if ( ! $this->form_validation->run() )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting update your password.';
			
			$this->json['content'] = $this->update_password_content();
			
			return $this->ajax_response();
		}
		
		// check password the current password
		// and return the AJAX response if current password is invalid
		$user_password = $this->user_model->get_user_password($this->user->id);
			
		$this->load->library('phpass');
		
		if ( ! $this->phpass->check($this->input->post('password'), $user_password) )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'The current password you entered is incorrect. Please try again.';
			
			$this->json['content'] = $this->update_password_content();
			
			return $this->ajax_response();
		}
		
		// if everything is OK we can update the user's password
		$new_password = $this->input->post('new_password');
		$new_password = $this->phpass->hash($new_password);
		
		$update_password = $this->user_model->update($this->user->id, array(
			'password'		=> $new_password,
		), TRUE);
		
		// if database update is unsuccessful
		// return the AJAX response with an error message
		if ( ! $update_password )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'An error occurred whilst attempting to update your password. Please try again.';
			
			$this->json['content'] = $this->update_password_content();
			
			return $this->ajax_response();
		}
		
		$this->json['status'] = 'success';
		$this->json['message'] = 'Your password has been successfully updated.';
		
		$this->json['content'] = $this->update_password_content();
		
		return $this->ajax_response();
	}


	/**
	 * Build update user password form
	 *
	 * @return	view
	 */
	private function update_password_content()
	{
		return $this->load->view('user_account/partials/user_password_edit', FALSE, TRUE);
	}


}