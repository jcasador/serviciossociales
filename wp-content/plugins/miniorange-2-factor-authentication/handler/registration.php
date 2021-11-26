<?php

	class RegistrationHandler
	{
		function __construct()
		{
			add_filter( 'registration_errors' , array($this, 'mo_wpns_registration_validations' ), 10, 3 );			
		}

		function mo_wpns_registration_validations( $errors, $sanitized_user_login, $user_email ) 
		{
			global $moWpnsUtility;
			
			if(get_option('mo_wpns_activate_recaptcha_for_registration'))
				$recaptchaError = $moWpnsUtility->verify_recaptcha(sanitize_text_field($_POST['g-recaptcha-response']));
			if(get_site_option('mo_wpns_enable_fake_domain_blocking')){
			if($moWpnsUtility->check_if_valid_email($user_email) && empty($recaptchaError->errors))
				$errors->add( 'blocked_email_error', __( '<strong>ERROR</strong>: Your email address is not allowed to register. Please select different email address.') );
			else if(!empty($recaptchaError->errors))
				$errors = $recaptchaError;
				
			}
			else{
				$count= get_site_option('number_of_fake_reg');
				if($moWpnsUtility->check_if_valid_email($user_email) && empty($recaptchaError->errors))
						{
							$count = $count + 1;
							update_site_option('number_of_fake_reg' ,$count );
						}
				}
					return $errors;
	
	
		}

	}
	new RegistrationHandler;