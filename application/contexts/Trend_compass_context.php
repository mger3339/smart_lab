<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trend_compass_context extends Application_context {


	/**
	 * Default settings model identifier
	 *
	 */
	public $application_default_settings_model = 'trend_compass/trend_com_setting_model';


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// add page render observers to the class callback lists
		$this->before_render[] = 'build_environemnt';
		
		// load required models and set their aliases
		$this->load->model('trend_compass/trend_com_round_model', 'round_model');
		$this->load->model('trend_compass/trend_com_question_result_model', 'question_result_model');
		$this->load->model('trend_compass/trend_com_filter_model', 'filter_model');
		
		// add any generic page titles
		if ( $this->admin_area )
		{
			$this->page_title[] = lang('application_admin_title');
		}
	}


	/**
	 * Build trend compass environment
	 *
	 * @return	void
	 */
	protected function build_environemnt()
	{
		// build the trend compass navigation content
		$navigation_view = $this->load->view('trend_compass/page/navigation', FALSE, TRUE);
		
		// build the trend compass admin navigation content
		$admin_navigation_view = FALSE;
		
		if ( $this->user->admin == 1 )
		{
			$admin_navigation_view = $this->load->view('trend_compass/page/admin_navigation', FALSE, TRUE);
		}
		
		// add the generic application header to the top of the wrap_views container
		$header_content = array();
		$header_content['navigation_view'] = $navigation_view;
		$header_content['admin_navigation_view'] = $admin_navigation_view;
		$header_content['admin_area'] = $this->admin_area;
		
		array_unshift($this->wrap_views, $this->load->view('application/page/header', $header_content, TRUE));
	}


}