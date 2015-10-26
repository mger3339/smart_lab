<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Admin_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		// load lang files
		$this->lang->load('admin_users');

		// set the page title
		$this->page_title[] = lang('admin_users_title');
	}


	/**
	 * Default class method - lists users
	 *
	 */
	public function index()
	{
        if(isset($_GET["per_page"])){
            $per_page = $_GET["per_page"];
        }else{
            $per_page = "25";
        }


        if(!empty($_GET["page"])){
            $show = $_GET["page"] * $per_page - $per_page;
        }elseif(!empty($this->uri->segment(4))){
            $show = $this->uri->segment(4) * $per_page - $per_page;
        }else{
            $show = "0";
        }
		// get the users
        $users = $this->user_model->get_users($show,$per_page);
        $total_users = $this->user_model->count_by('id !=', '1');
        $this->load->library('pagination');
        $url = $_SERVER["QUERY_STRING"];
        $new_url = preg_replace('/&page=[^&]*/', '', $url);

        $config['base_url'] = base_url("admin/users?") . $new_url;

        $config['total_rows'] = $total_users;
        $config['per_page'] = $per_page;
        $config['cur_tag_open'] = '<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config["uri_segment"] = 2;
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $this->pagination->initialize($config);


        // add the required JS assets
		$this->js_assets[] = 'admin/data_rows.js';

        $all_roles = $this->user_role_model->get_roles_select_options();

        $this->load->model('country_model');
        $this->load->model('time_zone_model');

        $country_options = $this->country_model->get_country_options();
        $time_zone_options = $this->time_zone_model->get_time_zone_options();

        $this->content['users'] = $users;
        $this->content['total_users'] = $total_users;
        $this->content['all_roles'] = $all_roles;
        $this->content['country_options'] = $country_options;
        $this->content['time_zone_options'] = $time_zone_options;
        $this->content['pagination'] = $this->pagination->create_links();
        $this->content['per_page_option'] = array(
            "25" => "25",
            "50" => "50",
            "75" => "75"
        );

        if(isset($_GET["per_page"])){
            $this->content['per_page'] = $_GET["per_page"];
        }else{
            $this->content['per_page'] = "";
        }
        $this->wrap_views[] = $this->load->view('admin/users/index', $this->content, TRUE);
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
     * Build set/reset user form
     *
     * @param	int
     * @return	function
     */
//    public function set_reset_username_pass($user_id)
//    {
//        return $this->set_reset_op($user_id);
//    }


    /**
	 * Build add/edit user form
	 *
	 * @param	string
	 * @param	int
	 * @return	function
	 */
	public function user_op($action = 'add', $user_id = NULL)
	{
        $this->load->model('client_user_group_model');

        if ($action === 'add' || $action === 'import')
		{
			$user = $this->user_model->create_prototype();
            $group = $this->client_group_model->create_prototype($this->client->id);
			// set the default user locale properties
			$user->country_iso	= $this->client->country_iso;
			$user->time_zone	= $this->client->time_zone;
			$user->currency		= $this->client->currency;
            if($action === 'add'){
                $this->content['action_title'] = lang('admin_add_user_action_title');
            }else{
                $this->content['action_title'] = lang('admin_import_user_action_title');
            }
		}
		else // edit
		{
			$user = $this->user_model->set_active_only(FALSE)->get(intval($user_id));

            $user->group_id = $this->client_user_group_model->get_groups('user_id',$user_id);

			$this->content['action_title'] = sprintf(lang('admin_edit_user_action_title'), $user->fullname);
		}
		$this->content['action'] = $action;
		$this->content['row'] = $user;

		$this->load->model('user_role_model');
        $this->content['user_role_options'] = $this->user_role_model->get_roles_select_options();
        $groups = $this->client_group_model->get_client_group($this->client->id);
        foreach($groups as $group) {
            foreach($group as $key => $val) {
                if($key == "name"){
                    $groups_arr[$group->id] = $val;
                }
            }
        }
        $this->content['user_groups_options'] = $groups_arr;

        if($action === 'import') {
            $this->content['group'] = $group;
            $this->json['content'] = $this->load->view('admin/users/partials/import_users_row', $this->content, TRUE);

        } else {
            $this->json['content'] = $this->load->view('admin/users/partials/user_add_edit_row', $this->content, TRUE);
        }

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
        unset($data['ids']);
        // set up form validation for password submission
        $this->load->library('form_validation');
        $this->form_validation->set_rules('new_password', 'new password', 'required|trim|min_length[8]|valid_password');
        $this->form_validation->set_rules('new_password_confirm', 're-type password', 'required|matches[new_password]');

        if ( ! $this->form_validation->run() )
        {
            $this->json['status'] = 'error';
            $this->json['message'] = lang('admin_update_user_error_message');
            return $this->add_user();
        }

        $this->load->library('phpass');

        // if everything is OK we can update the user's password
        $password = $data['new_password'];
        $password = $this->phpass->hash($password);

        $data['password'] = $password;

		// attempt to insert the new user
        if(isset($data['group_id'])){
            $group_ids = $data['group_id'];
            unset($data['group_id']);
        }
        unset($data['new_password']);
        unset($data['new_password_confirm']);

		$insert_id = $this->user_model->insert($data);

        if(!empty($group_ids) && $insert_id ){
            $this->load->model('client_user_group_model');
            $group_data['user_id'] =$insert_id;
            foreach($group_ids as $group_id){
                $group_data['group_id'] = $group_id;
                $insert_group_id[] = $this->client_user_group_model->insert($group_data);
            }
        }
		if ( ! $insert_id)
		{
			$success = FALSE;
		}

		if ( ! $success)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = lang('admin_add_user_error_message');
			return $this->add_user();
		}

		$this->set_flash_message('success', lang('admin_add_user_success_message'));

		$this->json['redirect'] = 'admin/users';

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

        // set up form validation for password submission
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim');
        $this->form_validation->set_rules('new_password', 'new password', 'trim|min_length[8]|valid_password');
        $this->form_validation->set_rules('new_password_confirm', 're-type password', 'matches[new_password]');

        // return the AJAX response if form validation fails
        if ( empty($data['password']) && ( !empty($data['new_password']) || !empty($data['new_password_confirm']) ))
        {
            $this->json['status'] = 'error';
            $this->json['message'] = lang('admin_update_user_error_message');
            return $this->edit_user($user_id);
        }

        if(!empty($data['password'])){

            if ( ! $this->form_validation->run() )
            {
                $this->json['status'] = 'error';
                $this->json['message'] = lang('admin_update_user_error_message');
                return $this->edit_user($user_id);
            }
            // check password the current password
            // and return the AJAX response if current password is invalid
            $user_password = $this->user_model->get_user_password($user_id);

            $this->load->library('phpass');

            if ( ! $this->phpass->check($this->input->post('password'), $user_password) )
            {
                $this->json['status'] = 'error';
                $this->json['message'] = lang('admin_update_user_error_message');
                return $this->edit_user($user_id);
            }

            // if everything is OK we can update the user's password
            $new_password = $data['new_password'];
            $new_password = $this->phpass->hash($new_password);

            $data['password'] = $new_password;
        }

        $this->load->model('client_user_group_model');
        $group_data['user_id'] = $user_id;

        $user_groups = $this->client_user_group_model->get_many_by('user_id',$user_id);
        $deleted_groups = $this->client_user_group_model->delete_user_groups($user_groups);

        if(!in_array(0,$deleted_groups)){
            $delete = true;
        }
        if($delete && isset($data['group_id'])){
                foreach($data['group_id'] as $group_id){
                    $group_data['group_id'] = $group_id;
                    $insert_group_id[] = $this->client_user_group_model->insert($group_data);
                }
        }

        // attempt to update the user
        unset($data['group_id']);
        unset($data['new_password']);
        unset($data['new_password_confirm']);

		$update = $this->user_model->update($user_id, $data);

		if ( ! $update)
		{
			$success = FALSE;
		}

		if ( ! $success)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = lang('admin_update_user_error_message');
			return $this->edit_user($user_id);
		}

		$this->content['row'] = $this->user_model->set_active_only(FALSE)->get($user_id);

        $groups_id = explode(",",$this->content['row']->group_id);
        $groups = $this->client_group_model->get_groups_by_id($groups_id);

        foreach($groups as $group) {
            foreach($group as $key => $value) {
                if($key == "name") {
                    $groups_arr[$group["id"]] = $value;
                }
            }
        }
        $this->content['row']->user_groups_options = (isset($groups_arr)) ? $groups_arr : [];


		$this->json['message'] = lang('admin_update_user_success_message');
		$this->json['content'] = $this->load->view('admin/users/partials/user_row', $this->content, TRUE);

		return $this->ajax_response();
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

		if ( ! $delete )
		{
			$this->set_flash_message('error', lang('admin_delete_user_error_message'));
		}
		else
		{
			$this->set_flash_message('success', lang('admin_delete_user_success_message'));
		}

		return redirect('admin/users');
	}

    /**
     * Search users on search form submission
     *
     * @return	function
     */
    public function search_users()
    {
        if(isset($_GET["per_page"])){
            $per_page = $_GET["per_page"];
        }else{
            $per_page = "25";
        }

        if(!empty($_GET["page"])){
            $show = $_GET["page"] * $per_page - $per_page;
        }elseif(!empty($this->uri->segment(4))){
            $show = $this->uri->segment(4) * $per_page - $per_page;
        }else{
            $show = "0";
        }
        $data = $this->input->get();
        $total_users = count($this->user_model->search_users($data));

        $searched_users = $this->user_model->search_users($data,$show,$per_page);
        $this->content['total_users'] = '';
        $this->content['all_roles'] = '';

        $users = $searched_users;

        $this->load->model('country_model');
        $this->load->model('time_zone_model');


        $all_roles = $this->user_role_model->get_roles_select_options();
        $country_options = $this->country_model->get_country_options();
        $time_zone_options = $this->time_zone_model->get_time_zone_options();


        $this->load->library('pagination');
        $url = $_SERVER["QUERY_STRING"];
        $new_url = preg_replace('/&page=[^&]*/', '', $url);

        $config['base_url'] = base_url("admin/users/search?") . $new_url;

        $config['total_rows'] = $total_users;
        $config['per_page'] = $per_page;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['cur_tag_open'] = '<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['use_page_numbers'] = TRUE;
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $this->pagination->initialize($config);


        $this->content['users'] = $users;
        $this->content['total_users'] = $total_users;
        $this->content['all_roles'] = $all_roles;
        $this->content['country_options'] = $country_options;
        $this->content['time_zone_options'] = $time_zone_options;

        $this->content['users'] = $users;
        $this->content['date'] = $data['commence'];
        $this->content['total_users'] = $total_users;
        $this->content['all_roles'] = $all_roles;
        $this->content['country_options'] = $country_options;
        $this->content['time_zone_options'] = $time_zone_options;
        $this->content['pagination'] = $this->pagination->create_links();
        $this->content['per_page_option'] = array(
            "25" => "25",
            "50" => "50",
            "75" => "75"
        );


        // add the required JS assets
        $this->js_assets[] = 'admin/data_rows.js';
        if(isset($_GET["per_page"])){
            $this->content['per_page'] = $_GET["per_page"];
        }else{
            $this->content['per_page'] = "";
        }
        $this->wrap_views[] = $this->load->view('admin/users/index', $this->content, TRUE);
        $this->render();
    }

    public function import() {

        return $this->user_op('import');
    }

    public function import_users($import  = '')
    {
        $users = $this->user_model->get_all();
        $email = array();
        foreach($users as $users){
            $email[] = $users->email;
        }
        $config['upload_path'] = '_/files/';
        $config['allowed_types'] = 'csv|xls';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $success = TRUE;
        if (!$this->upload->do_upload('userfile')){
            $userfile_data = array('msg' => $this->upload->display_errors());

        } else {
            $userfile_data['msg'] = "success";
            $userfile_data['upload_data'] = $this->upload->data();
            $file_ext = $userfile_data['upload_data']["file_ext"];
            $userfile = array(
                            'file_type' => $userfile_data['upload_data']['file_type'],
                            'file_path' => $userfile_data['upload_data']['file_path'],
                            'url_path'  => base_url().'_/files/' .$userfile_data['upload_data']['orig_name'],
                            'orig_name' => $userfile_data['upload_data']['orig_name'],
                            'client_name' => $userfile_data['upload_data']['client_name'],
                            'file_ext' => $userfile_data['upload_data']['file_ext'],
                            'file_size' => $userfile_data['upload_data']['file_size'],
                            'is_image' => $userfile_data['upload_data']['is_image'],
                            'created' => date("Y/m/d")
                        );
        }

        if($userfile_data['msg'] === 'success') {
            if($file_ext == ".xls") {
                $file = $userfile_data["upload_data"]["full_path"];

                $this->load->library('excel');

                $objPHPExcel = PHPExcel_IOFactory::load($file);
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $groups_id = $this->input->post('group_id');

                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

                    if ($row == 1) {
                        $header[$row][$column] = $data_value;
                    } else {
                        $arr_data[$row][$header[1][$column]] = $data_value;
                        $arr_data[$row]['group_id'] = $groups_id;
                    }
                }

                $data = $arr_data;

            }else{
                $this->load->model('files_model');
                $add_userfile = $this->files_model->insert($userfile);

                $handle = fopen($userfile_data['upload_data']['full_path'], "r");
                $data = fgetcsv($handle, 1000, "\n");
                $result = array();
                do {
                    if ($data[0]) {
                        $result[] = $data[0];
                    }
                } while ($data = fgetcsv($handle, 1000, "\n", "'"));

                $first_count = count($result);
                $columns = explode(',', $result[0]);
                unset($result[0]);

                $groups_id = $this->input->post('group_id');
                $currency = $this->input->post('currency');

                $data = array();
                foreach ($result as $key => $value) {
                    $value = explode(',', $value);
                    for ($i = 0; $i < count($columns); $i++) {
                        $data[$key - 1][$columns[$i]] = $value[$i];
                    }
                    $data[$key - 1]['client_id'] = 1;
                    $data[$key - 1]['currency'] = $currency;
                    $data[$key - 1]['group_id'] = $groups_id;
                    $data[$key - 1]['country_iso'] = $this->client->admin_user->country_iso;
                    $data[$key - 1]['time_zone'] = $this->client->admin_user->time_zone;
                }
                foreach($data as $k => $item){
                    $data[$key - 1]['username'] = $item['email'];
                    if(in_array($item['email'], $email))
                    {
                        unset($data[$k]);
                    }
                    if(empty($item['lastname']) || empty($item['firstname']) || empty($item['email']))
                    {
                        unset($data[$k]);
                    }
                }
            }

            $groups_ids = array();
            foreach($data as $key => $val) {
                $groups_ids[] = $val['group_id'];
                unset($data[$key]['group_id']);
            }
            $insert_ids = array();
            foreach ($data as $val) {
                $insert_ids[] = $this->user_model->insert($val);
            }
            $count = count($insert_ids);
            $unsaccess_count = $first_count - $count;
            $deleted = array();
            if(in_array(false, $insert_ids)){
                foreach($insert_ids as $insert_id){
                    $deleted[] = $this->user_model->delete($insert_id);
                }
                $success = FALSE;
            }

            if($success && !empty(current($groups_ids))){
                foreach($insert_ids as $key => $insert_id){
                    $this->load->model('client_user_group_model');
                    $group_data['user_id'] =$insert_id;
                    foreach($groups_ids[$key] as $group_id){
                        $group_data['group_id'] = $group_id;
                        $insert_group_id[] = $this->client_user_group_model->insert($group_data);
                    }
                }
            }

            if($success) {
                $this->set_flash_message('success', "$count ".lang('admin_import_user_success_message').": $unsaccess_count users unsuccessfully");
            }else {
                $this->set_flash_message('error', lang('admin_import_user_error_message'));
            }

            $this->json['redirect'] = 'admin/users';

            return $this->ajax_response();
        }

        $this->set_flash_message('error', lang('admin_import_user_error_message'));

        return redirect('admin/users/import');
//
    }

    public function edit_user_groups($user_ids = [])
    {
        if($this->input->post()){
            $this->content['user_ids'] = $this->input->post('user_ids');
        } elseif($this->input->get('user_ids')) {
            $data = $this->input->get('user_ids');
            $this->content['user_ids'] = implode(',',$data);
        }

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

        $this->json['content'] = $this->load->view('admin/users/partials/user_groups_row', $this->content, TRUE);

        return $this->ajax_response();
    }

    public function edit_user_role()
    {
        $data = $this->input->get('user_ids');
        $this->load->model('user_role_model');
        $all_roles = $this->user_role_model->get_roles_select_options();
        $this->content['all_roles'] = $all_roles;
        $this->content['user_ids'] = implode(',',$data);
        $this->load->model('user_model');
        $this->content['users'] = $this->user_model->get_users_by_id($data);
        $this->json['users'] = $this->load->view('admin/users/partials/user_row_by_id', $this->content, TRUE);
        $this->json['content'] = $this->load->view('admin/users/partials/user_role_row', $this->content, TRUE);

        return $this->ajax_response();
    }

    public function update_user_role()
    {
        $success = TRUE;
        $data = $this->input->post();
        if(strlen($data['user_ids']) == 1){
            $user_ids = array('0'=>$data['user_ids']);
        }else{
            $user_ids = explode(',',$data['user_ids']);
        }
        unset($data['user_ids']);
        $update = array();

        foreach($user_ids as $value)
        {
            $update[] = $this->user_model->update_user_role($value, $data,false);

        }

        if (in_array(0,$update))
        {
            $success = FALSE;
        }

        if ( ! $success)
        {
            $this->json['status'] = 'error';
            $this->json['message'] = lang('admin_update_user_error_message');
            return $this->edit_user_role();
        }

        $this->set_flash_message('success', lang('admin_update_user_role_success_message'));

        $this->json['redirect'] = 'admin/users';

        return $this->ajax_response();
    }

    public function update_user_groups(){
        $data = $this->input->post();

        $this->load->model('client_user_group_model');

        $result = array();
        $success = true;
        if($data['action'] === 'add') {
            $user_ids = explode(',',$data['user_ids']);
            $insert_group_id = array();
            if(isset($data['group_id']) && !empty($data['group_id'])) {
                foreach($user_ids as $user_id) {
                    $group_data = array();
                    $group_data['user_id'] = $user_id;
                    foreach($data['group_id'] as $group_id) {
                        $group_data['group_id'] = $group_id;
                        $insert_group_id[] = $this->client_user_group_model->insert($group_data);
                    }
                }

            }
            $result = $insert_group_id;
        } elseif($data['action'] === 'overwrite') {

            $user_ids = explode(',',$data['user_ids']);
            if(isset($data['group_id']) && !empty($data['group_id'])) {
                $insert_group_id = array();
                foreach($user_ids as $user_id) {
                    $user_groups = $this->client_user_group_model->get_many_by('user_id',$user_id);
                    $deleted_groups = $this->client_user_group_model->delete_user_groups($user_groups);

                    if(!in_array(0,$deleted_groups)){
                        $delete = true;
                    }

                    $group_data = array();
                    $group_data['user_id'] = $user_id;

                    if($delete){
                        foreach($data['group_id'] as $group_id){
                            $group_data['group_id'] = $group_id;
                            $insert_group_id[] = $this->client_user_group_model->insert($group_data);
                        }
                    }

                }

            }
           $result = $insert_group_id;
        } elseif($data['action'] === 'remove') {
            $user_ids = explode(',',$data['user_ids']);
            if(isset($data['group_id']) && !empty($data['group_id'])) {
                $deleted_groups = array();
                foreach($user_ids as $user_id) {
                    foreach($data['group_id'] as $group_id) {
                        $user_groups = $this->client_user_group_model->get_many_by(['user_id'=>$user_id,'group_id' => $group_id]);
                        if($user_groups){
                            $deleted_groups[$user_id][] = $this->client_user_group_model->delete_user_groups($user_groups);
                        }
                    }

                }
                $result = $deleted_groups;
            }
        }

        if(!$result || in_array(0,$result)) {
            $success = FALSE;
        }

        if ( ! $success)
        {
            $this->json['status'] = 'error';
            $this->json['message'] = lang('admin_update_user_group_error_message');
            return $this->edit_user_groups($data['user_ids']);
        }

        $this->set_flash_message('success', lang('admin_update_user_group_success_message'));

        $this->json['redirect'] = 'admin/users';

        return $this->ajax_response();

    }


}