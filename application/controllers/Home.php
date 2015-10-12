<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends User_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load relevant lang files
		$this->lang->load('home');
		$this->lang->load('applications_nav');
		
		// add page render observers to the class callback lists
		$this->before_render[] = 'build_environemnt';
		
		// set the page title
		$this->page_title[] = lang('home_title');
		
		// add page specific CSS classes
		$this->wrap_classes[] = 'home';
	}


	/**
	 * Default class method - build home page
	 *
	 * @return	void
	 */
	public function index()
	{
		$this->wrap_views[] = $this->load->view('home/index', $this->content, TRUE);
		
		$this->render();
	}


	/**
	 * Build timeline environment
	 *
	 * @return	void
	 */
	protected function build_environemnt()
	{
		// add the home header to the top of the wrap_views container
		$header_content = array();
		
		array_unshift($this->wrap_views, $this->load->view('home/page/header', $header_content, TRUE));
	}


}