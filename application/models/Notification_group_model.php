<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_group_model extends Smartlab_model {

	protected $belongs_to = array('notifications');
	protected $after_get = array('_add_client_group');

	private $_group_cache = array();


    /**
     * Initialise the model
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_group_model');
    }


	/**
	 * Add group details to results
	 * 
	 * @param array $row
	 * @return array
	 */
	protected function _add_client_group($row)
	{
		if (empty($row))
		{
			return $row;
		}

		if (! in_array($row->group_id, array_keys($this->_group_cache)))
		{
			$this->_group_cache[$row->group_id] = $this->client_group_model->get($row->group_id);
		}

		$row->client_group = $this->_group_cache[$row->group_id];

		return $row;
	}


	/**
	 * [insert_ids description]
	 * @param  [type] $notification_id [description]
	 * @param  [type] $ids             [description]
	 * @return [type]                  [description]
	 */
	public function insert_ids($notification_id, $ids)
	{
		if (empty($ids))
		{
			return FALSE;
		}

		$data = array();
		foreach ($ids as $id)
		{
			if ( ! is_numeric($id))
			{
				return FALSE;
			}

			$data[] = array(
				'notification_id' => $notification_id,
				'group_id' => $id
			);
		}

		return $this->insert_many($data);
	}

}