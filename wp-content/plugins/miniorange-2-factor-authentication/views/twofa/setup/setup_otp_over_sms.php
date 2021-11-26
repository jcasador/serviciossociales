<?php

function mo2f_configure_otp_over_sms( $user ) {
	global $Mo2fdbQueries;
	$mo2f_user_phone = $Mo2fdbQueries->get_user_detail( 'mo2f_user_phone', $user->ID );
	$user_phone      = $mo2f_user_phone ? $mo2f_user_phone : get_option( 'user_phone_temp' );

	?>

    <h3><?php echo mo2f_lt( 'Configure OTP over SMS' ); ?>
    </h3>
    <h4> Remaining SMS Transaction: <?php echo get_site_option('cmVtYWluaW5nT1RQVHJhbnNhY3Rpb25z');?> </h4> 
    <hr>
    <form name="f" method="post" action="" id="mo2f_verifyphone_form">
        <input type="hidden" name="option" value="mo2f_configure_otp_over_sms_send_otp"/>
		<input type="hidden" name="mo2f_configure_otp_over_sms_send_otp_nonce"
						value="<?php echo wp_create_nonce( "mo2f-configure-otp-over-sms-send-otp-nonce" ) ?>"/>

        <div style="display:inline;">
            <input class="mo2f_table_textbox" style="width:200px;" type="text" name="verify_phone" id="phone"
                   value="<?php echo $user_phone ?>" pattern="[\+]?[0-9]{1,4}\s?[0-9]{7,12}"
                   title="<?php echo mo2f_lt( 'Enter phone number without any space or dashes' ); ?>"/><br>
            <input type="submit" name="verify" id="verify" class="mo_wpns_button mo_wpns_button1"
                   value="<?php echo mo2f_lt( 'Verify' ); ?>"/>
        </div>
    </form>
    <form name="f" method="post" action="" id="mo2f_validateotp_form">
        <input type="hidden" name="option" value="mo2f_configure_otp_over_sms_validate"/>
		<input type="hidden" name="mo2f_configure_otp_over_sms_validate_nonce"
						value="<?php echo wp_create_nonce( "mo2f-configure-otp-over-sms-validate-nonce" ) ?>"/>
        <p><?php echo mo2f_lt( 'Enter One Time Passcode' ); ?></p>
        <input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token"
               placeholder="<?php echo mo2f_lt( 'Enter OTP' ); ?>" style="width:95%;"/>
        <a href="#resendsmslink"><?php echo mo2f_lt( 'Resend OTP ?' ); ?></a>
        <br><br>
        <input type="button" name="back" id="go_back" class="mo_wpns_button mo_wpns_button1"
               value="<?php echo mo2f_lt( 'Back' ); ?>"/>
        <input type="submit" name="validate" id="validate" class="mo_wpns_button mo_wpns_button1"
               value="<?php echo mo2f_lt( 'Validate OTP' ); ?>"/>
    </form><br>
    <form name="f" method="post" action="" id="mo2f_go_back_form">
        <input type="hidden" name="option" value="mo2f_go_back"/>
		<input type="hidden" name="mo2f_go_back_nonce"
						value="<?php echo wp_create_nonce( "mo2f-go-back-nonce" ) ?>"/>
    </form>
    <script>
        jQuery("#phone").intlTelInput();
        jQuery('#go_back').click(function () {
            jQuery('#mo2f_go_back_form').submit();
        });
        jQuery('a[href=\"#resendsmslink\"]').click(function (e) {
            jQuery('#mo2f_verifyphone_form').submit();
        });

    </script>
	<?php
}

?>