<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timeline_workstream_model extends Smartlab_model {


	/**
	 * Enable soft delete
	 */
	protected $soft_delete = TRUE;


	/**
	 * Protected columns
	 */
	public $protected_attributes = array( 'id' );

	
	/**
	 * Max number of workstreams
	 */
	private $max_workstreams = 24;


	/**
	 * Some default (dummy) workstreams
	 */
	private $default_workstreams = array(
		
		array(
				'name'			=> 'Workstream A',
				'description'	=> 'Unt pratie feuis ad do dolore magnim in hent ulluptatie magnim at. Dui tet wiscipit nulla aliquatue magna faccum zzrit utat. Dui te dolenit, sequip exer ipit ipit utat am nonsed delis dolor ad magna feuisim dolummo dolore consequis nim irit am, sed er ipit nostio odolore conummy num ipit praessi.',
				'color'			=> 'feee94',
		),
		
		array(
				'name'			=> 'Workstream B',
				'description'	=> 'Ex et, conumsandre faccum dolesto consequis nonse eugait iure ent lobore modiam zzril ilisit illuptat lum quam, quat lutat. Ipis dionsecte magnim nis nostrud modoloboreet ad dignim dolore digna feuis nummy nulla alisl ulla alit ver iureet ute facinciduisi tem ilis nostie erilit nisl eugueratem quamet acil in henibh et wis nulputet iusto etueraesto conse dunt essim eros eu facin ero odigniam, sectem ilis dolumsan heniam, con vel delit utat. Ut inci bla feu facipit lor si.',
				'color'			=> 'ffdada',
		),
		
		array(
				'name'			=> 'Workstream C',
				'description'	=> 'Ud er iusciduis duisim vero ero elesecte ea facil illa autatie moloreet nisi blamet, quate dolor iuscilla auguer init.',
				'color'			=> 'a8e4f8',
		),
		
		array(
				'name'			=> 'Workstream with a long long name dolor iuscilla auguer',
				'description'	=> 'Ud er iusciduis duisim vero ero elesecte ea facil illa autatie moloreet nisi blamet, quate dolor iuscilla auguer init.',
				'color'			=> 'ffe6b3',
		),
		
		array(
				'name'			=> 'Workstream Y',
				'description'	=> '',
				'color'			=> 'aff7ca',
		),
		
		array(
				'name'			=> 'Workstream Z',
				'description'	=> '',
				'color'			=> 'e0befb',
		),
		
	);


	/**
	 * Default workstream colors
	 */
	private $default_colors = array(
		'FDED93', 'FFD9D9', 'A7E3F7', 'FFE5B2', 'AEF6C9', 'DFBDFA',
		'F2F272', 'EDC3DC', 'A6BBF4', 'FFBA9C', '98EA81', 'AFA2F9',
		'E2D48D', 'E2C5C5', '9ACDDB', 'DBC59C', '9BD1AD', 'BEA3D8',
		'B6FC95', 'FFB0B0', '73FFEE', 'FFBF80', 'CCFF73', 'FA9FFF',
	);


	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> 'name',
			'label'		=> 'name',
			'rules'		=> 'required|trim|max_length[60]'
		),
		array(
			'field'		=> 'description',
			'label'		=> 'description',
			'rules'		=> 'trim'
		),
		array(
			'field'		=> 'color',
			'label'		=> 'color',
			'rules'		=> 'trim|max_length[6]'
		),
		array(
			'field'		=> 'sort_order',
			'label'		=> 'sort order',
			'rules'		=> 'trim|integer'
		),
	);


	/**
	 * Model observers
	 */
	public $before_create		= array(
											'set_client_id',
											'set_client_application_id',
											'set_created_time',
											'set_modified_time',
											'set_created_user_id',
								);
	public $before_update		= array(
											'where_client_id',
											'where_client_application_id',
											'set_modified_time',
											'set_modified_user_id',
								);
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
	 * Get workstreams
	 * - checks for & sets some default workstreams if none are present
	 * - optionally adds relative milestones to each workstream
	 *
	 * @param	bool
	 * @return	array
	 */
	public function get_workstreams($include_milestones = FALSE)
	{
		$this->order_by('sort_order', 'ASC');
		$workstreams = $this->get_all();
		
		// set some default workstreams if none are present
		if (empty($workstreams))
		{
			$this->set_default_workstreams();
			$this->order_by('sort_order', 'ASC');
			$workstreams = $this->get_all();
		}
		
		// add the milestones data if required
		if ($include_milestones === TRUE)
		{
			$this->load->model('timeline/timeline_milestone_model', 'milestone_model');
			
			$workstreams_data = array();
			
			foreach ($workstreams as $row)
			{
				$this->milestone_model->order_by('start_date', 'ASC');
				$row->milestones = $this->milestone_model->get_many_by('timeline_workstream_id', $row->id);
				
				$workstreams_data[] = $row;
			}
			
			$workstreams = $workstreams_data;
		}
		
		return $workstreams;
	}


	/**
	 * Return max number of workstreams
	 *
	 * @return	int
	 */
	public function get_max_workstreams()
	{
		return $this->max_workstreams;
	}


	/**
	 * Return default workstream colors
	 *
	 * @return	array
	 */
	public function get_default_colors()
	{
		return $this->default_colors;
	}


	/**
	 * Set default workstreams
	 *
	 * @return	void
	 */
	private function set_default_workstreams()
	{
		$sort_order = 1;
		
		foreach ($this->default_workstreams as $workstream)
		{
			$workstream['sort_order'] = $sort_order;
			
			$this->insert($workstream, TRUE);
			
			$sort_order++;
		}
	}


}