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
    public function index($snapshot_id)
    {
        // add the required JS assets
        $this->js_assets[] = 'admin/data_rows.js';
        $this->js_assets[] = 'super_admin/client_applications.js';

        $this->content['applications'] = $this->client_application_model->get_many_by('snapshot_id', $snapshot_id);
        $this->content['snapshot'] = $this->client_snapshot_model->get($snapshot_id);
        $this->wrap_views[] = $this->load->view('admin/snapshots/applications/index', $this->content, TRUE);
        $this->render();
    }


    /**
     * Insert a new client application on add form submission
     *
     * @return	function
     */
    public function put_application($snapshot_id)
    {
        $this->confirm_valid_client();

        $data = $this->input->post();
        $insert = $this->client_application_model->insert($data);

        if ( ! $insert )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to add the client application.';
            return $this->add_application($snapshot_id);
        }

        $this->set_flash_message('success', 'Client application successfully added.');

        $this->json['redirect'] = 'admin/snapshots/applications/' . $snapshot_id;

        return $this->ajax_response();
    }

    /**
     * Build add client application form
     *
     * @return	function
     */
    public function add_application($snapshot_id)
    {
        $this->confirm_valid_client();

        return $this->application_op('add', $snapshot_id);
    }

    /**
     * Build add/edit client application form
     *
     * @param	string
     * @param	int
     * @return	function
     */
    public function application_op($action = 'add', $snapshot_id ,$client_application_id = NULL)
    {
        if ($action === 'add')
        {
            $client_application = $this->client_application_model->create_prototype($this->client->id,$snapshot_id);

            $this->content['snapshot_id'] = $snapshot_id;

            $this->content['action_title'] = 'Add a new client application';
        }
        else // edit
        {
            $client_application = $this->client_application_model->get($client_application_id);

            $this->content['snapshot_id'] = $snapshot_id;

            $this->content['action_title'] = "Edit <span>{$client_application->name}</span>";
        }

        $this->content['action'] = $action;
        $this->content['row'] = $client_application;
        $this->content['applications'] = $this->application_model->get_application_options();


        $this->json['content'] = $this->load->view('admin/snapshots/applications/partials/snapshot_application_add_edit_row', $this->content, TRUE);
        return $this->ajax_response();
    }


    /**
     * Update a client application on edit form submission
     *
     * @param	int
     * @param	int
     * @return	function
     */
    public function update_application($snapshot_id,$client_application_id)
    {
        $this->confirm_valid_client($this->client->id);
        $this->confirm_valid_client_application($client_application_id);

        $data = $this->input->post();

        $update = $this->client_application_model->update($client_application_id, $data);

        if ( ! $update )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to update the client application.';
            return $this->edit_application($this->client->id, $client_application_id);
        }

        $this->content['row'] = $this->client_application_model->get($client_application_id);
        $this->content['applications'] = $this->application_model->get_all();
        $this->content['snapshot'] = $this->client_snapshot_model->get($snapshot_id);

        $this->json['message'] = 'Client application successfully updated.';
        $this->json['content'] = $this->load->view('admin/snapshots/applications/partials/snapshot_application_row', $this->content, TRUE);

        return $this->ajax_response();
    }


    /**
     * Build add/edit client application form
     *
     * @param	int
     * @param	int
     * @return	function
     */
    public function edit_application($snapshot_id,$client_application_id)
    {
        $this->confirm_valid_client($this->client->id);
        $this->confirm_valid_client_application($client_application_id);

        return $this->application_op('edit', $snapshot_id, $client_application_id);
    }


    /**
     * Sort snapshot applications
     *
     * @param	string
     * @return	function
     */
    public function sort_applications($application_ids)
    {
        $this->client_snapshot_model->update_sort_order($this->client->id, $application_ids);

        $this->json['message'] = 'Applications sort-order updated.';

        return $this->ajax_response();
    }



    /**
     * Delete a snapshot application
     *
     * @param	int
     * @return	function
     */
    public function delete_application($client_application_id,$snapshot_id)
    {

        $this->confirm_valid_client_application($client_application_id);

        $delete = $this->client_application_model->delete($client_application_id);

        if ( ! $delete )
        {
            $this->set_flash_message('error', 'An error occurred whilst attempting to delete the client application.');
        }
        else
        {
            $this->set_flash_message('success', 'Client application successfully deleted.');
        }
        return redirect('admin/snapshots/applications/' . $snapshot_id);
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
     * Check that client application is valid (exists)
     *
     * @param	int
     * @return	function or void
     */
    protected function confirm_valid_client_application($client_application_id)
    {
        $client_application = $this->client_application_model->get_by(array(
            'id'			=> $client_application_id,
            'client_id'		=> $this->client->id,

        ));

        if ( ! $client_application )
        {
            $this->set_flash_message('error', 'This client application does not appear to exist.');
            $redirect = 'admin/snapshots/applications/' . $this->client->id;

            if ( $this->input->is_ajax_request() )
            {
                $this->json['redirect'] = $redirect;
                return $this->ajax_response();
            }

            return redirect($redirect);
        }
    }

}