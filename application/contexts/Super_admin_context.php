<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Super_admin_context extends Client_context {


	/**
	 * The user object
	 *
	 */
	public $user;


	/**
	 * The super admin areas and current area
	 *
	 */
	public $super_admin_areas;
	public $area = NULL;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// session validation - assume it is valid
		$valid_session = TRUE;
		
		// check that the user has a valid session for super admin
		if ( ! $this->session->has_userdata('super_admin_logged_in') || ! $this->session->has_userdata('user_id') )
		{
			$valid_session = FALSE;
		}
		
		// if session is valid, set the user object
		if ( $valid_session )
		{
			$this->load->model('user_model');
			$this->user = $this->user_model->get_super_admin_user(intval($this->session->userdata('user_id')));
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
		// is beyond the default session timeout range
		if ( $this->user )
		{
			$now = time();
			$session_timeout = intval($this->user->last_activity) + 3600;
			
			if ( $now > $session_timeout )
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
			
			if ( $uri )
			{
				$this->session->set_userdata('redirect', $uri);
			}
			
			// unset the logged in session data
			$this->session->unset_userdata('super_admin_logged_in');
			
			redirect('login');
		}
		
		// update the user's last activity data
		$this->user_model->update_last_activity($this->user->id, time(), $this->input->ip_address());
		
		// set the super admin areas and current area
		$this->load->model('super_admin_area_model');
		$this->super_admin_areas = $this->super_admin_area_model->get_all();
		
		if ( is_null($this->area) )
		{
			$this->area = $this->uri->segment(2);
		}
		
		// if user is not super admin admin check if this area is super-admin-admin,
		// and redirect if necessary
		if ( $this->user->super_admin_admin != 1 && $this->area && $this->super_admin_areas[$this->area]['is_admin'] === TRUE )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'super-admin';
				return $this->ajax_response();
			}
			
			redirect('super-admin');
		}
		
		// if user is not super-admin-admin,
		// remove admin areas from the areas array
		if ( $this->user->super_admin_admin != 1 )
		{
			foreach ($this->super_admin_areas as $area => $properties)
			{
				if ( $properties['is_admin'] === TRUE )
				{
					unset($this->super_admin_areas[$area]);
				}
			}
		}
		
		// add page render observers to the class callback lists
		$this->before_render[] = 'build_environemnt';
	}


	/**
	 * Build super admin environment
	 *
	 * @return	void
	 */
	protected function build_environemnt()
	{
		// add the global super admin assets
		$this->css_assets[] = 'admin/admin.less';
		$this->css_assets[] = 'super_admin/super_admin.less';
		
		// include the date picker UI assets
		$this->css_assets[] = 'third_party/glDatePicker.smartlab.less';
		$this->js_assets[] = 'third_party/glDatePicker.min.js';
		$this->js_assets[] = 'default/date_picker.js';
		$this->css_assets[] = 'default/date_picker_touch.less';
		$this->js_assets[] = 'default/date_picker_touch.js';
		
		// include the sortable UI assets
		$this->js_assets[] = 'default/sortable.js';
		
		// set the environment vars
		$this->content['super_admin_areas'] = $this->super_admin_areas;
		$this->content['current_area'] = $this->area;
		
		// build the super admin navigation
		$navigation_view =  $this->load->view('super_admin/page/navigation', $this->content, TRUE);
		
		$header_content = array();
		$header_content['navigation_view'] = $navigation_view;
		
		// add the super admin header to the top of the wrap_views container
		array_unshift($this->wrap_views, $this->load->view('super_admin/page/header', $header_content, TRUE));
		
		// add the page footer
		$this->footer_views[] = $this->load->view('super_admin/page/footer', FALSE, TRUE);
	}


	/**
	 * Check that client is valid (exists)
	 *
	 * @param	int
	 * @return	function or void
	 */
	protected function confirm_valid_client($client_id)
	{
		$client = $this->client_model->get($client_id);
		
		if ( ! $client )
		{
			$this->set_flash_message('error', 'This client does not appear to exist.');
			
			$redirect = 'super-admin/clients';
			
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = $redirect;
				return $this->ajax_response();
			}
			
			return redirect($redirect);
		}
	}


	/**
	 * Check that client application is valid (exists)
	 *
	 * @param	int
	 * @param	int
	 * @return	function or void
	 */
	protected function confirm_valid_client_application($client_id, $client_application_id)
	{
		$client_application = $this->client_application_model->get_by(array(
			'id'			=> $client_application_id,
			'client_id'		=> $client_id,
		));
		
		if ( ! $client_application )
		{
			$this->set_flash_message('error', 'This client application does not appear to exist.');
			
			$redirect = 'super-admin/client-applications/' . $client_id;
			
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = $redirect;
				return $this->ajax_response();
			}
			
			return redirect($redirect);
		}
	}
}