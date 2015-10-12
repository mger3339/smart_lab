<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_context extends MY_Controller {


	/**
	 * The client object
	 *
	 */
	public $client;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load required models
		$this->load->model('client_model');
		
		// extract the domain and sub-domain for client look-up
		$domain = $_SERVER['HTTP_HOST'];
		$domain_segs = explode('.', $domain);
		$sub_domain = array_shift($domain_segs);
		
		// build the client object
		$this->client = $this->client_model->get_current($domain, $sub_domain);
		
		// if the client is not valid, redirect to the welcome page
		if ( ! $this->client )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'welcome';
				return $this->ajax_response();
			}
			
			redirect('welcome');
		}
		
		// if the client is not active,
		// redirect to the client "unavailable" page
		if ( $this->client->active != 1 )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'client-unavailable';
				return $this->ajax_response();
			}
			
			redirect('client-unavailable');
		}
		
		// if the client has not yet commenced,
		// redirect to the client "not available yet" page
		if ( $this->client->commence && $this->client->commence > time() )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'client-not-yet-commenced';
				return $this->ajax_response();
			}
			
			redirect('client-not-yet-commenced');
		}
		
		// if the client has expired,
		// redirect to the client "expired" page
		if ( $this->client->expire && $this->client->expire < time() )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'client-expired';
				return $this->ajax_response();
			}
			
			redirect('client-expired');
		}
		
		// set the timezone
		date_default_timezone_set($this->client->time_zone);
		
		// set the client language
		$this->config->set_item('language', $this->client->language);
		
		// set the page title
		$this->page_title[] = $this->client->name;
	}


	/**
	 * Utility function to determine whether user
	 * is logged in, and if so redirect
	 *
	 */
	protected function logged_in_redirect()
	{
		// client user redirect
		if ( $this->client->id > 0 && $this->session->has_userdata('client_id') && $this->session->has_userdata('user_id') )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'home';
				return $this->ajax_response();
			}
			
			redirect();
		}
		
		// super admin user redirect
		if ( $this->client->id == 0 && $this->session->has_userdata('super_admin_logged_in') && $this->session->has_userdata('user_id'))
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = 'super-admin';
				return $this->ajax_response();
			}
			
			redirect('super-admin');
		}
	}


}