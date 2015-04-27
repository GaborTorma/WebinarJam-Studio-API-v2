<?php
require_once dirname(__FILE__) . '/apicall/apicall.php';

class WebinarJam
{
	public $api_key;
	
	public $apiCall;

	public function __construct($api_key)
    {
		$this->apiCall = new ApiCall();
		$this->api_key = $api_key;
    }

	public function Webinars()
	{
		$url = 'https://app.webinarjam.com/api/v2/webinars';
		return $this->apiCall->execute('POST', $url, array('api_key' => $this->api_key));
	}

	public function Webinar($webinar_id)
	{
		$url = 'https://app.webinarjam.com/api/v2/webinar';
		return $this->apiCall->execute('POST', $url, array('api_key' => $this->api_key,
														   'webinar_id' => $webinar_id));
	}
	
	public function Register($webinar_id, $name, $email, $schedule, $ip_address = null, $country_code = null, $phone = null)
	{
		$url = 'https://app.webinarjam.com/api/v2/register';

		$data = array('api_key' => $this->api_key,
				   'webinar_id' => $webinar_id,
				   'name' => $name,
				   'email' => $email,
				   'schedule' => $schedule);
				   
		if ($ip_address != null)
			$data['ip_address'] = $ip_address;
		
		if ($country_code != null && $phone != null)
		{
			$data['country_code'] = $country_code;
			$data['phone'] = $phone;
		}

		return $this->apiCall->execute('POST', $url, $data);
	}	
}
?>