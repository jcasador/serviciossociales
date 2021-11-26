<?php

	global $mo2f_dirName;
	
	if(current_user_can( 'manage_options' )  && isset($_POST['option']))
	{
		switch($_POST['option'])
		{
			case "mo_wpns_send_query":
				wpns_handle_support_form($_POST['query_email'],$_POST['query'],$_POST['query_phone']);		break;
		}
	}

	$current_user 	= wp_get_current_user();
	$email 			= get_option("mo2f_email");
	$phone 			= get_option("mo_wpns_admin_phone");

	
	if(empty($email))
		$email 		= $current_user->user_email;

	include $mo2f_dirName . 'views'.DIRECTORY_SEPARATOR.'support.php';


	/* SUPPORT FORM RELATED FUNCTIONS */

	//Function to handle support form submit
	function wpns_handle_support_form($email,$query,$phone)
	{
        $call_setup = false;
        if(array_key_exists('2fa_setup_call',$_POST)===true){
            $time_zone = sanitize_text_field($_POST['mo_2fa_setup_call_timezone']);
            $call_date = sanitize_text_field($_POST['mo_2fa_setup_call_date']);
            $call_time = sanitize_text_field($_POST['mo_2fa_setup_call_time']);
            $call_setup = true;
        }
            $send_configuration = (isset($_POST['mo2f_send_configuration'])?$_POST['mo2f_send_configuration']:0);
            if(empty($email) || empty($query)){
			do_action('wpns_show_message',MoWpnsMessages::showMessage('SUPPORT_FORM_VALUES'),'ERROR');
			return;
		}


		$query = sanitize_text_field( $query );
		$email = sanitize_text_field( $email );
		$phone = sanitize_text_field( $phone );
		$contact_us = new MocURL();
        if($send_configuration)       
            $query = $query.MoWpnsUtility::mo_2fa_send_configuration(true);
        else
            $query = $query.MoWpnsUtility::mo_2fa_send_configuration();
        if($call_setup == false) {
            $query = $query.'<br><br>';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                do_action('wpns_show_message',MoWpnsMessages::showMessage('SUPPORT_FORM_ERROR'),'ERROR');
            } else {
		$submited = json_decode($contact_us->submit_contact_us($email, $phone, $query),true);
            }
        } else {

                $local_timezone='Asia/Kolkata';
                $call_datetime=$call_date.$call_time;
                $convert_datetime = strtotime ( $call_datetime );
                $ist_date = new DateTime(date ( 'Y-m-d H:i:s' , $convert_datetime ), new DateTimeZone($time_zone));
                $ist_date->setTimezone(new DateTimeZone($local_timezone));


                $query = $query .  '<br><br>' .'Meeting Details: '.'('.$time_zone.') '. date('d M, Y  H:i',$convert_datetime). ' [IST Time -> '. $ist_date->format('d M, Y  H:i').']'.'<br><br>';
                $submited = json_decode($contact_us->submit_contact_us( $email, $phone, $query, true),true);

		}
                if(json_last_error() == JSON_ERROR_NONE && $submited){
                        do_action('wpns_show_message',MoWpnsMessages::showMessage('SUPPORT_FORM_SENT'),'SUCCESS');
                }else{
		do_action('wpns_show_message',MoWpnsMessages::showMessage('SUPPORT_FORM_ERROR'),'ERROR');
	}
    }
