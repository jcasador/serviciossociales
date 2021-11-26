<?php 

function mo2f_configure_miniorange_authenticator($user){?>
    <div id="mo2f_width">
        <?php $mobile_reg_status = get_user_meta($user->ID,'mobile_registration_status',true);
        if(!$mobile_reg_status) {
            download_instruction_for_mobile_app($mobile_reg_status);
        } ?>
    </div>
    <div>
        <h3><?php echo mo2f_lt('Step-2 : Scan QR code');?></h3>
        <hr>
        <form name="f" method="post" action="">
            <input type="hidden" name="option" value="mo_auth_refresh_mobile_qrcode" />
    		<input type="hidden" name="mo_auth_refresh_mobile_qrcode_nonce"	value="<?php echo wp_create_nonce( "mo-auth-refresh-mobile-qrcode-nonce" ) ?>"/>
            <?php if($mobile_reg_status) { ?>
                <div id="reconfigurePhone">
                    <a data-toggle="collapse" href="#mo2f_show_download_app" aria-expanded="false">
                        <?php echo mo2f_lt( 'Click here to see Authenticator App download instructions');?>.</a>
                    <div id="mo2f_show_download_app" class="mo2f_collapse">
                    </div>
                    <br>
                    <h4><?php echo mo2f_lt('Please click on \'Reconfigure your phone\' button below to see QR Code.');?></h4>

                    <input type="button" name="back" id="go_back" class="mo_wpns_button mo_wpns_button1" value="<?php echo mo2f_lt('Back');?>" />

                    <input type="submit" name="submit" class="mo_wpns_button mo_wpns_button1" value="<?php echo mo2f_lt('Reconfigure your phone');?>" />
                </div>
            <?php } else {?>
                <div id="configurePhone" style="padding:20px;">
                    <input type="button" name="back" id="go_back" class="mo_wpns_button mo_wpns_button1" value="<?php echo mo2f_lt('Back');?>" />
                    <input type="submit" name="submit" class="mo_wpns_button mo_wpns_button1" value="<?php echo mo2f_lt('Configure your phone');?>" />
                </div>
            <?php } ?>
        </form>
        <?php if(isset($_SESSION[ 'mo2f_show_qr_code' ]) && $_SESSION[ 'mo2f_show_qr_code' ]=='MO_2_FACTOR_SHOW_QR_CODE' && isset($_POST[ 'option']) && $_POST[ 'option']=='mo_auth_refresh_mobile_qrcode' ){ 
            initialize_mobile_registration(); 
            if($mobile_reg_status) { ?>
                <script>
                    jQuery("#mo2f_app_div").show();
                </script>
            <?php } else{ ?>
                <script>
                    jQuery("#mo2f_app_div").hide();
                </script>
            <?php } 
        } else{ ?>
            <br>
            <form name="f" method="post" action="" id="mo2f_go_back_form">
                <input type="hidden" name="option" value="mo2f_go_back" />
        		<input type="hidden" name="mo2f_go_back_nonce" value="<?php echo wp_create_nonce( "mo2f-go-back-nonce" ) ?>"/>
            </form>
            <script>
                jQuery('#go_back').click(function() {
                    jQuery('#mo2f_go_back_form').submit();
                });
            </script>
        <?php } ?>
    </div>
    <?php 
} 

function download_instruction_for_mobile_app( $mobile_reg_status){ ?>
    <div id="mo2f_app_div" class="mo_margin_left">
        <a class="mo_app_link" data-toggle="collapse" href="#mo2f_sub_header_app" aria-expanded="false">
            <h3 class="mo2f_authn_header"><?php echo mo2f_lt('Step-1 : Download the miniOrange');?> <span style="color: #F78701;"> <?php echo mo2f_lt('Authenticator');?></span> <?php echo mo2f_lt('App');?>
        </h3>
        </a>
        <hr class="mo_hr">
        <div class="mo2f_collapse in" id="mo2f_sub_header_app">
            <table width="100%;" id="mo2f_inline_table">
                <tr id="mo2f_inline_table">
                    <td style="padding:10px;">
                        <h4 id="user_phone_id"><?php echo mo2f_lt('iPhone Users');?></h4>
                        <hr>
                        <ol>
                            <li>
                                <?php echo mo2f_lt( 'Go to App Store');?>
                            </li>
                            <li>
                                <?php echo mo2f_lt( 'Search for');?> <b><?php echo mo2f_lt('miniOrange');?></b>
                            </li>
                            <li>
                                <?php echo mo2f_lt( 'Download and install ');?><span style="color: #F78701;"><?php echo mo2f_lt('miniOrange');?><b> <?php echo mo2f_lt('Authenticator');?></b></span>
                                <?php echo mo2f_lt( 'app ');?>(<b><?php echo mo2f_lt('NOT MOAuth');?></b>)
                            </li>
                        </ol>
                        <br>
                        <a style="margin-left:10%" target="_blank" href="https://apps.apple.com/app/id1482362759"><img src="<?php echo plugins_url( 'includes/images/appstore.png' , dirname(dirname(dirname(__FILE__))) );?>" style="width:120px; height:45px; margin-left:6px;">
                        </a>
                    </td>
                    <td style="padding:10px;">
                        <h4 id="user_phone_id"><?php echo mo2f_lt('Android Users');?></h4>
                        <hr>
                        <ol>
                            <li>
                                <?php echo mo2f_lt( 'Go to Google Play Store.');?>
                            </li>
                            <li>
                                <?php echo mo2f_lt( 'Search for ');?><b><?php echo mo2f_lt('miniOrange.');?></b>
                            </li>
                            <li>
                                <?php echo mo2f_lt( 'Download and install');?> <span style="color: #F78701;"><b><?php echo mo2f_lt('Authenticator');?></b></span>
                                <?php echo mo2f_lt( 'app');?> (<b><?php echo mo2f_lt('NOT miniOrange Authenticator/MOAuth');?> </b>)
                            </li>
                        </ol>
                        <br>
                        <a style="margin-left:10%" target="_blank" href="https://play.google.com/store/apps/details?id=com.miniorange.android.authenticator&hl=en"><img src="<?php echo plugins_url( 'includes/images/playStore.png' , dirname(dirname(dirname(__FILE__))) );?>" style="width:120px; height:=45px; margin-left:6px;"></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php 
} 
function initialize_mobile_registration() {
    $data=$_SESSION[ 'mo2f_qrCode' ]; ?>
    <div style="padding: 20px;">
        <p>
            <?php echo mo2f_lt( 'Open your miniOrange');?><b> <?php echo mo2f_lt('Authenticator');?></b> app and
            <?php echo mo2f_lt( 'click on');?> <b> <?php echo mo2f_lt('Add Account');?></b>
            <?php echo mo2f_lt( 'to scan the QR Code. Your phone should have internet connectivity to scan QR code.');?>
        </p>
        <div>
            <p style="color:indianred;">
                <?php echo mo2f_lt( 'I am not able to scan the QR code, ');?>
                <a data-toggle="collapse" href="#mo2f_scanqrcode" aria-expanded="false">
                    <?php echo mo2f_lt( 'click here ');?>
                </a>
            </p>
        </div>
        <div class="mo2f_collapse" id="mo2f_scanqrcode">
            <?php echo mo2f_lt( 'Follow these instructions below and try again.');?>
            <ol>
                <li>
                    <?php echo mo2f_lt( 'Make sure your desktop screen has enough brightness.');?>
                </li>
                <li>
                    <?php echo mo2f_lt( 'Open your app and click on Configure button to scan QR Code again.');?>
                </li>
                <li>
                    <?php echo mo2f_lt( 'If you get a cross mark on QR Code then click on \'Refresh QR Code\' link.');?>
                </li>
            </ol>
        </div>
        <table class="mo2f_settings_table">
            <a href="#refreshQRCode">
                <?php echo mo2f_lt( 'Click here to Refresh QR Code.');?>
            </a>
            <div id="displayQrCode" style="margin-left:250px;">
                <br>
                <?php echo '<img style="width:200px;" src="data:image/jpg;base64,' . $data . '" />'; ?>
            </div>
        </table>
        <br>
        <div id="mobile_registered">
            <form name="f" method="post" id="mobile_register_form" action="" class="mo2f_display_none_forms">
                <input type="hidden" name="option" value="mo2f_configure_miniorange_authenticator_validate" />
				<input type="hidden" name="mo2f_configure_miniorange_authenticator_validate_nonce"
						value="<?php echo wp_create_nonce( "mo2f-configure-miniorange-authenticator-validate-nonce" ) ?>"/>
            </form>
        </div>
        <form name="f" method="post" action="" id="mo2f_go_back_form" class="mo2f_display_none_forms">
            <input type="hidden" name="option" value="mo2f_go_back" />
			<input type="hidden" name="mo2f_go_back_nonce"
						value="<?php echo wp_create_nonce( "mo2f-go-back-nonce" ) ?>"/>
        </form>
        <form name="f" method="post" id="mo2f_refresh_qr_form" action="" class="mo2f_display_none_forms">
            <input type="hidden" name="option" value="mo_auth_refresh_mobile_qrcode" />
			<input type="hidden" name="mo_auth_refresh_mobile_qrcode_nonce"
						value="<?php echo wp_create_nonce( "mo-auth-refresh-mobile-qrcode-nonce" ) ?>"/>
        </form>
        <input type="button" name="back" id="back_to_methods" class="mo_wpns_button mo_wpns_button1" value="<?php echo mo2f_lt('Back');?>" />
        <br>
        <br>
    </div>
    <script>
        jQuery('#back_to_methods').click(function(e) {
            jQuery('#mo2f_go_back_form').submit();
        });
        jQuery('a[href=\"#refreshQRCode\"]').click(function(e) {
            jQuery('#mo2f_refresh_qr_form').submit();
        });
        jQuery("#configurePhone").hide();
        jQuery("#reconfigurePhone").hide();
        var timeout;
        pollMobileRegistration();

        function pollMobileRegistration() {
            var transId = "<?php echo $_SESSION[ 'mo2f_transactionId' ];  ?>";
            var jsonString = "{\"txId\":\"" + transId + "\"}";
            var postUrl = "<?php echo MO_HOST_NAME;  ?>" + "/moas/api/auth/registration-status";
            jQuery.ajax({
                url: postUrl,
                type: "POST",
                dataType: "json",
                data: jsonString,
                contentType: "application/json; charset=utf-8",
                success: function(result) {
                    var status = JSON.parse(JSON.stringify(result)).status;
                    if (status == 'SUCCESS') {
                        var content = "<br><div id='success'><img style='width:165px;margin-top:-1%;margin-left:2%;' src='" + "<?php echo plugins_url( 'includes/images/right.png' , dirname(dirname(dirname(__FILE__))) );?>" + "' /></div>";
                        jQuery("#displayQrCode").empty();
                        jQuery("#displayQrCode").append(content);
                        setTimeout(function() {
                            jQuery("#mobile_register_form").submit();
                        }, 1000);
                    } else if (status == 'ERROR' || status == 'FAILED') {
                        var content = "<br><div id='error'><img style='width:165px;margin-top:-1%;margin-left:2%;' src='" + "<?php echo plugins_url( 'includes/images/wrong.png' , dirname(dirname(dirname(__FILE__))) );?>" + "' /></div>";
                        jQuery("#displayQrCode").empty();
                        jQuery("#displayQrCode").append(content);
                        jQuery("#messages").empty();

                        jQuery("#messages").append("<div class='error mo2f_error_container'> <p class='mo2f_msgs'>An Error occured processing your request. Please try again to configure your phone.</p></div>");
                    } else {
                        timeout = setTimeout(pollMobileRegistration, 3000);
                    }
                }
            });
        }
        jQuery('html,body').animate({
            scrollTop: jQuery(document).height()
        }, 800);
    </script>
    <?php 
} ?>