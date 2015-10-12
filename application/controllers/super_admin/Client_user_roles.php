<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_user_roles extends Super_admin_context {


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
		$this->load->model('user_role_model');
	}


	/**
	 * Default class method - lists client user roles within an edit form
	 *
	 * @param	int
	 * @return	function
	 */
	public function index($client_id = NULL)
	{
		$this->confirm_valid_client($client_id);
		
		$this->content['client'] = $this->client_model->get($client_id);
		$this->content['user_roles'] = $this->user_role_model->get_roles();
		
		$this->wrap_views[] = $this->load->view('super_admin/client_user_roles/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Update a client's user roles on edit form submission
	 *
	 * @param	int
	 * @return	function
	 */
	public function update($client_id)
	{
		$this->confirm_valid_client($client_id);
		
		$user_roles = $this->input->post('user_roles');
		$this->load->model('client_user_role_model');
		$this->client_user_role_model->set_client_user_roles($client_id, $user_roles);
		
		$this->set_flash_message('success', 'Client user roles updated.');
		
		return redirect('super-admin/client-user-roles/' . $client_id);
	}


}