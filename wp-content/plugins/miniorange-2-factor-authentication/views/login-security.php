<?php
global $mo2f_dirName;
$setup_dirName = $mo2f_dirName.'views'.DIRECTORY_SEPARATOR.'twofa'.DIRECTORY_SEPARATOR.'link_tracer.php';
 include $setup_dirName;

add_action( 'admin_footer', 'login_security_ajax' );
echo '	<div>
		<div class="mo_wpns_setting_layout" id ="mo2f_bruteforce">';


echo ' 		<h3>Brute Force Protection ( Login Protection )<a href='.$two_factor_premium_doc['Brute Force Protection'].' target="_blank"><span class="dashicons dashicons-text-page" style="font-size:23px;color:#269eb3;float: right;"></span></a></h3>
			<div class="mo_wpns_subheading">This protects your site from attacks which tries to gain access / login to a site with random usernames and passwords.</div>
			
				<input id="mo_bf_button" type="checkbox" name="enable_brute_force_protection" '.$brute_force_enabled.'> Enable Brute force protection
			<br>';

			 
				
echo'			<form id="mo_wpns_enable_brute_force_form" method="post" action="">
					<input type="hidden" name="option" value="mo_wpns_brute_force_configuration">
					<table class="mo_wpns_settings_table">
						<tr>
							<td style="width:40%">Allowed login attempts before blocking an IP  : </td>
							<td><input class="mo_wpns_table_textbox" type="number" id="allwed_login_attempts" name="allwed_login_attempts" required placeholder="Enter no of login attempts" value="'.$allwed_login_attempts.'" /></td>
							<td></td>
						</tr>
						<tr>
							<td>Time period for which IP should be blocked  : </td>
							<td>
								<select id="time_of_blocking_type" name="time_of_blocking_type" style="width:100%;">
								  <option value="permanent" '.($time_of_blocking_type=="permanent" ? "selected" : "").'>Permanently</option>
								  <option value="months" '.($time_of_blocking_type=="months" ? "selected" : "").'>Months</option>
								  <option value="days" '.($time_of_blocking_type=="days" ? "selected" : "").'>Days</option>
								  <option value="hours" '.($time_of_blocking_type=="hours" ? "selected" : "").'>Hours</option>
								</select>
							</td>
							<td><input class="mo_wpns_table_textbox '.($time_of_blocking_type=="permanent" ? "hidden" : "").' type="number" id="time_of_blocking_val" name="time_of_blocking_val" value="'.$time_of_blocking_val.'" placeholder="How many?" /></td>
						</tr>
						<tr>
							<td>Show remaining login attempts to user : </td>
							<td><input  type="checkbox"  id="rem_attempt" name="show_remaining_attempts" '.$remaining_attempts.' ></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td><br>
							<input type="hidden" id="brute_nonce" value ="'. wp_create_nonce("wpns-brute-force").'" />
							<input type="button" style="width:100px;" value="Save" class="mo_wpns_button mo_wpns_button1" id="mo_bf_save_button">
							</td>
							<td></td>
						</tr>
					</table>
				</form>';
			
		
echo'	
       </div>
		<div class="mo_wpns_setting_layout" id="mo2f_google_recaptcha">		
			<h3>Google reCAPTCHA<a href='.$two_factor_premium_doc['Google reCAPTCHA'].' target="_blank"><span class="dashicons dashicons-text-page" style="font-size:23px;color:#269eb3;float: right;"></span></a></h3>
			<div class="mo_wpns_subheading">Google reCAPTCHA protects your website from spam and abuse. reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep automated software from engaging in abusive activities on your site. It does this while letting your valid users pass through with ease.</div>
			<form id="mo_wpns_activate_recaptcha" method="post" action="">
				<input type="hidden" name="option" value="mo_wpns_activate_recaptcha">
				<input id="enable_captcha" type="checkbox" name="mo_wpns_activate_recaptcha" '.$google_recaptcha.'> Enable Google reCAPTCHA
			</form>';
			
echo'			<p>Before you can use reCAPTCHA, you must need to register your domain/webiste <a href="'.$captcha_url.'" target="blank">here</a>.</p>
				<p>Enter Site key and Secret key that you get after registration.</p>
				<form id="mo_wpns_recaptcha_settings" method="post" action="">
					<input type="hidden" name="option" value="mo_wpns_recaptcha_settings">
					<table class="mo_wpns_settings_table">
						<tr>
							<td style="width:30%">Site key  : </td>
							<td style="width:30%"><input id="captcha_site_key" class="mo_wpns_table_textbox" type="text" name="mo_wpns_recaptcha_site_key" required placeholder="site key" value="'.$captcha_site_key.'" /></td>
							<td style="width:20%"></td>
						</tr>
						<tr>
							<td>Secret key  : </td>
							<td><input id="captcha_secret_key" class="mo_wpns_table_textbox" type="text" name="mo_wpns_recaptcha_secret_key" required placeholder="secret key" value="'.$captcha_secret_key.'" /></td>
						</tr>
						<tr>
							<td style="vertical-align:top;">Enable reCAPTCHA for :</td>
							<td><input id="login_captcha" type="checkbox" name="mo_wpns_activate_recaptcha_for_login" '.$captcha_login.'> Login form
							<input id="reg_captcha" style="margin-left:10px" type="checkbox" name="mo_wpns_activate_recaptcha_for_registration" '.$captcha_reg.' > Registration form</td>
						</tr>
					</table><br/>
					<input type="hidden" id="captcha_nonce" value = "'.wp_create_nonce("wpns-captcha").'">
					<input id="captcha_button" type="button" value="Save Settings" class="mo_wpns_button mo_wpns_button1" />
					<input type="button" value="Test reCAPTCHA Configuration" onclick="testcaptchaConfiguration()" class="mo_wpns_button mo_wpns_button1" />
				</form>';
			


echo		'<br>
		</div>
		
		<div class="mo_wpns_setting_layout" id="mo2f_enforce_strong_password_div">		
			<h3>Enforce Strong Passwords <a href='.$two_factor_premium_doc['Enforce Strong Passwords'].' target="_blank"><span class="dashicons dashicons-text-page" style="font-size:23px;color:#269eb3;float: right;"></span></a></h3>
			<div class="mo_wpns_subheading">Checks the password strength of admin and other users to enhance login security</div>
			
			<form id="mo_wpns_enable_brute_force_form" method="post" action="">
				<input type="hidden" name="option" value="mo2f_enforce_strong_passswords">
				<input id="strong_password_check" type="checkbox" name="mo2f_enforce_strong_passswords" '.$enforce_strong_password.' > Enable strong passwords
				
				<table style="width:100%"><tr><td style="width:58%">Select accounts for which you want to enable password security</td>
				<td><select id="mo2f_enforce_strong_passswords_for_accounts" name="mo2f_enforce_strong_passswords_for_accounts" style="width:100%;">
				  <option value="all" '.($strong_password_account=="all" ? "selected" : "").'>All Accounts</option>
				  <option value="admin" '.($strong_password_account=="admin" ? "selected" : "").'>Administrators Account Only</option>
				  <option value="user" '.($strong_password_account=="user" ? "selected" : "").'>Users Account Only</option>
				</select></td></tr></table>
				<input type="hidden" id="str_pass" value ="'.wp_create_nonce("wpns-strn-pass").'" >
				<input type="button" id="strong_password" name="submit" style="width:100px;" value="Save" class="mo_wpns_button mo_wpns_button1">
			</form>
		</div>';
		
	
echo '<script>

		function testcaptchaConfiguration(){
			var myWindow = window.open("'.$test_recaptcha_url.'", "Test Google reCAPTCHA Configuration", "width=600, height=600");	
		}
	</script>';			

			
echo'		<br>
		</div>
	</div>
	
	<script>
		jQuery(document).ready(function(){
			$("#time_of_blocking_type").change(function() {
				if($(this).val()=="permanent")
					$("#time_of_blocking_val").addClass("hidden");
				else
					$("#time_of_blocking_val").removeClass("hidden");	
			});
		});	

		function mo_enable_disable_bf(){
			jQuery.ajax({
				type : "POST",
				data : {
					option: "mo_wpns_enable_brute_force",
					status: "'.$brute_force_enabled.'",
				},
				success: function(data){
				}  
			 });
		}
		</script>
'; 

		function login_security_ajax(){
			if ( ('admin.php' != basename( $_SERVER['PHP_SELF'] )) || ($_GET['page'] != 'mo_2fa_login_and_spam') ) {
				return;
            }
		?>
				<script>
					jQuery(document).ready(function(){
						jQuery("#mo_bf_save_button").click(function(){
						var data =  {
					'action'				  : 'wpns_login_security',
					'wpns_loginsecurity_ajax' : 'wpns_bruteforce_form', 
					'bf_enabled/disabled'     : jQuery("#mo_bf_button").is(":checked"),
					'allwed_login_attempts'   : jQuery("#allwed_login_attempts").val(),
					'time_of_blocking_type'   : jQuery("#time_of_blocking_type").val(),
					'time_of_blocking_val'    : jQuery("#time_of_blocking_val").val(),
					'show_remaining_attempts' : jQuery("#rem_attempt").is(':checked'),
					'nonce' 				  : jQuery("#brute_nonce").val(),	
				};
				jQuery.post(ajaxurl, data, function(response) {
				
				if (response == "empty"){
                    			error_msg(" Please fill out all the fields");
			    	}else if(response == "true"){
                    			success_msg("Brute force is enabled and configuration has been saved");
				}else if(response == "false"){
                    			error_msg(" Brute force is disabled");
				}
				else if(response == "ERROR" ){
                    error_msg("There was an error in processing your request");
                }
				});
					});

					
			});
jQuery(document).ready(function(){
						jQuery("#captcha_button").click(function(){
							var data = {
					'action'                 :'wpns_login_security',  
					'wpns_loginsecurity_ajax':'wpns_save_captcha',
					'site_key'  			 : jQuery("#captcha_site_key").val(),
					'secret_key'			 : jQuery("#captcha_secret_key").val(), 
					'enable_captcha'		 : jQuery("#enable_captcha").is(':checked'),
					'login_form'			 : jQuery("#login_captcha").is(':checked'),
					'registeration_form'	 : jQuery("#reg_captcha").is(':checked'),
					'nonce'		           	 :jQuery("#captcha_nonce").val(),
				}
				jQuery.post(ajaxurl, data, function(response) {

				if (response == "empty"){
                    		    error_msg(" Please fill out all the fields");
				}else if(response == "true") {
				    jQuery('#loginURL').empty();
				    jQuery('#loginURL').hide();
				    jQuery('#loginURL').show();
				    jQuery('#loginURL').append(data.input_url);
				    success_msg(" CAPTCHA is enabled.");
				}else if(response == "false") {
				    jQuery('#loginURL').empty();
				    jQuery('#loginURL').hide();
				    jQuery('#loginURL').show();
				    jQuery('#loginURL').append('wp-login.php');
				    error_msg("CAPTCHA is disabled.");
				}else if(response == "ERROR" ){
                    		    error_msg("There was an error in procession your request");
				}
				});
						});
					});
					jQuery(document).ready(function(){
						jQuery("#strong_password").click(function(){
							var data = {
					'action'                 :'wpns_login_security',  
					'wpns_loginsecurity_ajax':'save_strong_password',
					'enable_strong_pass'	 :jQuery("#strong_password_check").is(':checked'),
					'accounts_strong_pass'	 :jQuery("#mo2f_enforce_strong_passswords_for_accounts").val(),
					'nonce'					 :jQuery("#str_pass").val(), 
				}
				jQuery.post(ajaxurl, data, function(response) {

				if(response == "true"){
                    success_msg("Strong password is enabled.");
				}else if(response == "false") {
                    error_msg("Strong Password is disabled.");
				}else if(response == "ERROR" ){
                    error_msg("There was an error in procession your request");
				}
				});
						});
					});

				</script>


			<?php }
