<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application_context extends User_context {


	/**
	 * Application object
	 *
	 */
	public $application;


	/**
	 * Default settings model identifier
	 * and settings object
	 *
	 */
	public $application_default_settings_model = '';
	public $application_settings;


	/**
	 * Admin-only: Default is FALSE
	 * Can be set to TRUE in child classes to
	 * restrict access to admin
	 *
	 */
	public $admin_area = FALSE;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		// application validation - assume it is valid
		$valid_application = TRUE;

		// check user has the application session data
		if ( ! $this->session->has_userdata('application_id') || ! $this->session->has_userdata('application') )
		{
			$valid_application = FALSE;
		}
		
		// set the application ID
		$application_id = $this->session->userdata('application_id');
		
		// check that the application ID is valid
		if ( ! isset($this->client->applications[$application_id]) )
		{
			$valid_application = FALSE;
		}

		// set up the application object
		$this->application = $this->client->applications[$application_id];

		// check that the application ID belongs to the application URI
		$application_uri = $this->uri->segment(1);
		
		if ( $application_uri != $this->application->application )
		{
			$valid_application = FALSE;
		}
		
		// check if the sub-context or application controller
		// has been defined as admin-only
		if ( $this->admin_area && $this->user->admin != 1 )
		{
			$valid_application = FALSE;
		}

        // check that application has commenced
        if ( $this->application->commence && $this->application->commence > time() && $this->user->admin != 1 )
        {
            $valid_application = FALSE;
        }

        // check that application has not expired
        if ( $this->application->expire && $this->application->expire < time() && $this->user->admin != 1 )
        {
            $valid_application = FALSE;
        }

		// if the application is invalid, unset the session
		// application user data and redirect to home
		if ( ! $valid_application )
		{
			$this->session->unset_userdata('application_id');
			$this->session->unset_userdata('application');
			
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'home';
				return $this->ajax_response();
			}
			
			redirect('home');
		}
		
		// set up the application settings model and object
		$this->load->model('client_application_setting_model');
		$this->client_application_setting_model->initialize($this->application_default_settings_model);
		$this->application_settings = $this->client_application_setting_model->get_all_settings();
		
		// load global application lang files
		$this->lang->load('application');
		
		// add the default global application assets
		$this->css_assets[] = 'default/application.less';
		$this->js_assets[] = 'application/app_navigation_toggle.js';
		
		// set the page title
		$this->page_title[] = $this->application->name;
		
		// add application specific CSS classes
		$this->wrap_classes[] = 'application';
		$this->wrap_classes[] = $this->application->application;
	}


}