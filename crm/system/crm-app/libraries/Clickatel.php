<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Clickatel
*
* Author: Wilson Santos
*		  wsantos@santossystems.com
*
* Created:  07.02.2011
*
* Description:  Designed for Forma-CRM
*
* Requirements: PHP5 or above
*
*/

class Clickatel
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;
	
	protected $api_url;
	protected $api_id;
	protected $username;
	protected $password;
	protected $sender;

	public $errors = array();
	/**
	 * __construct
	 *
	 * @return void
	 * @author Wilson
	 **/
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->config('clickatel', TRUE);
		
		$this->api_url = $this->ci->config->item('api_url', 'clickatel');
		$this->api_id = $this->ci->config->item('api_id', 'clickatel');
		$this->username = $this->ci->config->item('username', 'clickatel');
		$this->password = $this->ci->config->item('password', 'clickatel');
		$this->sender = $this->ci->config->item('sender', 'clickatel');
		
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ci->clickatel_model, $method) )
		{
			throw new Exception('Undefined method Clickatel::' . $method . '() called');
		}

		return call_user_func_array( array($this->ci->clickatel_model, $method), $arguments);
	}

	protected function authorize(){
		$returnValue = false;
		$url = $this->api_url . '/http/auth?user=' . $this->username . '&password=' . $this->password . '&api_id=' . $this->api_id;
		$response = file($url);
		$response_array = explode(":",$response[0]);
		if($response_array[0] == "OK"){
			$returnValue = trim($response_array[1]);
		}
		else
			$this->errors[] = array('Authentication failure', $response_array[1]);
			
		return $returnValue;
	}

	protected function send_message($target, $message, $api_session_id){
		$returnValue = false;
		$url = $this->api_url . '/http/sendmsg?session_id=' . $api_session_id . '&to=' . $target . '&from=' . $this->sender . '&text=' . $message;
		$response = file($url);
		$response_array = explode(":",$response[0]);
			
		if ($response_array[0] == "ID")
			$returnValue = $response_array[1];
		else
			$this->errors[] = array('Error', $response_array[1]);
		return $returnValue;
	}
	
	public function send($target, $message){
		$returnValue = false;
		$api_session_id = $this->authorize();
		if($api_session_id !== false){
			$confirmation_code = $this->send_message($target, urlencode($message), $api_session_id);
			if($confirmation_code !== false)
				$returnValue = $confirmation_code;
		}
		return $returnValue;
	}
}