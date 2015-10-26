<?php
/**
 * Created by PhpStorm.
 * group: Suren Aghayan
 * Date: 16.07.2015
 * Time: 17:11
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expertise extends Admin_context {


    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('expertise_model');
        $this->load->model('client_expertise_model');

    }


    /**
     * Default class method - lists groups
     *
     */
    public function index()
    {
        // get the groups
        $expertise = $this->expertise_model
            ->order_by('expertise', 'ASC')
            ->get_all();


        // add the required JS assets
        $this->js_assets[] = 'admin/data_rows.js';

        $this->content['expertise'] = $expertise;

        $this->wrap_views[] = $this->load->view('admin/expertise/index', $this->content, TRUE);
        $this->render();
    }


    /**
     * Build add client group form
     *
     * @return	function
     */
    public function add_expertise()
    {
        return $this->expertise_op('add', $this->client->id);
    }

    public function add_merge()
    {
        return $this->merge_op('add', $this->client->id);
    }


    /**
     * Build add/edit client group form
     *
     * @param	string
     * @param	int
     * @return	function
     */
    public function expertise_op($action = 'add', $expertise_id = NULL)
    {
        if ($action === 'add')
        {
            $expertise = $this->expertise_model->create_prototype($this->client->id);

            $this->content['action_title'] = 'Add a new expertise';
        }
        else // edit
        {
            $expertise = $this->expertise_model->get($expertise_id);

            $this->content['action_title'] = "Edit <span>{$expertise->expertise}</span>";
        }
        $this->content['action'] = $action;
        $this->content['row'] = $expertise;


        $this->json['content'] = $this->load->view('admin/expertise/partials/expertise_add_edit_row', $this->content, TRUE);

        return $this->ajax_response();
    }

    public function merge_op($action = 'add', $expertise_id = NULL)
    {
        if ($action === 'add')
        {
            $expertise = $this->expertise_model->create_prototype($this->client->id);

            $this->content['action_title'] = 'Merge expertises';
        }
        else // edit
        {
            $expertise = $this->expertise_model->get($expertise_id);

            $this->content['action_title'] = "Edit <span>{$expertise->expertise}</span>";
        }
        $this->content['action'] = $action;
        $this->content['row'] = $expertise;


        $this->json['content'] = $this->load->view('admin/expertise/partials/expertise_merge_add_edit_row', $this->content, TRUE);

        return $this->ajax_response();
    }


    /**
     * Insert a new client group on add form submission
     *
     * @return	function
     */
    public function put_expertise()
    {
        $data = $this->input->post();
        unset($data['ids']);
        $data_expertise = array('expertise' => $data['name']);
        $insert = $this->expertise_model->insert($data_expertise);

        if ( ! $insert )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to add the group.';
            return $this->add_expertise($this->client->id);
        }

        $this->set_flash_message('success', 'Expertise successfully added.');

        $this->json['redirect'] = 'admin/expertise/' . $this->client->id;

        return $this->ajax_response();
    }

    public function put_merge()
    {
        $data = $this->input->post();
        $expertise_ids = explode(",", $data['ids']);
        $data_expertise = array('expertise' => $data['name']);
        $bbb = $this->client_expertise_model->get_expertises_by_id($expertise_ids);
        $user_id = array();
        foreach($bbb as $item){
            $user_id[] = $item->user_id;
        }
        $insert = $this->expertise_model->insert($data_expertise);
        foreach($user_id as $user_id)
        {
            $data_client_expertise = array('expertise_id' => $insert, 'user_id' => $user_id);
            $insert_client = $this->client_expertise_model->insert($data_client_expertise);
        }
        $delete_id = $this->client_expertise_model->delete_expertises($expertise_ids);
//        echo "<pre>";
//        print_r($delete_id); die;
        $this->expertise_model->delete_expertises($expertise_ids);

        if ( ! $insert || !$insert_client )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to merge expertises.';
            return $this->add_merge($this->client->id);
        }

        $this->set_flash_message('success', 'Expertises successfully merged.');

        $this->json['redirect'] = 'admin/expertise/' . $this->client->id;

        return $this->ajax_response();
    }

    public function add_new_expertise()
    {
        $data = $this->input->post();
        $data_expertise = array('expertise' => $data['name']);
        $insert = $this->expertise_model->insert($data_expertise);

        $this->content['row_id'] = 'row_id';
        $this->json['insert'] = $insert;
        $this->json['content'] = $this->load->view('admin/expertise/partials/new_expertise_row', $this->content, TRUE);
        $this->json['token'] = array('name' => $this->security->get_csrf_token_name(), 'value' => $this->security->get_csrf_hash());


        return $this->ajax_response();
    }


    /**
     * Build add/edit client group form
     *
     * @param	int
     * @return	function
     */
    public function edit_expertise( $expertise_id)
    {
        $this->confirm_valid_expertise($expertise_id);

        return $this->expertise_op('edit',$expertise_id);
    }


    /**
     * Check that group is valid (exists)
     *
     * @param	int
     * @return	function or void
     */
    protected function confirm_valid_expertise($expertise_id)
    {
        $expertise = $this->expertise_model->get_by(array(
                                                        'id' => $expertise_id,
                                                    ));

        if ( ! $expertise )
        {
            $this->set_flash_message('error', 'This expertise does not appear to exist.');

            $redirect = 'admin/expertise/' . $this->client->id;

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
    public function update_expertise($expertise_id)
    {
        $this->confirm_valid_expertise($expertise_id);
        $data = $this->input->post();
        $data_expertise = array('expertise' => $data['name']);
        $update = $this->expertise_model->update($expertise_id, $data_expertise);

        if ( ! $update )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There errors when attempting to update the expertise.';
            return $this->edit_group($expertise_id);
        }

        $this->content['row'] = $this->expertise_model->get($expertise_id);
        $this->content['groups'] = $this->expertise_model->get_all();

        $this->json['message'] = 'Expertise successfully updated.';
        $this->json['content'] = $this->load->view('admin/expertise/partials/expertise_row', $this->content, TRUE);

        return $this->ajax_response();
    }


    /**
     * Delete a client group
     *
     * @param	int
     * @return	function
     */
    public function delete_expertise($expertise_id)
    {
        $this->confirm_valid_expertise($expertise_id);

        $delete = $this->expertise_model->delete($expertise_id);

        if ( ! $delete )
        {
            $this->set_flash_message('error', 'An error occurred whilst attempting to delete the expertise.');
        }
        else
        {
            $this->set_flash_message('success', 'Expertise successfully deleted.');
        }

        return redirect('admin/expertise/' . $this->client->id);
    }

}