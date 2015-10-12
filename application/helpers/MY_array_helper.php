<?php defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('is_assoc'))
{
	/**
	 * Returns TRUE if supplied array is associative array type
	 *
	 * @param	array
	 * @return	bool
	 */
	function is_assoc($array)
	{
		return (bool) count(array_filter(array_keys($array), 'is_string'));
	}
}


/* End of file MY_array_helper.php */
/* Location: ./application/helpers/MY_array_helper.php */