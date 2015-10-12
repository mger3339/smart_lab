<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timeline_setting_model extends CI_Model {


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
		$this->default_options['calendar_start']		= date('Y-m-d');
		$this->default_options['calendar_end']			= date('Y-m-d', strtotime('+2 years'));
		$this->default_options['calendar_division']		= 'months';
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