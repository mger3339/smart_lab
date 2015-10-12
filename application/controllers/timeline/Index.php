<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Timeline_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method
	 *
	 */
	public function index()
	{
		// define the calendar scale
		switch ($this->application_settings->calendar_division)
		{
			case 'years':
			$calendar_scale = 2;
			break;
			
			default:
			$calendar_scale = 5;
			break;
		}
		
		$this->content['timeline_settings'] = $this->application_settings;
		$this->content['workstreams'] = $this->workstream_model->get_workstreams();
		$this->content['calendar_scale'] = $calendar_scale;
		
		// add the required page assets
		$this->css_assets[] = 'icons/timeline/flaticon.css';
		$this->css_assets[] = 'default/date_picker_touch.less';
		$this->css_assets[] = 'timeline/timeline.less';
		
		$this->js_assets[] = 'timeline/timeline_data.js';
		$this->js_assets[] = 'timeline/timeline_layout.js';
		$this->js_assets[] = 'timeline/timeline_milestone_crud.js';
		$this->js_assets[] = 'timeline/timeline_navigation.js';
		
		$this->js_assets[] = 'third_party/jquery.mobile-events.min.js';
		$this->js_assets[] = 'third_party/jquery.elastic.source.js';
		$this->js_assets[] = 'third_party/jquery.scrollTo.min.js';
		$this->js_assets[] = 'third_party/jquery.ui.touch-punch.js';
		$this->js_assets[] = 'third_party/spin.min.js';
		
		$this->js_assets[] = 'default/spinner.js';
		$this->js_assets[] = 'default/date_picker_touch.js';
		
		// build inline js
		$this->js_views[] = $this->load->view('timeline/index/index_js', $this->content, TRUE);
		
		// build the workstreams list & calendar
		$this->content['workstreams_list_head_view']
			= $this->load->view('timeline/workstreams/workstreams_list_head', $this->content, TRUE);
		$this->content['workstreams_list_column_view']
			= $this->load->view('timeline/workstreams/workstreams_list_column', $this->content, TRUE);
		$this->content['workstreams_calendar_view']
			= $this->load->view('timeline/workstreams/workstreams_calendar', $this->content, TRUE);
		
		// build the milestones list & calendar containers
		$this->content['milestones_list_column_view']
			= $this->load->view('timeline/milestones/milestones_list_column', FALSE, TRUE);
		$this->content['milestones_calendar_view']
			= $this->load->view('timeline/milestones/milestones_calendar', FALSE, TRUE);
		
		$this->wrap_views[]
			= $this->load->view('timeline/index/index', $this->content, TRUE);
		
		$this->render();
	}


}