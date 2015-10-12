<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_export extends Timeline_context {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Default class method
	 *  - compile CSV data download file
	 *
	 * @return	function
	 */
	public function index()
	{
		$this->content['workstreams'] = $this->workstream_model->get_workstreams(TRUE);
		
		$file_name = strtoupper($this->application->name) . '_DATA_' . strftime("%Y%m%d") . '.csv';
		
		$data = $this->load->view('timeline/admin/data_export/index', $this->content, TRUE);
		
		$this->load->helper('download');
		
		return force_download($file_name, $data, TRUE);
	}


}