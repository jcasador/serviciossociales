<?php
$setup_dirName = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'twofa'.DIRECTORY_SEPARATOR.'link_tracer.php';
include $setup_dirName;

?>

<div class="mo_wpns_setting_layout">

    <form name="form_custom_form_config" method="post" action="" id="mo2f_custom_form_config">

        <input type="button" style="float: right" class="button button-primary" value="Save Settings"
               id="mo2f_form_config_save"  name= "mo2f_form_config_save">
        <input type="hidden" id="mo2f_nonce_save_form_settings" name="mo2f_nonce_save_form_settings"
               value="<?php echo wp_create_nonce( "mo2f-nonce-save-form-settings" ) ?>"/>
        <h3> <?php echo 'Custom Registration Forms';?> </h3>
        <hr>
        <input type="checkbox" id="use_shortcode_config" name="use_shortcode_config" value="yes" checked>
        <label for="use_shortcode_config">Enable Shortcode</label>
        <h4> <?php echo 'Enables/Disables OTP over SMS and OTP over EMAIL for custom Registration Forms where You have added the Shortcode'?></h4>
        <?php
        $isRegistered = get_site_option('mo2f_customerkey')? get_site_option('mo2f_customerkey') : 'false';
        if($isRegistered=='false')
        {
            ?><br>
            <div style="padding: 10px;border: red 1px solid">
                <a href="admin.php?page=mo_2fa_account"> Register/Login</a> with miniOrange to Enable the Shortcode
            </div>
            <?php
        }
        ?>
        <h2> Shortcode </h2>
        <hr>
        <p style="color: red">*Add this on the page where you have your registration form to enable OTP verification for the same.</p>
        <div style="padding: 10px;border: 1px #DCDCDC solid">
            <h4 class="shortcode_form" style="font-family: monospace">[mo2f_enable_register]</h4>
        </div>
        <h3>Select Authentication Method</h3>
        <hr>
        <table>
            <tbody>
            <tr>
                <td>
                    <input type="checkbox" name="mo2f_method_phone" id="mo2f_method_phone" value="phone" <?php if(get_site_option('mo2f_custom_auth_type')=='phone' or get_site_option('mo2f_custom_auth_type')=='both') {echo "checked";}?> >
                    <label for="mo2f_method_phone"> OTP over SMS </label>
                </td>
                <td>
                    <input type="checkbox" name="mo2f_method_email" id="mo2f_method_email" value="email" <?php if(get_site_option('mo2f_custom_auth_type')=='email' or get_site_option('mo2f_custom_auth_type')=='both') {echo "checked";}?>>
                    <label for="mo2f_method_email"> OTP over Email </label>
                </td>
            </tr>

            </tbody></table>
        <table style="padding: 10px;">
            <tbody >
            <tr>
                <?php
                $EmailTransactions  = MoWpnsUtility::get_mo2f_db_option('cmVtYWluaW5nT1RQ', 'site_option');
                $EmailTransactions 	= $EmailTransactions? $EmailTransactions : 0;
                $SMSTransactions = get_site_option('cmVtYWluaW5nT1RQVHJhbnNhY3Rpb25z')?get_site_option('cmVtYWluaW5nT1RQVHJhbnNhY3Rpb25z'):0;
                $isRegistered = get_site_option('mo2f_customerkey')? get_site_option('mo2f_customerkey') : 'false';
                if($isRegistered!='false')
                {
                ?>
                <td style="padding: 5px;">SMS Transactions: <strong><?php echo $SMSTransactions;?></strong></td>
                <td> </td>
                <td style="padding: 5px;">Email Transactions: <strong><?php echo $EmailTransactions;?></strong></td>  </tr>
            <tr>
                <td>
                    <p style="color:red" >*You will get 10 SMS and 30 EMAIL Transactions in Free Account, to Recharge <a href="<?php echo MO_HOST_NAME.'/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=otp_recharge_plan' ?>" target="_blank">Click Here</a></p>
                </td>
            </tr>
            <?php
            }
            ?>

            </tbody>
        </table>
        <h3>Custom Form Selectors</h3>
        <p style="color:red;padding:10px;font-style: italic; border: teal 1px solid">If you need any help finding the
            selectors or facing any other issue, reach out to us at <a href="mailto:2fasupport@xecurify.com">2fasupport@xecurify.com</a>
        </p>
        <div style="padding: 20px;border: 1px #DCDCDC solid">
            <table>
                <h3>Click on Form name to autofill Selectors</h3>
                <tbody>
                <tr>
                    <td><div class ="button" style="<?php if($is_woocommerce) { echo 'color : white; background-color: teal';}?>" name="wc_auto" id="wc_auto"> WooCommerce</div> </td>
                    <td><div class ="button" style="<?php if($is_bbpress) { echo 'color:white; background-color: teal';}?>" name="bbpress_auto" id="bbpress_auto">BB Press</div> </td>
                    <td><div class ="button" style="<?php if($is_custom) { echo 'color:white; background-color: teal';}?>" name="custom_auto" id="custom_auto">Custom</div> </td>
                </tr>
                </tbody>
            </table>
            <h4 id="enterMessage" name="enterMessage" style="display: none;padding:8px; color: white; background-color: teal">Enter Selectors for your Form</h4>
            <h3>Form Selector<span style="color: red;font-size: 14px">*</span></h3>
            <input type="text" value="<?php echo get_site_option('mo2f_custom_form_name');?>" style="width: 100%" name="mo2f_shortcode_form_selector" id="mo2f_shortcode_form_selector" placeholder="Example #form_id" <?php if($is_any_of_woo_bb) { echo 'disabled';}?> >

            <h3>Email Field Selector <span style="color: red;font-size: 14px">*</span></h3>
            <h4>Enter selector for Email field</h4>
            <input type="text" style="width: 100%" value="<?php echo get_site_option('mo2f_custom_email_selector');?>" name="mo2f_shortcode_email_selector" id="mo2f_shortcode_email_selector" placeholder="example #email_field_id" <?php if($is_any_of_woo_bb) { echo 'disabled';}?> >

            <h3>Phone Field Selector</h3>
            <h4>Enter selector for Phone field</h4>
            <input type="text" style="width: 100%" value="<?php echo get_site_option('mo2f_custom_phone_selector');?>" name="mo2f_shortcode_phone_selector" id="mo2f_shortcode_phone_selector" placeholder="example #phone_field_id" >

            <h3>Submit Button Selector <span style="color: red;font-size: 14px">*</span></h3>
            <h4>Enter selector for Submit Button</h4>
            <input type="text" style="width: 100%" value="<?php echo get_site_option('mo2f_custom_submit_selector');?>" name="mo2f_shortcode_submit_selector" id="mo2f_shortcode_submit_selector" placeholder="example #submit_button_id" <?php if($is_any_of_woo_bb) { echo 'disabled';}?> >
            <p style="color:red;">* Required</p>
        </div>


        <h2>NOTE : Choosing your Selector</h2>
        <table>
            <tbody>
            <tr>
                <td>
                    <h4 style="color: red">If a Element has ID attribute then use <code>#element-id</code><sup
                                style="color: #2EB150"> (*Recommended)</sup></h4>
                    For <code> &lt;input type="submit" value="Register" <strong>id="um-submit-btn</strong>"&gt; </code>
                </td>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Selector will be <code>#um-submit-btn</code> <span
                                style="color: red">(With # as a Prefix)</span>
                    </h4></td>
            </tr>
            <tr>

                <td>
                    <h4 style="color: red">If a Element has Class attribute then use <code>.element-class</code></h4>
                    For <code> &lt;div <strong>class ="um-form"</strong> data-mode="register"&gt; </code>
            </tr>
            <tr>
                <td><h4>Selector will be <code>.um-form</code><span
                                style="color: red"> (With . as a Prefix)</span></h4></td>
            </tr>
            </tbody>
        </table>


    </form>
    <script>
        jQuery(document).ready(function () {
            let $mo = jQuery;
            let customForm = false;
            is_registered   = '<?php echo $is_registered; ?>';
            if(!is_registered)
            {
                $mo('#use_shortcode_config').prop('checked',false)
                $mo('#use_shortcode_config').prop('disabled',true)
            }

            $mo('#bbpress_auto').click(function()
            {
                $mo('#bbpress_auto').css('color','white');
                $mo('#bbpress_auto').css('background-color','teal');
                $mo('#wc_auto').css('color','');
                $mo('#wc_auto').css('background-color','');
                $mo('#custom_auto').css('color','');
                $mo('#custom_auto').css('background-color','');
                $mo('#mo2f_shortcode_form_selector').val('.bbp-login-form');
                $mo('#mo2f_shortcode_submit_selector').val('.user-submit');
                $mo('#mo2f_shortcode_email_selector').val('#user_email');
                $mo('.shortcode_form').text('[mo2f_enable_register]');
                $mo('#enterMessage').css('display','none');
                $mo('#mo2f_shortcode_form_selector').attr('disabled',true);
                $mo('#mo2f_shortcode_submit_selector').attr('disabled',true);
                $mo('#mo2f_shortcode_email_selector').attr('disabled',true);
            });

            $mo('#wc_auto').click(function()
            {
                $mo('#wc_auto').css('color','white');
                $mo('#wc_auto').css('background-color','teal');
                $mo('#bbpress_auto').css('color','');
                $mo('#bbpress_auto').css('background-color','');
                $mo('#custom_auto').css('color','');
                $mo('#custom_auto').css('background-color','');
                $mo('#mo2f_shortcode_form_selector').val('.woocommerce-form woocommerce-form-register');
                $mo('#mo2f_shortcode_submit_selector').val('.woocommerce-form-register__submit');
                $mo('#mo2f_shortcode_email_selector').val('#reg_email');
                $mo('.shortcode_form').text('[mo2f_enable_register]');
                $mo('#enterMessage').css('display','none');
                $mo('#mo2f_shortcode_form_selector').attr('disabled',true);
                $mo('#mo2f_shortcode_submit_selector').attr('disabled',true);
                $mo('#mo2f_shortcode_email_selector').attr('disabled',true);
            });

            $mo('#custom_auto').click(function()
            {
                customForm = true;
                $mo('#enterMessage').css('display','inherit');
                $mo('#wc_auto').css('color','');
                $mo('#wc_auto').css('background-color','');
                $mo('#bbpress_auto').css('color','');
                $mo('#bbpress_auto').css('background-color','');
                $mo('#custom_auto').css('color','white');
                $mo('#custom_auto').css('background-color','teal');
                $mo('#mo2f_shortcode_form_selector').attr('disabled',false);
                $mo('#mo2f_shortcode_submit_selector').attr('disabled',false);
                $mo('#mo2f_shortcode_email_selector').attr('disabled',false);
                $mo('#mo2f_shortcode_form_selector').val('<?php echo get_site_option('mo2f_custom_form_name')?>');
                $mo('#mo2f_shortcode_submit_selector').val('<?php echo get_site_option('mo2f_custom_submit_selector');?>');
                $mo('#mo2f_shortcode_email_selector').val('<?php echo get_site_option('mo2f_custom_email_selector');?>');
                $mo('.shortcode_form').text('[mo2f_enable_register]');
            });


            $mo('#mo2f_form_config_save').click(function () {
                is_registered   = '<?php echo $is_registered; ?>';
                if(!is_registered)
                    error_msg("Please Register/Login with miniOrange");
                else{
                    let sms,email,authType,enableShortcode
                    enableShortcode = $mo('#use_shortcode_config').is(':checked');
                    sms             = $mo('#mo2f_method_phone').is(':checked');
                    email           = $mo('#mo2f_method_email').is(':checked');
                    email_selector  = $mo('#mo2f_shortcode_email_selector').val();
                    phone_selector  = $mo('#mo2f_shortcode_phone_selector').val();
                    form_selector   = $mo('#mo2f_shortcode_form_selector').val();
                    submit_selector = $mo('#mo2f_shortcode_submit_selector').val();
                    authType        = (email === true && sms === true) ? 'both' : (email === true && sms===false) ? 'email' : 'phone'
                    error          = "";
                    if(authType === 'both' || authType === 'email')
                        if(email_selector === ''){
                            error = "Add email selector to use OTP Over Email";
                        }
                    if(authType === 'both' || authType === 'phone')
                        if(phone_selector === ''){
                            error = "Add phone selector to use OTP Over SMS";
                        }

                    if(!validate_selector(email_selector))
                        error = "NOTE: Choosing your Selector<br>Element\'s ID Selector looks like #element_id <br> Element\'s name Selector looks like input[name=element_name]";
                    if(error != ""){
                        error_msg(error);
                    }
                    else{
                        let data =  {
                            'action'                        : 'mo_two_factor_ajax',
                            'mo_2f_two_factor_ajax'         : 'mo2f_save_custom_form_settings',
                            'mo2f_nonce_save_form_settings' :  $mo('#mo2f_nonce_save_form_settings').val(),
                            'submit_selector'               :  submit_selector,
                            'form_selector'                 :  form_selector,
                            'email_selector'                :  email_selector,
                            'phone_selector'                :  phone_selector,
                            'authType'                      :  authType,
                            'customForm'                    :  customForm,
                            'enableShortcode'               :  enableShortcode
                        };
                        jQuery.post(ajaxurl, data, function(response) {
                            if(response.saved === false)
                            {
                                error_msg('One or more fields are empty.');
                            }
                            if(response.saved === true)
                            {
                                success_msg("Selectors Saved Successfully.");
                            }
                        });
                    }
                }
            });
        });

        function validate_selector(selector){
            let is_valid = false
            if(/^#/.test(selector))
                is_valid = true
            if(/^\./.test(selector))
                is_valid = true
            if(/^input\[name=/.test(selector))
                is_valid = true

            return is_valid;
        }



    </script>

</div>

<div class="mo_wpns_setting_layout">
    <h2>Custom Login Forms</h2>
    <p>We support most of the login forms present on the wordpress. And our plugin is tested with almost all the forms like Woocommerce, Ultimate Member, Restrict Content Pro and so on.</p>

    <div>
        <table class="customloginform" style="width: 100%;" align="left">
            <tr>
                <th style="width: 65%">
                    Custom Login form
                </th>
                <th style="width: 22%">
                    Show 2FA prompt on Custom login

                </th>
                <th style="width: 13%">
                    Documents
                </th>
            </tr>
            <tr>
                <td>
                    <?php echo '<img style="width:30px; height:30px;display: inline;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/woocommerce.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit; padding-right: 50px;">Woocommerce</h3>
                </td>
                <td style="align-items: right;">
                    <form id="woocommerce_login_prompt_form" method="post">
                        <div align="center">
                            <input  type="checkbox" name="woocommerce_login_prompt"  onchange="document.getElementById('woocommerce_login_prompt_form').submit();" <?php if(get_site_option('mo2f_woocommerce_login_prompt')){?> checked <?php } ?> <?php if(!MoWpnsUtility::get_mo2f_db_option('mo2f_enable_2fa_prompt_on_login_page', 'site_option')){?> checked <?php } ?>/>
                        </div>
                        <input type="hidden" name="option" value="woocommerce_disable_login_prompt">

                    </form>
                </td>
                <td>
                    <div style="text-align: center;">
                        <a href='<?php echo $two_factor_premium_doc['Woocommerce'];?>' target="blank"><span class="dashicons dashicons-text-page mo2f_doc_icon_style" style="font-size: 25px;color: #199C95"></span></a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo '<img style="width:30px; height:30px;display: inline;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/ultimate_member.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit;">Ultimate Member</h3>
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name=""  checked>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo '<img style="width:30px; height:30px;display: inline;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/restrict_content_pro.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit;">Restrict Content Pro</h3>
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="" checked>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td >
                    <?php echo '<img style="width:30px; height:30px;display: inline;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/theme_my_login.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit;">My Theme Login</h3>
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="" checked>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo '<img style="width:30px; height:30px;display: inline;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/user_registration.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit;">User Registration</h3>
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="" checked>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo '<img style="width:30px; height:30px;display: inline;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/Custom_Login_Page_Customizer_LoginPress.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit;">Custom Login Page Customizer | LoginPress</h3>
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="" checked>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo '<img style="width:30px; height:30px;display: inline;float: left;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/Admin_Custom_Login.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit;">Admin Custom Login</h3>
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" name="" checked>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo '<img style="width:30px; height:30px;display: inline;float: left;" src="'.dirname(plugin_dir_url(dirname(__FILE__))).'/includes/images/RegistrationMagic_Custom_Registration_Forms_and_User_Login.png">';?><h3 style="margin-left: 15px; font-size: large; display: inline; float: inherit;">RegistrationMagic – Custom Registration Forms and User Login</h3>
                </td>
                <td style="text-align: center; ">
                    <input type="checkbox" name="" checked>
                </td>
                <td>
                </td>
            </tr>

        </table>
    </div>
    <div style="float: left;">
        <br>
        <b style="color: red; " >**If you want to enable/disable 2FA prompt on other Custom login pages please Contact us.</b>
        <br>
        <b style="color: red;" >**This feature will only work when you enable 2FA prompt on wordpress login page.</li></b>

        <p style="font-size:15px">If there is any custom login form where Two Factor is not initiated for you, plese reach out to us by dropping a query in the <b>Support</b> section.</p>
    </div>
</div>
