<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time_zone_model extends CI_Model {


	/**
	 * Get a list of time zones select options
	 *
	 * @return	array
	 */
	public function get_time_zone_options()
	{
		$options = array();
		$timestamp = time();
		
		foreach(timezone_identifiers_list() as $key => $zone)
		{
			date_default_timezone_set($zone);
			
			$options[$zone] = 'UTC/GMT ' . date('P', $timestamp) . ' - ' . $zone;
		}
		
		ksort($options);
		
		return $options;
	}


}