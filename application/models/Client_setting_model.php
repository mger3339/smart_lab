<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_setting_model extends Smartlab_model {


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> 'client_id',
			'label'		=> 'client ID',
			'rules'		=> 'trim'
		),
		array(
			'field'		=> 'setting',
			'label'		=> 'client setting key',
			'rules'		=> 'trim|max_length[30]'
		),
		array(
			'field'		=> 'value',
			'label'		=> 'client setting value',
			'rules'		=> 'trim'
		),
	);


	/**
	 * Default client settings
	 */
	private $default_settings = array(
		'text_example'		=> array(
					'label'			=> 'Text example',
					'type'			=> 'text',
					'options'		=> NULL,
					'value'			=> '',
					'boolean'		=> FALSE,
					'group'			=> 'example',
		),
		'date_example'		=> array(
					'label'			=> 'Date example',
					'type'			=> 'date',
					'options'		=> NULL,
					'value'			=> 0,
					'boolean'		=> FALSE,
					'group'			=> 'example',
		),
		'select_example'	=> array(
					'label'			=> 'Dropdown example',
					'type'			=> 'select',
					'options'		=> array(
						'option_1'		=> 'Option 1',
						'option_2'		=> 'Option 2 (default)',
						'option_3'		=> 'Option 3',
					),
					'value'			=> 'option_2',
					'boolean'		=> FALSE,
					'group'			=> 'example',
		),
	);


	/**
	 * Groups for organising settings
	 */
	private $setting_groups = array(
		'example'				=> 'Example settings',
	);


	/**
	 * Get settings for a client
	 *
	 * @param	int
	 * @return	array
	 */
	public function get_client_settings($client_id)
	{
		// first get the client's settings DB data
		$settings_data = $this->get_many_by('client_id', $client_id);
		
		// set the default settings if DB data does not exist
		if ( ! $settings_data || empty($settings_data))
		{
			$this->set_client_default_settings($client_id);
			
			$settings_data = $this->get_many_by('client_id', $client_id);
		}
		
		// re-define the client's settings data
		// into a key [setting] => value array
		$client_settings_data = array();
		
		foreach ($settings_data as $row)
		{
			$client_settings_data[$row->setting] = $row->value;
		}
		
		// purge any orphaned client DB settings
		// that are no longer in the default settings list
		foreach ($client_settings_data as $setting => $value)
		{
			if ( ! array_key_exists($setting, $this->default_settings))
			{
				$this->delete_by(array(
					'client_id'		=> $client_id,
					'setting'		=> $setting,
				));
			}
		}
		
		// build the client settings array
		// using the default settings as a foundation
		$settings = array();
		
		foreach ($this->default_settings as $setting => $props)
		{
			// add any newly created settings that may be missing
			// from the client DB settings
			if ( ! array_key_exists($setting, $client_settings_data))
			{
				$data = array(
					'client_id'		=> $client_id,
					'setting'		=> $setting,
					'value'			=> $props['value'],
				);
				
				$this->insert($data, TRUE);
				
				$client_settings_data[$setting] = $props['value'];
			}
			
			// set the setting boolean value if necessary
			if ($props['boolean'] === TRUE && $props['value'] == 1)
			{
				$client_settings_data[$setting] = TRUE;
			}
			else if ($props['boolean'] === TRUE)
			{
				$client_settings_data[$setting] = FALSE;
			}
			
			// set the setting display value for readability
			$display_value = $client_settings_data[$setting];
			
			switch ($props['type'])
			{
				case 'date':
				
				$time = intval($client_settings_data[$setting]);
				
				if ($time > 0)
				{
					$display_value = strftime("%e %B %Y", $time);
				}
				else
				{
					$client_settings_data[$setting] = '';
					$display_value = '';
				}
				
				break;
				
				case 'select':
				
				if ($client_settings_data[$setting])
				{
					$display_value = $props['options'][$client_settings_data[$setting]];
				}
				else
				{
					$client_settings_data[$setting] = '';
					$display_value = '';
				}
				
				break;
				
				default: break;
			}
			
			// set the setting and its properties
			$settings[$setting] = array(
					'label'				=> $props['label'],
					'type'				=> $props['type'],
					'options'			=> $props['options'],
					'value'				=> $client_settings_data[$setting],
					'display_value'		=> $display_value,
					'group'				=> $props['group'],
			);
		}
		
		return $settings;
	}


	/**
	 * Get settings for a client
	 *
	 * @return	array
	 */
	public function get_setting_groups()
	{
		return $this->setting_groups;
	}


	/**
	 * Set default settings for a client
	 *
	 * @param	int
	 * @return	void
	 */
	public function set_client_default_settings($client_id)
	{
		foreach ($this->default_settings as $setting => $props)
		{
			$data = array(
				'client_id'		=> $client_id,
				'setting'		=> $setting,
				'value'			=> $props['value'],
			);
			
			$this->insert($data, TRUE);
		}
	}


	/**
	 * Set (update) settings for a client
	 *
	 * @param	int
	 * @return	void
	 */
	public function update_client_settings($client_id)
	{
		foreach ($this->default_settings as $setting => $props)
		{
			$setting_value = $this->input->post($setting);
			
			switch ($props['type'])
			{
				case 'date':
				
				if ( ! $setting_value)
				{
					$setting_value = 0;
				}
				else
				{
					$setting_value = strtotime($setting_value);
				}
				
				break;
				
				default:
				
				if ($setting_value && $props['boolean'] === TRUE)
				{
					$setting_value = 1;
				}
				else if ( ! $setting_value && $props['boolean'] === TRUE)
				{
					$setting_value = 0;
				}
				else if ( ! $setting_value)
				{
					$setting_value = NULL;
				}
				
				break;
			}
			
			$data = array(
				'value' 		=> $setting_value,
			);
			
			$this->update_by(array(
				'client_id'		=> $client_id,
				'setting'		=> $setting,
			), $data, TRUE);
		}
	}


}