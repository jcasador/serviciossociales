<?php 
$setup_dirName = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'twofa'.DIRECTORY_SEPARATOR.'link_tracer.php';
        global $current_user;
        $current_user = wp_get_current_user();
        ?>
        
        <div class="mo_wpns_setting_layout">
            
            
            <div id="mo2f_hide_shortcode_content" >
                <h1>3. Shortcode
                    <span style="text-align: right;font-size: large;"><?php echo '<a href="'.$addons_url     .'" style="color: red">'; ?>[ PREMIUM ]</a></span>
                </h1><hr>
                <h3><?php echo __( 'List of Shortcodes', 'miniorange-2-factor-authentication' ); ?><a href='<?php echo $two_factor_premium_doc['Shortcode'];?>' target="_blank"><span class="dashicons dashicons-text-page" style="font-size:19px;color:#269eb3;float: right;"></span></a></h3>
                <ol style="margin-left:2%">
                    <li>
                        <b><?php echo __( 'Enable Two Factor: ', 'miniorange-2-factor-authentication' ); ?></b> <?php echo __( 'This shortcode provides an option to turn on/off 2-factor by user.', 'miniorange-2-factor-authentication' ); ?>
                    </li>
                    <li>
                        <b><?php echo __( 'Enable Reconfiguration: ', 'miniorange-2-factor-authentication' ); ?></b> <?php echo __( 'This shortcode provides an option to configure the Google Authenticator and Security Questions by user.', 'miniorange-2-factor-authentication' ); ?>
                    </li>
                    <li>
                        <b><?php echo __( 'Enable Remember Device: ', 'miniorange-2-factor-authentication' ); ?></b> <?php echo __( ' This shortcode provides\'Enable Remember Device\' from your custom login form.', 'miniorange-2-factor-authentication' ); ?>
                    </li>
                </ol>
            </div>
            <h3><?php echo mo2f_lt('Shortcodes');?></h3>
            <hr>
            <div style="margin-left:2%">
                <p>1. <b style="font-size:16px;color: #0085ba;">[miniorange_enable2fa]</b> :<?php echo mo2f_lt(' Add this shortcode to provide
                    the option to turn on/off 2-factor by user.');?><br><br>
                    2. <b style="font-size:16px;color: #0085ba;">[mo2f_enable_reconfigure]</b> : <?php echo mo2f_lt('Add this shortcode to
                    provide the option to configure the Google Authenticator and Security Questions by user.');?><br>
                    <br>
                    3. <b style="font-size:16px;color: #0085ba;">[mo2f_enable_rba_shortcode]</b> :<?php echo mo2f_lt(' Add this shortcode to
                    \'Enable Remember Device\' from your custom login form.');?>
                </p>

                <form name="f" id="custom_login_form" method="post" action="">
                    <?php echo mo2f_lt('Enter the id of your custom login form to use \'Enable Remember Device\' on the login page:');?>
                    <input type="text" class="mo2f_table_textbox" id="mo2f_rba_loginform_id"
                           name="mo2f_rba_loginform_id" <?php 
                        echo 'disabled';
                    ?> value="<?php echo get_option('mo2f_rba_loginform_id') ?>"/>
                    <br><br>
                    <input type="hidden" name="option" value="custom_login_form_save"/>
                    <input type="submit" name="submit" value="Save Settings" style="background-color: #20b2aa; color: white;" class="mo_wpns_button mo_wpns_button1" <?php
                    
                        echo 'disabled';
                     ?> />
                </form>
            </div>
        </div>