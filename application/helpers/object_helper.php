<?php defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('object_array_to_dropdown_array'))
{
	/**
	 * Return a CI dropdown array from an object array
	 * using the key and value properties provided
	 *
	 * Nested objects can be accessed using dot notation: 'propertyLevel1.propertyLevel2.propertyLevel3'
	 * 
	 * @param  array $object
	 * @param  string $key_property
	 * @param  string $value_property
	 * @param  string $format
	 * @param  array $initial_key_value_pair
	 * @return array
	 */
	function object_array_to_dropdown_array($object, $key_property, $value_property, $format = '', $initial_key_value_pair = NULL)
	{
		// Initialize result array
		$formatted_array = array();

		// Should we include an initial key/value pair?
		if ( ! is_null($initial_key_value_pair) && is_array($initial_key_value_pair) && count($initial_key_value_pair) == 1)
		{
			reset($initial_key_value_pair);
			$key = key($initial_key_value_pair);

			$formatted_array[$key] = $initial_key_value_pair[$key];
		}

		// Prepare nested properties array
		$nested_keys = explode('.', $key_property);
		$nested_properties = explode('.', $value_property);

		// Iterate over results and build result array
		foreach ($object as $row)
		{
			$key = NULL;
			$value = NULL;

			// Get key target property
			$key_target = $row;
			foreach ($nested_keys as &$property)
			{
				if ( ! isset($key_target->$property))
				{
					continue;
				}

			    $key_target =& $key_target->$property;
			}

			$key = $key_target;

			// Get value target property
			$value_target = $row;
			foreach ($nested_properties as &$property)
			{
				if ( ! isset($value_target->$property))
				{
					continue;
			    }

			    $value_target =& $value_target->$property;
			}
			
			$value = $value_target;

			// No key or value valid? Let's skip this
			if (is_object($key) OR is_object($value) OR empty($key))
			{
				continue;
			}

			// Format value if requested
			if ( ! is_null($format))
			{
				if ($format == 'ucwords')
				{
					$value = ucwords($value);
				}
				elseif ($format == 'ucfirst')
				{
					$value = ucfirst($value);
				}
			}

			$formatted_array[$key] = $value;
		}

		return $formatted_array;
	}
}


/* End of file MY_object_helper.php */
/* Location: ./application/helpers/MY_object_helper.php */