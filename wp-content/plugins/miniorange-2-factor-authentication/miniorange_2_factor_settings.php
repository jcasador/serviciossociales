<?php
/**
 * Plugin Name: miniOrange 2 Factor Authentication
 * Plugin URI: https://miniorange.com
 * Description: This plugin provides various two-factor authentication methods as an additional layer of security after the default wordpress login. We Support Google/Authy/LastPass Authenticator, QR Code, Push Notification, Soft Token and Security Questions(KBA) for 3 User in the free version of the plugin.
 * Version: 5.4.26
 * Author: miniOrange
 * Author URI: https://miniorange.com
 * Text Domain: miniorange-2-factor-authentication
 * License: GPL2
 */
	include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'mo2f_db_options.php';
    define( 'MO_HOST_NAME', 'https://login.xecurify.com' );
	define( 'MO2F_VERSION', '5.4.26' );
	define( 'MO2F_TEST_MODE', false );
	define( 'MO2F_IS_ONPREM', get_option('is_onprem'));
	class Miniorange_twoFactor{

		function __construct()
		{
			register_deactivation_hook(__FILE__		 , array( $this, 'mo_wpns_deactivate'		       )		);
			register_activation_hook  (__FILE__		 , array( $this, 'mo_wpns_activate'			       )		);
			add_action( 'admin_menu'				 , array( $this, 'mo_wpns_widget_menu'		  	   )		);
			add_action( 'admin_enqueue_scripts'		 , array( $this, 'mo_wpns_settings_style'	       )		);
			add_action( 'admin_enqueue_scripts'		 , array( $this, 'mo_wpns_settings_script'	       )	    );
			add_action( 'wpns_show_message'		 	 , array( $this, 'mo_show_message' 				   ), 1 , 2 );		
			add_action( 'admin_init'                 , array( $this, 'miniorange_reset_save_settings'  )         );		
			add_filter('manage_users_columns'        , array( $this, 'mo2f_mapped_email_column'        )         );
			add_action('manage_users_custom_column'  , array( $this, 'mo2f_mapped_email_column_content'), 10,  3 );

			$actions = add_filter('user_row_actions' , array( $this, 'miniorange_reset_users'          ),10 , 2 );
            add_action( 'admin_footer'				 , array( $this, 'feedback_request' 			   )        );
	        add_action('admin_notices',array( $this, 'mo_wpns_malware_notices' ) );
			add_action( 'plugins_loaded', array( $this, 'mo2fa_load_textdomain') );

			if(!defined("DISALLOW_FILE_EDIT") && get_option('mo2f_disable_file_editing') ) 	 define('DISALLOW_FILE_EDIT', true);
			$this->includes();
			$notify = new miniorange_security_notification;
		    add_action('wp_dashboard_setup', array($notify,'my_custom_dashboard_widgets'));

		    $customShort = new TwoFACustomRegFormShortcode();
            add_action('admin_init',array( $this, 'mo2f_enable_register_shortcode' ));
            add_action('admin_init',array( $customShort, 'mo_enqueue_shortcode' ));
            add_shortcode('mo2f_enable_register',array($this,'mo2f_enable_register_shortcode'));
            if(defined("DIGIMEMBER_DIR"))
			{
				add_action( 'wp_footer', array( $this, 'mo_wpns_ajax_login_script'));
			}

        }

        public function mo2f_enable_register_shortcode()
        {
            $submitSelector = get_site_option('mo2f_custom_submit_selector');
            $formName=        get_site_option('mo2f_custom_form_name');
            $emailField =     get_site_option('mo2f_custom_email_selector');
            $authType   =     get_site_option('mo2f_custom_auth_type');
            $phoneSelector =  get_site_option('mo2f_custom_phone_selector');

            if(get_site_option('mo2f_customerkey') > 0)
                $isRegistered =   get_site_option('mo2f_customerkey');
            else $isRegistered = 'false';


			$formAjax = array(".um-form", ".wpcf7-form", "#um-submit-btn");

            $formRCP = array("#rcp_registration_form",".rcp_form","#rc_registration_form",".rc_form");
			$formMepr 	= array(".mepr-signup-form");

            if(in_array($formName,$formAjax))
                $javaScript = 'includes/js/custom-form-ajax.js';
            else if (in_array($formName,$formRCP))
                $javaScript = 'includes/js/custom-ajax-rcp.js';
            else if (in_array($formName,$formMepr))
                $javaScript = 'includes/js/custom-ajax-mepr.js';
            else
                $javaScript = 'includes/js/custom-form.js';

            wp_enqueue_style( 'mo2f_intl_tel_style',  plugin_dir_url(__FILE__).'includes/css/phone.css');
            wp_enqueue_script( 'mo2f_intl_tel_script',plugin_dir_url(__FILE__).'includes/js/phone.js');
            wp_register_script('mo2f_otpVerification',plugin_dir_url(__FILE__).$javaScript);
            wp_localize_script('mo2f_otpVerification', 'otpverificationObj',
                array('siteURL'=> admin_url( 'admin-ajax.php'),
                    'nonce'=>wp_create_nonce('ajax-nonce'),
                    'authType'=>$authType,
                    'submitSelector'=>$submitSelector,
                    'formname'=>$formName,
                    'emailselector'=>$emailField,
                    'isRegistered' => $isRegistered,
                    'phoneSelector' => $phoneSelector,
                    'loaderUrl' 		=> plugin_dir_url(__FILE__).'includes/images/loader.gif',
                    'isEnabledShortcode' => get_site_option('enable_form_shortcode')));
            wp_enqueue_script('mo2f_otpVerification');

            //Register Shortcode JavaScript And Pass Parameters To JS
        }

        // As on plugins.php page not in the plugin
        function feedback_request() {
            wp_enqueue_style( 'mo_wpns_admin_plugins_page_style', plugins_url( '/includes/css/style_settings.css?ver=4.8.60', __FILE__ ) );
            if ( 'plugins.php' != basename( $_SERVER['PHP_SELF'] ) ) {
                return;
            }
            global $mo2f_dirName;

             $email = get_option("mo2f_email");
            if(empty($email)){
                $user = wp_get_current_user();
                $email = $user->user_email;
            }
            $imagepath=plugins_url( '/includes/images/', __FILE__ );

            wp_enqueue_style( 'wp-pointer' );
            wp_enqueue_script( 'wp-pointer' );
            wp_enqueue_script( 'utils' );

            include $mo2f_dirName . 'views'.DIRECTORY_SEPARATOR.'feedback_form.php';;

        }
		/**
		 * Function tells where to look for translations.
		 */
		function mo2fa_load_textdomain()
		{
			load_plugin_textdomain( 'miniorange-2-factor-authentication', FALSE, dirname( plugin_basename(__FILE__) ) . '/lang/' );
		}
		
		function mo_wpns_malware_notices(){
			
			$one_day = 60*60*24;
		    $dismiss_time   = get_site_option('notice_dismiss_time');
		    
		    $dismiss_time   = (time()-$dismiss_time)/$one_day;
            $dismiss_time   = (int)$dismiss_time;
           
           //setting variables for low SMS/email notification
			global $Mo2fdbQueries;
        	$user_object = wp_get_current_user();
			$mo2f_configured_2FA_method = $Mo2fdbQueries->get_user_detail( 'mo2f_configured_2FA_method', $user_object->ID );
        	$one_day = 60*60*24;
		    $day_sms= (time()-get_site_option('sms_dismiss'))/$one_day;
		    $day_sms = floor($day_sms);
		    $day_email= (time()-get_site_option('email_dismiss'))/$one_day;
		    $day_email = floor($day_email);

		 if(get_option('mo_wpns_2fa_with_network_security'))
		    {
	    	
           $notify = MoWpnsMessages::$notification_array;
		   $dismissedExpired = 0;	
		   foreach ($notify as $key => $value){

                   if((!get_site_option($key) && !get_site_option('notice_dismiss_time') ) || ($dismissedExpired and !get_site_option($key))){ 
                      echo $value;
                      break;
                   }
                   else{ 
                  	if($dismiss_time >=1){
					   $dismissedExpired = 1;
					  
                       }
                      else
                   		$dismissedExpired = 0;
                   	  }
		   }
	    	
	    }
	    if(!get_site_option('donot_show_low_email_notice') && (get_site_option('cmVtYWluaW5nT1RQ')<=5) && ($day_email >= 1) && $mo2f_configured_2FA_method == "OTP Over Email"){
	    		echo MoWpnsMessages::showMessage('LOW_EMAIL_TRANSACTIONS');
	    	}
	    }
	    
		function mo_wpns_widget_menu()
		{
		$user  = wp_get_current_user();
		$userID = $user->ID;
		$onprem_admin = get_option('mo2f_onprem_admin');
        $roles = ( array ) $user->roles;
        $flag  = 0;
  		foreach ( $roles as $role ) {
            if(get_option('mo2fa_'.$role)=='1')
            	$flag=1;
        }

         $is_2fa_enabled=(($flag) or ($userID == $onprem_admin));
         
            if( $is_2fa_enabled){	
				$menu_slug = 'mo_2fa_two_fa';
			}
			else{
				$menu_slug =  'mo_2fa_dashboard';
			}
			add_menu_page (	'miniOrange 2-Factor' , 'miniOrange 2-Factor' , 'administrator', $menu_slug , array( $this, 'mo_wpns'), plugin_dir_url(__FILE__) . 'includes/images/miniorange_icon.png' );
			if(MoWpnsUtility::get_mo2f_db_option('mo_wpns_2fa_with_network_security', 'get_option'))
			{
				add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Dashboard'		    ,'administrator','mo_2fa_dashboard'			, array( $this, 'mo_wpns'),1);
			}

			if(MO2F_IS_ONPREM)
			{	
				if( $is_2fa_enabled){
				add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Two Factor'		,'read',		'mo_2fa_two_fa'			, array( $this, 'mo_wpns'),1);
				}
			}
			else{
			add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Two Factor'		   	,'administrator','mo_2fa_two_fa'			, array( $this, 'mo_wpns'),2);
			}
			if(MoWpnsUtility::get_mo2f_db_option('mo_wpns_2fa_with_network_security', 'get_option'))
		{
			add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Firewall'		   		,'administrator','mo_2fa_waf'				, array( $this, 'mo_wpns'),3);
			add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Login and Spam'		,'administrator','mo_2fa_login_and_spam'	, array( $this, 'mo_wpns'),4);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Backup'				,'administrator','mo_2fa_backup'			, array( $this, 'mo_wpns'),5);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Malware Scan'			,'administrator','mo_2fa_malwarescan'  		, array( $this, 'mo_wpns'),6);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'IP Blocking'	,'administrator','mo_2fa_advancedblocking'	, array( $this, 'mo_wpns'),7);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Notifications'		,'administrator','mo_2fa_notifications'		, array( $this, 'mo_wpns'),8);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Reports'				,'administrator','mo_2fa_reports'			, array( $this, 'mo_wpns'),9);
        }
           add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Troubleshooting'		,'administrator','mo_2fa_troubleshooting'	, array( $this, 'mo_wpns'),10);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Account'				,'administrator','mo_2fa_account'			, array( $this, 'mo_wpns'),11);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Addons'		,'administrator','mo_2fa_addons'			, array( $this, 'mo_wpns'),10);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Upgrade'				,'administrator','mo_2fa_upgrade'			, array( $this, 'mo_wpns'),12);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Request for Demo'				,'administrator','mo_2fa_request_demo'			, array( $this, 'mo_wpns'),13);
            add_submenu_page( $menu_slug	,'miniOrange 2-Factor'	,'Christmas Offers'				,'administrator','mo_2fa_request_christmas_offer'			, array( $this, 'mo_wpns'),14);
            $mo2fa_hook_page = add_users_page ('Reset 2nd Factor',  null , 'manage_options', 'reset', array( $this, 'mo_reset_2fa_for_users_by_admin' ),66);
            
        
    }



		function mo_wpns()
		{
			global $wpnsDbQueries,$Mo2fdbQueries;
			$wpnsDbQueries->mo_plugin_activate();
			$Mo2fdbQueries->mo_plugin_activate();
			add_option('SQLInjection', 1);
			add_option('WAFEnabled' ,0);
			add_option('XSSAttack' ,1);
			add_option('RFIAttack' ,0);
			add_option('LFIAttack' ,0);
			add_option('RCEAttack' ,0);
			add_option('actionRateL',0);
			add_option('Rate_limiting',0);
			add_option('Rate_request',240);
			add_option('limitAttack',10);
			add_site_option('EmailTransactionCurrent',30);
			add_site_option(base64_encode("totalUsersCloud"),0);
			add_site_option(base64_encode('remainingWhatsapptransactions'),30);

			include 'controllers/main_controller.php';
		}

		function mo_wpns_activate()
		{

			global $wpnsDbQueries,$Mo2fdbQueries;
			$userid = wp_get_current_user()->ID;
			$wpnsDbQueries->mo_plugin_activate();
			$Mo2fdbQueries->mo_plugin_activate();
			add_option( 'mo2f_is_NC', 1 );
			add_option( 'mo2f_is_NNC', 1 );
			add_option( 'mo2fa_administrator',1 );
			add_action( 'mo_auth_show_success_message', array($this, 'mo_auth_show_success_message'), 10, 1 );
			add_action( 'mo_auth_show_error_message', array($this, 'mo_auth_show_error_message'), 10, 1 );
			add_option( 'mo2f_onprem_admin' ,  $userid );
			add_option( 'mo_wpns_last_scan_time', time());
			if(get_site_option('mo2f_activated_time') == null){
				add_site_option('mo2f_activated_time', time());
			}
			update_site_option('mo2f_plugin_redirect', true);
		}

		function mo_wpns_deactivate() 
		{
			update_option('mo2f_activate_plugin', 1);

      		$two_fa_settings = new Miniorange_Authentication();
			$two_fa_settings->mo_auth_deactivate();
		}

		function mo_wpns_settings_style($hook)
		{
			if(strpos($hook, 'page_mo_2fa')){
				wp_enqueue_style( 'mo_2fa_admin_settings_jquery_style'		, plugins_url('includes/css/jquery.ui.css', __FILE__ ) );
				wp_enqueue_style( 'mo_2fa_admin_settings_phone_style'		, plugins_url('includes/css/phone.css', __FILE__ ) );
				wp_enqueue_style( 'mo_wpns_admin_settings_style'			, plugins_url('includes/css/style_settings.css', __FILE__));
				wp_enqueue_style( 'mo_wpns_admin_settings_phone_style'		, plugins_url('includes/css/phone.css', __FILE__));
				wp_enqueue_style( 'mo_wpns_admin_settings_datatable_style'	, plugins_url('includes/css/jquery.dataTables.min.css', __FILE__));
				wp_enqueue_style( 'mo_wpns_button_settings_style'			, plugins_url('includes/css/button_styles.css',__FILE__));
				wp_enqueue_style( 'mo_wpns_popup_settings_style'			, plugins_url('includes/css/popup.css',__FILE__));
				wp_enqueue_style( 'mo_2fa_time_settings_style'				, plugins_url('includes/css/datetime-style-settings.min.css', __FILE__));
				$file     = plugin_dir_path( __FILE__ ) .'controllers'.DIRECTORY_SEPARATOR. 'pointers.php';
				
				$tour_started=get_option('mo2f_tour_started',0);
        
				
				$manager  = new Mo2FAPointersManager( $file, '4.8.52', 'custom_admin_pointers' );
		        $manager->parse();
		       	$pointers = $manager->filter( $hook );
		        if ( empty( $pointers ) ) {
		            return;
		        }
		        wp_enqueue_style( 'wp-pointer' );
		        $js_url = plugins_url( 'includes\js\pointers.js', __FILE__ );
		       
		        if($tour_started == 3)
		        wp_enqueue_script( 'custom_admin_pointers', $js_url, array('wp-pointer'), NULL, TRUE );
		        $data = array(
		            'close_label' => __('Close'),
		            'next_label' => __( 'Next' ),
		            'pointers' => $pointers
		        );
		        wp_localize_script( 'custom_admin_pointers', 'MOAdminPointers', $data );
				
			}

		}

		function mo_wpns_settings_script($hook)
		{
			wp_enqueue_script( 'mo_wpns_admin_settings_script'			, plugins_url('includes/js/settings_page.js', __FILE__ ), array('jquery'));
			if(strpos($hook, 'page_mo_2fa')){
				
			
				wp_enqueue_script( 'mo_wpns_admin_settings_phone_script'	, plugins_url('includes/js/phone.js', __FILE__ ));
				wp_enqueue_script( 'mo_wpns_admin_datatable_script'			, plugins_url('includes/js/jquery.dataTables.min.js', __FILE__ ), array('jquery'));
				wp_enqueue_script( 'mo_wpns_qrcode_script', plugins_url( "/includes/jquery-qrcode/jquery-qrcode.js", __FILE__ ) );
				wp_enqueue_script( 'mo_wpns_min_qrcode_script', plugins_url( "/includes/jquery-qrcode/jquery-qrcode.min.js", __FILE__ ) );
				wp_enqueue_script('jquery-ui-core');
		        wp_enqueue_script('jquery-ui-autocomplete');
		        wp_enqueue_script('jquery-ui-datepicker');
		        wp_enqueue_script('mo_2fa_select2_script', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js');
		        wp_enqueue_script('mo_2fa_timepicker_script', 'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js');
			}
		}


       
		function mo_wpns_ajax_login_script($hook){
	    	if(get_option('mo2f_activate_plugin') and (get_option( 'mo_2factor_admin_registration_status' ) == 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' or MO2F_IS_ONPREM )){ 
		    	wp_enqueue_script( 'dmajax_script', plugins_url('includes/js/dmajax.js',__FILE__ )); 
				wp_localize_script( 'dmajax_script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));
				?>
				<input type="hidden" name="miniorange_login_nonce"
               	value="<?php echo wp_create_nonce( 'miniorange-2-factor-login-nonce' ); ?>"/>
				<?php
				if ( get_option( 'mo2f_remember_device' ) ) {
					?>
					<script type="text/javascript">
					jQuery(".ncore_input_password ").append("<input type='hidden' id='miniorange_rba_attribures' name='miniorange_rba_attribures' value=''/>");
					</script>
					 <?php
					wp_enqueue_script( 'jquery_script', plugins_url( 'includes/js/rba/js/jquery-1.9.1.js', __FILE__ ) );
					wp_enqueue_script( 'flash_script', plugins_url( 'includes/js/rba/js/jquery.flash.js', __FILE__ ) );
					wp_enqueue_script( 'uaparser_script', plugins_url( 'includes/js/rba/js/ua-parser.js', __FILE__) );
					wp_enqueue_script( 'client_script', plugins_url( 'includes/js/rba/js/client.js', __FILE__ ) );
					wp_enqueue_script( 'device_script', plugins_url( 'includes/js/rba/js/device_attributes.js',__FILE__ ) );
					wp_enqueue_script( 'swf_script', plugins_url( 'includes/js/rba/js/swfobject.js', __FILE__ ) );
					wp_enqueue_script( 'font_script', plugins_url( 'includes/js/rba/js/fontdetect.js', __FILE__ ) );
					wp_enqueue_script( 'murmur_script', plugins_url( 'includes/js/rba/js/murmurhash3.js', __FILE__ ) );							
					wp_enqueue_script( 'miniorange_script', plugins_url( 'includes/js/rba/js/miniorange-fp.js', __FILE__ ) );
				}
				else if( get_option('mo2f_enable_2fa_prompt_on_login_page'))
				{
					?>
					<script type="text/javascript">
					jQuery(".ncore_input_password ").append('<input type="text" placeholder="No soft Token ? Skip" name="mo_softtoken" id="mo2f_2fa_code" class="mo2f_2fa_code" value="" size="20" style="ime-mode: inactive;">');
					</script>
					<?php
				}
			}
		}


		function mo_show_message($content,$type) 
		{
		     if($type=="CUSTOM_MESSAGE")
			{
				echo "<div class='overlay_not_JQ_success' id='pop_up_success'><p class='popup_text_not_JQ'>".$content."</p> </div>";
				?>
				<script type="text/javascript">
				 setTimeout(function () {
					var element = document.getElementById("pop_up_success");
					   element.classList.toggle("overlay_not_JQ_success");
					   element.innerHTML = "";
						}, 7000);
						
				</script>
				<?php
			}
			 if($type=="NOTICE")
			{
				echo "<div class='overlay_not_JQ_error' id='pop_up_error'><p class='popup_text_not_JQ'>".$content."</p> </div>";
				?>
				<script type="text/javascript">
				 setTimeout(function () {
					var element = document.getElementById("pop_up_error");
					   element.classList.toggle("overlay_not_JQ_error");
					   element.innerHTML = "";
						}, 7000);
						
				</script>
				<?php
			}
			 if($type=="ERROR")
			 {
				echo "<div class='overlay_not_JQ_error' id='pop_up_error'><p class='popup_text_not_JQ'>".$content."</p> </div>";
				?>
				<script type="text/javascript">
				 setTimeout(function () {
					var element = document.getElementById("pop_up_error");
					   element.classList.toggle("overlay_not_JQ_error");
					   element.innerHTML = "";
						}, 7000);
						
				</script>
				<?php
			 }
			 if($type=="SUCCESS")
			 	{
					echo "<div class='overlay_not_JQ_success' id='pop_up_success'><p class='popup_text_not_JQ'>".$content."</p> </div>";
					?>
					<script type="text/javascript">
					 setTimeout(function () {
						var element = document.getElementById("pop_up_success");
						   element.classList.toggle("overlay_not_JQ_success");
						   element.innerHTML = "";
							}, 7000);
							
					</script>
					<?php
				}
		}

		function includes()
		{
			require('helper/pluginUtility.php');
			require('database/database_functions.php');
			require('database/database_functions_2fa.php');
			require('helper/utility.php');
			require('handler/ajax.php');
			require('api/class-customer-common-setup.php');

			if(!MO2F_IS_ONPREM)
			    require('api/class-customer-setup.php');
			else
			    require('api/class-customer-onprem-setup.php');
			require('api/class-rba-attributes.php');
			require('api/class-two-factor-setup.php');
			// require('api/mo2f_api.php');
			require('handler/backup.php');
			require('handler/security_features.php');
			require('handler/feedback_form.php');
			require('handler/recaptcha.php');
			require('handler/login.php');
			require('handler/twofa/setup_twofa.php');
			require('handler/twofa/two_fa_settings.php');
			require('handler/twofa/two_fa_utility.php');
			require('handler/twofa/two_fa_constants.php');
			require('handler/registration.php');
			require('handler/logger.php');
			require('handler/spam.php');
			require('helper/dashboard_security_notification.php');
			require('helper/curl.php');
			require('helper/plugins.php');
			require('helper/constants.php');
			require('helper/messages.php');
			require('views/common-elements.php');

			require('handler/twofa/two_fa_short_custom.php');
			require('controllers/wpns-loginsecurity-ajax.php');
			require('controllers/malware_scanner/malware_scan_ajax.php');
			require('controllers/backup/backup_ajax.php');
			require('controllers/tour/tour_ajax.php');
			require('controllers/twofa/two_factor_ajax.php');
			require('controllers/dashboard_ajax.php');
			require('handler/malware_scanner/malware_scanner_cron.php');
			require('handler/malware_scanner/scanner_set_cron.php');
			require_once "controllers/PointersManager.php";
		}

		function miniorange_reset_users($actions, $user_object){
		    global $Mo2fdbQueries;
			$mo2f_configured_2FA_method = $Mo2fdbQueries->get_user_detail( 'mo2f_configured_2FA_method', $user_object->ID );

		$tfa_enabled = $Mo2fdbQueries->get_user_detail( 'mo2f_2factor_enable_2fa_byusers', $user_object->ID );
		$mo_2factor_user_registration_status = $Mo2fdbQueries->get_user_detail( 'mo_2factor_user_registration_status', $user_object->ID );
				
		if($tfa_enabled == 0 && ($mo_2factor_user_registration_status != 'MO_2_FACTOR_PLUGIN_SETTINGS') && $tfa_enabled != '')
			$mo2f_configured_2FA_method = 1;
		if ( current_user_can( 'administrator', $user_object->ID )  && $mo2f_configured_2FA_method ) {
			if(get_current_user_id() != $user_object->ID){
				$actions['miniorange_reset_users'] = "<a class='miniorange_reset_users' href='" . admin_url( "users.php?page=reset&action=reset_edit&amp;user=$user_object->ID") . "'>" . __( 'Reset 2 Factor', 'cgc_ub' ) . "</a>";
			}
		}	
		return $actions;
		
	}



	function mo2f_mapped_email_column($columns) {
		$columns['current_method'] = '2FA Method';
		return $columns;
	}

	function mo_reset_2fa_for_users_by_admin(){
		$nonce = wp_create_nonce('ResetTwoFnonce');
		if(isset($_GET['action']) && $_GET['action']== 'reset_edit'){
			$user_id = $_GET['user'];
			$user_info = get_userdata($user_id);	
		?> 
			<form method="post" name="reset2fa" id="reset2fa" action="<?php echo esc_url('users.php'); ?>">
				
				<div class="wrap">
				<h1>Reset 2nd Factor</h1>

				<p>You have specified this user for reset:</p>

				<ul>
				<li>ID #<?php echo $user_info->ID; ?>: <?php echo $user_info->user_login; ?></li> 
				</ul>
					<input type="hidden" name="userid" value="<?php echo $user_id; ?>">
					<input type="hidden" name="miniorange_reset_2fa_option" value="mo_reset_2fa">
					<input type="hidden" name="nonce" value="<?php echo $nonce;?>">
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Confirm Reset" ></p>
				</div>
			</form>
		<?php
		}	
	}

		function miniorange_reset_save_settings()
		{
		if(isset($_POST['miniorange_reset_2fa_option']) && sanitize_text_field($_POST['miniorange_reset_2fa_option']) == 'mo_reset_2fa'){
				$nonce = sanitize_text_field($_POST['nonce']);
				if(!wp_verify_nonce($nonce,'ResetTwoFnonce'))
				{
					
					return;
				}
				$user_id = isset($_POST['userid']) && !empty($_POST['userid']) ? sanitize_text_field($_POST['userid']) : '';
				if(!empty($user_id)){
					if ( current_user_can( 'edit_user' ) ){
					    global $Mo2fdbQueries;
                        delete_user_meta($user_id,'mo2f_kba_challenge');
                        delete_user_meta($user_id,'mo2f_2FA_method_to_configure');
                        delete_user_meta($user_id,'Security Questions');
						delete_user_meta($user_id,'mo2f_chat_id');
						delete_user_meta($user_id,'mo2f_whatsapp_num');
						delete_user_meta($user_id,'mo2f_whatsapp_id');
						$Mo2fdbQueries->delete_user_details( $user_id);
                        delete_user_meta($user_id,'mo2f_2FA_method_to_test');
					}
				}
			}
		}

	function mo2f_mapped_email_column_content($value, $column_name, $user_id) {
		global $Mo2fdbQueries;
		$currentMethod = $Mo2fdbQueries->get_user_detail( 'mo2f_configured_2FA_method', $user_id );
			if(!$currentMethod)
			    $currentMethod = 'Not Registered for 2FA';
		if ( 'current_method' == $column_name )
			return $currentMethod;
		return $value;
	}

	}

	new Miniorange_twoFactor;
?>