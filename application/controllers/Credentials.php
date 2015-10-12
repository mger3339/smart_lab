<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credentials extends Client_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// check if user is already logged in, and if so redirect
		$this->logged_in_redirect();
		
		// load lang files
		$this->lang->load('credentials');
	}


	/**
	 * Reset password request form
	 *
	 * @return	function
	 */
	public function reset_password_request()
	{
		$this->page_title[] = lang('reset_password_title');
		
		$this->css_assets[] = 'default/reset_password.less';
		
		$this->wrap_views[] = $this->load->view('credentials/reset_password_request', $this->content, TRUE);
		
		return $this->render();
	}


	/**
	 * Send reset password invitation email
	 * on reset request submission
	 *
	 * @return	function
	 */
	public function reset_password_invite()
	{
		// set up form validation for email submission
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		
		// if form validation fails show the reset password request form
		if ( ! $this->form_validation->run() )
		{
			return $this->reset_password_request();
		}
		
		$email = $this->input->post('email');
		$user_ip = $this->input->ip_address();
		
		// load the required models
		$this->load->model('password_reset_request_model');
		
		// check to see whether email has been submitted within
		// password reset request time window
		$reset_requested = $this->password_reset_request_model->password_reset_requested($email);
		
		if ( $reset_requested )
		{
			// redirect to login with email sent message
			$this->set_flash_message('success', lang('reset_password_request_email_sent'));
			
			return redirect('login');
		}
		
		// check whether the user email exists for the client
		$this->load->model('user_model');
		$user = $this->user_model->get_by_email_or_username($email);
		
		if ( ! $user )
		{
			// redirect to login with email sent message
			$this->set_flash_message('success', lang('reset_password_request_email_sent'));
			
			return redirect('login');
		}
		
		// create the reset password request token,
		// and register the request
		$plain_text_token = $user->id . $user_ip;
		
		$this->load->library('encryption');
		$hash_token = $this->encryption->encrypt($plain_text_token);
		$hash_token = preg_replace("/[^A-Za-z0-9 ]/", '', $hash_token);
		$hash_token_length = strlen($hash_token);
		$hash_token = substr($hash_token, mt_rand(0, ($hash_token_length - 64)), 64);
		
		$register_password_reset_request = $this->password_reset_request_model
			->register_password_reset_request($user->email, $user_ip, $hash_token);
		
		if ( $register_password_reset_request )
		{
			// send the password reset instruction email
			$this->load->library('email_notification');
			$this->email_notification
				->template('password_reset')
				->client($this->client)
				->to($user->email, $user->fullname)
				->from($this->client->admin_user->email, $this->client->name)
				->subject(lang('reset_password_request_email_subject'))
				->content(array(
					'fullname'		=> $user->fullname,
					'reset_link'	=> site_url("reset-password/{$hash_token}"),
				));
			
			$send_email = $this->email_notification->send();
			
			if ( $send_email )
			{
				// redirect to login with email sent message
				$this->set_flash_message('success', lang('reset_password_request_email_sent'));
				
				return redirect('login');
			}
		}
		
		// an error occurred while registering the request
		$this->set_flash_message('error', lang('reset_password_request_error'));
		
		return redirect($this->uri->uri_string());
	}


	/**
	 * Reset password form
	 *
	 * @param	string
	 * @return	function
	 */
	public function edit_password($token)
	{
		// load the required models
		$this->load->model('password_reset_request_model');
		
		// verify the token
		$valid_token = $this->password_reset_request_model
			->validate_password_reset_request($token);
		
		// if the token is invalid show the reset password request form
		if ( ! $valid_token )
		{
			return $this->reset_password_request();
		}
		
		// build the reset password form
		$this->page_title[] = lang('reset_password_title');
		
		$this->css_assets[] = 'default/reset_password.less';
		
		$this->wrap_views[] = $this->load->view('credentials/edit_password', $this->content, TRUE);
		
		return $this->render();
	}


	/**
	 * Update password on reset form submission
	 *
	 * @param	string
	 * @return	function
	 */
	public function update_password($token)
	{
		// check to make sure that the correct form has been subitted
		if ( ! $this->input->post('update_password') )
		{
			return redirect('reset-password');
		}
		
		// set up form validation for password submission
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[8]|valid_password');
		$this->form_validation->set_rules('password_confirm', 're-type password', 'required|matches[password]');
		
		// if form validation fails show the reset password form
		if ( ! $this->form_validation->run() )
		{
			return $this->edit_password($token);
		}
		
		// load the required models
		$this->load->model('password_reset_request_model');
		
		// verify the token
		$valid_token = $this->password_reset_request_model
			->validate_password_reset_request($token);
		
		// if the token is invalid redirect to the reset password request form
		// and set an error message stating token expiration
		if ( ! $valid_token )
		{
			$this->set_flash_message('error', lang('reset_password_expired'));
			
			return redirect('reset-password');
		}
		
		// get the password reset request data and determine the user
		$password_reset_request = $this->password_reset_request_model
			->get_by('request_hash', $token);
		
		// if for some reason the password reset request data
		// no longer exists, redirect with an error message
		if ( ! $password_reset_request )
		{
			$this->set_flash_message('error', lang('reset_password_error'));
			
			return redirect('reset-password');
		}
		
		// check whether the request email exists for the client
		// and redirect with an error message if no user is found
		$this->load->model('user_model');
		$user = $this->user_model->get_by_email_or_username($password_reset_request->email);
		
		if ( ! $user )
		{
			$this->set_flash_message('error', lang('reset_password_error'));
			
			return redirect('reset-password');
		}
		
		// update the user's password
		$new_password = $this->input->post('password');
		
		$this->load->library('phpass');
		$password = $this->phpass->hash($new_password);
		
		$update_password = $this->user_model->update($user->id, array(
			'password'		=> $password,
		), TRUE);
		
		if ( $update_password )
		{
			// delete the reset password request data
			$this->password_reset_request_model->delete($password_reset_request->id);
			
			// redirect to login with reset password success message
			$this->set_flash_message('success', lang('reset_password_success'));
			
			return redirect('login');
		}
		
		// an error occurred while updating the password
		$this->set_flash_message('error', lang('reset_password_error'));
		
		return redirect('reset-password');
	}


}