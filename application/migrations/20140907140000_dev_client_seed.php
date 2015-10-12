<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_dev_client_seed extends CI_Migration {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Insert test client
	 *
	 */
	public function up()
	{
		if (ENVIRONMENT === 'development')
		{
			echo "\n Adding dev client 'client' data";
			$client = array(
				'name'				=> 'Client',
				'slug'				=> 'client',
				'user_id'			=> 1,
				'admin_user_id'		=> 1,
				'language'			=> DEFAULT_LANGUAGE,
				'country_iso'		=> DEFAULT_COUNTRY_ISO,
				'time_zone'			=> DEFAULT_TIMEZONE,
				'currency'			=> DEFAULT_CURRENCY,
			);
			$this->load->model('client_model');
			$client_id = $this->client_model->insert($client);
			
			echo "\n Schema changes successful";
		}
	}


	/**
	 * Delete test client
	 *
	 */
	public function down()
	{
		if (ENVIRONMENT === 'development')
		{
			$this->load->model('client_model');
			$client = $this->client_model->get_by('slug', 'client');
			
			if ($client)
			{
				$client_id = $client->id;
				
				echo "\n Removing dev client 'client' data";
				$this->client_model->delete($client_id);
			}
			
			echo "\n Schema changes successful";
		}
	}


}