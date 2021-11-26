<?php

$pointers = array();
$tab= 'default';
if(array_key_exists('tab',$_GET))
    $tab = $_GET['tab'];

if(MoWpnsUtility::get_mo2f_db_option('mo2f_two_factor_tour', 'get_option') ==1)

{
    $pointers['default-miniorange-2fa-select-authentication'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Select Authentication Method (Step 1 out of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Choose your Two Factor authentication method.' ) ),
        'anchor_id' => '#mo2f_save_free_plan_auth_methods_form',
        'isdefault' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'middle',
        'index'     => 'default-miniorange-2fa-select-authentication',
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    $pointers['default-miniorange-2fa-configure'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Click on configure(Step 2 out of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Setup the two-factor authentication here.' ) ),
        'anchor_id' => '#GoogleAuthenticator_configuration',
        'isdefault' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-configure',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    
    $pointers['default-miniorange-2fa-choose_app'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Choose the app type(Step 1 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Choose the app which you want to use as the second factor' ) ),
        'anchor_id' => '#mo2f_choose_app_tour',
        'isdefault' => 'yes',
        'edge'      => 'left',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-choose_app1',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );

    $pointers['default-miniorange-2fa-download_app'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Download app(Step 2 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'If you do not have app in your phone then you can donwload the app here.' ) ),
        'anchor_id' => '#links_to_apps_tour',
        'isdefault' => 'yes',
        'edge'      => 'left',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-download_app1',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    
    
    $pointers['default-miniorange-2fa-scan-qrcode'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Scan the QR code(Step 3 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Scan the QR code with your app on your phone.' ) ),
        'anchor_id' => '#displayGAQrCodeTour',
        'isdefault' => 'yes',
        'edge'      => 'left',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-scan-qrcode1',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    $pointers['default-miniorange-2fa-choose_name_on_app'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Choose app name(Step 4 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can choose the app name which you want to display on your app for the code.' ) ),
        'anchor_id' => '#mo2f_change_app_name',
        'isdefault' => 'yes',
        'edge'      => 'left',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-choose_name_on_app1',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );

    $pointers['default-miniorange-2fa-enter_code_manually'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Can\'t scan the QR code?(Step 5 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'If you can not scan the QR code then you can follow these steps to configure the two-factor without scanning the code.' ) ),
        'anchor_id' => '#mo2f_scanbarcode_a',
        'isdefault' => 'yes',
        'edge'      => 'left',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-enter_code_manually1',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    
    $pointers['default-miniorange-2fa-enter-otp'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Enter the OTP(Step 6 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'After Scanning the QR code please enter the OTP generated in the app on your phone.' ) ),
        'anchor_id' => '#EnterOTPGATour',
        'isdefault' => 'yes',
        'edge'      => 'right',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-enter-otp1',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    $pointers['default-miniorange-2fa-save-otp'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Verify and Save(Step 7 of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Verify and Save the google-authentication code.' ) ),
        'anchor_id' => '#SaveOTPGATour',
        'isdefault' => 'yes',
        'edge'      => 'right',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-save-otp1',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    $pointers['default-miniorange-2fa-test'] = array(
          'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Test the method(Step 3 out of 9).' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'After configuring the 2-factor you can test it here by clicking on Test button.' ) ),
        'anchor_id' => '#test',
        'isdefault' => 'yes',
        'edge'      => 'right',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-test',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );

    $pointers['default-miniorange-2fa-customizations'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Temporary disable two-factor(Step 4 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'While testing if you need to disable the plugin. You can do it from here.' ) ),
        'anchor_id' => '#disable_two_factor_tour',
        'isdefault' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-customizations',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
     $pointers['default-miniorange-2fa-inline-registration'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'User Enrollment(Step 5 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can force two-factor setup of login for other user here.' ) ),
        'anchor_id' => '#mo2f_inline_registration_tour',
        'isdefault' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-inline-registration',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    $pointers['default-minorange-2fa-integration'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Integrate 2fa with custom forms(Step 6 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'We support almost all worpdress forms and some popular forms are listed here. If your form is not in the list you can contact us.' ) ),
        'anchor_id' => '#custom_form_2fa_div',
        'isdefault' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'middle',
        'index'     => 'default-minorange-2fa-integration',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    $pointers['default-minorange-2fa-premium-features'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Premium features (Step 7 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check what features you will get in the premium and upgrade to your preferred plan.' ) ),
        'anchor_id' => '#custom_login_2fa',
        'isdefault' => 'yes',
        'edge'      => 'left',
        'align'     => 'left',
        'index'     => 'default-minorange-2fa-premium-features',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    
    $pointers['default-miniorange-2fa-upgrade'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Upgrade your plan(step 8 out of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the premium features and upgrade your plan here.' ) ),
        'anchor_id' => '#mo_2fa_upgrade_tour',
        'isdefault' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-upgrade',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
   $pointers['default-miniorange-2fa-support_open'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Contact us!!(step 9 out of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Need Help? We are just one click away.' ) ),
        'anchor_id' => '#mo_wpns_support_layout_tour',
        'isdefault' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'right',
        'index'     => 'default-miniorange-2fa-support_open',    
        'where'     => array( 'toplevel_page_mo_2fa_two_fa' ) // <-- Please note this
    );
    
    
}
if(get_option('mo2f_tour_firewall') == 1 ){
    $pointers['default-miniorange-firewall-level'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Choose your level of the firewall(step 1 out of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Choose on which level you want to enable firewall. htaccess level is the recommended one.' ) ),
        'anchor_id' => '#mo_waf_options_tour',
        'isfirewall'=> 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-firewall-level',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_waf' ) // <-- Please note this
    );
    $pointers['default-miniorange-firewall-attacks'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Select the types of attacks you want to stop.(step 2 out of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Enable attack protection here for different attacks.' ) ),
        'anchor_id' => '#mo2f_AttackTypes',
        'isfirewall'=> 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-firewall-attacks',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_waf' ) // <-- Please note this
    );
    $pointers['default-miniorange-firewall-attack-limit'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Choose attack limit(step 3 out of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Choose the number of attacks an IP can make before getting blocked. If an IP reach the limit it will be blocked on the next attack.' ) ),
        'anchor_id' => '#mo2f_waf_block_after',
        'isfirewall'=> 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-firewall-attack-limit',    
        'align'     => 'left',
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_waf' ) // <-- Please note this
    );
    
    $pointers['default-miniorange-firewall-rate-limit'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Turn on rate limiting(step 4 out of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Turn on rate limiting to protect from Dos attack. Choose request limit and action for rate limiting.' ) ),
        'anchor_id' => '#mo2f_ratelimiting',
        'isfirewall'=> 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-firewall-rate-limit',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_waf' ) // <-- Please note this
    );
    $pointers['default-miniorange-firewall-check-attacks'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Check blocked IPs and attacks.(step 5 out of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the Information about blocked IPs and Attacks here.' ) ),
        'anchor_id' => '#mo2f_firewall_attack_dash',
        'isfirewall'=> 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-firewall-check-attacks',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_waf' ) // <-- Please note this
    );
    $pointers['default-miniorange-2fa-upgrade'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Upgrade your plan (step 6 out of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the premium features and upgrade your plan here.' ) ),
        'anchor_id' => '#mo_2fa_upgrade_tour',
        'isfirewall' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-upgrade',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_waf' ) // <-- Please note this
    );
   
    $pointers['default-miniorange-firewall-support'] = array(
     'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Contact us!!(step 7 out of 7)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Need Help? We are just one click away.' ) ),
        'anchor_id' => '#mo_wpns_support_layout_tour',
        'isfirewall' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-firewall-support',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_waf' ) // <-- Please note this
    );

}

if(get_option('mo2f_tour_malware_scan') ==1){
    $pointers['default-miniorange-malware-scan-modes'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Scanning Modes (Step 1 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Choose the Scanning mode ' ) ),
        'anchor_id' => '#scan_status_table',
        'ismalware' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-malware-scan-modes',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_malwarescan' ) // <-- Please note this
    );
    $pointers['default-miniorange-malware-custom-scan-files'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Select files from custom scan (Step 2 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can select the files you want to scan. Just select the files and start the custom scan' ) ),
        'anchor_id' => '#mo2f_select_scanning_files',
        'ismalware' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-malware-custom-scan-files',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_malwarescan' ) // <-- Please note this
    );
    $pointers['default-miniorange-malware-scan-reports'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Scan report.(Step 3 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the scan report here.' ) ),
        'anchor_id' => '#scan_report_table',
        'ismalware' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-malware-scan-reports',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_malwarescan' ) // <-- Please note this
    );
    
    $pointers['default-miniorange-malware-scan-dashboard'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Scan dashboard (Step 4 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the Information about the files being scanned currently, files scanned in last scans & Infected files' ) ),
        'anchor_id' => '#mo2f_scan_dash',
        'ismalware' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-malware-scan-dashboard',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_malwarescan' ) // <-- Please note this
    );
    $pointers['default-miniorange-2fa-upgrade'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Upgrade your plan(step 5 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the premium features and upgrade your plan here.' ) ),
        'anchor_id' => '#mo_2fa_upgrade_tour',
        'ismalware' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-upgrade',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_malwarescan' ) // <-- Please note this
    );
   
    $pointers['default-miniorange-malware-support'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Contact us!!(step 6 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Need Help? We are just one click away.' ) ),
        'anchor_id' => '#mo_wpns_support_layout_tour',
        'ismalware' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-malware-support',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_malwarescan' ) // <-- Please note this
    );

}

if(get_option('mo2f_tour_advance_blocking') ==1){
    $pointers['default-miniorange-advance-blocking-IP-blocking'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Manual IP Blocking (Step 1 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can block a specific IP. Access for that IP will be blocked for your site.' ) ),
        'anchor_id' => '#mo2f_manual_ip_blocking',
        'advcblock' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-IP-blocking',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
    $pointers['default-miniorange-advance-blocking-IP-whitelisting'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Whitelist IP (Step 2 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can Whitelist a specific IP. The IP will never get blocked on your site.' ) ),
        'anchor_id' => '#mo2f_ip_whitelisting',
        'advcblock' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-IP-whitelisting',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
   
   $pointers['default-miniorange-advance-blocking-IP-lookup'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Lookup IP(Step 3 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can get details of an IP here. Example country, city, etc.' ) ),
        'anchor_id' => '#mo2f_ip_lookup',
        'advcblock' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-IP-lookup',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
   

    $pointers['default-miniorange-advance-blocking-IP-range'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'IP range Blocking.(Step 4 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can block a specific range of IPs. Access from those IP will be blocked for your site.' ) ),
        'anchor_id' => '#mo2f_ip_range_blocking',
        'advcblock' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-IP-range',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
    $pointers['default-miniorange-advance-blocking-htaccess-blocking'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Htaccess Blocking (Step 5 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'htaccess level blocking will block the IP before wordpress load on your site. So it will minimize server resources from illegitimate users.' ) ),
        'anchor_id' => '#mo2f_htaccess_blocking',
        'advcblock' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-htaccess-blocking',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
    $pointers['default-miniorange-advance-blocking-browser-blocking'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Browser Blocking (Step 6 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can block specific browser from which you don\'t want users to access.'  ) ),
        'anchor_id' => '#mo2f_browser_blocking',
        'advcblock' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-browser-blocking',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
    $pointers['default-miniorange-advance-blocking-country-blocking'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Country Blocking (Step 7 of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can choose the countries from where you don\'t want access to your site.' ) ),
        'anchor_id' => '#mo2f_country_blocking',
        'advcblock' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-country-blocking',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
    
     $pointers['default-miniorange-2fa-upgrade'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Upgrade your plan (step 8 out of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the premium features and upgrade your plan here.' ) ),
        'anchor_id' => '#mo_2fa_upgrade_tour',
        'advcblock' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-upgrade',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );
    $pointers['default-miniorange-advance-blocking-support'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Contact us!!(step 9 out of 9)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Need Help? We are just one click away.' ) ),
        'anchor_id' => '#mo_wpns_support_layout_tour',
        'advcblock' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-advance-blocking-support',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_advancedblocking' ) // <-- Please note this
    );

}


if(get_option('mo2f_tour_backup') == 1  ){
    $pointers['default-miniorange-backup-manual-db'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Manual database backup.(Step 1 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can take manual database backup here.The backup will be saved in your uploads directory.' ) ),
        'anchor_id' => '#mo2f_select_files_backup',
        'isBackup'=> 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-backup-manual-db',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_backup' ) // <-- Please note this
    );
    $pointers['default-miniorange-backup-auto-db'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Scheduled/Automated Database backups.(Step 2 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'With the help of this you can specify the time duration after which an automatic backup will be taken.' ) ),
        'anchor_id' => '#mo2f_auto_dbbackup',
        'isBackup'=> 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-backup-auto-db',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_backup' ) // <-- Please note this
    );
    $pointers['default-miniorange-backup-file'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Auto backup status(Step 3 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the auto backup status.' ) ),
        'anchor_id' => '#mo2f_schedule_backup_status',
        'isBackup'=> 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-backup-file',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_backup' ) // <-- Please note this
    );
    $pointers['default-miniorange-backup-report'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Report of backups.(Step 4 of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check backup taken details.' ) ),
        'anchor_id' => '#backup_report_table',
        'isBackup'=> 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-backup-report',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_backup' ) // <-- Please note this
    );

    $pointers['default-miniorange-2fa-upgrade'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Upgrade your plan (step 5 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the premium features and upgrade your plan here.' ) ),
        'anchor_id' => '#mo_2fa_upgrade_tour',
        'isBackup' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-upgrade',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_backup' ) // <-- Please note this
    );
    $pointers['default-miniorange-backup-support'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Contact us!!(step 6 out of 6)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Need Help? We are just one click away.' ) ),
        'anchor_id' => '#mo_wpns_support_layout_tour',
        'isBackup' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-backup-support',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_backup' ) // <-- Please note this
    );

}

if(get_option('mo2f_tour_loginSpam') == 1){
    $pointers['default-miniorange-login-spam-bruteforce'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Enable BruteForce protection.(step 1 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Choose the number of attempts before blocking an IP on login page. It will protect you from bruteforce attack.' ) ),
        'anchor_id' => '#mo2f_bruteforce',
        'loginSpam' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-login-spam-bruteforce',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
    $pointers['default-miniorange-login-spam-recaptcha'] = array(
        'title'     => sprintf( '<h3>%s</h3>',  esc_html__( 'Enable google reCaptcha.(step 2 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Enable google reCaptcha ' ) ),
        'anchor_id' => '#mo2f_google_recaptcha',
        'loginSpam' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-login-spam-recaptcha',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
    $pointers['default-miniorange-login-spam-strong-pass'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Enforce strong password(step 3 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Enforce strong password to your users so that their account will not get hacked easily.' ) ),
        'anchor_id' => '#mo2f_enforce_strong_password_div',
        'loginSpam' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-login-spam-strong-pass',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
    
    $pointers['default-miniorange-login-spam-fake-registration'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Turn on block fake registration(step 4 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'This will block fake registration on your site.' ) ),
        'anchor_id' => '#mo2f_block_registration',
        'loginSpam' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-login-spam-fake-registration',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
    $pointers['default-miniorange-login-spam-content'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Content Protection.(step 5 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can protect your content which is directly accessible from path/URL by anyone.' ) ),
        'anchor_id' => '#mo2f_content_protection',
        'loginSpam' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-login-spam-content',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
    $pointers['default-miniorange-login-spam-block-spam'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Block Spam Comment(Step 6 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Block automated scripts and bots on comment.' ) ),
        'anchor_id' => '#mo2f_comment_protection',
        'loginSpam' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-login-spam-block-spam',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
    $pointers['default-miniorange-2fa-upgrade'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Upgrade your plan(step 7 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'You can check the premium features and upgrade your plan here.' ) ),
        'anchor_id' => '#mo_2fa_upgrade_tour',
        'loginSpam' => 'yes',
        'edge'      => 'top',
        'align'     => 'left',
        'index'     => 'default-miniorange-2fa-upgrade',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
    
    $pointers['default-miniorange-login-spam-support'] = array(
        'title'     => sprintf( '<h3>%s</h3>', esc_html__( 'Contact us!!(step 8 out of 8)' ) ),
        'content'   => sprintf( '<p>%s</p>', esc_html__( 'Need Help? We are just one click away.' ) ),
        'anchor_id' => '#mo_wpns_support_layout_tour',
        'loginSpam' => 'yes',
        'edge'      => 'bottom',
        'align'     => 'left',
        'index'     => 'default-miniorange-login-spam-support',    
        'where'     => array( 'miniorange-2-factor_page_mo_2fa_login_and_spam' ) // <-- Please note this
    );
  

}






return $pointers;
