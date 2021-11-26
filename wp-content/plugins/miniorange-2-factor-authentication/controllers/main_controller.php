<?php

	global $moWpnsUtility,$mo2f_dirName;

	$controller = $mo2f_dirName . 'controllers'.DIRECTORY_SEPARATOR;

	
	if(current_user_can('administrator'))
	{
		include $controller 	 . 'navbar.php';
		include $controller 	 . 'newtork_security_features.php';

        $tour_started=get_option('mo2f_tour_started',0);
        
        
        if($tour_started<1)
			include $controller . 'two-fa-intro.php';
		else if($tour_started != 0)
			include $controller 	 . 'tour-model.php';

	if( isset( $_GET[ 'page' ])) 
	{
		switch($_GET['page'])
		{
			case 'mo_2fa_dashboard':
		                include $controller . 'dashboard.php';			    break;
			case 'mo_2fa_login_and_spam':
				include $controller . 'login-spam.php';				break;
			case 'default':
				include $controller . 'login-security.php';			break;
			case 'mo_2fa_account':
				include $controller . 'account.php';				break;		
			case 'mo_2fa_backup':
				include $controller . 'backup'.DIRECTORY_SEPARATOR.'backup.php'; 				break;
			case 'mo_2fa_upgrade':
				include $controller . 'upgrade.php';                break;
			case 'mo_2fa_waf':
				include $controller . 'waf.php';		    		break;
			case 'mo_2fa_blockedips':
				include $controller . 'ip-blocking.php';			break;
			case 'mo_2fa_advancedblocking':
				include $controller . 'advanced-blocking.php';		break;
			case 'mo_2fa_notifications':
				include $controller . 'notification-settings.php';	break;
			case 'mo_2fa_reports':
				include $controller . 'reports.php';				break;
			case 'mo_2fa_licensing':
				include $controller . 'licensing.php';				break;
			case 'mo_2fa_troubleshooting':
				include $controller . 'troubleshooting.php';		break;
			case 'mo_2fa_addons':
				include $controller . 'addons.php';					break;
			case 'mo_2fa_malwarescan':
				include $controller . 'malware_scanner'.DIRECTORY_SEPARATOR.'scan_malware.php';			break;
			case 'mo_2fa_two_fa':
				include $controller .'twofa'.DIRECTORY_SEPARATOR. 'two_fa.php';					break;
			case 'mo_2fa_request_demo':
				include $controller . 'request_demo.php';			break;	
			case 'mo_2fa_request_christmas_offer':
				include $controller . 'request_christmas_offer.php';
		}
	}

	}
	else
	{
		if( isset( $_GET[ 'page' ])) 
		{
			switch($_GET['page'])
			{
				case 'mo_2fa_two_fa':
					include $controller .'twofa'.DIRECTORY_SEPARATOR. 'two_fa.php';					break;	
			
			}

		}

	}
	if (isset( $_GET[ 'page' ])) {

		if ($_GET[ 'page' ] == "mo_2fa_upgrade" || $_GET[ 'page' ] == "mo_2fa_addons") 
		{
			include $controller . 'feedback_footer.php';
		}
		else
		{
			include $controller . 'support.php';
		}
	}
?>

<!-- <script>
    	jQuery(document).ready(function(){
	    	 var nonce 	= "<?php //echo wp_create_nonce('wpns-quick-scan');?>";
	         var data={
					'action':'mo_wpns_malware_redirect',
					'call_type':'malware_scan_initiate',
					'scan':'scan_start',
					'scantype':'quick_scan',
					'nonce': nonce
	               };
	        jQuery.post(ajaxurl, data, function(response){
	        	jQuery('input[name="quick_scan_button"]').removeAttr('disabled');
				document.getElementById('quick_scan_button').style.backgroundColor = '#20b2aa';
				jQuery('input[name="standard_scan_button"]').removeAttr('disabled');
				document.getElementById('standard_scan_button').style.backgroundColor = '#20b2aa';
				jQuery('input[name="custom_scan_button"]').removeAttr('disabled');
				document.getElementById('custom_scan_button').style.backgroundColor = '#20b2aa'; 
				document.getElementById("quick_scan_button").value = "Quick Scan";
	        });
        });
</script>   -->   

