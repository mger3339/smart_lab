<?php
/**
 * Created by PhpStorm.
 * group: Suren Aghayan
 * Date: 16.07.2015
 * Time: 17:11
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends Admin_context {


    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_model');
        $this->load->model('client_group_model');

    }


    /**
     * Default class method - lists groups
     *
     */
    public function index()
    {
        // get the groups
        $groups_ids = $this->client_user_group_model->get_groups('user_id', $this->user->id);
        $groups = $this->client_group_model->get_many($groups_ids);

        // add the required JS assets
        $this->js_assets[] = 'admin/data_rows.js';

        $this->content['groups'] = $groups;

        $this->wrap_views[] = $this->load->view('admin/groups/index', $this->content, TRUE);
        $this->render();
    }


    /**
     * Build add client group form
     *
     * @return	function
     */
    public function add_group()
    {
        return $this->group_op('add', $this->client->id);
    }


    /**
     * Build add/edit client group form
     *
     * @param	string
     * @param	int
     * @return	function
     */
    public function group_op($action = 'add', $group_id = NULL)
    {
        if ($action === 'add')
        {
            $group = $this->client_group_model->create_prototype($this->client->id);

            $this->content['action_title'] = 'Add a new group';
        }
        else // edit
        {
            $group = $this->client_group_model->get($group_id);

            $this->content['action_title'] = "Edit <span>{$group->name}</span>";
        }
        $this->content['action'] = $action;
        $this->content['row'] = $group;


        $this->json['content'] = $this->load->view('admin/groups/partials/group_add_edit_row', $this->content, TRUE);

        return $this->ajax_response();
    }


    /**
     * Insert a new client group on add form submission
     *
     * @return	function
     */
    public function put_group()
    {
        $data = $this->input->post();
        $insert = $this->client_group_model->insert($data);
        $data_groups = array(
            'user_id' => $this->user->id,
            'group_id' => $insert
        );
        $this->client_user_group_model->insert($data_groups);

        if ( ! $insert )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to add the group.';
            return $this->add_group($this->client->id);
        }

        $this->set_flash_message('success', 'Group successfully added.');

        $this->json['redirect'] = 'admin/groups/' . $this->client->id;

        return $this->ajax_response();
    }

    public function add_new_group()
    {
        $data = $this->input->post();
        $insert = $this->client_group_model->insert($data);

        $this->content['row_id'] = 'row_id';
        $this->json['insert'] = $insert;
        $this->json['content'] = $this->load->view('admin/users/partials/new_group_row', $this->content, TRUE);
        $this->json['token'] = array('name' => $this->security->get_csrf_token_name(), 'value' => $this->security->get_csrf_hash());


        return $this->ajax_response();
    }


    /**
     * Build add/edit client group form
     *
     * @param	int
     * @return	function
     */
    public function edit_group( $group_id)
    {
        $this->confirm_valid_group($group_id);

        return $this->group_op('edit',$group_id);
    }


    /**
     * Check that group is valid (exists)
     *
     * @param	int
     * @return	function or void
     */
    protected function confirm_valid_group($group_id)
    {
        $group = $this->client_group_model->get_by(array(
            'id'			=> $group_id,
            'client_id'		=> $this->client->id,
        ));

        if ( ! $group )
        {
            $this->set_flash_message('error', 'This group does not appear to exist.');

            $redirect = 'admin/groups/' . $this->client->id;

            if ( $this->input->is_ajax_request() )
            {
                $this->json['redirect'] = $redirect;
                return $this->ajax_response();
            }

            return redirect($redirect);
        }
    }


    /**
     * Update a client group on edit form submission
     *
     * @param	int
     * @return	function
     */
    public function update_group($group_id)
    {
        $this->confirm_valid_group($group_id);
        $data = $this->input->post();
        $update = $this->client_group_model->update($group_id, $data);

        if ( ! $update )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to update the group.';
            return $this->edit_group($group_id);
        }

        $this->content['row'] = $this->client_group_model->get($group_id);
        $this->content['groups'] = $this->client_group_model->get_all();

        $this->json['message'] = 'Group successfully updated.';
        $this->json['content'] = $this->load->view('admin/groups/partials/groups_row', $this->content, TRUE);

        return $this->ajax_response();
    }


    /**
     * Delete a client group
     *
     * @param	int
     * @return	function
     */
    public function delete_group($group_id)
    {
        $this->confirm_valid_group($group_id);

        $delete = $this->client_group_model->delete($group_id);
        $this->client_user_group_model->delete_user_group_by_id($this->user->id, $group_id);

        if ( ! $delete )
        {
            $this->set_flash_message('error', 'An error occurred whilst attempting to delete the group.');
        }
        else
        {
            $this->set_flash_message('success', 'Group successfully deleted.');
        }

        return redirect('admin/groups/' . $this->client->id);
    }


    /**
     * Sort client groups
     *
     * @param	string
     * @return	function
     */
    public function sort_groups($group_ids)
    {
        $this->client_group_model->update_sort_order($this->client->id, $group_ids);

        $this->json['message'] = 'Groups sort-order updated.';

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

        if (!$client) {
            $this->set_flash_message('error', 'This client does not appear to exist.');

            $redirect = 'admin/clients';

            if ($this->input->is_ajax_request()) {
                $this->json['redirect'] = $redirect;
                return $this->ajax_response();
            }

            return redirect($redirect);
        }
    }




}