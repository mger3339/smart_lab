<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trend_com_setting_model extends CI_Model {


	/**
	 * Default Timeline App options
	 */
	protected $default_options = array();


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// set the default options
		$this->default_options['filtering']				= 0;
		$this->default_options['filter_page_title']		= 'Tell us about you';
		$this->default_options['results_manual_update']	= 0;
	}


	/**
	 * Get the default options
	 *
	 */
	public function get_default_settings()
	{
		return $this->default_options;
	}


}