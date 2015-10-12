<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends Smartlab_model {

	protected $has_many = array('notification_group', 'notification_user');
	protected $before_create = array('set_created_time', 'set_user_id', 'set_client_id');

	/**
	 * Model validation rules
	 */
	public $validate = array(
		array(
			'field'		=> 'subject',
			'label'		=> 'Subject',
			'rules'		=> 'required|trim|max_length[200]'
		),
		array(
			'field'		=> 'message',
			'label'		=> 'Message',
			'rules'		=> 'required|trim'
		)
	);


	/**
	 * Add new notification
	 * 
	 * @param array $data
	 */
	public function add_notification($data)
	{
		$recipients = $this->_get_input_recipients($data);

		// If empty recipients, let's add a validation rule to trigger the error
		if (empty($recipients))
		{
			$this->validate[] = array(
				'field'		=> 'recipients',
				'label'		=> 'Recipients',
				'rules'		=> 'required'
			);
		}

		// Start transaction because we are storing values on three tables
		$this->db->trans_begin();

		$notification_id = $this->insert(array(
			'subject' => $data['subject'],
			'message' => $data['message']
		));
		
		if (empty($notification_id))
		{
			$this->db->trans_rollback();
			return FALSE;	
		}

		foreach ($recipients as $type => $recipients)
		{
			$model_name = 'Notification_' . $type . '_model';
			$this->load->model($model_name);
			$insert_ids = $this->$model_name->insert_ids($notification_id, $recipients);
		}

		if (in_array(FALSE, $insert_ids))
		{
			$this->db->trans_rollback();
			return FALSE;	
		}

		$this->db->trans_commit();

		return $notification_id;
	}


	/**
	 * Get notification input recipients by type
	 * 
	 * @param  array $data
	 * @return array
	 */
	private function _get_input_recipients($data)
	{
		$recipient_types = array('user', 'group');

		$recipients = array();
		foreach ($recipient_types as $recipient_type)
		{
			if ( ! empty($data[$recipient_type . '_ids']))
			{
				$ids = explode(',', $data[$recipient_type . '_ids']);
				if ( ! empty($ids))
				{
					$recipients[$recipient_type] = $ids;
				}
			}
		}

		return $recipients;
	}
}