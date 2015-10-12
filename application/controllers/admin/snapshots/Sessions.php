<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sessions extends Admin_context {


    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();

        // load lang files
        $this->load->model('client_model');
        $this->load->model('client_snapshots_session_model');
        $this->load->model('user_model');

        // set the page title
        $this->page_title[] = lang('admin_users_title');
    }


    /**
     * Default class method - lists sessions
     *
     */
    public function index($snapshot_id)
    {
        // add the required JS assets
        $this->js_assets[] = 'admin/data_sorting.js';
        $this->js_assets[] = 'admin/data_rows.js';
        $this->js_assets[] = 'super_admin/client_sessions.js';

        $this->content['sessions'] = $this->client_snapshots_session_model->get_many_by('snapshot_id', $snapshot_id);
        $this->content['snapshot'] = $this->client_snapshot_model->get($snapshot_id);
        $this->wrap_views[] = $this->load->view('admin/snapshots/sessions/index', $this->content, TRUE);
        $this->render();
    }


    /**
     * Insert a new client sessions on add form submission
     *
     * @return	function
     */
    public function put_session($snapshot_id)
    {
        $this->confirm_valid_client();

        $data = $this->input->post();

        if(isset($data['group_id'])) {
            $group_ids = $data['group_id'];
            unset($data['group_id']);
            $insert = $this->client_snapshots_session_model->insert($data);

            if(!empty($group_ids) && $insert ){
                $this->load->model('client_snapshot_session_group_model');
                $group_data['session_id'] = $insert;
                foreach($group_ids as $group_id){
                    $group_data['group_id'] = $group_id;
                    $insert_group_id[] = $this->client_snapshot_session_group_model->insert($group_data);
                }
            }
        } else{
            $insert = $this->client_snapshots_session_model->insert($data);
        }

        if ( ! $insert )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to add the client sessions.';
            return $this->add_sessions($snapshot_id);
        }

        $this->set_flash_message('success', 'Client sessions successfully added.');

        $this->json['redirect'] = 'admin/snapshots/sessions/' . $snapshot_id;

        return $this->ajax_response();
    }

    /**
     * Build add client sessions form
     *
     * @return	function
     */
    public function add_sessions($snapshot_id)
    {
        $this->confirm_valid_client();

        return $this->sessions_op('add', $snapshot_id);
    }

    /**
     * Build add/edit client sessions form
     *
     * @param	string
     * @param	int
     * @return	function
     */
    public function sessions_op($action = 'add', $snapshot_id ,$client_sessions_id = NULL)
    {
        if ($action === 'add')
        {
            $client_snapshot_sessions = $this->client_snapshots_session_model->create_prototype($this->client->id,$snapshot_id);

            $this->content['snapshot_id'] = $snapshot_id;

            $this->content['action_title'] = 'Add a new client snapshot sessions';
        }
        else // edit
        {
            $client_snapshot_sessions = $this->client_snapshots_session_model->get($client_sessions_id);

            $this->content['snapshot_id'] = $snapshot_id;

            $this->content['action_title'] = "Edit <span>{$client_snapshot_sessions->name}</span>";
        }
        $client_snapshot_sessions->user_id = explode(',',$client_snapshot_sessions->user_id);
        $client_snapshot_sessions->group_id = explode(',',$client_snapshot_sessions->group_id);

        $this->content['action'] = $action;
        $this->content['row'] = $client_snapshot_sessions;

        $this->load->model('user_role_model');
        $groups = $this->client_group_model->get_client_group($this->client->id);
        foreach($groups as $group) {
            foreach($group as $key => $val) {
                if($key == "name"){
                    $groups_arr[$group->id] = $val;
                }
            }
        }
        $this->content['user_groups_options'] = $groups_arr;

        $facilitator = $this->user_model->get_many_by('user_role', 'facilitator');
        $facilitator_arr = array();
        foreach($facilitator as $user) {
            foreach($user as $key => $val) {
                if($key == "username"){
                    $facilitator_arr[$user->id] = $val;
                }
            }
        }
        $this->content['facilitator_arr'] = $facilitator_arr;
        $this->json['content'] = $this->load->view('admin/snapshots/sessions/partials/snapshot_session_add_edit_row', $this->content, TRUE);
        return $this->ajax_response();
    }


    /**
     * Update a client sessions on edit form submission
     *
     * @param	int
     * @param	int
     * @return	function
     */
    public function update_session($snapshot_id,$client_sessions_id)
    {
        $this->confirm_valid_client($this->client->id);
        $this->confirm_valid_client_sessions($client_sessions_id);

        $data = $this->input->post();

        $this->load->model('client_snapshot_session_group_model');
        $group_data['session_id'] = $client_sessions_id;

        $session_groups = $this->client_snapshot_session_group_model->get_many_by('session_id',$group_data['session_id']);
        $deleted_groups = $this->client_snapshot_session_group_model->delete_session_groups($session_groups);

        if(!in_array(0,$deleted_groups)){
            $delete = true;
        }
        if($delete && isset($data['group_id'])){
            foreach($data['group_id'] as $group_id){
                $group_data['group_id'] = $group_id;
                $insert_group_id[] = $this->client_snapshot_session_group_model->insert($group_data);
            }
            unset($data['group_id']);
            }

        // attempt to update the user

        $update = $this->client_snapshots_session_model->update($client_sessions_id, $data);

        if ( ! $update )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to update the client sessions.';
            return $this->edit_session($this->client->id, $client_sessions_id);
        }

        $this->json['message'] = 'Client sessions successfully updated.';
        $this->json['redirect'] = 'admin/snapshots/sessions/' . $snapshot_id;
        return $this->ajax_response();
    }


    /**
     * Build add/edit client sessions form
     *
     * @param	int
     * @param	int
     * @return	function
     */
    public function edit_session($snapshot_id,$client_sessions_id)
    {
        $this->confirm_valid_client($this->client->id);
        $this->confirm_valid_client_sessions($client_sessions_id);

        return $this->sessions_op('edit', $snapshot_id, $client_sessions_id);
    }


    /**
     * Sort snapshot sessions
     *
     * @param	string
     * @return	function
     */
    public function sort_sessions($sessions_ids)
    {
        $this->client_snapshots_session_model->update_sort_order($this->client->id, $sessions_ids);

        $this->json['message'] = 'sessions sort-order updated.';

        return $this->ajax_response();
    }



    /**
     * Delete a snapshot sessions
     *
     * @param	int
     * @return	function
     */
    public function delete_session($client_sessions_id,$snapshot_id)
    {
        $this->confirm_valid_client_sessions($client_sessions_id);

        $delete = $this->client_snapshots_session_model->delete($client_sessions_id);
        if ( ! $delete )
        {
            $this->set_flash_message('error', 'An error occurred whilst attempting to delete the client sessions.');
        }
        else
        {
            $this->set_flash_message('success', 'Client sessions successfully deleted.');
        }
        return redirect('admin/snapshots/sessions/' . $snapshot_id);
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


    /**
     * Check that client sessions is valid (exists)
     *
     * @param	int
     * @return	function or void
     */
    protected function confirm_valid_client_sessions($client_sessions_id)
    {
        $client_sessions = $this->client_snapshots_session_model->get_by(array(
            'id'			=> $client_sessions_id,
            'client_id'		=> $this->client->id,

        ));

        if ( ! $client_sessions )
        {
            $this->set_flash_message('error', 'This client sessions does not appear to exist.');
            $redirect = 'admin/snapshots/sessions/' . $this->client->id;

            if ( $this->input->is_ajax_request() )
            {
                $this->json['redirect'] = $redirect;
                return $this->ajax_response();
            }

            return redirect($redirect);
        }
    }

}