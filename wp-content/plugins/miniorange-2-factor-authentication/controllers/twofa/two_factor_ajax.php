<?php
class mo_2f_ajax
{
	function __construct(){

		add_action( 'admin_init'  , array( $this, 'mo_2f_two_factor' ) );
	}

	function mo_2f_two_factor(){
		add_action( 'wp_ajax_mo_two_factor_ajax', array($this,'mo_two_factor_ajax') );
	}

	function mo_two_factor_ajax(){
		switch ($_POST['mo_2f_two_factor_ajax']) {
			case 'mo2f_save_email_verification':
				$this->mo2f_save_email_verification();	break;
			case 'mo2f_unlimitted_user':
				$this->mo2f_unlimitted_user();break;
			case 'mo2f_check_user_exist_miniOrange':
				$this->mo2f_check_user_exist_miniOrange();break;
			case 'mo2f_single_user':
				$this->mo2f_single_user();break;
			case 'CheckEVStatus':
				$this->CheckEVStatus();		break;
			case 'mo2f_role_based_2_factor':
				$this->mo2f_role_based_2_factor();break;
			case 'mo2f_enable_disable_twofactor':
				$this->mo2f_enable_disable_twofactor();	break;
			case 'mo2f_enable_disable_inline':
				$this->mo2f_enable_disable_inline();	break;
			case 'mo2f_shift_to_onprem':
				$this->mo2f_shift_to_onprem();break;
			case 'mo2f_save_custom_form_settings':
				$this ->mo2f_save_custom_form_settings();
				break;
		}
	}
	function mo2f_save_custom_form_settings()
	{

		$customForm = false;
		$nonce = sanitize_text_field($_POST['mo2f_nonce_save_form_settings']);

		if ( ! wp_verify_nonce( $nonce, 'mo2f-nonce-save-form-settings' ) ) {
			$error = new WP_Error();
			$error->add( 'empty_username', '<strong>' . mo2f_lt( 'ERROR' ) . '</strong>: ' . mo2f_lt( 'Invalid Request.' ) );
			//return $error;
		}
		if(isset($_POST['submit_selector']) and
			isset($_POST['email_selector']) and
			isset($_POST['authType']) and
			isset($_POST['customForm']) and
			isset($_POST['form_selector']) and

			$_POST['submit_selector']!="" and
			$_POST['email_selector']!="" and
			$_POST['customForm']!="" and
			$_POST['form_selector']!="")
		{
			$submit_selector 				= sanitize_text_field($_POST['submit_selector']);
			$form_selector					= sanitize_text_field($_POST['form_selector']);
			$email_selector 				= sanitize_text_field($_POST['email_selector']);
			$phone_selector 				= sanitize_text_field($_POST['phone_selector']);
			$authType 						= sanitize_text_field($_POST['authType']);
			$customForm 					= sanitize_text_field( $_POST['customForm']);
			$enableShortcode 				= sanitize_text_field($_POST['enableShortcode']);

			switch ($form_selector)
			{
				case '.bbp-login-form':
					update_site_option('mo2f_custom_reg_bbpress',true);
					update_site_option('mo2f_custom_reg_wocommerce',false);
					update_site_option('mo2f_custom_reg_custom',false);
					break;
				case '.woocommerce-form woocommerce-form-register':
					update_site_option('mo2f_custom_reg_bbpress',false);
					update_site_option('mo2f_custom_reg_wocommerce',true);
					update_site_option('mo2f_custom_reg_custom',false);
					break;
				default:
					update_site_option('mo2f_custom_reg_bbpress',false);
					update_site_option('mo2f_custom_reg_wocommerce',false);
					update_site_option('mo2f_custom_reg_custom',true);
			}

			update_site_option('mo2f_custom_form_name', $form_selector);
			update_site_option('mo2f_custom_email_selector', $email_selector);
			update_site_option('mo2f_custom_phone_selector', $phone_selector);
			update_site_option('mo2f_custom_submit_selector', $submit_selector);
			update_site_option('mo2f_custom_auth_type', $authType);

			update_site_option('enable_form_shortcode',$enableShortcode);
			$saved = true;
		}
		else
		{
			$submit_selector = 'NA';
			$form_selector = 'NA';
			$email_selector = 'NA';
			$authType ='NA';
			$saved = false;
		}
		$return = array(
			'authType' => $authType,
			'submit' => $submit_selector,
			'emailSelector' => $email_selector,
			'phone_selector' => $phone_selector,
			'form' => $form_selector,
			'saved' => $saved,
			'customForm' => $customForm,
			'enableShortcode' => $enableShortcode
		);

		return wp_send_json($return);
	}

	function mo2f_check_user_exist_miniOrange()
	{
		$nonce = sanitize_text_field($_POST['nonce']);

		if ( ! wp_verify_nonce( $nonce, 'checkuserinminiOrangeNonce' ) ) {
			$error = new WP_Error();
			$error->add( 'empty_username', '<strong>' . mo2f_lt( 'ERROR' ) . '</strong>: ' . mo2f_lt( 'Invalid Request.' ) );
			echo "NonceDidNotMatch";
			exit;
		}

		if(!get_option('mo2f_customerKey')){
			echo "NOTLOGGEDIN";
			exit;
		}
		$user = wp_get_current_user();
		global $Mo2fdbQueries;
		$email = $Mo2fdbQueries->get_user_detail( 'mo2f_user_email', $user->ID );
    	if($email == '' or is_null($email))
    		$email = $user->user_email;



    	if(isset($_POST['email']))
    	{
    		$email  = sanitize_text_field($_POST['email']);
    	}

    	$enduser    = new Two_Factor_Setup();
		$check_user = json_decode( $enduser->mo_check_user_already_exist( $email ), true );


		if(strcasecmp($check_user['status'], 'USER_FOUND_UNDER_DIFFERENT_CUSTOMER') == 0 ){
           echo "alreadyExist";
           exit;
	    }
	    else
	    {

	    	update_user_meta($user->ID,'mo2f_email_miniOrange',$email);
	    	echo "USERCANBECREATED";
	    	exit;
	    }

	}
function mo2f_shift_to_onprem(){

		$current_user 	= wp_get_current_user();
		$current_userID = $current_user->ID;
		$miniorangeID = get_option( 'mo2f_miniorange_admin' );
		if(is_null($miniorangeID) or $miniorangeID =='')
			$is_customer_admin = true;
		else
			$is_customer_admin = $miniorangeID == $current_userID ? true : false;
		if($is_customer_admin)
		{
			update_option('is_onprem', 1);
			update_option( 'mo2f_remember_device',0);
			wp_send_json('true');
		}
		else
		{
			$adminUser = get_user_by('id',$miniorangeID);
			$email = $adminUser->user_email;
			wp_send_json($email);
		}

	}


		function mo2f_enable_disable_twofactor(){
			$nonce = sanitize_text_field($_POST['mo2f_nonce_enable_2FA']);

			if ( ! wp_verify_nonce( $nonce, 'mo2f-nonce-enable-2FA' ) ) {
				$error = new WP_Error();
				$error->add( 'empty_username', '<strong>' . mo2f_lt( 'ERROR' ) . '</strong>: ' . mo2f_lt( 'Invalid Request.' ) );

			}

			$enable = sanitize_text_field($_POST['mo2f_enable_2fa']);
			if($enable == 'true'){
				update_option('mo2f_activate_plugin' , true);
				wp_send_json('true');
			}
			else{
				update_option('mo2f_activate_plugin' , false);
				wp_send_json('false');
			}
		}

		function mo2f_enable_disable_inline(){
			$nonce = sanitize_text_field($_POST['mo2f_nonce_enable_inline']);

			if ( ! wp_verify_nonce( $nonce, 'mo2f-nonce-enable-inline' ) ) {
				wp_send_json("error");
			}
			$enable = sanitize_text_field($_POST['mo2f_inline_registration']);
			if($enable == 'true'){
				update_site_option('mo2f_inline_registration' , 1);
				wp_send_json('true');
			}
			else{
				update_site_option('mo2f_inline_registration' , 0);
				wp_send_json('false');
			}
		}

		function mo2f_role_based_2_factor(){
			if ( !wp_verify_nonce($_POST['nonce'],'unlimittedUserNonce') ){
    			   			wp_send_json('ERROR');
    			   			return;
                        }
					    global $wp_roles;
		                if (!isset($wp_roles))
			             $wp_roles = new WP_Roles();
                        foreach($wp_roles->role_names as $id => $name) {
                        	update_option('mo2fa_'.$id, 0);
                        }

                        if(isset($_POST['enabledrole'])){
                        $enabledrole = $_POST['enabledrole'];
                         }
                         else{
                         	$enabledrole = array();
                         }
                         foreach($enabledrole as $role){
   							 update_option($role, 1);
  						}
                        wp_send_json('true');
                        return;
		 }
		function mo2f_single_user()
		{
			if(!wp_verify_nonce($_POST['nonce'],'singleUserNonce'))
			{
				echo "NonceDidNotMatch";
				exit;
			}
			else
			{
				$current_user 	= wp_get_current_user();
				$current_userID = $current_user->ID;
				$miniorangeID = get_option( 'mo2f_miniorange_admin' );
				$is_customer_admin = $miniorangeID == $current_userID ? true : false;

				if(is_null($miniorangeID) or $miniorangeID =='')
					$is_customer_admin = true;

				if($is_customer_admin)
				{
					update_option('is_onprem', 0);
					wp_send_json('true');
				}
				else
				{
					$adminUser = get_user_by('id',$miniorangeID);
					$email = $adminUser->user_email;
					wp_send_json($email);
				}

			}
		}

		function mo2f_unlimitted_user()
		{
			if(!wp_verify_nonce($_POST['nonce'],'unlimittedUserNonce'))
			{
				echo "NonceDidNotMatch";
				exit;
			}
			else
			{
				if($_POST['enableOnPremise'] == 'on')
				{
					global $wp_roles;
					if (!isset($wp_roles))
						$wp_roles = new WP_Roles();
					foreach($wp_roles->role_names as $id => $name) {
					add_site_option('mo2fa_'.$id, 1);
						if($id == 'administrator'){
							add_option('mo2fa_'.$id.'_login_url',admin_url());
						}else{
							add_option('mo2fa_'.$id.'_login_url',home_url());
						}
					}
					echo "OnPremiseActive";
					exit;
				}
				else
				{
					echo "OnPremiseDeactive";
					exit;
				}
			}
		}
		function mo2f_save_email_verification()
		{

			if(!wp_verify_nonce($_POST['nonce'],'EmailVerificationSaveNonce'))
			{
				echo "NonceDidNotMatch";
				exit;
			}
			else
			{

				$email 		= sanitize_text_field($_POST['email']);
				$currentMethod = sanitize_text_field($_POST['current_method']);
				$error 		= false;
				$user_id 	= sanitize_text_field($_POST['user_id']);
				if(MO2F_IS_ONPREM)
				{
					$twofactor_transactions = new Mo2fDB;
					$exceeded = $twofactor_transactions->check_alluser_limit_exceeded($user_id);

					if($exceeded){
						echo "USER_LIMIT_EXCEEDED";
						exit;
					}
				}
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$error = true;
				}
				if($email!='' && !$error)
				{
					global $Mo2fdbQueries;
					if($currentMethod == 'EmailVerification')
					{
						$Mo2fdbQueries->update_user_details(get_current_user_id(),array(
						'mo2f_EmailVerification_config_status'=>true,
						'mo_2factor_user_registration_status'               => 'MO_2_FACTOR_PLUGIN_SETTINGS',
						'mo2f_configured_2FA_method'=>"Email Verification",
						'mo2f_user_email'                      => $email
						));
					}
					else
					{
						$Mo2fdbQueries->update_user_details(get_current_user_id(),array(
						'mo2f_EmailVerification_config_status'=>true,
						'mo2f_user_email'                      => $email
						));

					}
					update_user_meta($user_id,'tempEmail',$email);
					echo "settingsSaved";
					exit;
				}
				else
				{
					echo "invalidEmail";
					exit;
				}

			}

		}
		function CheckEVStatus()
		{
			if(isset($_POST['txid']))
			{
				$txid = sanitize_text_field($_POST['txid']);
				$status = get_site_option($txid);
				if($status ==1 || $status ==0)
				delete_site_option($_POST['txid']);
				echo $status;
				exit();
			}
			echo "empty txid";
			exit;
		}


}

new mo_2f_ajax;
?>
