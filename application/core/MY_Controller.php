<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {


	/**
	 * The various callbacks available to the controller and its children.
	 * Allows this and its children to run methods soley in the event of
	 * rendering a page request or soley in the event of an AJAX response.
	 * Each are simple lists of method names (methods will be run on $this).
	 */
	protected $before_render = array();
	protected $before_ajax_response = array();


	/**
	 * Container for content
	 *
	 */
	protected $content = [];


	/**
	 * Flash status and message
	 *
	 */
	protected $status = 'success';
	protected $message = NULL;


	/**
	 * Containers for browser output
	 *
	 */
	protected $page_title			= array();
	protected $system_nav_items		= array();
	protected $pre_wrap_views		= array();
	protected $wrap_classes			= array();
	protected $wrap_views			= array();
	protected $footer_views			= array();
	protected $css_assets			= array();
	protected $js_assets			= array();
	protected $js_views				= array();
	protected $domready_js_views	= array();


	/**
	 * Container for ajax output JSON object
	 *
	 */
	protected $json = [
							'status'	=> 'success',
							'message'	=> '',
							'content'	=> '',
							'redirect'	=> '',
	];


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load the session library
		$this->load->library('session');
		
		// Run the profiler for non ajax and non cli requests
		// on development environments
		if ( ! $this->input->is_ajax_request() && ! is_cli() && ENVIRONMENT === 'development')
		{
			$this->output->enable_profiler(TRUE);
		}
		
		// globally set form validation error delimeters
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	}


	/**
	 * Compile assets and render final output for page requests
	 *
	 * @return	void
	 */
	protected function render()
	{
		// trigger the before_render callbacks
		$this->trigger('before_render');
		
		// set the flash status and message if set
		$flash_message_content = array();
		if ($this->session->flashdata('status'))
		{
			$this->status = $this->session->flashdata('status');
		}
		if ($this->session->flashdata('message'))
		{
			$this->message = $this->session->flashdata('message');
		}
		$flash_message_content['status'] = $this->status;
		$flash_message_content['message'] = $this->message;
		
		$this->content['flash_message_view']
			= $this->load->view('flash_message/flash_message', $flash_message_content, TRUE);
		
		// set the page title
		if (empty($this->page_title))
		{
			$this->page_title[] = APPLICATION_NAME;
		}
		$this->content['page_title'] = implode(' - ', $this->page_title);
		
		// compile the system nav items view
		$this->content['system_nav_items_view'] = FALSE;
		if (!empty($this->system_nav_items))
		{
			$this->css_assets[] = 'default/system_nav.less';
			$this->js_assets[] = 'default/system_nav.js';
			
			$system_nav_content = array();
			$system_nav_content['system_nav_items'] = $this->system_nav_items;
			
			$this->content['system_nav_items_view']
				= $this->load->view('system_nav/system_nav', $system_nav_content, TRUE);
		}
		
		// compile the default css assets
		$default_css_assets = array();
		$this->load->library('user_agent');
		if ($this->agent->browser() == 'Internet Explorer' && $this->agent->version() < 8)
		{
			$default_css_assets[] = 'third_party/normalize.1.1.3.css';
		}
		else
		{
			$default_css_assets[] = 'third_party/normalize.3.0.1.css';
		}
		$default_css_assets[] = 'default/default.less';
		$default_css_assets[] = 'third_party/jquery-ui.min.css';
		
		// compile the final combined css assets to a single file
		$css_assets = array_merge($default_css_assets, $this->css_assets);
		$css_assets = array_unique($css_assets);
		foreach ($css_assets as $css)
		{
			$this->carabiner->css($css);
		}
		$this->content['css_assets'] = $this->carabiner->display_string('css');
		
		// compile the body content
		$this->content['pre_wrap_views']	= $this->pre_wrap_views;
		$this->content['wrap_classes']		= implode(' ', $this->wrap_classes);
		$this->content['wrap_views']		= $this->wrap_views;
		$this->content['footer_views']		= $this->footer_views;
		
		// compile the default js assets
		$default_js_assets = array(
			'third_party/jquery-1.11.1.min.js',
			'third_party/jquery-migrate-1.2.1.min.js',
			'third_party/jquery.ba-outside-events.min.js',
			'third_party/jquery-ui.min.js',
			'default/flash_message.js',
			'default/ajax_request.js',
			'default/forms.js',
		);
		
		// compile the final combined js assets to a single file
		$js_assets = array_merge($default_js_assets, $this->js_assets);
		$js_assets = array_unique($js_assets);
		foreach ($js_assets as $js)
		{
			$this->carabiner->js($js);
		}
		$this->content['js_assets'] = $this->carabiner->display_string('js');
		
		// compile the js views
		$js_views = array_unique($this->js_views);
		$this->content['js_views'] = $js_views;
		
		// compile the domready js views
		$domready_js_views = array_unique($this->domready_js_views);
		$this->content['domready_js_views'] = $domready_js_views;
		
		// set some default headers
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Strict-Transport-Security: max-age=2592000");
		$this->output->set_header("X-Frame-Options: SAMEORIGIN");
		
		// load the final page view
		$this->load->view('templates/page/base_view', $this->content);
	}


	/**
	 * Build JSON response for ajax requests
	 *
	 * @return	void
	 */
	protected function ajax_response()
	{
		// last minute check to make sure request came via ajax
		if ( ! $this->input->is_ajax_request() )
		{
			show_404($this->uri->uri_string());
		}
		
		// trigger the before_ajax_response callbacks
		$this->trigger('before_ajax_response');
		
		// set some default headers
		// investigate headers from http://codeigniter.com/forums/viewthread/79844/#401259
		$this->output->set_header("Vary: Accept");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Strict-Transport-Security: max-age=2592000");
		$this->output->set_header("X-Frame-Options: SAMEORIGIN");
		
	    if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false))
		{
			$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
		    $this->output->set_header('Expires: '.gmdate('D, d M Y H:i:s', time()).' GMT');
			$this->output->set_header("Content-type: application/x-javascript");
			$this->output->set_content_type('application/x-javascript');
		}
		else
		{
		    $this->output->set_header("Content-type: text/plain");
		    $this->output->set_content_type('text/plain');
		}
	    
	    // encode and return json data...
		$this->output->set_output(json_encode($this->json));
	}


	/**
	 * Set a flash message for the next server request
	 * 
	 * @param	string
	 * @param	string
	 * @return 	void
	 */
	protected function set_flash_message($status = 'success', $message = '')
	{
		$this->session->set_flashdata('status', $status);
		$this->session->set_flashdata('message', $message);
	}


	/**
	 * Trigger an event and call its observers
	 *
	 * @param	string
	 * @return 	void
	 */
	protected function trigger($event)
	{
		if (isset($this->$event) && is_array($this->$event))
		{
			foreach ($this->$event as $method)
			{
				call_user_func_array(array($this, $method), array());
			}
		}
	}


	/**
	 * Trigger an event and call its observers
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return 	void
	 */
	protected function add_system_nav_item($id, $title, $view, $href = NULL)
	{
		$this->system_nav_items[$id] = array(
			'title'		=> $title,
			'view'		=> $view,
			'href'		=> $href
		);
	}


}