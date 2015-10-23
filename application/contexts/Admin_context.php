<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_context extends User_context {


	/**
	 * The client admin areas and current area
	 *
	 */
	public $admin_areas;
	public $area = NULL;


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// verify that the user is admin
		if ( $this->user->admin != 1 )
		{
			redirect();
		}
		
		// load lang files
		$this->lang->load('admin');
		
		// set the client admin areas and current area
		$this->load->model('admin_area_model');
		$this->admin_areas = $this->admin_area_model->get_all();
		if ( is_null($this->area) )
		{
			$this->area = $this->uri->segment(2);
		}
		
		// set the page title
		$this->page_title[] = lang('admin_title');
				
		// add page render observers to the class callback lists
		$this->before_render[] = 'build_environemnt';
		$this->before_render[] = 'build_notifications_nav';
	}


	/**
	 * Build client admin environment
	 *
	 * @return	void
	 */
	protected function build_environemnt()
	{
		// add the global client admin assets
		$this->css_assets[] = 'admin/admin.less';
		$this->js_assets[] = 'admin/ajax_form.js';
		
		// include the date picker UI assets
		$this->css_assets[] = 'third_party/glDatePicker.smartlab.less';
		$this->js_assets[] = 'third_party/glDatePicker.min.js';
		$this->js_assets[] = 'default/date_picker.js';
		$this->css_assets[] = 'default/date_picker_touch.less';
		$this->js_assets[] = 'default/date_picker_touch.js';
		
		// include the sortable UI assets
		$this->js_assets[] = 'default/sortable.js';
		
		// set the environment vars
		$this->content['admin_areas'] = $this->admin_areas;
		$this->content['current_area'] = $this->area;
		
		// build the client admin navigation
		$navigation_view =  $this->load->view('admin/page/navigation', $this->content, TRUE);
		$header_content = array();
		$header_content['navigation_view'] = $navigation_view;
		
		// add the client admin header to the top of the wrap_views container
		array_unshift($this->wrap_views, $this->load->view('admin/page/header', $header_content, TRUE));
		
		// add the page footer
		$this->footer_views[] = $this->load->view('admin/page/footer', FALSE, TRUE);
	}


	/**
	 * Build the notifications navigation item
	 *
	 * @return	void
	 */
	protected function build_notifications_nav()
	{
		$this->lang->load('admin_notifications_nav');

		$this->add_system_nav_item('notifications', lang('admin_notifications_nav_title'), '', base_url('admin/notifications'));
	}

}