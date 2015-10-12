<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_user_model extends Smartlab_model {

	protected $after_get = array('_add_user');
	protected $belongs_to = array('notifications', 'user');

	private $_users_cache = array();


    /**
     * Initialise the model
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
    }


	/**
	 * Add user details to results
	 * 
	 * @param array $row
	 * @return array
	 */
	protected function _add_user($row)
	{
		if (empty($row))
		{
			return $row;
		}

		if ( ! in_array($row->user_id, array_keys($this->_users_cache)))
		{
			$this->_users_cache[$row->user_id] = $this->user_model->get($row->user_id);
		}

		$row->user = $this->_users_cache[$row->user_id];

		return $row;
	}


	/**
	 * Insert notification user ids
	 * 
	 * @param  integer  $notification_id
	 * @param  array  $ids
	 * @param  integer $through_group_id
	 * @return array
	 */
	public function insert_ids($notification_id, $ids, $through_group_id = FALSE)
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

			$row = array(
				'notification_id' => $notification_id,
				'user_id' => $id
			);

			if ( ! empty($group_id))
			{
				$row['through_group_id'] = $group_id;
			}

			$data[] = $row;
		}

		return $this->insert_many($data);
	}

}