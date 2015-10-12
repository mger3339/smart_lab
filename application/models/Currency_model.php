<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_model extends Smartlab_model {


	/**
	 * Get a list of currency select options
	 *
	 * @return	array
	 */
	public function get_currency_options()
	{
		$results = $this->order_by('id', 'ASC')->get_many_by('active', 1);
		
		$options = array();
		
		foreach($results as $row)
		{
			$options[$row->code] = $row->name;
		}
		
		return $options;
	}
}