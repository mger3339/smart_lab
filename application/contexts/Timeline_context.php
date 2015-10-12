<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timeline_context extends Application_context {


	/**
	 * Default settings model identifier
	 *
	 */
	public $application_default_settings_model = 'timeline/timeline_setting_model';


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// disable the system profiler for this application
		$this->output->enable_profiler(FALSE);
		
		// load required models and set their aliases
		$this->load->model('timeline/timeline_workstream_model', 'workstream_model');
		$this->load->model('timeline/timeline_milestone_model', 'milestone_model');
		
		// add page render observers to the class callback lists
		$this->before_render[] = 'build_environemnt';
		
		// add any generic page titles
		if ( $this->admin_area )
		{
			$this->page_title[] = lang('application_admin_title');
		}
	}


	/**
	 * Build timeline environment
	 *
	 * @return	void
	 */
	protected function build_environemnt()
	{
		// build the timeline navigation content
		$navigation_view = $this->load->view('timeline/page/navigation', FALSE, TRUE);
		
		// build the timeline admin navigation content
		$admin_navigation_view = FALSE;
		
		if ( $this->user->admin == 1 )
		{
			$admin_navigation_view = $this->load->view('timeline/page/admin_navigation', FALSE, TRUE);
		}
		
		// add the generic application header to the top of the wrap_views container
		$header_content = array();
		$header_content['navigation_view'] = $navigation_view;
		$header_content['admin_navigation_view'] = $admin_navigation_view;
		$header_content['admin_area'] = $this->admin_area;
		
		array_unshift($this->wrap_views, $this->load->view('application/page/header', $header_content, TRUE));
	}


}