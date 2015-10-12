<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applications extends Admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		// load lang files
		$this->load->model('client_model');
		$this->load->model('application_model');
		$this->load->model('client_application_model');

		// set the page title
		$this->page_title[] = lang('admin_users_title');
	}


	/**
	 * Default class method - lists applications
	 *
	 */
	public function index()
	{
		// add the required JS assets
		$this->js_assets[] = 'admin/data_rows.js';
		$this->js_assets[] = 'admin/app_search.js';
		$this->js_assets[] = 'super_admin/applications.js';

		$this->content['applications'] = $this->client_application_model->get_all();

		//getting $applications_colors options
		$this->content['applications_colors'] = $this->application_model->get_application_colors_options();

		// check if start or end date/time not set , make start - today, end - today + 1 week;
		date_default_timezone_set($this->client->time_zone);

		foreach($this->client->applications as $value)
		{
			$value->commence = $value->commence ? $value->commence : strtotime('today 0:00');
			$value->expire = $value->expire ? $value->expire :	strtotime('+1 week 0:00');
		}


		$this->wrap_views[] = $this->load->view('admin/applications/index', $this->content, TRUE);
		
		$this->render();
	}


	/**
	 * Build add client application form
	 *
	 * @return	function
	 */
	public function add_application()
	{
		return $this->application_op('add', $this->client->id);
	}


	/**
	 * Build add/edit client application form
	 *
	 * @param	int
	 * @return	function
	 */
	public function edit_application( $application_id)
	{
		$this->confirm_valid_application($application_id);

		return $this->application_op('edit',$application_id);
	}


	/**
	 * Build add/edit client application form
	 *
	 * @param	string
	 * @param	int
	 * @return	function
	 */
	public function application_op($action = 'add', $application_id = NULL)
	{
		if ($action === 'add')
		{
			$application = $this->client_application_model->create_prototype($this->client->id);

			$this->content['action_title'] = 'Add a new application';
		}
		else // edit
		{
			$application = $this->client_application_model->get($application_id);

			$this->content['action_title'] = "Edit <span>{$application->name}</span>";
		}

		$this->content['action'] = $action;
		$this->content['row'] = $application;
		$this->content['applications'] = $this->application_model->get_application_options();
		$this->content['applications_colors'] = $this->application_model->get_application_colors_options();

		// check if start or end date/time not set , make start - today, end - today + 1 week;
		date_default_timezone_set($this->client->time_zone);
		
		$this->content['row']->commence = $this->content['row']->commence ? $this->content['row']->commence : strtotime('today 0:00');
		$this->content['row']->expire = $this->content['row']->expire ? $this->content['row']->expire :	 strtotime('+1 week 0:00');

        $this->load->model('client_group_model');
        $groups = $this->client_group_model->get_client_group($this->client->id);
        foreach($groups as $group) {
            foreach($group as $key => $val) {
                if($key == "name"){
                    $groups_arr[$group->id] = $val;
                }
            }
        }
        $this->content['user_groups_options'] = $groups_arr;

        $this->load->model('user_model');

        $users = $this->user_model->get_many(explode(',',$application->user_id));

        $this->content['user_arr'] = $users;

        $groups = $this->client_group_model->get_many(explode(',',$application->group_id));

        $this->content['group_arr'] = $groups;

        $facilitators = $this->user_model->get_many(explode(',',$application->facilitator_id));

        $this->content['facilitator_arr'] = $facilitators;

		$this->json['content'] = $this->load->view('admin/applications/partials/application_add_edit_row', $this->content, TRUE);

		return $this->ajax_response();
	}


	/**
	 * Insert a new client application on add form submission
	 *
	 * @return	function
	 */
	public function put_application()
	{
		$data = $this->input->post();
        // attempt to insert the new user
        $group_ids = explode(',',$data['group_id']);
        unset($data['group_id']);

        $insert = $this->client_application_model->insert($data);

        if(!empty($group_ids) && $insert ){
            $this->load->model('client_application_group_model');
            $group_data['application_id'] = $insert;
            foreach($group_ids as $group_id){
                $group_data['group_id'] = $group_id;
                $insert_group_id[] = $this->client_application_group_model->insert($group_data);
            }
        }

		if ( ! $insert )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There errors when attempting to add the application.';
			return $this->add_application($this->client->id);
		}

		$this->set_flash_message('success', 'Application successfully added.');

		$this->json['redirect'] = 'admin/applications/' . $this->client->id;

		return $this->ajax_response();
	}


	/**
	 * Update a client application on edit form submission
	 *
	 * @param	int
	 * @return	function
	 */
	public function update_application($application_id)
	{
		$this->confirm_valid_application($application_id);

		$data = $this->input->post();

        $this->load->model('client_application_group_model');
        $group_data['application_id'] = $application_id;

        $application_groups = $this->client_application_group_model->get_many_by('application_id',$application_id);
        $deleted_groups = $this->client_application_group_model->delete_application_groups($application_groups);

        if(!in_array(0,$deleted_groups)){
            $delete = true;
        }
        $data['group_id'] = explode(',',$data['group_id']);
        if($delete){
            foreach($data['group_id'] as $group_id){
                $group_data['group_id'] = $group_id;
                $insert_group_id[] = $this->client_application_group_model->insert($group_data);
            }
        }

        // attempt to update the user
        unset($data['group_id']);

		$update = $this->client_application_model->update($application_id, $data);

		if ( ! $update )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There errors when attempting to update the application.';
			return $this->edit_application($application_id);
		}

		$this->content['row'] = $this->client_application_model->get($application_id);
		$this->content['applications'] = $this->client_application_model->get_all();

		//getting $applications_colors options
		$this->content['applications_colors'] = $this->application_model->get_application_colors_options();

		$this->json['message'] = 'Application successfully updated.';
		$this->json['content'] = $this->load->view('admin/applications/partials/application_row', $this->content, TRUE);

		return $this->ajax_response();
	}


	/**
	 * Delete a client application
	 *
	 * @param	int
	 * @return	function
	 */
	public function delete_application($application_id)
	{
		$this->confirm_valid_application($application_id);

		$delete = $this->client_application_model->delete($application_id);

		if ( ! $delete )
		{
			$this->set_flash_message('error', 'An error occurred whilst attempting to delete the application.');
		}
		else
		{
			$this->set_flash_message('success', 'Application successfully deleted.');
		}

		return redirect('admin/applications/' . $this->client->id);
	}


	/**
	 * Sort client applications
	 *
	 * @param	string
	 * @return	function
	 */
	public function sort_applications($application_ids)
	{
		$this->client_application_model->update_sort_order($this->client->id, $application_ids);

		$this->json['message'] = 'Applications sort-order updated.';

		return $this->ajax_response();
	}


	/**
	 * Check that client is valid (exists)
	 *
	 * @return	function or void
	 */
	protected function confirm_valid_client()
	{
		$client = $this->client_model->get($this->client->id);

		if ( ! $client )
		{
			$this->set_flash_message('error', 'This client does not appear to exist.');

			$redirect = 'admin/clients';

			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = $redirect;
				return $this->ajax_response();
			}

			return redirect($redirect);
		}
	}

    public function searchUsers() {

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $ids = isset($_GET['ids']) && !empty($_GET['ids']) ? $_GET['ids'] : '';

        if($keyword){

            $users = $this->client_application_model->search_users($keyword,$ids);

            echo json_encode($users);
        }else{
            $users = [];
            echo json_encode($users);
        }

    }

    public function searchGroups() {

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $ids = isset($_GET['ids']) && !empty($_GET['ids']) ? $_GET['ids'] : '';

        if($keyword){

            $groups = $this->client_application_model->search_groups($keyword,$ids);

            echo json_encode($groups);
        }else{
            $groups = [];
            echo json_encode($groups);
        }

    }

    public function searchFacilitators() {

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $ids = isset($_GET['ids']) && !empty($_GET['ids']) ? $_GET['ids'] : '';

        if($keyword){

            $facilitators = $this->client_application_model->search_facilitators($keyword,$ids);

            echo json_encode($facilitators);
        }else{
            $facilitators = [];
            echo json_encode($facilitators);
        }

    }

	/**
	 * Check that application is valid (exists)
	 *
	 * @param	int
	 * @return	function or void
	 */
	protected function confirm_valid_application($application_id)
	{
		$application = $this->client_application_model->get_by(array(
			'id'			=> $application_id,
			'client_id'		=> $this->client->id,
		));

		if ( ! $application )
		{
			$this->set_flash_message('error', 'This application does not appear to exist.');

			$redirect = 'admin/applications/' . $this->client->id;

			if ( $this->input->is_ajax_request() )
			{
				$this->json['redirect'] = $redirect;
				return $this->ajax_response();
			}

			return redirect($redirect);
		}
	}


}