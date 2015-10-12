<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load language files
		$this->lang->load('admin_settings');
		
		// set the page title
		$this->page_title[] = lang('admin_settings_title');
	}


	/**
	 * Default class method - display client settings
	 *
	 */
	public function index()
	{
		// set up the auto register settings content
		$auto_register_content = array();
		$auto_register_content['user_auto_register'] = $this->client->user_auto_register;
		
		$this->content['auto_register_content'] = $auto_register_content;
		
		// set up the email hostnames settings content
		$this->load->model('email_hostname_model');
		
		$email_hostnames_content = array();
		$email_hostnames_content['email_hostnames'] = $this->email_hostname_model
			->order_by('hostname', 'ASC')
			->get_all();
		$email_hostnames_content['error_id'] = NULL;
		
		$this->content['email_hostnames_content'] = $email_hostnames_content;
		
		$this->wrap_views[] = $this->load->view('admin/settings/index', $this->content, TRUE);
		$this->render();
	}


}