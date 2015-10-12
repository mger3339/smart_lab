<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Country_model extends Smartlab_model {


	/**
	 * Get a list of country select options
	 *
	 * @return	array
	 */
	public function get_country_options()
	{
		$results = $this->order_by('country_printable_name', 'ASC')->get_all();
		
		$options = array();
		
		foreach($results as $row)
		{
			$options[$row->country_iso] = $row->country_printable_name;
		}
		
		return $options;
	}


}