<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_account extends Super_admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - build super admin user account page
	 *
	 * @return	void
	 */
	public function index()
	{
		$this->content['user'] = $this->user;
		
		$this->wrap_views[] = $this->load->view('super_admin/user_account/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Update super admin user account details on update form submission
	 *
	 * @return	function
	 */
	public function update_account()
	{
		$data = $this->input->post();
		unset($data['update_account']);
		
		$success = $this->user_model->update($this->user->id, $data);
		
		if ( $success )
		{
			$this->status = 'success';
			$this->message = 'Your account details have been successfully updated.';
		}
		else
		{
			$this->status = 'error';
			$this->message = 'There were errors when attempting update your account.';
		}
		
		return $this->index();
	}


	/**
	 * Update super admin user password on update password form submission
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
		
		// return the index page if form validation fails
		if ( ! $this->form_validation->run() )
		{
			$this->status = 'error';
			$this->message = 'There were errors when attempting update your password.';
			
			return $this->index();
		}
		
		// check password the current password
		// and return the index page if current password is invalid
		$user_password = $this->user_model->get_user_password($this->user->id);
			
		$this->load->library('phpass');
		
		if ( ! $this->phpass->check($this->input->post('password'), $user_password) )
		{
			$this->set_flash_message('error', 'The current password you entered is incorrect. Please try again.');
			
			return redirect('super-admin/my-account');
		}
		
		// if everything is OK we can update the user's password
		$new_password = $this->input->post('new_password');
		$new_password = $this->phpass->hash($new_password);
		
		$update_password = $this->user_model->update($this->user->id, array(
			'password'		=> $new_password,
		), TRUE);
		
		// if database update is unsuccessful
		// return the index page with an error message
		if ( ! $update_password )
		{
			$this->set_flash_message('error', 'An error occurred whilst attempting to update your password. Please try again.');
			
			return redirect('super-admin/my-account');
		}
		
		$this->set_flash_message('success', 'Your password has been successfully updated.');
		
		return redirect('super-admin/my-account');
	}


}