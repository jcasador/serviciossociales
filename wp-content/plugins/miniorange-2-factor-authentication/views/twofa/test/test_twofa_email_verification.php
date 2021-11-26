<?php
 function mo2f_test_email_verification() {
    $mo2f_dirName = dirname(__FILE__);
    $mo2f_dirName = explode('wp-content', $mo2f_dirName);
    $mo2f_dirName = explode('views', $mo2f_dirName[1]);

    $checkEV = get_site_option('siteurl').DIRECTORY_SEPARATOR."wp-content".$mo2f_dirName[0]."handler".DIRECTORY_SEPARATOR."two_fa_pass2login.php";
    $checkEV = 'http://localhost/onpremchanges/wordpress\wp-content\plugins\miniorange-2-factor-authentication\viewshandler\two_fa_pass2login.php';
  ?>

    <h3><?php echo mo2f_lt( 'Test Email Verification' ); ?></h3>
    <hr>
    <div>
        <br>
        <br>
        <center>
            <h3><?php echo mo2f_lt( 'A verification email is sent to your registered email.' ); ?>
                <br>
                <?php echo mo2f_lt( 'We are waiting for your approval...' ); ?></h3>
            <img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif', dirname(dirname(dirname(__FILE__))) ); ?>"/>
        </center>

        <input type="button" name="back" id="go_back" class="mo_wpns_button mo_wpns_button1"
               value="<?php echo mo2f_lt( 'Back' ); ?>"
               style="margin-top:100px;margin-left:10px;"/>
    </div>

    <form name="f" method="post" action="" id="mo2f_go_back_form">
        <input type="hidden" name="option" value="mo2f_go_back"/>
		<input type="hidden" name="mo2f_go_back_nonce"
						value="<?php echo wp_create_nonce( "mo2f-go-back-nonce" ) ?>"/>
    </form>
    <form name="f" method="post" id="mo2f_out_of_band_success_form" action="">
        <input type="hidden" name="option" value="mo2f_out_of_band_success"/>
		<input type="hidden" name="mo2f_out_of_band_success_nonce"
						value="<?php echo wp_create_nonce( "mo2f-out-of-band-success-nonce" ) ?>"/>
        <input type="hidden" name="TxidEmail" value="<?php echo $_SESSION['txid']; ?>"/>
    </form>
    <form name="f" method="post" id="mo2f_out_of_band_error_form" action="">
        <input type="hidden" name="option" value="mo2f_out_of_band_error"/>
		
		<input type="hidden" name="mo2f_out_of_band_error_nonce"
						value="<?php echo wp_create_nonce( "mo2f-out-of-band-error-nonce" ) ?>"/>
    </form>

    <script type="text/javascript">
        jQuery('#go_back').click(function () {
            jQuery('#mo2f_go_back_form').submit();
        });
    </script>
    <?php

    if(MO2F_IS_ONPREM)
    {
        $otpToken = isset($_SESSION['otpToken']) ? $_SESSION['otpToken'] : '';
        $txid     = isset($_SESSION["txid"])     ? $_SESSION["txid"]     : ''; 
    ?>
    <script type="text/javascript">
        var timeout;
        pollMobileValidation();
        function pollMobileValidation() {
            var otpToken = "<?php echo $otpToken;  ?>";
            var jsonString = "{\"otpToken\":\"" + otpToken + "\"}"; 
            var txid = '<?php echo $txid;?>';
            var data = {
                'action'                    : 'mo_two_factor_ajax',
                'mo_2f_two_factor_ajax'     : 'CheckEVStatus', 
                'txid'                      : txid
            };
            jQuery.post(ajaxurl, data, function(response) {
                var response = response.replace(/\s+/g,' ').trim();
                var status = response;
                if (status == '1') {
                    jQuery('#mo2f_out_of_band_success_form').submit();
                } else if (status == 'ERROR' || status == 'FAILED' || status == 'DENIED' || status =='0') {
                    jQuery('#mo2f_out_of_band_error_form').submit();
                } else {
                    timeout = setTimeout(pollMobileValidation, 1000);
                }
            });
            
        }

</script>
    <?php 
    }
    else 
    {
        $mo2f_transactionId = isset($_SESSION['mo2f_transactionId']) ? $_SESSION['mo2f_transactionId'] : '';

    ?>
        <script type="text/javascript">
        var timeout;
        pollMobileValidation();
        function pollMobileValidation() {
            var transId = "<?php echo $mo2f_transactionId; ?>";
            var jsonString = "{\"txId\":\"" + transId + "\"}";
            var postUrl = "<?php echo MO_HOST_NAME;  ?>" + "/moas/api/auth/auth-status";

            jQuery.ajax({
                url: postUrl,
                type: "POST",
                dataType: "json",
                data: jsonString,
                contentType: "application/json; charset=utf-8",
                success: function (result) {
                    var status = JSON.parse(JSON.stringify(result)).status;
                    
                    if (status == 'SUCCESS') {
                        jQuery('#mo2f_out_of_band_success_form').submit();
                    } else if (status == 'ERROR' || status == 'FAILED' || status == 'DENIED') {
                        jQuery('#mo2f_out_of_band_error_form').submit();
                    } else {
                        timeout = setTimeout(pollMobileValidation, 3000);
                    }
                }
            });
        }
        </script>

<?php }
}

?>
