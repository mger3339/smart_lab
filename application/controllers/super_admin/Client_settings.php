<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_settings extends Super_admin_context {


	public $area = 'clients';


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load models
		$this->load->model('client_model');
		$this->load->model('client_setting_model');
	}


	/**
	 * Default class method - lists client settings
	 *
	 * @param	int
	 * @return	function
	 */
	public function index($client_id = NULL)
	{
		$this->confirm_valid_client($client_id);
		
		$this->content['client'] = $this->client_model->get($client_id);
		$this->content['setting_groups'] = $this->client_setting_model->get_setting_groups();
		
		$this->wrap_views[] = $this->load->view('super_admin/client_settings/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Update a client's settings on edit form submission
	 *
	 * @param	int
	 * @return	function
	 */
	public function update($client_id)
	{
		$this->confirm_valid_client($client_id);
		
		$this->client_setting_model->update_client_settings($client_id);
		
		$this->set_flash_message('success', 'Additional client settings updated.');
		
		return redirect('super-admin/client-settings/' . $client_id);
	}


}