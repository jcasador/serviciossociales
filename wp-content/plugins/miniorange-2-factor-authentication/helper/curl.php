<?php

class MocURL
{

	public static function create_customer($email, $company, $password, $phone = '', $first_name = '', $last_name = '')
	{
		$url = MoWpnsConstants::HOST_NAME . '/moas/rest/customer/add';
		$fields = array (
			'companyName' 	 => $company,
			'areaOfInterest' => 'WordPress 2 Factor Authentication Plugin',
			'productInterest' => 'API_2FA',
			'firstname' 	 => $first_name,
			'lastname' 		 => $last_name,
			'email' 		 => $email,
			'phone' 		 => $phone,
			'password' 		 => $password
		);
		$json = json_encode($fields);
		$response = self::callAPI($url, $json);
		return $response;
	}
	
	public static function get_customer_key($email, $password) 
	{
		$url 	= MoWpnsConstants::HOST_NAME. "/moas/rest/customer/key";
		$fields = array (
					'email' 	=> $email,
					'password'  => $password
				);
		$json = json_encode($fields);
		$response = self::callAPI($url, $json);

		return $response;
	}
	
	function submit_contact_us( $q_email, $q_phone, $query,  $call_setup=false)
	{
		$current_user = wp_get_current_user();
		$url    = MoWpnsConstants::HOST_NAME . "/moas/rest/customer/contact-us";
		
		$is_nc_with_1_user          = MoWpnsUtility::get_mo2f_db_option('mo2f_is_NC', 'get_option') && MoWpnsUtility::get_mo2f_db_option('mo2f_is_NNC', 'get_option');
		$is_ec_with_1_user          = ! MoWpnsUtility::get_mo2f_db_option('mo2f_is_NC', 'get_option');
        	$onprem = MO2F_IS_ONPREM ?'O':'C';

		$customer_feature = "";

		if ( $is_ec_with_1_user ) {
			$customer_feature = "V1";
		}else if ( $is_nc_with_1_user ) {
			$customer_feature = "V3";
		}
		global $moWpnsUtility;
		if($call_setup)
			$query = '[Call Request - WordPress 2 Factor Authentication Plugin: ' .$onprem.$customer_feature . ' - V '.MO2F_VERSION.'- Ticket Id:'.$moWpnsUtility->getFeatureStatus().']: ' . $query;
		else
		$query = '[WordPress 2 Factor Authentication Plugin: ' .$onprem.$customer_feature . ' - V '.MO2F_VERSION.'- Ticket Id:'.$moWpnsUtility->getFeatureStatus().']: ' . $query;
		
		$fields = array(
					'firstName'	=> $current_user->user_firstname,
					'lastName'	=> $current_user->user_lastname,
					'company' 	=> $_SERVER['SERVER_NAME'],
					'email' 	=> $q_email,
					'ccEmail' 	=> '2fasupport@xecurify.com',
					'phone'		=> $q_phone,
					'query'		=> $query
				);
		$field_string = json_encode( $fields );
		$response = self::callAPI($url, $field_string);
		
		return true;
	}

	function lookupIP($ip)
	{
		$url 	= MoWpnsConstants::HOST_NAME. "/moas/rest/security/iplookup";
		$fields = array (
					'ip' => $ip
				);
		$json = json_encode($fields);
		$response = self::callAPI($url, $json);
		return $response;
	}
	//CHECK
	function send_otp_token($auth_type, $phone, $email)
	{
		
		$url 		 = MoWpnsConstants::HOST_NAME . '/moas/api/auth/challenge';
		$customerKey = MoWpnsConstants::DEFAULT_CUSTOMER_KEY;
		$apiKey 	 = MoWpnsConstants::DEFAULT_API_KEY;

		$fields  	 = array(
							'customerKey' 	  => $customerKey,
							'email' 	  	  => $email,
							'phone' 	  	  => $phone,
							'authType' 	  	  => $auth_type,
							'transactionName' => 'miniOrange 2-Factor'
						);
		$json 		 = json_encode($fields);
		$authHeader  = $this->createAuthHeader($customerKey,$apiKey);
		$response 	 = self::callAPI($url, $json, $authHeader);
		return $response;
	}

	function validate_recaptcha($ip,$response)
	{
		$url 		 = MoWpnsConstants::RECAPTCHA_VERIFY;
		$json		 = "";
		$fields 	 = array(
							'response' => $response,
							'secret'   => get_option('mo_wpns_recaptcha_secret_key'),
							'remoteip' => $ip
						);
		foreach($fields as $key=>$value) { $json .= $key.'='.$value.'&'; }
		rtrim($json, '&');
		$response 	 = self::callAPI($url, $json, null);
		return $response;
	}

	function validate_otp_token($transactionId,$otpToken)
	{
		$url 		 = MoWpnsConstants::HOST_NAME . '/moas/api/auth/validate';
		$customerKey = MoWpnsConstants::DEFAULT_CUSTOMER_KEY;
		$apiKey 	 = MoWpnsConstants::DEFAULT_API_KEY;

		$fields 	 = array(
						'txId'  => $transactionId,
						'token' => $otpToken,
					 );

		$json 		 = json_encode($fields);
		$authHeader  = $this->createAuthHeader($customerKey,$apiKey);
		$response    = self::callAPI($url, $json, $authHeader);
		return $response;
	}
	
	function check_customer($email)
	{
		$url 	= MoWpnsConstants::HOST_NAME . "/moas/rest/customer/check-if-exists";
		$fields = array(
					'email' 	=> $email,
				);
		$json     = json_encode($fields);
		$response = self::callAPI($url, $json);
		return $response;
	}
	
	function mo_wpns_forgot_password()
	{
	
		$url 		 = MoWpnsConstants::HOST_NAME . '/moas/rest/customer/password-reset';
		$email       = get_option('mo2f_email');
		$customerKey = get_option('mo2f_customerKey');
		$apiKey 	 = get_option('mo2f_api_key');
	
		$fields 	 = array(
						'email' => $email
					 );
	
		$json 		 = json_encode($fields);
		$authHeader  = $this->createAuthHeader($customerKey,$apiKey);
		$response    = self::callAPI($url, $json, $authHeader);
		return $response;
	}

	function send_notification($toEmail,$subject,$content,$fromEmail,$fromName,$toName)
	{
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		$headers .= 'From: '.$fromName.'<'.$fromEmail.'>' . "\r\n";

		mail($toEmail,$subject,$content,$headers);

		return json_encode(array("status"=>'SUCCESS','statusMessage'=>'SUCCESS'));
	}

	//added for feedback

    function send_email_alert($email,$phone,$message,$feedback_option){
    	    global $moWpnsUtility;
	    global $user;
        $url = MoWpnsConstants::HOST_NAME . '/moas/api/notify/send';
        $customerKey = MoWpnsConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 = MoWpnsConstants::DEFAULT_API_KEY;
        $fromEmail			= 'no-reply@xecurify.com';
        if ($feedback_option == 'mo_wpns_skip_feedback') 
        {
        	$subject            = "Deactivate [Feedback Skipped]: WordPress miniOrange 2-Factor Plugin";
        }
        elseif ($feedback_option == 'mo_wpns_feedback') 
        {
        	$subject            = "Feedback: WordPress miniOrange 2-Factor Plugin - ". $email;;
        }
        elseif ($feedback_option == 'mo_wpns_rating') 
        {
        	$subject            = "Feedback: WordPress miniOrange 2-Factor Plugin - ". $email;;
        }

        $user         = wp_get_current_user();
		
		$is_nc_with_1_user          = MoWpnsUtility::get_mo2f_db_option('mo2f_is_NC', 'get_option') && MoWpnsUtility::get_mo2f_db_option('mo2f_is_NNC', 'get_option');
		$is_ec_with_1_user          = ! MoWpnsUtility::get_mo2f_db_option('mo2f_is_NC', 'get_option');
        $onprem = MO2F_IS_ONPREM ? 'O':'C';

		$customer_feature = "";


		if ( $is_ec_with_1_user ) {
			$customer_feature = "V1";
		}else if ( $is_nc_with_1_user ) {
			$customer_feature = "V3";
		}

		$query = '[WordPress 2 Factor Authentication Plugin: ' .$onprem.$customer_feature . ' - V '.MO2F_VERSION.']: ' . $message;


        $content='<div >Hello, <br><br>Ticket ID:'.$moWpnsUtility->getFeatureStatus().'<br><br>First Name :'.$user->user_firstname.'<br><br>Last  Name :'.$user->user_lastname.'   <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>Phone Number :'.$phone.'<br><br>Email :<a href="mailto:'.$email.'" target="_blank">'.$email.'</a><br><br>Query :'.$query.'</div>';


        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,
                'fromName' 		=> 'Xecurify',
                'toEmail' 		=> '2fasupport@xecurify.com',
                'toName' 		=> '2fasupport@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
        );
        $field_string = json_encode($fields);
        $authHeader  = $this->createAuthHeader($customerKey,$apiKey);
        $response = self::callAPI($url, $field_string,$authHeader);
        return $response;

    }


	private static function createAuthHeader($customerKey, $apiKey) {
		$currentTimestampInMillis = round(microtime(true) * 1000);
		$currentTimestampInMillis = number_format($currentTimestampInMillis, 0, '', '');

		$stringToHash = $customerKey . $currentTimestampInMillis . $apiKey;
		$authHeader = hash("sha512", $stringToHash);

		$header = array (
			"Content-Type: application/json",
			"Customer-Key: $customerKey",
			"Timestamp: $currentTimestampInMillis",
			"Authorization: $authHeader"
		);
		return $header;
	}


	private static function callAPI($url, $json_string, $headers = array("Content-Type: application/json")) {
		//For testing (0, false)
		//For Production (2, true)
		
		$sslhost=2;
		$sslpeer=false;
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, $sslhost );

		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, $sslpeer );  
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		if(!is_null($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
		$content = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'Request Error:' . curl_error($ch);
			exit();
		}

		curl_close($ch);
		return $content;
	}
}
