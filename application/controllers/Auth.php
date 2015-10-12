<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Client_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load lang files
		$this->lang->load('auth');
	}


	/**
	 * Default class method - build login page
	 *
	 */
	public function index()
	{
		// check if user is already logged in, and if so redirect
		$this->logged_in_redirect();
		
		// build login page
		$this->page_title[] = lang('login_title');
		
		$this->content['client_name'] = $this->client->name;
		$this->content['user_redirect'] = $this->session->userdata('redirect');
		
		$this->css_assets[] = 'default/login.less';
		
		$this->wrap_views[] = $this->load->view('auth/login', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Authenticate login submission
	 *
	 */
	public function authenticate()
	{
		// set up login form validation
		$this->form_validation->set_rules('username', 'username', 'required|trim');
		$this->form_validation->set_rules('password', 'password', 'required|trim');
		
		// if login form submission is valid
		if ( $this->form_validation->run() !== FALSE )
		{
			// set the credentials
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			// check for client user first
			$this->load->model('user_model');
			$user = $this->user_model->get_by_email_or_username($username);
			
			// check if client auto register is enabled if user does not exist
			if ( ! $user && $this->client->user_auto_register == 1 )
			{
				// check that submitted username is a valid email
				if ( filter_var($username, FILTER_VALIDATE_EMAIL) )
				{
					// check that user has valid email hostname
					$this->load->model('email_hostname_model');
					$valid_email_hostname = $this->email_hostname_model->validate_hostname($username);
					
					if ( $valid_email_hostname )
					{
						// auto register the user by creating a new uer account
						$user_data = $this->user_model->create_prototype(TRUE);
						
						$user_data['client_id']		= $this->client->id;
						$user_data['email']			= $username;
						$user_data['username']		= $username;
						$user_data['password']		= $this->client_model
							->get_auto_register_password($this->client->id);
						$user_data['country_iso']	= $this->client->country_iso;
						$user_data['time_zone']		= $this->client->time_zone;
						$user_data['currency']		= $this->client->currency;
						
						$user_id = $this->user_model->insert($user_data, TRUE);
						
						if ( $user_id )
						{
							$user = $this->user_model->get($user_id);
						}
					}
				}
			}
			
			// check for super admin user if client user does not exist
			if ( ! $user )
			{
				$user = $this->user_model->get_super_admin_user($username);
			}
			
			// if the user is valid...
			if ( $user )
			{
				// ...check to see if user is locked
				if ( $user->locked == 1 )
				{
					// check if user can be unlocked automatically
					$this->load->model('login_attempt_model');
					$user_unlock = $this->login_attempt_model
						->user_unlock($username, $this->input->ip_address());
					
					if ( $user_unlock )
					{
						$this->user_model->update($user->id, array(
							'locked'		=> 0,
						), TRUE);
					}
					else
					{
						// redirect to the login form with user locked message
						$this->set_flash_message('error', lang('login_user_locked'));
						
						redirect($this->uri->uri_string());
					}
				}
				
				// ...check password
				$valid_password = $this->user_model->get_user_password($user->id);
				
				$this->load->library('phpass');
				
				if ( $this->phpass->check($password, $valid_password) )
				{
					$login_time = time();
					
					// set the user logged in session data
					// and redirect to the pre login request uri
					// or redirect to the default controller
					$session_data = array(
						'client_id'				=> $this->client->id,
						'user_id'				=> $user->id,
						'login_time'			=> $login_time,
					);
					
					// If the user is super admin set session data accordingly.
					// Otherwise, unset super admin session data in the event
					// of a super admin user logging in as a regular user
					if ( $user->super_admin == 1 )
					{
						$session_data['super_admin'] = TRUE;
					}
					else
					{
						$this->session->unset_userdata('super_admin');
					}
					
					// if we are within the context of super admin,
					// set the special super admin session data
					if ( $this->client->id == 0 )
					{
						$session_data['super_admin_logged_in'] = TRUE;
					}
					
					$this->session->set_userdata($session_data);
					
					// update the user's last login time and last activity time
					$this->user_model->update($user->id, array(
						'last_login'		=> $login_time,
						'last_activity'		=> $login_time,
					), TRUE);
					
					$user_redirect = $this->input->post('user_redirect');
					if ( $user_redirect )
					{
						redirect($user_redirect);
					}
					
					// if we are within the context of super admin,
					// redirect accordingly
					if ( $this->client->id == 0 )
					{
						redirect('super-admin');
					}
					
					redirect();
				}
				
				// ...password was invalid, register login attempt
				// and lock user if necessary
				$this->load->model('login_attempt_model');
				$lock_user = $this->login_attempt_model
					->register_login_attempt($username, $this->input->ip_address());
				
				if ( $lock_user )
				{
					$this->user_model->update($user->id, array(
						'locked'		=> 1,
					), TRUE);
					
					// redirect to the login form with user locked message
					$this->set_flash_message('error', lang('login_too_many_attempts'));
					
					redirect($this->uri->uri_string());
				}
			}
			
			// user is invalid so set error message
			// and redirect to the login form
			$this->set_flash_message('error', lang('login_invalid_submission'));
			
			redirect($this->uri->uri_string());
		}
		
		// invalid login form submission - re-render the login form
		$this->index();
	}


	/**
	 * Close client user session
	 *
	 */
	public function logout()
	{
		// completely destroy the session
		$this->session->sess_destroy();
		
		redirect();
	}


}