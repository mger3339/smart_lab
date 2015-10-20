<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_account extends User_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		// load relevant lang files
		$this->lang->load('user_account');
	}


	/**
	 * Default class method - build user account page
	 *
	 * @return	void
	 */
	public function index()
	{
		// load assets
		$this->css_assets[] = 'default/user_account.less';
		$this->js_assets[] = 'default/user_account.js';
        $this->js_assets[] = 'default/jquery.fileupload.js';

        $this->content['update_account_content'] = $this->update_account_content();
		$this->content['update_password_content'] = $this->update_password_content();

		$this->wrap_views[] = $this->load->view('user_account/page/header', FALSE, TRUE);

		$this->wrap_views[] = $this->load->view('user_account/index', $this->content, TRUE);

		$this->render();
	}

    public function image_upload()
    {
        $config['upload_path'] = '_/img/user';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['overwrite'] = true;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('user_image'))
        {
            $arr = $this->upload->display_errors();
        }
        else
        {
            $arr = $this->upload->data();
        }
        $data_file = array(
                        'file_type' => $arr['file_type'],
                        'file_path' => $arr['file_path'],
                        'url_path'  => base_url().'_/img/user/' .$arr['orig_name'],
                        'orig_name' => $arr['orig_name'],
                        'client_name' => $arr['client_name'],
                        'file_ext' => $arr['file_ext'],
                        'file_size' => $arr['file_size'],
                        'is_image' => $arr['is_image'],
                        'created' => date("Y/m/d")
                     );
        $this->load->model('files_model');
        $add_image = $this->files_model->insert($data_file);
        if($add_image)
        {
            $this->json['status'] = 'success';
            $this->json['content'] = $add_image;
            $this->json['image'] = $data_file['url_path'];
        }
        else
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There were errors when uploading file.';
        }
        return $this->ajax_response();
    }

	/**
	 * Update user account details on update form submission
	 *
	 * @return	function
	 */
	public function update_account()
	{
        $user = array();
		$data = $this->input->post();
        if(($data['new_password'] && !$data['confirm_password']) || (!$data['new_password'] && $data['confirm_password']))
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'Missed password when attempting update your password.';
            return $this->ajax_response();
        }
        if(!$data['new_password'] && !$data['confirm_password'])
        {
            unset($data['new_password']);
            unset($data['confirm_password']);
        }
        else
        {
            // set up form validation for password submission
            $this->load->library('form_validation');
            $this->form_validation->set_rules('new_password', 'new password', 'trim|min_length[8]|valid_password');
            $this->form_validation->set_rules('confirm_password', 're-type password', 'matches[new_password]');

            // return the AJAX response if form validation fails
            if ( ! $this->form_validation->run() )
            {
                $this->json['status'] = 'error';
                $this->json['message'] = 'There were errors when attempting update your password.';

                $this->json['content'] = $this->update_password_content();

                return $this->ajax_response();
            }
            $this->load->library('phpass');

            // if everything is OK we can update the user's password
            $new_password = $data['new_password'];
            $new_password = $this->phpass->hash($new_password);
            $data['password'] = $new_password;
            // if database update is unsuccessful
            unset($data['new_password']);
            unset($data['confirm_password']);
        }
        $user['job_title'] = $data['job_title'];
        $user['department'] = $data['department'];
        $user['biography'] = $data['biography'];
        $user_info_id = $data['user_info_id'];
        $groups = $data['groups'];
        $user['user_id'] = $this->user->id;
        unset($data['job_title']);
        unset($data['department']);
        unset($data['hidden_expertise']);
        unset($data['hidden_interests']);
        unset($data['biography']);
        unset($data['expertise']);
        unset($data['interests']);
        unset($data['groups']);
        unset($data['user_info_id']);
        print_r($data);die;
		$success = $this->user_model->safe_update($this->user->id, $data);
        $this->load->model('client_info_model');
        if(!$user_info_id)
        {
            $user_info = $this->client_info_model->insert($user);
        }
        else
        {
            $user_info = $this->client_info_model->update($user_info_id,$user);
        }
		if ( $success )
		{
			$this->json['message'] = 'Your account details have been successfully updated.';
		}
		else
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting update your account.';
		}
        if ( $user_info )
        {
            $this->json['message'] = 'Your account details have been successfully updated.';
        }
        else
        {
            $this->json['status'] = 'error';
            $this->json['message'] = 'There were errors when attempting update your account.';
        }

		$this->json['content'] = $this->update_account_content();

		return $this->ajax_response();
	}


	/**
	 * Build update user account form
	 *
	 * @return	view
	 */
	private function update_account_content()
	{
		$content = array();
		$content['user'] = $this->client->admin_user;

        $user_id = $this->client->id;
        $this->load->model('client_info_model');
        $content['user_info'] = $this->client_info_model->get_user_info($user_id);

        $this->load->model('files_model');
        $id = $this->user->avatar_file_id;
        $content['user_image'] = $this->files_model->get_user_image($id);

        $this->load->model('country_model');
		$content['country_options'] = $this->country_model->get_country_options();

		$this->load->model('time_zone_model');
		$content['time_zone_options'] = $this->time_zone_model->get_time_zone_options();

		$this->load->model('currency_model');
		$content['currency_options'] = $this->currency_model->get_currency_options();

        $this->load->model('client_expertise_model');
        $content['expertise_options'] = $this->client_expertise_model->get_expertise_options();

        $this->load->model('client_interest_model');
        $content['interest_options'] = $this->client_interest_model->get_interests_options();

		return $this->load->view('user_account/partials/user_account_edit', $content, TRUE);
	}


	/**
	 * Update user password on update password form submission
	 *
	 * @return	function
	 */
	public function update_password()
	{
		// set up form validation for password submission
		$this->load->library('form_validation');
		$this->form_validation->set_rules('new_password', 'new password', 'trim|min_length[8]|valid_password');
		$this->form_validation->set_rules('confirm_password', 're-type password', 'matches[new_password]');

		// return the AJAX response if form validation fails
		if ( ! $this->form_validation->run() )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'There were errors when attempting update your password.';

			$this->json['content'] = $this->update_password_content();

			return $this->ajax_response();
		}
		$this->load->library('phpass');

		// if everything is OK we can update the user's password
		$new_password = $this->input->post('new_password');
		$new_password = $this->phpass->hash($new_password);

		$update_password = $this->user_model->update($this->user->id, array(
			'password'		=> $new_password,
		), TRUE);

		// if database update is unsuccessful
		// return the AJAX response with an error message
		if ( ! $update_password )
		{
			$this->json['status'] = 'error';
			$this->json['message'] = 'An error occurred whilst attempting to update your password. Please try again.';

			$this->json['content'] = $this->update_password_content();

			return $this->ajax_response();
		}
        else
        {
            $this->json['status'] = 'success';
            $this->json['message'] = 'Your password has been successfully updated.';
            $this->json['content'] = $this->update_password_content();
        }




		return $this->ajax_response();
	}


	/**
	 * Build update user password form
	 *
	 * @return	view
	 */
	private function update_password_content()
	{
		return $this->load->view('user_account/partials/user_password_edit', FALSE, TRUE);
	}

    public function add_expertise()
    {
        $client_id = $this->client->id;
        $expertise = $this->input->get('expertise');
        $data_client = array(  'expertise' => $expertise,
                        'client_id' => $client_id
                      );
        $data = array(  'expertise' => $expertise,);
        $this->load->model('client_expertise_model');
        $this->load->model('expertise_model');
        $aaa = $this->expertise_model->get_by($data);
        $aaa_client = $this->client_expertise_model->get_by($data);
        if($aaa && !$aaa_client)
        {
            $add_expertise_client = $this->client_expertise_model->insert($data_client);
            $data['id'] = $aaa->id;
            if($add_expertise_client)
            {
                $this->json['status'] = 'success';
            }
            else
            {
                $this->json['status'] = 'error';
            }
        }
        if($aaa && $aaa_client)
        {
            $this->json['message'] = 'Expertise exist in database';
            $this->json['status'] = 'error';
        }
        if(!$aaa && !$aaa_client)
        {
            $add_expertise = $this->expertise_model->insert($data);
            $add_expertise_client = $this->client_expertise_model->insert($data_client);
            $data['id'] = $add_expertise;
            if($add_expertise && $add_expertise_client)
            {
                $this->json['status'] = 'success';
            }
            else
            {
                $this->json['status'] = 'error';
            }
        }
        $this->json['expertise'] = $data;
        return $this->ajax_response();
    }

    public function delete_expertise()
    {
        $expertise_id = $this->input->get('expertise_id');
        $this->load->model('client_expertise_model');
        $delete_expertise = $this->client_expertise_model->delete($expertise_id);
        if($delete_expertise)
        {
            $this->json['status'] = 'success';
        }
        else
        {
            $this->json['status'] = 'error';
        }
    }

    public function add_interests()
    {
        $client_id = $this->client->id;
        $interests = $this->input->get('interests');
        $data_client = array(  'interests' => $interests,
            'client_id' => $client_id
        );
        $data = array(  'interests' => $interests,);
        $this->load->model('client_interest_model');
        $this->load->model('interest_model');
        $aaa = $this->interest_model->get_by($data);
        $aaa_client = $this->client_interest_model->get_by($data);
        if($aaa && !$aaa_client)
        {
            $add_interests_client = $this->client_interest_model->insert($data_client);
            $data['id'] = $aaa->id;
            if ($add_interests_client)
            {
                $this->json['status'] = 'success';
            } else
            {
                $this->json['status'] = 'error';
            }
        }
        if($aaa && $aaa_client)
        {
            $this->json['message'] = 'Interest exist in database';
            $this->json['status'] = 'error';
        }
        if(!$aaa && !$aaa_client)
        {
            $add_interests = $this->interest_model->insert($data);
            $add_interests_client = $this->client_interest_model->insert($data_client);
            $data['id'] = $add_interests;
            if($add_interests && $add_interests_client)
            {
                $this->json['status'] = 'success';
            }
            else
            {
                $this->json['status'] = 'error';
            }
        }
        $this->json['interests'] = $data;
        return $this->ajax_response();
    }

    public function delete_interests()
    {
        $interests_id = $this->input->get('interests_id');
        $this->load->model('client_interest_model');
        $delete_interests = $this->client_interest_model->delete($interests_id);
        if($delete_interests)
        {
            $this->json['status'] = 'success';
        }
        else
        {
            $this->json['status'] = 'error';
        }
    }

    public function autocomplete_interests()
    {
        $text = trim($this->input->get('text'));
        $this->load->model('interest_model');
        $result = $this->interest_model->get_interests($text);
        if($result)
        {
            $this->json['status'] = 'success';
        }
        else
        {
            $this->json['status'] = 'error';
        }
        $this->json['result'] = $result;
        return $this->ajax_response();
    }

    public function autocomplete_expertise()
    {
        $text = trim($this->input->get('text'));
        $this->load->model('expertise_model');
        $result = $this->expertise_model->get_expertise($text);
        if($result)
        {
            $this->json['status'] = 'success';
        }
        else
        {
            $this->json['status'] = 'error';
        }
        $this->json['result'] = $result;
        return $this->ajax_response();
    }
}