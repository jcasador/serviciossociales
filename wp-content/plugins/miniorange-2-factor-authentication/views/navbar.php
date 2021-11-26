<?php

$security_features_nonce = wp_create_nonce('mo_2fa_security_features_nonce');

	$user = wp_get_current_user();
	$userID = wp_get_current_user()->ID;
	$onprem_admin = get_option('mo2f_onprem_admin');
	$roles = ( array ) $user->roles;
	$is_onprem = MO2F_IS_ONPREM;
        $flag  = 0;
  		foreach ( $roles as $role ) {
            if(get_option('mo2fa_'.$role)=='1')
            	$flag=1;
        }
	
	if(!$safe)
	{
		if (MoWpnsUtility::get_mo2f_db_option('mo_wpns_2fa_with_network_security', 'get_option')) 
		{
			echo MoWpnsMessages::showMessage('WHITELIST_SELF');		
		}
	}
	?>
	<?php
	if((!get_user_meta($userID, 'mo_backup_code_generated', true) || ($backup_codes_remaining == 5 && !get_user_meta($userID, 'mo_backup_code_downloaded', true))) && $mo2f_two_fa_method != '' && !get_user_meta($userID, 'donot_show_backup_code_notice', true)){
		echo MoWpnsMessages::showMessage('GET_BACKUP_CODES');
	}
?>
<br><br>
<?php
if( isset( $_GET[ 'page' ]) && $_GET['page'] != 'mo_2fa_upgrade') 
	{	
			echo'<div class="wrap">';

				$date1 = "2020-12-31";
				$dateTimestamp1 = strtotime($date1);

				$date2 = date("Y-m-d");
				$dateTimestamp2 = strtotime($date2);

				if($dateTimestamp2<=$dateTimestamp1)
				{
					echo'<div class="mo2f_christmas_main_div">

					

					<div class="mo2f_christmas_first_section">
						<img style="height: 201px;width: 540px;" src="'.dirname(plugin_dir_url(__FILE__)).'/includes/images/christmas_offer.png">

					</div>

					<div class="mo2f_christmas_middle_section">
						<p class="mo2f_christmas_get_upto">Get Upto </p>
						<p class="mo2f_christmas_first_section_text">50% off</p>
						<center><a class="mo2f_christmas_contact_us" href="'.$request_christmas_offer_url.'">Contact Us</a></center>
					</div>

					<div class="mo2f_christmas_last_section">
						<img src="'.dirname(plugin_dir_url(__FILE__)).'/includes/images/santa-gif.gif">
					</div>




						

					</div><br><br>';
				}
				echo' <div><img  style="float:left;margin-top:5px;" src="'.$logo_url.'"></div>
				<h1>
					miniOrange 2-Factor 
					<a class="add-new-h2" style="font-size:17px;border-radius:4px;"  href="'.$profile_url.'">Account</a>
					<a class="add-new-h2" style="font-size:17px;border-radius:4px;" href="'.$help_url.'">FAQs</a>
					<a class="license-button add-new-h2" style="font-size:17px;border-radius:4px;" href="'.$request_demo_url.'">Demo</a>
					<a class="add-new-h2" style="font-size:17px;border-radius:4px;background-color:orange; color:black;" href="'.$addons_url.'">AddOns Plans</a>
					<a class="add-new-h2" id ="mo_2fa_upgrade_tour" style="font-size:17px;border-radius:4px;background-color:orange; color:black;" href="'.$upgrade_url.'">See Plans and Pricing</a>';
					echo '	
						<a class="license-button add-new-h2"  id="restart-tour" href="#" style="background-color: lightblue;font-size:17px;border-radius:4px;">Restart tour</a>
					';
echo'					<span style="text-align:right;"> 

					<form id="mo_wpns_2fa_with_network_security" method="post" action="" style="margin-top: -2%; width: 30%; text-align: right; padding-left: 70%;">
					<input type="hidden" name="mo_security_features_nonce" value="'.$security_features_nonce.'"/>

					<input type="hidden" name="option" value="mo_wpns_2fa_with_network_security">
					<div><br><i>2FA + Website Security</i><span>
					<label class="mo_wpns_switch">
					<input type="checkbox" name="mo_wpns_2fa_with_network_security" '.$network_security_features.'  onchange="document.getElementById(\'mo_wpns_2fa_with_network_security\').submit();"> 
					<span class="mo_wpns_slider mo_wpns_round"></span>
					</label></span>
					</div>
					
					</form>
					</span>';
					
					
					echo '<div id = "wpns_nav_message"></div>';
				echo'</h1>			
		</div>';
?>



		<div class="mo_flex-container">
			<?php 
		if(MoWpnsUtility::get_mo2f_db_option('mo_wpns_2fa_with_network_security', 'get_option'))
		{
			echo '<a class="nav-tab '.($active_tab == 'mo_2fa_dashboard' 	  ? 'nav-tab-active' : '').'" href="'.$dashboard_url	.'">Dashboard</a>';
			if($is_onprem){
				if(  ($flag) or ($userID == $onprem_admin) ){
					echo '<a class="nav-tab '.($active_tab == 'mo_2fa_two_fa'		  ?	'nav-tab-active' : '').'" href="'.$two_fa		 	.'">Two Factor</a>'; 
				}
			}
			else{
				echo '<a class="nav-tab '.($active_tab == 'mo_2fa_two_fa'		  ?	'nav-tab-active' : '').'" href="'.$two_fa		 	.'">Two Factor</a>';
				}	
		
			if(get_site_option('mo_2f_switch_waf')){
	 			echo '<a id="mo_2fa_waf" class="nav-tab '.($active_tab == 'mo_2fa_waf' 			  ? 'nav-tab-active' : '').'" href="'.$waf				.'">Firewall</a>';
	 		}
	 		if(get_site_option('mo_2f_switch_loginspam')){
	 			echo '<a id="login_spam_tab" class="nav-tab '.($active_tab == 'mo_2fa_login_and_spam'  ? 'nav-tab-active' : '').'" href="'.$login_and_spam	.'">Login and Spam</a>';
	 		}
	 		if(get_site_option('mo_2f_switch_backup')){
				echo '<a id="backup_tab" class="nav-tab '.($active_tab == 'mo_2fa_backup' 	  	  ? 'nav-tab-active' : '').'" href="'.$backup			.'">Encrypted Backup</a>';
			}
			if(get_site_option('mo_2f_switch_malware')){
				echo '<a id="malware_tab" class="nav-tab '.($active_tab == 'mo_2fa_malwarescan'	  ?	'nav-tab-active' : '').'" href="'.$scan_url 		.'">Malware Scan</a>';
			}
			if(get_site_option('mo_2f_switch_adv_block')){
				echo '<a id="adv_block_tab" class="nav-tab '.($active_tab == 'mo_2fa_advancedblocking'? 'nav-tab-active' : '').'" href="'.$advance_block	.'">IP Blocking</a>';
			}
		
		}
			?>
		</div>
<?php 
	}



