<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Snapshots extends Admin_context {


    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();

        // load lang files
        $this->lang->load('snapshots');

        // set the page title
        $this->page_title[] = lang('admin_snapshot_title');
    }


    /**
     * Default class method - lists snapshot
     *
     */
    public function index()
    {
        $this->confirm_valid_client();

        // add the required JS assets
        $this->js_assets[] = 'admin/data_rows.js';

        // check if start or end date/time not set , make start - today, end - today + 1 week;

        date_default_timezone_set($this->client->time_zone);

        foreach($this->client->snapshots as $value)
        {
            $value->commence = $value->commence ? $value->commence : strtotime('today 0:00');
            $value->expire = $value->expire ? $value->expire :  strtotime('+1 week 0:00');
        }
        $this->wrap_views[] = $this->load->view('admin/snapshots/index', $this->content, TRUE);
        $this->render();
    }

    /**
     * Build add client snapshot form
     *
     * @return	function
     */
    public function add_client_snapshot()
    {
        $this->confirm_valid_client();

        return $this->client_snapshot_op('add', $this->client->id);
    }

    /**
     * Insert a new client snapshot on add form submission
     *
     * @return	function
     */
    public function put_client_snapshot()
    {
        $this->confirm_valid_client();

        $data = $this->input->post();
        $insert = $this->client_snapshot_model->insert($data);

        if ( ! $insert )
        {
            $this->json['status'] = lang('error');
            $this->json['message'] = lang('admin_add_snapshot_error_message');
            return $this->add_client_snapshot($this->client->id);
        }

        $this->set_flash_message('success', lang('admin_add_snapshot_success_message'));

        $this->json['redirect'] = 'admin/snapshots/' . $this->client->id;

        return $this->ajax_response();
    }

    /**
     * Build add/edit client snapshot form
     *
     * @param	int
     * @return	function
     */
    public function edit_client_snapshot( $client_snapshot_id)
    {
        $this->confirm_valid_client();
        $this->confirm_valid_client_snapshot($client_snapshot_id);

        return $this->client_snapshot_op('edit',$client_snapshot_id);
    }

    /**
     * Build add/edit client snapshot form
     *
     * @param	string
     * @param	int
     * @return	function
     */
    public function client_snapshot_op($action = 'add', $client_snapshot_id = NULL)
    {
        if ($action === 'add')
        {
            $client_snapshot = $this->client_snapshot_model->create_prototype($this->client->id);

            $this->content['action_title'] = lang('admin_add_new_clinet_snapshot');
        }
        else // edit
        {
            $client_snapshot = $this->client_snapshot_model->get($client_snapshot_id);

            $this->content['action_title'] = sprintf(lang('admin_edit_snapshot_action_title'), $client_snapshot->name);
        }

        $this->content['action'] = $action;
        $this->content['row'] = $client_snapshot;

        // check if start or end date/time not set , make start - today, end - today + 1 week;

        date_default_timezone_set($this->user->time_zone);
        $this->content['row']->commence = $this->content['row']->commence ? $this->content['row']->commence : strtotime('today 0:00');
        $this->content['row']->expire = $this->content['row']->expire ? $this->content['row']->expire :  strtotime('+1 week 0:00');

        $this->json['content'] = $this->load->view('admin/snapshots/partials/client_snapshot_add_edit_row', $this->content, TRUE);

        return $this->ajax_response();
    }


    /**
     * Update a client snapshot on edit form submission
     *
     * @param	int
     * @return	function
     */
    public function update_client_snapshot($client_snapshot_id)
    {

        $this->confirm_valid_client();
        $this->confirm_valid_client_snapshot($client_snapshot_id);

        $data = $this->input->post();
        $update = $this->client_snapshot_model->update($client_snapshot_id, $data);

        if ( ! $update )
        {
            $this->json['status'] = lang('error');
            $this->json['message'] = lang('admin_update_snapshot_error_message');
            return $this->edit_client_snapshot($client_snapshot_id);
        }

        $this->content['row'] = $this->client_snapshot_model->get($client_snapshot_id);

        $this->json['message'] = lang('admin_update_snapshot_success_message');
        $this->json['content'] = $this->load->view('admin/snapshots/partials/client_snapshot_row', $this->content, TRUE);

        return $this->ajax_response();
    }


    /**
     * Delete a client snapshot
     *
     * @param	int
     * @return	function
     */
    public function delete_client_snapshot($client_snapshot_id)
    {
        $this->confirm_valid_client();
        $this->confirm_valid_client_snapshot($client_snapshot_id);

        $delete = $this->client_snapshot_model->delete($client_snapshot_id);

        if ( ! $delete )
        {
            $this->set_flash_message(lang('error'), lang('admin_delete_snapshot_error_message'));
        }
        else
        {

            $this->set_flash_message(lang('success'), lang('admin_delete_snapshot_success_message'));
        }

        return redirect('admin/snapshots/' . $this->client->id);
    }

    /**
     * Sort client snapshot
     *
     * @param	string
     * @return	function
     */
    public function sort_snapshots($snapshot_ids)
    {
        $this->client_snapshot_model->update_sort_order($this->client->id, $snapshot_ids);

        $this->json['message'] = 'Snapshot sort-order updated.';

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
            $this->set_flash_message(lang('error'), lang('admin_client_doesnt_exist'));

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
     * Check that client snapshot is valid (exists)
     *
     * @param	int
     * @return	function or void
     */
    protected function confirm_valid_client_snapshot($client_snapshot_id)
    {
        $client_snapshot = $this->client_snapshot_model->get_by(array(
            'id'			=> $client_snapshot_id,
            'client_id'		=> $this->client->id,
        ));

        if ( ! $client_snapshot )
        {
            $this->set_flash_message(lang('error'), lang('admin_client_snapshot_doesnt_exist'));

            $redirect = 'admin/snapshots/' . $this->client->id;

            if ( $this->input->is_ajax_request() )
            {
                $this->json['redirect'] = $redirect;
                return $this->ajax_response();
            }

            return redirect($redirect);
        }
    }


}