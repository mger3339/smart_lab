<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends Admin_context {

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		// load lang files
		$this->lang->load('admin_notifications');

		// set the page title
		$this->page_title[] = lang('admin_notifications_title');

		$this->load->helper('object');
		$this->load->model('notification_model');
	}


	/**
	 * Default class method - lists notifications
	 *
	 */
	public function index()
	{
		// add the required assets
		$this->js_assets[] = 'admin/data_rows.js';
		$this->js_assets[] = 'admin/notifications.js';
		$this->css_assets[] = 'default/notifications.less';

		$this->content['admin_notifications'] = $this->notification_model
			->with('notification_user')
			->with('notification_group')
			->get_many_by('client_id', $this->client->id);

		$this->wrap_views[] = $this->load->view('admin/notifications/index', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Build notification form
	 *
	 * @param	string
	 * @param	int
	 * @return	function
	 */
	public function add_notification()
	{
		$this->content['action_title'] = lang('admin_add_notification_action_title');

		$this->json['content'] = $this->load->view('admin/notifications/partials/notification_add_row', $this->content, TRUE);
		return $this->ajax_response();
	}


	/**
	 * Insert a new user on add form submission
	 *
	 * @return	function
	 */
	public function put_notification()
	{
		$success = TRUE;
		$data = $this->input->post();

		$insert_id = $this->notification_model->add_notification($data);
		if ( ! $insert_id)
		{
			$success = FALSE;
		}

		if ( ! $success)
		{
			$this->json['status'] = 'error';
			$this->json['message'] = lang('admin_add_notification_error_message');
			return $this->add_notification();
		}

		$this->set_flash_message('success', lang('admin_add_notification_success_message'));

		$this->json['redirect'] = 'admin/notifications';

		return $this->ajax_response();
	}

}