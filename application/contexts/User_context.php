<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_context extends Client_context {


	/**
	 * The user object
	 *
	 */
	public $user;


	/**
	 * Default build the applications navigation
	 *
	 */
	protected $build_application_nav = TRUE;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// session validation - assume it is valid
		$valid_session = TRUE;
		
		// check that the user has a valid session
		if ( ! $this->session->has_userdata('client_id') || ! $this->session->has_userdata('user_id') )
		{
			$valid_session = FALSE;
		}
		
		// if session is valid, check the session client ID
		if ( $valid_session && intval($this->session->userdata('client_id')) != intval($this->client->id) )
		{
			$valid_session = FALSE;
		}
		
		// if client is valid, set the user object
		if ( $valid_session )
		{
			$this->load->model('user_model');
			
			// if the super admin session variable is set
			// we need to bypass the client_id lookup
			if ($this->session->has_userdata('super_admin') && $this->session->userdata('super_admin'))
			{
				$this->user = $this->user_model->get_super_admin_user(intval($this->session->userdata('user_id')));
			}
			else
			{
				$this->user = $this->user_model->get(intval($this->session->userdata('user_id')));
			}
		}
		
		// if the user is invalid, invalidate the session
		if ( ! $this->user )
		{
			$valid_session = FALSE;
		}
		
		// invalidate the session if the user's last login time
		// does not match the session login time (to prevent concurrent logins)
		$login_time = $this->session->userdata('login_time');
		
		if ( ! $login_time || ($this->user && $this->user->last_login != $login_time) )
		{
			$valid_session = FALSE;
		}
		
		// invalidate the session if the user's last activity time
		// is beyond the client sessions timeout range
		if ( $this->user )
		{
			$now = time();
			$session_timeout = intval($this->user->last_activity) + $this->client->session_timeout;
			if ($now > $session_timeout)
			{
				$valid_session = FALSE;
			}
		}
		
		// if the session is invalid during an AJAX request,
		// return the 'invalid session' response
		if ( ! $valid_session && $this->input->is_ajax_request() )
		{
			echo 'invalid_session';
			exit;
		}
		
		// if session is invalid during a page request,
		// redirect to login page
		if ( ! $valid_session )
		{
			// grab the request URI for redirect in case of successful login
			$uri = $this->uri->uri_string();
			if ($uri)
			{
				$this->session->set_userdata('redirect', $uri);
			}
			
			// unset the logged in session data
			$this->session->unset_userdata('client_id');
			$this->session->unset_userdata('user_id');
			
			redirect('login');
		}
		
		// if the client is super admin
		// redirect to the super-admin URI
		if ( $this->client->id == 0 )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'super-admin';
				return $this->ajax_response();
			}
			
			redirect('super-admin');
		}
		
		// update the user's last activity data
		$this->user_model->update_last_activity($this->user->id, time(), $this->input->ip_address());
		
		// add page render observers to the parent class callback lists
		$this->before_render[] = 'build_user_nav';
		
		if ( $this->build_application_nav )
		{
			$this->before_render[] = 'build_application_nav';
		}

		$this->before_render[] = 'build_notifications_nav';

		// Insert notifications aside right after header
		$user_notification_data = $this->user_model->with('notifications')->get($this->user->id);
		$this->wrap_views[] = $this->load->view('user_account/partials/notifications_aside', $user_notification_data, TRUE);

		// Load notifications aside assets
		$this->js_assets[] = 'default/jquery.timeago.js';
		$this->js_assets[] = 'default/notifications_aside.js';
		$this->css_assets[] = 'default/notifications_aside.less';
	}


	/**
	 * Build the user's navigation area
	 *
	 * @return	void
	 */
	protected function build_user_nav()
	{
		$this->lang->load('user_nav');
		
		$user_nav_content = array();
		$user_nav_content['client'] = $this->client;
		$user_nav_content['user'] = $this->user;

		$user_nav_view = $this->load->view('system_nav/user_nav', $user_nav_content, TRUE);
		
		$this->add_system_nav_item('user_nav', $this->user->fullname, $user_nav_view);
	}


	/**
	 * Build the application navigation area
	 *
	 * @return	void
	 */
	protected function build_application_nav()
	{
		$this->lang->load('applications_nav');
		
		$application_nav_content = array();
		$application_nav_content['client'] = $this->client;
		$application_nav_content['user'] = $this->user;

        //check if applications is valid or not for non-admin users
        foreach($this->client->applications as $value)
        {
            $valid_application = TRUE;

            // check that application has commenced
            if ( $value->commence && $value->commence > time() && $this->user->admin != 1 )
            {
                $valid_application = FALSE;
            }

            // check that application has not expired
            if ( $value->expire && $value->expire < time() && $this->user->admin != 1 )
            {
                $valid_application = FALSE;
            }

            $value->valid_application = $valid_application;

        }

        $application_nav_view = $this->load->view('system_nav/application_nav', $application_nav_content, TRUE);
		
		$this->add_system_nav_item('applications_nav', lang('applications_nav_title'), $application_nav_view);
	}


	/**
	 * Build the notifications navigation item which will display the notification aside
	 *
	 * @return	void
	 */
	protected function build_notifications_nav()
	{
		$this->add_system_nav_item('user-notifications', lang('user_nav_notifications'), '');
	}

}