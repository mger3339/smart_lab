<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application extends User_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Validate and launch an application
	 *
	 * @param	int
	 * @return	function
	 */
	public function launch($application_id)
	{
		// validate the application
		if ( isset($this->client->applications[$application_id]) )
		{
			$application = $this->client->applications[$application_id];
		}
		else
		{
			redirect();
		}
		
		// set the user's application session data
		$application_session_data = array(
			'application_id'		=> $application->id,
			'application'			=> $application->application,
		);
		
		$this->session->set_userdata($application_session_data);
		
		// redirect to the application
		redirect($application->application);
	}


}