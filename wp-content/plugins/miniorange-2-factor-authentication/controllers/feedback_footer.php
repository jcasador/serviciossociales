<?php
  
  	global $moWpnsUtility,$mo2f_dirName;

  	if(current_user_can( 'manage_options' )  && isset($_POST['option']))
	{
		switch($_POST['option'])
		{
			case "mo_wpns_send_query":
				wpns_handle_feedback_footer_form($_POST['query_email'],$_POST['query'],$_POST['query_phone']);		break;
		}
	}

	$current_user 	= wp_get_current_user();
	$email 			= get_option("mo2f_email");
	$phone 			= get_option("mo_wpns_admin_phone");

	
	/* SUPPORT FORM RELATED FUNCTIONS */

	//Function to handle support form submit
	function wpns_handle_feedback_footer_form($email,$query,$phone)
	{
		
		if( empty($email) || empty($query) )
		{
			do_action('wpns_show_message',MoWpnsMessages::showMessage('SUPPORT_FORM_VALUES'),'ERROR');
			return;
		}


		$query = sanitize_text_field( $query );
		$email = sanitize_text_field( $email );
		$phone = sanitize_text_field( $phone );
		$contact_us = new MocURL();
		$submited = json_decode($contact_us->submit_contact_us($email, $phone, $query),true);

		if(json_last_error() == JSON_ERROR_NONE && $submited) 
		{
			do_action('wpns_show_message',MoWpnsMessages::showMessage('SUPPORT_FORM_SENT'),'SUCCESS');
			return;
		}
			
		do_action('wpns_show_message',MoWpnsMessages::showMessage('SUPPORT_FORM_ERROR'),'ERROR');
	}

  include $mo2f_dirName . 'views'.DIRECTORY_SEPARATOR.'feedback_footer.php';