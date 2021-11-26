<?php
$setup_dirName = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'twofa'.DIRECTORY_SEPARATOR.'link_tracer.php';
 include $setup_dirName;?>

<div class="mo_wpns_setting_layout" id = "premium_feature_phone_lost">
    <h3>What happens if my phone is lost, discharged or not with me<a href='<?php echo $two_factor_premium_doc['What happens if my phone is lost, discharged or not with me'];?>' target="_blank">
            <span class="dashicons dashicons-text-page" style="font-size:19px;color:#269eb3;float: right;"></span>

        </a></h3><hr>
    <p>
        <input type="checkbox" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>Enable Forgot Phone.
    <p>Select the alternate login method in case your phone is lost, discharged or not with you.</p>
    <input type="checkbox" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>KBA&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="checkbox" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>OTP over EMAIL
    </p>
    <div class="mo2f_advanced_options_note" style="background-color: #bfe5e9;padding:12px"><b>Note:</b> This option will provide you alternate way of login in case your phone is lost, discharged or not with you.</div>

</div>

 <div class="mo_wpns_setting_layout" id = "premium_feature_specific_method">
		<?php
		$current_user = wp_get_current_user();
		$opt='OUT OF BAND EMAIL';
		?>
		<h3><?php echo mo2f_lt('Select the specific set of authentication methods for your users');?><a href='<?php echo $two_factor_premium_doc['Specific set of authentication methods'];?>' target="_blank"><span class="dashicons dashicons-text-page" style="font-size:19px;color:#269eb3;float: right;"></span></a></h3><hr>
		<p>
		<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" />
				 <?php echo __('For all Users','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				 <input type="radio" class="option_for_auth2" name="mo2f_all_users_method" value="0"  />
				 <?php echo __('Specific Roles','miniorange-2-factor-authentication'); ?>
				</p>
				<table class="mo2f_for_all_users" <?php if(!get_site_option('mo2f_all_users_method')){echo 'hidden';} ?> ><tbody>
				<tr>
                    <td>
                        <input type='checkbox'  name='mo2f_authmethods[]'  value='OUT OF BAND EMAIL' disabled/>Email Verification&nbsp;&nbsp;
                    </td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SMS' disabled />OTP Over SMS&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PHONE VERIFICATION' disabled />Phone Call Verification&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SOFT TOKEN' disabled />Soft Token&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='MOBILE AUTHENTICATION' disabled />QR Code Authentication&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PUSH NOTIFICATIONS' disabled />Push Notifications&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='GOOGLE AUTHENTICATOR' disabled />Google Authenticator&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='AUTHY 2-FACTOR AUTHENTICATION' disabled />AUTHY 2-FACTOR AUTHENTICATION&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='KBA' disabled />Security Questions (KBA)&nbsp;&nbsp;
				</td>
				</tr>
                <tr>
                    <td>
                        <input type='checkbox' name='mo2f_authmethods[]'  value='SMS AND EMAIL' disabled /><?php echo __('OTP Over SMS And Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
                    </td>
                    <td>
                        <input type='checkbox'  name='mo2f_authmethods[]'  value='OTP_OVER_EMAIL' disabled /><?php echo __('OTP Over Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
                    </td>
                </tr>
				</tbody>
				</div>
				</table>
				
		<?php
		$opt = (array) get_site_option('mo2f_auth_methods_for_users');
		$copt=array();
		$newcopt=array();
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		foreach($wp_roles->role_names as $id => $name)
		{
			$copt[$id]=get_site_option('mo2f_auth_methods_for_'.$id);
			if(empty($copt[$id])){
				$copt[$id]=array("No Two Factor Selected");
		}?>
			 <span class="mo2f_display_tab mo2f_btn_premium_features" style="border-radius:6px;padding: 7px 25px;"	 ID="mo2f_role_<?php echo $id ?>" onclick="displayTab('<?php echo $id ?>');" value="<?php echo $id ?>" <?php if(get_site_option('mo2f_all_users_method')){echo 'hidden';}?>> <?php echo $name ?></span>

			 <?php
		}
		?> <br><br><?php
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		print '<div> ';
		foreach($wp_roles->role_names as $id => $name) {	
				$setting = get_site_option('mo2fa_'.$id);
				$newcopt=$copt[$id];
				?>
				<table class="mo2f_for_all_roles" id="mo2f_for_all_<?php echo $id ?>" hidden><tbody>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='OUT OF BAND EMAIL' <?php echo (in_array("OUT OF BAND EMAIL", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('Email Verification','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='SMS' <?php echo (in_array("SMS", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('OTP Over SMS','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='PHONE VERIFICATION' <?php echo (in_array("PHONE VERIFICATION", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('Phone Call Verification','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='SOFT TOKEN' <?php echo (in_array("SOFT TOKEN", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('Soft Token','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='MOBILE AUTHENTICATION' <?php echo (in_array("MOBILE AUTHENTICATION", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('QR Code Authentication','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='PUSH NOTIFICATIONS' <?php echo (in_array("PUSH NOTIFICATIONS", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('Push Notifications','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='GOOGLE AUTHENTICATOR' <?php echo (in_array("GOOGLE AUTHENTICATOR", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('Google Authenticator','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='AUTHY 2-FACTOR AUTHENTICATION' <?php echo (in_array("AUTHY 2-FACTOR AUTHENTICATION", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('AUTHY 2-FACTOR AUTHENTICATION','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='KBA' <?php echo (in_array("KBA", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('Security Questions (KBA)','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='SMS AND EMAIL' <?php echo (in_array("SMS AND EMAIL", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('OTP Over SMS And Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='OTP_OVER_EMAIL' <?php echo (in_array("OTP_OVER_EMAIL", $newcopt)) ? 'checked="checked"' : '';  ?> disabled /><?php echo __('OTP Over Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				</tbody>
				</div>
				</table>
		<?php			
		}
		print '</div>';

	?>
	<div class="mo2f_advanced_options_note" style="background-color: #bfe5e9;padding:12px"><b>Note:</b> You can select which Two Factor methods you want to enable for your users. By default all Two Factor methods are enabled for all users of the role you have selected above.</div>
	<script>
	jQuery('.mo2f_display_tab').hide();
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('.mo2f_for_all_users').show();
	function displayTab(role){
		jQuery('.mo2f_display_tab').removeClass("mo2f_blue_premium_features");
		jQuery('.mo2f_display_tab').addClass("mo2f_btn_premium_features");
		jQuery('#mo2f_role_'+role).removeClass("mo2f_btn_premium_features");
		jQuery('#mo2f_role_'+role).addClass("mo2f_blue_premium_features");
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('#mo2f_for_all_'+role).show();
	}
	jQuery(".option_for_auth").click(function(){
		jQuery('.mo2f_display_tab').hide();
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('.mo2f_for_all_users').show();
	})
	jQuery(".option_for_auth2").click(function(){
		jQuery('.mo2f_display_tab').show();
		jQuery('.mo2f_for_all_users').hide();
	}
	)
	</script>
	<?php
	?>
</div>

<div class="mo_wpns_setting_layout" id = "premium_feature_skip_option">	
	<h3>Skip Option for Users During User Enrollment</h3><hr>
	<p>
	<input type="checkbox" class="option_for_auth" name=" Skip Option for users." value="1" checked="checked" disabled> Skip Option for users.
	</p>
	<div class="mo2f_advanced_options_note" style="background-color: #bfe5e9;padding:12px"><b>Note:</b>  If this option is enabled then users will have an option to skip User Enrollment.</div>

</div>

<div class="mo_wpns_setting_layout" id = "premium_feature_user_enrollment">	
	<h3>Email verification of Users during User Enrollment<a href='<?php echo $two_factor_premium_doc['Email verification of Users during Inline Registration'];?>' target="_blank">
			         	<span class="dashicons dashicons-text-page" style="font-size:19px;color:#269eb3;float: right;"></span>
	</a></h3><hr>
	<p>
	<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled> Enable users to edit their email address for registration with miniOrange.<br><br>
	<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>Skip e-mail verification by user.
	</p>
	<div class="mo2f_advanced_options_note" style="background-color: #bfe5e9;padding:12px"><b>Note:</b> If this option is enabled then users can edit their email during User Enrollment with miniOrange, and they will be prompted for e-mail verification. By selecting second option, the user will be silently registered with miniOrange without the need of e-mail verification.</div>

</div>

<div class="mo_wpns_setting_layout" id = "premium_feature_login_screen_option">
	<h3>Select Login Screen Options<a href='<?php echo $two_factor_premium_doc['Select login screen option'];?>'  target="_blank">
			         	<span class="dashicons dashicons-text-page" style="font-size:19px;color:#269eb3;float: right;"></span>
	</a></h3><hr>
	<p>
		<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled> Login with password + 2nd Factor <span style="color: red">(Recommended)</span>
	</p>

	<div class="mo2f_advanced_options_note" style="background-color: #bfe5e9;padding:12px"><b>Note:</b> By default 2nd Factor is enabled after password authentication. If you do not want to remember passwords anymore and just login with 2nd Factor, please select 2nd option.</div><br>
	<p>
		<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="0" disabled>
		 Login with 2nd Factor only <span style="color: red">(No password required)<a onclick="mo2f_login_with_username_only()">&nbsp;&nbsp;See Preview</a></span>
	</p>
	<div id="mo2f_login_with_username_only" style="display: none;">
		<?php
		echo '<div style="text-align:center;"><img  style="margin-top:5px;" src="'.$login_with_usename_only_url.'"></div><br>';?>
	</div>
	<div class="mo2f_advanced_options_note" style="background-color: #bfe5e9;padding:12px"><b>Note:</b> Checking this option will add login with your phone button below default login form. Click above link to see the preview.</div>

	<br>
	<p>
	<input type="checkbox" class="option_for_auth" value="0" disabled>I want to hide default login form.<a onclick="mo2f_hide_login_form()">&nbsp;&nbsp;See Preview</a>
</p>
	<div id="mo2f_hide_login_form" style="display: none;">
		<?php
		echo '<div style="text-align:center;"><img  style="margin-top:5px;" src="'.$hide_login_form_url.'"></div><br>';?>
	</div>

	<div class="mo2f_advanced_options_note" style="background-color: #bfe5e9;padding:12px"><b>Note:</b> Checking this option will hide default login form and just show login with your phone. Click above link to see the preview.</div>

</div>


<script type="text/javascript">
	function mo2f_login_with_username_only()
	{
		jQuery('#mo2f_login_with_username_only').toggle();
	}
	function mo2f_hide_login_form()
	{
		jQuery('#mo2f_hide_login_form').toggle();
	}
</script>