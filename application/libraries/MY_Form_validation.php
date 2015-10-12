<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {


	/**
	 * Is Unique - modified to overlook the data being modified if unique_id POST is set.
	 *
	 * Check if the input value doesn't already exist
	 * in the specified database field.
	 *
	 * @param	string
	 * @param	string	field
	 * @return	bool
	 */
	public function is_unique($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
		
		if ( isset($this->CI->db) )
		{
			if ( $this->CI->input->post('unique_id') )
			{
				$this->CI->db->where('id !=', $this->CI->input->post('unique_id'));
			}
			
			$this->CI->db->where('deleted !=', 1);
			
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
			
			return $query->num_rows() === 0;
		}
		
		return FALSE;
	}


	/**
	 * Is unique to client.
	 *
	 * Check if the input value doesn't already exist
	 * in the specified database field for a given client.
	 *
	 * Uses client_id (required) and unique_id (optional) POST vars.
	 *
	 * @param	string
	 * @param	string	field
	 * @return	bool
	 */
	public function is_client_unique($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
		
		if ( isset($this->CI->db) )
		{
			$client_id = $this->CI->input->post('client_id');
			
			if ( ! isset($client_id) && isset($this->CI->client) )
			{
				$client_id = $this->CI->client->id;
			}
			
			if ( ! isset($client_id) )
			{
				$this->set_message('is_client_unique', 'The {field} cannot be checked if it is unique to its client because the client ID could not be determined.');
				
				return FALSE;
			}
			
			if ( $this->CI->input->post('unique_id') )
			{
				$this->CI->db->where('id !=', $this->CI->input->post('unique_id'));
			}
			
			$this->CI->db->where('deleted !=', 1);
			
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str, 'client_id' => $client_id));
			
			$this->set_message('is_client_unique', 'The {field} must be unique.');
			
			return $query->num_rows() === 0;
		}
		
		return FALSE;
	}


	/**
	 * Check that password contains at least one number, one lowercase letter and one uppcase letter
	 *
	 * @param	string
	 * @return	bool
	 */
	public function valid_password($str)
	{
		if ( preg_match('#[0-9]#', $str) && preg_match('#[a-z]#', $str) && preg_match('#[A-Z]#', $str) )
		{
			return TRUE;
		}
		
		$this->set_message('valid_password', 'Passwords must contain at least one uppercase letter, one lowercase letter and one number.');
		
		return FALSE;
	}


	/**
	 * Check for valid domain
	 *
	 * @param	string
	 * @return	bool
	 */
	public function valid_domain($str)
	{
		if ( ! empty($str) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $str) > 0 )
		{
			return TRUE;
		}
		
		$this->set_message('valid_domain', 'The {field} must contain a valid domain name.');
		
		return FALSE;
	}


	/**
	 * Check for valid email hostname
	 *
	 * @param	string
	 * @return	bool
	 */
	public function valid_email_hostname($str)
	{
		if ( isset($this->CI->db) && isset($this->CI->client) )
		{
			$this->CI->load->model('email_hostname_model');
			
			$valid_hostname = $this->CI->email_hostname_model->validate_hostname($str);
			
			if ( $valid_hostname )
			{
				return TRUE;
			}
			
			$this->set_message('valid_email_hostname', 'The {field} must contain an email with a valid hostname.');
		}
		
		return FALSE;
	}


}