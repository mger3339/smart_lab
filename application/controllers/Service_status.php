<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_status extends MY_Controller {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method - redirect
	 *
	 */
	public function index()
	{
		return redirect('welcome');
	}


	/**
	 * Display client unavailable message
	 *
	 */
	public function client_unavailable()
	{
		$this->content['client'] = $this->get_client($this->session->userdata('client_id'));
		
		$this->session->unset_userdata('client_id');
		
		$this->wrap_views[] = $this->load->view('service_status/client_unavailable', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Display client unavailable message
	 *
	 */
	public function client_not_yet_commenced()
	{
		$this->content['client'] = $this->get_client($this->session->userdata('client_id'));
		
		$this->session->unset_userdata('client_id');
		
		$this->wrap_views[] = $this->load->view('service_status/client_not_yet_commenced', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Display client unavailable message
	 *
	 */
	public function client_expired()
	{
		$this->content['client'] = $this->get_client($this->session->userdata('client_id'));
		
		$this->session->unset_userdata('client_id');
		
		$this->wrap_views[] = $this->load->view('service_status/client_expired', $this->content, TRUE);
		$this->render();
	}


	/**
	 * Build & return a client - redirect if client is not valid
	 *
	 */
	private function get_client($client_id)
	{
		$this->load->model('client_model');
		$client = $this->client_model->get($client_id);
		
		if ( ! $client )
		{
			redirect('welcome');
		}
		
		return $client;
	}


}