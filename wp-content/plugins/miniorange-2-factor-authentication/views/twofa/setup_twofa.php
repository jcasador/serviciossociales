<?php
	$user   = wp_get_current_user();
    global $Mo2fdbQueries;
    $mo2f_second_factor = $Mo2fdbQueries->get_user_detail('mo2f_configured_2FA_method',$user->ID);
    
    if($mo2f_second_factor != 'OTP Over Telegram' and $mo2f_second_factor != 'OTP Over Whatsapp')
    $mo2f_second_factor = mo2f_get_activated_second_factor( $user );
    
    

	$is_customer_admin_registered = get_option( 'mo_2factor_admin_registration_status' );
	$configured_2FA_method        = $Mo2fdbQueries->get_user_detail( 'mo2f_configured_2FA_method', $user->ID );
    if ( $mo2f_second_factor == 'GOOGLE AUTHENTICATOR' ) {
		$app_type = get_user_meta( $user->ID, 'mo2f_external_app_type', true );

		if ( $app_type == 'Google Authenticator' ) {
			$selectedMethod = 'Google Authenticator';
		} else if ( $app_type == 'Authy Authenticator' ) {
			$selectedMethod = 'Authy Authenticator';
		} else {
			$selectedMethod = 'Google Authenticator';
			update_user_meta( $user->ID, 'mo2f_external_app_type', $selectedMethod );
		}
		$testMethod=$selectedMethod;
	} else {
		$selectedMethod = mo2f_decode_2_factor( $mo2f_second_factor, "servertowpdb" );
		$testMethod=$selectedMethod;
	}
				
	if($testMethod=='NONE'){
		$testMethod = "Not Configured"; 
	}
	if ( $selectedMethod != 'NONE' and !MO2F_IS_ONPREM and $selectedMethod != 'OTP Over Telegram' and $selectedMethod != 'OTP Over Whatsapp') {
		$Mo2fdbQueries->update_user_details( $user->ID, array(
			'mo2f_configured_2FA_method'                                         => $selectedMethod,
			'mo2f_' . str_replace( ' ', '', $selectedMethod ) . '_config_status' => true
		) );
		update_option('mo2f_configured_2_factor_method', $selectedMethod);
	    
    }

	if ( $configured_2FA_method == "OTP Over SMS" ) {
		update_option( 'mo2f_show_sms_transaction_message', 1 );
	} else {
		update_option( 'mo2f_show_sms_transaction_message', 0 );
	} 
	$is_customer_admin          = current_user_can( 'manage_options' );
	$can_display_admin_features = ! $is_customer_admin_registered || $is_customer_admin ? true : false;

	$is_customer_registered = $Mo2fdbQueries->get_user_detail( 'user_registration_with_miniorange', $user->ID ) == 'SUCCESS' ? true : false;
	if ( get_user_meta( $user->ID, 'configure_2FA', true ) ) {

		$current_selected_method = get_user_meta( $user->ID, 'mo2f_2FA_method_to_configure', true );
        echo '<div class="mo_wpns_setting_layout">';
			mo2f_show_2FA_configuration_screen( $user, $current_selected_method );
        echo '</div>';
	} else if ( get_user_meta( $user->ID, 'test_2FA', true ) ) {
		$current_selected_method = get_user_meta( $user->ID, 'mo2f_2FA_method_to_test', true );
        echo '<div class="mo_wpns_setting_layout">';
			mo2f_show_2FA_test_screen( $user, $current_selected_method );
        echo '</div>';
	}else if ( get_user_meta( $user->ID, 'register_account_popup', true ) && $can_display_admin_features ) {
        display_customer_registration_forms( $user ); 
	} else {
		$is_NC = MoWpnsUtility::get_mo2f_db_option('mo2f_is_NC', 'get_option');
		$free_plan_existing_user = array(
			"Email Verification",
			"Security Questions",
            "OTP Over SMS",
            "OTP Over Email",
			"miniOrange QR Code Authentication",
			"miniOrange Soft Token",
			"miniOrange Push Notification",
			"Google Authenticator",
			"Authy Authenticator",
            "OTP Over Telegram",
            "OTP Over Whatsapp"       


		);

		$free_plan_new_user = array(
			"Google Authenticator",
	        "Security Questions",
    		"OTP Over SMS",
            "OTP Over Email",
    		"miniOrange Soft Token",
			"miniOrange QR Code Authentication",
			"miniOrange Push Notification",
            "OTP Over Telegram",
            "OTP Over Whatsapp"        

		);

		$standard_plan_existing_user = array(
		        "",
			"OTP Over Email",
			"OTP Over SMS and Email"
		);

        $standard_plan_new_user = array(
		        "",
			"Email Verification",
			"OTP Over SMS",
			"OTP Over Email",
			"OTP Over SMS and Email",
			"Authy Authenticator"
		);

		$premium_plan = array(
			"Hardware Token"
		);

        if(MO2F_IS_ONPREM)
        {
            $free_plan_existing_user = array(
            "Email Verification",
            "Security Questions",
            "OTP Over SMS",
            "OTP Over Email",
            "Google Authenticator",
            "miniOrange QR Code Authentication",
            "miniOrange Soft Token",
            "miniOrange Push Notification",
            "OTP Over Telegram",
            "OTP Over Whatsapp"        
   
            );

            $free_plan_new_user = array(
            "Google Authenticator",
            "Security Questions",
            "OTP Over SMS",
            "OTP Over Email",
            "miniOrange QR Code Authentication",
            "miniOrange Soft Token",
            "miniOrange Push Notification",
            "OTP Over Telegram",
            "OTP Over Whatsapp"        

            );
            $premium_plan = array(
            "Hardware Token",
             "Authy Authenticator"
             
            );  
            $standard_plan_existing_user = array(
                "",
            "OTP Over SMS and Email",
            );
            $standard_plan_new_user =  array(
                "",
            "Email Verification",
            "OTP Over SMS and Email"        
            );  
        }

		$free_plan_methods_existing_user     = array_chunk( $free_plan_existing_user, 3 );
		$free_plan_methods_new_user          = array_chunk( $free_plan_new_user, 3 );
		$standard_plan_methods_existing_user = array_chunk( $standard_plan_existing_user, 3 );
		$standard_plan_methods_new_user      = array_chunk( $standard_plan_new_user, 3 );

		$premium_plan_methods_existing_user  = array_chunk( array_merge( $standard_plan_existing_user, $premium_plan) , 3 );
		$premium_plan_methods_new_user       = array_chunk( array_merge( $standard_plan_new_user, $premium_plan ), 3 );
        $showOTP=FALSE;
        if(MO2F_IS_ONPREM)
        {
	        $selectedMethod = $Mo2fdbQueries->get_user_detail( 'mo2f_configured_2FA_method', $user->ID );
            $is_customer_registered = true;
            $testMethod             = $selectedMethod;
            if($selectedMethod == '')
            {
                $selectedMethod = 'NONE';
                $testMethod     = 'Not Configured'; 
            }
			
			if($selectedMethod=="Google Authenticator"){
                $currentTimeSlice = floor(time() / 30);
				include_once $mo2f_dirName . DIRECTORY_SEPARATOR. 'handler'.DIRECTORY_SEPARATOR. 'twofa' . DIRECTORY_SEPARATOR . 'gaonprem.php';
				$gauth_obj= new Google_auth_onpremise();
				$secret= $gauth_obj->mo_GAuth_get_secret($user->ID);
                $i = get_option('mo2f_time_slice',0);
				$otpcode = $gauth_obj->getCode($secret, $currentTimeSlice + $i);
				$showOTP=TRUE;
			}        

        } 
        ?>

        <div class="mo_wpns_setting_layout">
            <div>
                <div>
                    <a class="mo2f_view_free_plan_auth_methods" onclick="show_free_plan_auth_methods()">
                        <img src="<?php echo plugins_url( 'includes/images/right-arrow.png"', dirname(dirname(__FILE__ ))); ?>"
                             class="mo2f_2factor_heading_images" style="margin-top: 2px;"/>
                        <p class="mo2f_heading_style" style="padding:0px;"><?php echo mo2f_lt( 'Authentication methods' ); ?>
	                        <?php if ( $can_display_admin_features ) { ?>
                                <span style="color:limegreen">( <?php echo mo2f_lt( 'Current Plan' ); ?> )</span>
	                        <?php } ?>
	                        <?php if($showOTP){?>
                                <span style="color:black">[ <?php echo mo2f_lt( 'Current OTP: ' ). $otpcode; ?> (<span style="color:blue" onclick="window.location.reload();">Refresh</span>)] </span>
	                        <?php } ?>

                            <button class="btn btn-primary btn-large" id="test" style="float:right; margin-right: 20px; height: 36px" onclick="testAuthenticationMethod('<?php echo $selectedMethod; ?>');"
		                        <?php echo $is_customer_registered && ( $selectedMethod != 'NONE' ) ? "" : " disabled "; ?>>Test : <?php echo $testMethod;?>
                            </button>


                        
                            <?php
                            if((!get_user_meta($userID, 'mo_backup_code_generated', true) || ($backup_codes_remaining == 5 && !get_user_meta($userID, 'mo_backup_code_downloaded', true))) && $mo2f_two_fa_method != ''){
                            ?>
                                <button class="btn btn-primary btn-large" id="mo_2f_generate_codes" style="float:right; margin-right: 3%; height: 36px">Get backup codes
                                </button>
                            <?php }
                            ?>
                            
                            
                        </p>
                    </a>
					

                </div>
				<?php 
    				// if ( in_array( $selectedMethod, array(
    					// "Google Authenticator",
    					// "miniOrange Soft Token",
    					// "Authy Authenticator",
         //                "Security Questions",
         //                "miniOrange Push Notification",
         //                "miniOrange QR Code Authentication"
    				// ) ) ) { 
                        ?>
                        <?php if(current_user_can('administrator')){ ?>
                        <div style="float:right;">
                            <form name="f" method="post" action="" id="mo2f_enable_2FA_on_login_page_form">
                                <input type="hidden" name="option" value="mo2f_enable_2FA_on_login_page_option"/>
    							<input type="hidden" name="mo2f_enable_2FA_on_login_page_option_nonce"
    							value="<?php echo wp_create_nonce( "mo2f-enable-2FA-on-login-page-option-nonce" ) ?>"/>

                                <input type="checkbox" id="mo2f_enable_2fa_prompt_on_login_page"
                                       name="mo2f_enable_2fa_prompt_on_login_page" 
                                       value="1" <?php checked( MoWpnsUtility::get_mo2f_db_option('mo2f_enable_2fa_prompt_on_login_page', 'get_option') == 1 );

    							if (!current_user_can('administrator') && ! in_array( $Mo2fdbQueries->get_user_detail( 'mo_2factor_user_registration_status', $user->ID ), array(
    								'MO_2_FACTOR_PLUGIN_SETTINGS',
    								'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'
    							) ) ) {
    								echo 'disabled';
    							} 
                                ?> onChange="document.getElementById('mo2f_enable_2fa_prompt_on_login_page').form.submit()"/>
    							<?php echo mo2f_lt( 'Enable 2FA prompt on the WP Login Page' ); ?>
                            </form>
                        </div>
                        
                    <?php 
                ?>
               <br>
               <?php
                            $EmailTransactions  = MoWpnsUtility::get_mo2f_db_option('cmVtYWluaW5nT1RQ', 'site_option');
                            $EmailTransactions  = $EmailTransactions? $EmailTransactions : 0;
                            $SMSTransactions    = get_site_option('cmVtYWluaW5nT1RQVHJhbnNhY3Rpb25z')?get_site_option('cmVtYWluaW5nT1RQVHJhbnNhY3Rpb25z'):0; 
                            $color_tras_sms = $SMSTransactions <= 2 ? 'red' : '#f3dbdb';
                            $color_tras_email = $EmailTransactions <= 2 ? 'red' : '#f3dbdb'; 
                            ?>
                <?php
                }

                 echo mo2f_create_2fa_form( $user, "free_plan", $is_NC ? $free_plan_methods_new_user : $free_plan_methods_existing_user, $can_display_admin_features ); ?>
            </div>

            <hr>
			<?php if ( $can_display_admin_features ) { ?>
                <div>
                   <span id="mo2f_premium_plan"> <a class="mo2f_view_premium_plan_auth_methods" onclick="show_premium_auth_methods()">
                        <img src="<?php echo plugins_url( 'includes/images/right-arrow.png"', dirname(dirname(__FILE__))); ?>"
                             class="mo2f_2factor_heading_images"/>
                        <p class="mo2f_heading_style"><?php echo mo2f_lt( 'Premium plan - Authentication methods' ); ?>
                                *</p></a></span>
					<?php echo mo2f_create_2fa_form( $user, "premium_plan", $is_NC ? $premium_plan_methods_new_user : $premium_plan_methods_existing_user ); ?>

                </div>
                <hr>
                <br>
                <p><?php if(current_user_can('administrator')){ ?>
                    * <?php echo mo2f_lt( 'These authentication methods are available in the STANDARD and PREMIUM plans' ); ?>
                    . <a
                            href="admin.php?page=mo_2fa_upgrade"><?php echo mo2f_lt( 'Click here' ); ?></a> <?php echo mo2f_lt( 'to learn more' ) ?>
                    .</p>
				<?php }} ?>
                <form name="f" method="post" action="" id="mo2f_2factor_test_authentication_method_form">
                    <input type="hidden" name="option" value="mo_2factor_test_authentication_method"/>
                    <input type="hidden" name="mo2f_configured_2FA_method_test" id="mo2f_configured_2FA_method_test"/>
					<input type="hidden" name="mo_2factor_test_authentication_method_nonce"
							value="<?php echo wp_create_nonce( "mo-2factor-test-authentication-method-nonce" ) ?>"/>
                </form>
                <form name="f" method="post" action="" id="mo2f_2factor_resume_flow_driven_setup_form">
                    <input type="hidden" name="option" value="mo_2factor_resume_flow_driven_setup"/>
					<input type="hidden" name="mo_2factor_resume_flow_driven_setup_nonce"
							value="<?php echo wp_create_nonce( "mo-2factor-resume-flow-driven-setup-nonce" ) ?>"/>
                </form>

                
                <form name="f" method="post" action="" id="mo2f_2factor_generate_backup_codes">
                    <input type="hidden" name="option" value="mo2f_2factor_generate_backup_codes"/>
                    <input type="hidden" name="mo_2factor_generate_backup_codes_nonce"
                            value="<?php echo wp_create_nonce( "mo-2factor-generate-backup-codes-nonce" ) ?>"/>
                </form>              


        </div>
         <div id="EnterEmailCloudVerification" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
            <!--    <span class="close">&times;</span>  -->
                <div class="modal-header">
                    <h3 class="modal-title" style="text-align: center; font-size: 20px; color: #20b2aa">Email Address for miniOrange</h3><span id="closeEnterEmailCloud" class="modal-span-close">X</span>
                </div>
                <div class="modal-body" style="height: auto">
                    <h2 style="color: red;">The email associated with your account is already registered in miniOrange. Please Choose another email.</h2>
                    <h2><i>Enter your Email:&nbsp;&nbsp;&nbsp;  <input type ='email' id='emailEnteredCloud' name='emailEnteredCloud' size= '40' required value="<?php echo $email;?>"/></i></h2> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="mo_wpns_button mo_wpns_button1 modal-button" id="save_entered_email_cloud">Save</button>
                </div>
            </div>
        </div>
        <div id="EnterEmail" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
            <!--    <span class="close">&times;</span>  -->
                <div class="modal-header">
                    <h3 class="modal-title" style="text-align: center; font-size: 20px; color: #20b2aa">Email Address for OTP</h3><span id="closeEnterEmail" class="modal-span-close">X</span>
                </div>
                <div class="modal-body" style="height: auto">
                    <h2><i>Enter your Email:&nbsp;&nbsp;&nbsp;  <input type ='email' id='emailEntered' name='emailEntered' size= '40' required value="<?php echo $email;?>"/></i></h2> 
                </div>
                <div class="modal-footer">
                    <input type="text" id="current_method" hidden value=""> 
                    <button type="button" class="mo_wpns_button mo_wpns_button1 modal-button" id="save_entered_email">Save</button>
                </div>
            </div>
        </div>

        <div id="mo2f_cloud" class = "modal" style="display: none;">
            <div id="mo2f_cloud_modal" class="modal-content" style="width: 30%;overflow: hidden;" >

            <div class="modal-header">
               <h3 class="modal-title" style="text-align: center; font-size: 20px; color: #2980b9">
                    Are you sure you want to do that?
                </h3>
            </div>
               
            <div class="modal-body" style="height: auto;background-color: beige;">
                
            <div style="text-align: center;">

                 <?php 
                $user_id = get_current_user_id();
                global $Mo2fdbQueries;
                $currentMethod = $Mo2fdbQueries->get_user_detail( 'mo2f_configured_2FA_method', $user_id );
                if($currentMethod)
                {}
                ?>
               
                <br>
                <h4 style="color: red;">You need to reconfigure second-factor by registering in miniOrange.</h4>
                <h4 style="color: red;">It will be available for one user in free plan.</h4>

                </div></div>
            <div class="modal-footer">
                <button type="button" class="mo_wpns_button mo_wpns_button1 modal-button" style="width: 30%;background-color:#61ace5;" id="ConfirmCloudButton1">Confirm</button>
                <button type="button" class="mo_wpns_button mo_wpns_button1 modal-button" style="width: 30%;background-color:#ff4168;" id="closeConfirmCloud1">Cancel</button>

            </div>
            </div>
        </div>
  
	<script>
        jQuery('#closeConfirmCloud1').click(function(){
             jQuery('#mo2f_cloud').css('display', 'none');
               
        });
        jQuery('#ConfirmCloudButton1').click(function(){
            document.getElementById('mo2f_cloud').checked = false;
            document.getElementById('mo2f_cloud_modal').style.display = "none";
             var nonce = '<?php echo wp_create_nonce("singleUserNonce");?>';
             var data = {
                        'action'                    : 'mo_two_factor_ajax',
                        'mo_2f_two_factor_ajax'     : 'mo2f_single_user',
                        'nonce' :  nonce
                        
                    };
                jQuery.post(ajaxurl, data, function(response) {
                        if(response == 'true')
                        {
                            location.reload(true);                     

                        }
                        else
                        {
                            jQuery('#mo2f_cloud').css('display', 'none');  
                             error_msg("<b>You are not authorized to perform this action</b>. Only <b>\"+response+\"</b> is allowed. For more details contact miniOrange.");
                        }
                });
            
        });
        
        jQuery('#test').click(function(){
                jQuery("#test").attr("disabled", true);
            });
           
            jQuery('#closeEnterEmailCloud').click(function(){
                jQuery('#EnterEmailCloudVerification').css('display', 'none');
            });
            jQuery('#closeEnterEmail').click(function(){
                jQuery('#EnterEmail').css('display', 'none');
                        });
            var emailinput = document.getElementById("emailEntered");
            emailinput.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("save_entered_email").click();
                }   
            });
            jQuery('#save_entered_email').click(function(){
                var email   = jQuery('#emailEntered').val();
                var nonce   = '<?php echo wp_create_nonce('EmailVerificationSaveNonce');?>';
                var user_id = '<?php echo get_current_user_id();?>';
                var current_method = jQuery('#current_method').val();
                            
                if(email != '')
                {
                    var data = {
                    'action'                        : 'mo_two_factor_ajax',
                    'mo_2f_two_factor_ajax'         : 'mo2f_save_email_verification', 
                    'nonce'                         : nonce,
                    'email'                         : email,
                    'user_id'                       : user_id,
                    'current_method'                : current_method
                    };
                    jQuery.post(ajaxurl, data, function(response) {    
                            var response = response.replace(/\s+/g,' ').trim();
                            if(response=="settingsSaved")
                            {
                                var method = jQuery('#current_method').val();
                                
                                jQuery('#mo2f_configured_2FA_method_free_plan').val(method);
                                jQuery('#mo2f_selected_action_free_plan').val('select2factor');
                                jQuery('#mo2f_save_free_plan_auth_methods_form').submit();
                            }
                            else if(response == "NonceDidNotMatch")
                            {
                                error_msg("An unknown error has occured.");
                            }else if(response=="USER_LIMIT_EXCEEDED"){
                                jQuery('#EnterEmail').css('display', 'none');
                                error_msg(" Your limit of 3 users has exceeded. Please upgrade to premium plans for more users.");
                            }
                            else
                            {
                                error_msg(" Invalid Email.");

                            }    
                            close_modal();
                        });
                }

            });

            jQuery('#mo_2f_generate_codes').click(function(){
                jQuery("#mo2f_2factor_generate_backup_codes").submit();
                jQuery("#mo2f_free_plan_auth_methods").slideToggle(1000); 
            });

            function configureOrSet2ndFactor_free_plan(authMethod, action, cloudswitch=null,allowed=null) {
                var is_onprem       = '<?php echo MO2F_IS_ONPREM;?>';
                

                <?php
                    global $Mo2fdbQueries;
                    $current_user = wp_get_current_user();
                    $is_user_registered =  $Mo2fdbQueries->get_user_detail( 'mo2f_user_email', $current_user->ID ) ? true : false;

                ?>
                var is_user_registered = '<?php echo $is_user_registered; ?>';
                
                    
                if((is_onprem == 0 || authMethod=='miniOrangeSoftToken'|| authMethod=='miniOrangeQRCodeAuthentication'|| authMethod=='miniOrangePushNotification') && is_user_registered == 0)
                {
                     var nonce = '<?php echo wp_create_nonce("checkuserinminiOrangeNonce");?>';
                     var data = {
                                'action'                    : 'mo_two_factor_ajax',
                                'mo_2f_two_factor_ajax'     : 'mo2f_check_user_exist_miniOrange',
                                'nonce' :  nonce
                                
                            };
                        jQuery.post(ajaxurl, data, function(response) {
                        if(response == 'alreadyExist')
                        {
                            jQuery('#EnterEmailCloudVerification').css('display', 'block');
                            jQuery('.modal-content').css('width', '35%');

                            jQuery('#save_entered_email_cloud').click(function(){

                                jQuery('#EnterEmailCloudVerification').css('display', 'none');
                                var nonce = '<?php echo wp_create_nonce("checkuserinminiOrangeNonce");?>';
                                var email   = jQuery('#emailEnteredCloud').val();
                                
                                var data = {
                                    'action'                    : 'mo_two_factor_ajax',
                                    'mo_2f_two_factor_ajax'     : 'mo2f_check_user_exist_miniOrange',
                                    'email'                     : email,
                                    'nonce' :  nonce
                                    
                                };

                                jQuery.post(ajaxurl, data, function(response) {

                                    if(response == 'alreadyExist')
                                    {

                                        jQuery('#EnterEmailCloudVerification').css('display', 'block');
                                        jQuery('.modal-content').css('width', '35%');
                                    }
                                    else if(response =="USERCANBECREATED")
                                    {
                                           
                                        jQuery('#mo2f_configured_2FA_method_free_plan').val(authMethod);
                                        jQuery('#mo2f_selected_action_free_plan').val(action);
                                        jQuery('#mo2f_save_free_plan_auth_methods_form').submit();

                                    }
                                });

                            });
   
                        }
                        else if(response =="USERCANBECREATED")
                        {
                               
                            jQuery('#mo2f_configured_2FA_method_free_plan').val(authMethod);
                            jQuery('#mo2f_selected_action_free_plan').val(action);
                            jQuery('#mo2f_save_free_plan_auth_methods_form').submit();

                        }
                        else if(response == "NOTLOGGEDIN")
                        {
                            jQuery('#mo2f_configured_2FA_method_free_plan').val(authMethod);
                            jQuery('#mo2f_selected_action_free_plan').val(action);
                            jQuery('#mo2f_save_free_plan_auth_methods_form').submit();
                        }else{
                            
                        }

                    });
                }
                else
                {
                if(authMethod == 'EmailVerification' || authMethod == 'OTPOverEmail')
                {
                    var is_registered   = '<?php echo $email_registered;?>';
                    jQuery('#current_method').val(authMethod);

                    if(is_onprem == 1 && is_registered!=0 && action != 'select2factor')
                    {
                        jQuery('#EnterEmail').css('display', 'block');
                        jQuery('.modal-content').css('width', '35%');
                    }
                    else
                    {
                        
                        jQuery('#mo2f_configured_2FA_method_free_plan').val(authMethod);
                        jQuery('#mo2f_selected_action_free_plan').val(action);
                        jQuery('#mo2f_save_free_plan_auth_methods_form').submit();       
                    }
                } 
                else
                {

                    jQuery('#mo2f_configured_2FA_method_free_plan').val(authMethod);
                    jQuery('#mo2f_selected_action_free_plan').val(action);
                    jQuery('#mo2f_save_free_plan_auth_methods_form').submit();
         

                    
                    }
                
                }            
            }

            function testAuthenticationMethod(authMethod) {
                jQuery('#mo2f_configured_2FA_method_test').val(authMethod);
                jQuery('#loading_image').show();

                jQuery('#mo2f_2factor_test_authentication_method_form').submit();
            }

            function resumeFlowDrivenSetup() {
                jQuery('#mo2f_2factor_resume_flow_driven_setup_form').submit();
            }


            function show_free_plan_auth_methods() {
                jQuery("#mo2f_free_plan_auth_methods").slideToggle(1000);                
            }


            function show_premium_auth_methods() {
                jQuery("#mo2f_premium_plan_auth_methods").slideToggle(1000);
            }

            jQuery("#how_to_configure_2fa").hide();

            function show_how_to_configure_2fa() {
                jQuery("#how_to_configure_2fa").slideToggle(700);
            }


        </script>
<?php } ?>
