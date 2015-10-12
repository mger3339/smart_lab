<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_application_setting_model extends Smartlab_model {


	/**
	 * Settings container
	 */
	private $settings = array();


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );


	/**
	 * Model observers
	 */
	public $before_create		= array(
										'set_client_id',
										'set_client_application_id',
										'set_created_time',
										'set_modified_time',
								);
	public $before_update		= array( 'where_client_id', 'where_client_application_id', 'set_modified_time' );
	public $before_delete		= array( 'where_client_id', 'where_client_application_id' );
	public $before_get			= array( 'where_client_id', 'where_client_application_id' );


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Sets up application settings.
	 * Checks for & sets any undefined settings
	 * if a model path is passed
	 *
	 * @param	string
	 * @return	void
	 */
	public function initialize($default_settings_model)
	{
		$settings_query = $this->get_all();
		$settings = array();
		
		$default_settings = array();
		
		if ( $default_settings_model )
		{
			$this->load->model($default_settings_model, 'default_settings_model');
			$default_settings = $this->default_settings_model->get_default_settings();
		}
		
		if ( ! empty($settings_query) )
		{
			foreach ($settings_query as $row)
			{
				$settings[$row->setting] = $row->value;
			}
			
			// check for and remove any redundant settings
			foreach ($settings as $setting => $value)
			{
				if ( ! array_key_exists($setting, $default_settings) )
				{
					$this->delete_by('setting', $setting);
					unset($settings[$setting]);
				}
			}
			
			// check for and add any newly defined settings
			foreach ($default_settings as $setting => $value)
			{
				if ( ! array_key_exists($setting, $settings) )
				{
					$this->insert(array(
						'setting'		=> $setting,
						'value'			=> $value,
					), TRUE);
					$settings[$setting] = $value;
				}
			}
		}
		else
		{
			if ( ! empty($default_settings) )
			{
				// store default settings
				foreach ($default_settings as $setting => $value)
				{
					$this->insert(array(
						'setting'		=> $setting,
						'value'			=> $value,
					), TRUE);
				}
			}
			
			$settings = $default_settings;
		}
		
		$this->settings = $settings;
	}


	/**
	 * Get all application settings
	 * -returns settings as an object
	 *
	 * @return	object
	 */
	public function get_all_settings()
	{
		return (object) $this->settings;
	}


	/**
	 * Get an application setting
	 * - returns FALSE if setting is not found
	 *
	 * @return	mixed
	 */
	public function setting($setting)
	{
		if (array_key_exists($setting, $this->settings))
		{
			return $this->settings[$setting];
		}
		
		return FALSE;
	}


}