<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Super_admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - lists super admin users
	 *
	 */
	public function index()
	{
		$super_admin_users = $this->user_model
			->order_by('lastname', 'ASC')
			->set_active_only(FALSE)
			->get_many_by('super_admin', 1);
		
		// add the required JS assets
		$this->js_assets[] = 'admin/data_rows.js';
		
		$this->content['super_admin_users'] = $super_admin_users;
		
		$this->wrap_views[] = $this->load->view('super_admin/users/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Build add user form
	 *
	 * @return	function
	 */
	public function add_user()
	{
		return $this->user_op();
	}


	/**
	 * Build add/edit user form
	 *
	 * @param	int
	 * @return	function
	 */
	public function edit_user($user_id)
	{
		return $this->user_op('edit', $user_id);
	}


	/**
	 * Build add/edit user form
	 *
	 * @param	string
	 * @param	int
	 * @return	function
	 */
	public function user_op($action = 'add', $user_id = NULL)
	{
		if ($action === 'add')
		{
			$user = $this->user_model->create_prototype();
			
			$this->content['action_title'] = 'Add a new super admin user';
		}
		else // edit
		{
			$user = $this->user_model->get_super_admin_user(intval($user_id));
			
			$this->content['action_title'] = "Edit <span>{$user->fullname}</span>";
		}
		
		$this->content['action'] = $action;
		$this->content['row'] = $user;
		
		$this->json['content'] = $this->load->view('super_admin/users/partials/user_add_edit_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Insert a new user on add form submission
	 *
	 * @return	function
	 */
	public function put_user()
	{
		$success = TRUE;
		$data = $this->input->post();
		
		// set the default super admin user properties
		$data = $this->set_default_user_properties($data);
		
		// attempt to insert the new user
		$insert_id = $this->user_model->insert($data);
		
		if ( ! $insert_id)
		{
			$success = FALSE;
		}
		
		if ( ! $success)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting to add the user.';
			return $this->add_user();
		}
		
		$this->set_flash_message('success', 'User successfully added.');
		
		$this->json['redirect'] = 'super-admin/users';
		
		return $this->ajax_response();
	}


	/**
	 * Update a user on edit form submission
	 *
	 * @param	int
	 * @return	function
	 */
	public function update_user($user_id)
	{
		$success = TRUE;
		$data = $this->input->post();
		
		// set the default super admin user properties
		$data = $this->set_default_user_properties($data);
		
		// attempt to update the user
		$update = $this->user_model->update($user_id, $data);
		
		if ( ! $update)
		{
			$success = FALSE;
		}
		
		if ( ! $success)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There errors when attempting to update the user. ' . validation_errors();
			return $this->edit_user($user_id);
		}
		
		$this->content['row'] = $this->user_model->set_active_only(FALSE)->get($user_id);
		
		$this->json['message'] = 'User successfully updated.';
		$this->json['content'] = $this->load->view('super_admin/users/partials/user_row', $this->content, TRUE);
		
		return $this->ajax_response();
	}


	/**
	 * Set default super admin user properties for put/update
	 *
	 * @param	array
	 * @return	array
	 */
	private function set_default_user_properties($data)
	{
		$data['admin']			= 1;
		$data['super_admin']	= 1;
		$data['client_id']		= 0;
		$data['user_role']		= 'admin';
		$data['admin']			= 1;
		
		return $data;
	}


	/**
	 * Delete a user
	 *
	 * @param	int
	 * @return	function
	 */
	public function delete_user($user_id)
	{
		$delete = $this->user_model->delete($user_id);
		
		if ( ! $delete)
		{
			$this->set_flash_message('error', 'An error occurred whilst attempting to delete the user.');
		}
		else
		{
			$this->set_flash_message('success', 'User successfully deleted.');
		}
		
		return redirect('super-admin/users');
	}


}