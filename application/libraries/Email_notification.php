<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_notification {


	private $CI;
	private $config;


	/**
	 * Template vars
	 *
	 */
	private $base_template_view	= 'base';
	private $language_dir;
	private $template_view		= 'default';
	private $client_obj			= NULL;


	/**
	 * Email setup & content
	 *
	 */
	private $to_email			= NULL;
	private $from_email			= NULL;
	private $to_name			= NULL;
	private $from_name			= NULL;
	private $cc_email;
	private $bcc_email;
	private $email_subject;
	private $email_content		= array();


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		// Get the CI instance
		$this->CI =& get_instance();
		
		// Load commonly used helpers
		$this->CI->load->helper('email');
		
		// Load & set configuration		
		$this->CI->load->config('email_notification', TRUE);
		$this->config = (object) $this->CI->config->item('email_notification');
		
		// Set default vars
		$this->language = DEFAULT_LANGUAGE;
	}


	/**
	 * Setter for base template
	 *
	 * @param	string
	 * @return	this
	 */
	public function base_template($template)
	{
		if ( $template )
		{
			$this->base_template_view = $template;
		}
		
		return $this;
	}


	/**
	 * Setter for language
	 *
	 * @param	string
	 * @return	this
	 */
	public function language($language)
	{
		if ( $language && is_dir(VIEWPATH . '/templates/email/' . $language) )
		{
			$this->language_dir = $language;
		}
		
		return $this;
	}


	/**
	 * Setter for the template
	 *
	 * @param	string
	 * @return	this
	 */
	public function template($template)
	{
		if ( $template && file_exists(VIEWPATH . "/templates/email/{$this->language}/{$template}_html.php") )
		{
			$this->template_view = $template;
		}
		else if ( $template && file_exists(VIEWPATH . "/templates/email/" . DEFAULT_LANGUAGE . "/{$template}_html.php") )
		{
			$this->language(DEFAULT_LANGUAGE);
			$this->template_view = $template;
		}
		
		return $this;
	}


	/**
	 * Setter for the client object
	 *
	 * @param	object
	 * @return	this
	 */
	public function client($client)
	{
		if ( is_object($client) )
		{
			$this->client_obj = $client;
			
			$this->language($this->client_obj->language);
		}
		
		return $this;
	}


	/**
	 * Setter for 'to' email address
	 * and optional 'to' name
	 *
	 * @param	string
	 * @param	string
	 * @return	this
	 */
	public function to($email = '', $name = '')
	{
		if ( filter_var($email, FILTER_VALIDATE_EMAIL) )
		{
			$this->to_email = $email;
		}
		
		if ( $name )
		{
			$this->to_name = $name;
		}
		
		return $this;
	}


	/**
	 * Setter for 'from' email address
	 * and optional 'from' name
	 *
	 * @param	string
	 * @param	string
	 * @return	this
	 */
	public function from($email = '', $name = '')
	{
		if ( filter_var($email, FILTER_VALIDATE_EMAIL) )
		{
			$this->from_email = $email;
		}
		
		if ( $name )
		{
			$this->from_name = $name;
		}
		
		return $this;
	}


	/**
	 * Setter for 'cc' email address(es)
	 *
	 * @param	string
	 * @return	this
	 */
	public function cc($email = '')
	{
		if ( $email )
		{
			$this->cc_email = $email;
		}
		
		return $this;
	}


	/**
	 * Setter for 'bcc' email address(es)
	 *
	 * @param	string
	 * @return	this
	 */
	public function bcc($email = '')
	{
		if ( $email )
		{
			$this->bcc_email = $email;
		}
		
		return $this;
	}


	/**
	 * Setter for email subject
	 *
	 * @param	string
	 * @return	this
	 */
	public function subject($subject = '')
	{
		$this->email_subject = $subject;
		
		return $this;
	}


	/**
	 * Setter for email content
	 *
	 * @param	array
	 * @return	this
	 */
	public function content($content)
	{
		$this->CI->load->helper('array');
		
		if ( is_array($content) && is_assoc($content) )
		{
			$this->email_content = $content;
		}
		
		return $this;
	}


	/**
	 * Send email notification
	 *
	 * @return	bool
	 */
	public function send()
	{
		if ( ! $this->config->email_notification_enable )
		{
			return TRUE;
		}
		
		if ( ! $this->to_email )
		{
			log_message('error', get_class($this) . ': TO email is missing');
			
			return FALSE;
		}
		
		if ( ! $this->from_email )
		{
			log_message('error', get_class($this) . ': FROM email is missing');
			
			return FALSE;
		}
		
		// save the subject to the email content container
		if ( ! array_key_exists('subject', $this->email_content) )
		{
			$this->email_content['subject'] = $this->email_subject;
		}
		
		// build the HTML message content
		$html_message_body = $this->CI->load->view("templates/email/{$this->language_dir}/{$this->template_view}_html", $this->email_content, TRUE);
		
		// add the HTML message content to the base HTML view
		$html_content = array();
		$html_content['client'] = $this->client_obj;
		$html_content['subject'] = $this->email_subject;
		$html_content['message'] = $html_message_body;
		
		$html_message =  $this->CI->load->view("templates/email/base_html", $html_content, TRUE);
		
		// build the plain text message content
		$text_message_body = $this->CI->load->view("templates/email/{$this->language_dir}/{$this->template_view}_text", $this->email_content, TRUE);
		
		// add the plain text message content to the base plain text view
		$text_content = array();
		$text_content['client'] = $this->client_obj;
		$text_content['message'] = $text_message_body;
		
		$text_message =  $this->CI->load->view("templates/email/base_text", $text_content, TRUE);
		
		// load and configure the email library
		$this->CI->load->library('email');
		
		$email_config = array();
		$email_config['charset'] = 'utf-8';
		$email_config['mailtype'] = 'html';
		$email_config['wordwrap'] = FALSE;
		
		if ( $this->config->email_notification_use_smtp == TRUE )
		{
			$email_config['protocol']		= $this->config->email_notification_protocol;
			$email_config['smtp_host']		= $this->config->email_notification_smtp_host;
			$email_config['smtp_port']		= $this->config->email_notification_smtp_port;
			$email_config['smtp_user']		= $this->config->email_notification_smtp_user;
			$email_config['smtp_pass']		= $this->config->email_notification_smtp_pass;
			$email_config['smtp_timeout']	= $this->config->email_notification_smtp_timeout;
		}
		
		$this->CI->email->initialize($email_config);
		$this->CI->email->set_newline("\r\n");
		$this->CI->email->clear();
		
		$this->CI->email->reply_to($this->from_email);
		
		if ( $this->from_name )
		{
			$this->CI->email->from($this->from_email, $this->from_name);
		}
		else
		{
			$this->CI->email->from($this->from_email);
		}
		
		if ( $this->to_name )
		{
			$this->CI->email->to($this->to_email, $this->to_name);
		}
		else
		{
			$this->CI->email->to($this->to_email);
		}
		
		if ( $this->cc_email )
		{
			$this->CI->email->cc($this->cc_email);
		}
		
		if ( $this->bcc_email )
		{
			$this->CI->email->bcc($this->bcc_email);
		}
		
		$this->CI->email->subject($this->email_subject);
		$this->CI->email->message($html_message);
		$this->CI->email->set_alt_message($text_message);
		
		// send the message
		$send = $this->CI->email->send();
		
		if ( ! $send )
		{
			log_message('error', get_class($this) . ': An email library error occurred');
		}
		
		return $send;
	}


}