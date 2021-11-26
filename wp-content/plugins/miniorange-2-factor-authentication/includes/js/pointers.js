( function($, MAP) {

    $(document).on( 'MOAdminPointers.setup_done', function( e, data ) {
        e.stopImmediatePropagation();
        MAP.setPlugin( data ); // open first popup
        //  var data1={
        // 'action'    : 'mo_wpns_tour',
        // 'call_type' : 'mo2f_close_tour_details',
        // 'page'      : data.where
        // };
        // jQuery.post(ajaxurl, data1, function(response){
        // });

    
    } );

    $(document).on( 'MOAdminPointers.current_ready', function( e ) {
        e.stopImmediatePropagation();
        MAP.openPointer(); // open a popup
    } );


    MAP.js_pointers = {};        // contain js-parsed pointer objects
    MAP.first_pointer = false;   // contain first pointer anchor jQuery object
    MAP.current_pointer = false; // contain current pointer jQuery object
    MAP.last_pointer = false;    // contain last pointer jQuery object
    MAP.visible_pointers = [];   // contain ids of pointers whose anchors are visible
   
    MAP.hasNext = function( data ) { // check if a given pointer has valid next property
        return typeof data.next === 'string'
            && data.next !== ''
            && typeof MAP.js_pointers[data.next].data !== 'undefined'
            && typeof MAP.js_pointers[data.next].data.id === 'string';
    };

    MAP.isVisible = function( data ) { // check if anchor for given pointer is visible
        return $.inArray( data.id, MAP.visible_pointers ) !== -1;
    };

    // given a pointer object, return its the anchor jQuery object if available
    // otherwise return first available, lookin at next property of subsequent pointers
    MAP.getPointerData = function( data ) {

        var $target = $( data.anchor_id );
        if ( $.inArray(data.id, MAP.visible_pointers) !== -1 ) {
            return { target: $target, data: data };
        }
        $target = false;

        while( MAP.hasNext( data ) && ! MAP.isVisible( data ) ) {
            data = MAP.js_pointers[data.next].data;
            if ( MAP.isVisible( data ) ) {
                $target = $(data.anchor_id);
            }
        }
        return MAP.isVisible( data )
            ? { target: $target, data: data }
            : { target: false, data: false };
    };

    // take pointer data and setup pointer plugin for anchor element
    MAP.setPlugin = function( data ) {
       

        if(data.anchor_id !='#mo2f_save_free_plan_auth_methods_form' && data.anchor_id != '#GoogleAuthenticator_configuration')
        {
            jQuery('#miniOrangeQRCodeAuthentication_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#SecurityQuestions_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#miniOrangeSoftToken_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#miniOrangePushNotification_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#GoogleAuthenticator_thumbnail_2_factor').css('opacity',0.2);                                  
            jQuery('#OTPOverSMS_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#OTPOverEmail_thumbnail_2_factor').css('opacity',0.2);
        }
        else if (data.anchor_id == '#mo2f_choose_app_tour')
        {
            jQuery('input[type=radio][name=authy]').click(function(){
               document.getElementById("mo2f_current_totp").value = "aa";
               delete MAP.visible_pointers[2];
               
            });
            jQuery('input[type=radio][name=lastpass]').click(function(){
                MAP.visible_pointers.push('custom_admin_pointers4_8_52_default-miniorange-2fa-choose_name_on_app');
                document.getElementById("mo2f_current_totp").value = "lpa";
            });
            jQuery('input[type=radio][name=google]').click(function(){
                document.getElementById("mo2f_current_totp").value = "ga";
                MAP.visible_pointers.push('custom_admin_pointers4_8_52_default-miniorange-2fa-choose_name_on_app');
            });
        }
        else if(data.anchor_id == "#GoogleAuthenticator_configuration")
        {
            jQuery('#miniOrangeQRCodeAuthentication_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#SecurityQuestions_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#miniOrangeSoftToken_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#miniOrangePushNotification_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#OTPOverSMS_thumbnail_2_factor').css('opacity',0.2);
            jQuery('#OTPOverEmail_thumbnail_2_factor').css('opacity',0.2);
        }
        jQuery(data.anchor_id).css('position','relative');   
        
        if(data.anchor_id == '#mo2f_save_free_plan_auth_methods_form')
        {
            jQuery('#mo2f_save_premium_plan_auth_methods_form').css('opacity',0.2);
        }  

        if(jQuery(data.anchor_id).is(":visible") || data.anchor_id =='#mo2f_choose_app_tour') {
            
            jQuery('#overlay').show();

        }

 
        var tab = localStorage.getItem("last_tab");
        var data1={
            'action'    : 'mo_wpns_tour',
            'call_type' : 'mo2f_last_visit_tab',
            'tab'       : tab
        };
        jQuery.post(ajaxurl, data1, function(response){
            
        });


        if ( typeof MAP.last_pointer === 'object') {
            MAP.last_pointer.pointer('destroy');
            MAP.last_pointer = false;
        }   
       // jQuery(data.anchor_id).css('top','80px');
        //  jQuery(data.anchor_id).css('opacity',0);



        MAP.current_pointer = false;
        var pointer_data = MAP.getPointerData( data );
        

        if ( ! pointer_data.target || ! pointer_data.data ) {
            return;
        }
        $target = pointer_data.target;
        data = pointer_data.data;
      
        
        $pointer = $target.pointer({
            content: data.title + data.content,
            position: { edge: data.edge, align: data.align },
            close: function() {
                
                jQuery(data.anchor_id).css('z-index','0');
                jQuery('#overlay').hide();
                $.post( ajaxurl, { pointer: data.id, action: 'dismiss-wp-pointer' } );

            }
        });
        MAP.current_pointer = { pointer: $pointer, data: data };
        
        $(document).trigger( 'MOAdminPointers.current_ready' );
    };

    // scroll the page to current pointer then open it
    MAP.openPointer = function() {
        var $pointer = MAP.current_pointer.pointer;
        

        if ( ! typeof $pointer === 'object' ) {
            return;
        }

        $('html, body').animate({ // scroll page to pointer
            scrollTop: $pointer.offset().top-120
        }, 300, function() { // when scroll complete
            
          
            MAP.last_pointer = $pointer;

            var $widget = $pointer.pointer('widget');
            MAP.setNext( $widget, MAP.current_pointer.data );
            $pointer.pointer( 'open' ); // open
        });

        jQuery('#mo2f_save_premium_plan_auth_methods_form').css('opacity',0.2);
                                 

    };

    // if there is a next pointer set button label to "Next", to "Close" otherwise
    MAP.setNext = function( $widget, data ) {

        
        if ( typeof $widget === 'object' ) {
            var $buttons = $widget.find('.wp-pointer-buttons').eq(0);
            var $close = $buttons.find('a.close').eq(0);
            
            $button = $close.clone(true, true).removeClass('close');
            $close_button = $close.clone(true, true).removeClass('close');
            $buttons.find('a.close').remove();
            $button.addClass('button').addClass('button-primary');
            $close_button.addClass('button').addClass('button-primary');

            has_next = false;
            


            if ( MAP.hasNext( data ) ) {
                has_next_data = MAP.getPointerData(MAP.js_pointers[data.next].data);
                has_next = has_next_data.target && has_next_data.data;
                $button.html(MAP.next_label).appendTo($buttons);
                $close_button.html(MAP.close_label).appendTo($buttons);
                jQuery($close_button).css('margin-right','10px');

                jQuery($close_button).click(function (e) {
                    jQuery('#GoogleAuthenticator_thumbnail_2_factor').css('opacity',1);
                    jQuery('#miniOrangeQRCodeAuthentication_thumbnail_2_factor').css('opacity',1);
                    jQuery('#SecurityQuestions_thumbnail_2_factor').css('opacity',1);
                    jQuery('#miniOrangeSoftToken_thumbnail_2_factor').css('opacity',1);
                    jQuery('#miniOrangePushNotification_thumbnail_2_factor').css('opacity',1);
                    jQuery('#mo2f_save_premium_plan_auth_methods_form').css('opacity',1);
                    jQuery('#OTPOverSMS_thumbnail_2_factor').css('opacity',1);
                    jQuery('#OTPOverEmail_thumbnail_2_factor').css('opacity',1);
                    
                
                    var data1={
                    'action'    : 'mo_wpns_tour',
                    'call_type' : 'mo2f_close_tour_details',
                    'page'      : data.where
                    };
                    jQuery.post(ajaxurl, data1, function(response){
                    });

                    jQuery('#overlay').hide();


                    
                    setTimeout(function () {
                        jQuery('#dismiss_pointers').submit();
                    }, 1000);
                });
            }
            else
            {

                var label = has_next ? MAP.next_label : MAP.close_label;
                jQuery($button).css('margin-right','10px');
                $button.html(label).appendTo($buttons);
                jQuery($button).click(function (e) {
                    var data1={
                        'action'    : 'mo_wpns_tour',
                        'call_type' : 'mo2f_close_tour_details',
                        'page'      : data.where
                    };
                    jQuery.post(ajaxurl, data1, function(response){
                      jQuery('#mo2f_save_premium_plan_auth_methods_form').css('opacity',1);
                
                    });
                });
            }

            
            jQuery($button).click(function () {
                
                var data1={
                    'action'    : 'mo_wpns_tour',
                    'call_type' : 'mo2f_visit_page_tour_details',
                    'index'     : data.index
                };
                jQuery.post(ajaxurl, data1, function(response){
                    
                });

                if(data.isdefault ==='yes')
                {
                    jQuery(data.anchor_id).css('position','');  

                    switch(data.anchor_id){
                        case '#mo2f_save_free_plan_auth_methods_form':
                            //jQuery(data.anchor_id).css('opacity',0.2);
                            jQuery('#miniOrangeQRCodeAuthentication_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#SecurityQuestions_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#miniOrangeSoftToken_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#miniOrangePushNotification_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#OTPOverSMS_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#OTPOverEmail_thumbnail_2_factor').css('opacity',0.2);
                            break;

                        case '#GoogleAuthenticator_configuration':
                           // configureOrSet2ndFactor_free_plan('GoogleAuthenticator', 'configure2factor');                        
                            //document.getElementById('setup_2fa_div').style.display = 'none';
                            //document.write('<?php mo2f_configure_google_authenticator(wp_get_current_user()); ?>');
                            jQuery('#GoogleAuthenticator_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#miniOrangeQRCodeAuthentication_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#SecurityQuestions_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#miniOrangeSoftToken_thumbnail_2_factor').css('opacity',0.2);
                            jQuery('#miniOrangePushNotification_thumbnail_2_factor').css('opacity',0.2);
                            
                            jQuery('#test').css('position','relative');   
                            //$("#setup_2fa_div").empty();
                            break;
                        case '#displayGAQrCodeTour':
                            $(data.anchor_id).removeAttr("style");
                            break;
                        case '#SaveOTPGATour':
                            $("#mo2f_go_back_form").submit();
                            break;
                        case '#test':
                            jQuery('#test').css('position','');
                            jQuery('#unlimittedUser_2fa').css('position','relative');   
                            document.getElementById("unlimittedUser_2fa").click();
                            break;      
                        case '#mo2f_inline_registration_tour':
                            jQuery('#custom_form_2fa').css('position','relative');   
                            jQuery('#custom_form_2fa_div').css('position','relative');   
                            jQuery('#unlimittedUser_2fa').css('position','');   
                            document.getElementById("custom_form_2fa").click();
                            break;                                                       
                        case '#custom_form_2fa_div':
                            jQuery('#custom_form_2fa_div').css('position','');
                            jQuery('#custom_form_2fa').css('position','');    
                            jQuery('#custom_login_2fa').css('z-index',1); 
                            document.getElementById("custom_login_2fa").click();
                            jQuery('#premium_feature_phone_lost').css('position','relative');
                            jQuery('#premium_feature_specific_method').css('position','relative');
                            jQuery('#premium_feature_login_screen_option').css('position','relative');
                            jQuery('#premium_feature_user_enrollment').css('position','relative');
                            jQuery('#premium_feature_skip_option').css('position','relative');
                            break;
                        case '#custom_login_2fa':
                            jQuery('#premium_feature_phone_lost').css('position','');
                            jQuery('#premium_feature_specific_method').css('position','');
                            jQuery('#premium_feature_login_screen_option').css('position','');
                            jQuery('#premium_feature_user_enrollment').css('position','');
                            jQuery('#premium_feature_skip_option').css('position','');
                           
                            jQuery('#custom_login_2fa').removeAttr("style");
                            jQuery('#custom_login_2fa').css('position','');
                            jQuery('#mo_2fa_upgrade_tour').css('position','relative');
                            jQuery('#mo_2fa_upgrade_tour').css('z-index',1);
                            
                            document.getElementById("setup_2fa").click();
                            break;
                        case  '#mo_2fa_upgrade_tour':
                            jQuery('#mo_wpns_support_layout_tour').css('position','relative');
                            break;
                        case '#mo_wpns_support_layout_tour':
                            jQuery('#GoogleAuthenticator_thumbnail_2_factor').css('opacity',1);
                            jQuery('#miniOrangeQRCodeAuthentication_thumbnail_2_factor').css('opacity',1);
                            jQuery('#SecurityQuestions_thumbnail_2_factor').css('opacity',1);
                            jQuery('#miniOrangeSoftToken_thumbnail_2_factor').css('opacity',1);
                            jQuery('#miniOrangePushNotification_thumbnail_2_factor').css('opacity',1);
                            jQuery('#OTPOverSMS_thumbnail_2_factor').css('opacity',1);
                            jQuery('#OTPOverEmail_thumbnail_2_factor').css('opacity',1);
                            break;
                   
                        
                    }
                }
                else if(data.isfirewall == 'yes')
                {
                    jQuery(data.anchor_id).css('position','');  
                    

                    switch(data.anchor_id){
                        case '#mo2f_waf_block_after':
                            document.getElementById("RateLimitTab").click();
                            break;
                        case '#mo2f_ratelimiting':
                            document.getElementById("defaultOpen").click();
                            break;
                        case '#mo2f_firewall_attack_dash':
                            jQuery('#mo_2fa_upgrade_tour').css('z-index',1);
                            break;
                        case '#mo_wpns_support_layout_tour':
                            break;
                    
                    }

                }
                else if(data.loginSpam == 'yes')
                {
                    jQuery(data.anchor_id).css('position','');  
                    
                    switch(data.anchor_id){
                        case '#mo2f_enforce_strong_password_div':
                            document.getElementById("reg_sec").click();
                            break;
                        case '#mo2f_block_registration':
                            document.getElementById("spam_content").click();
                            break;
                        case '#mo2f_comment_protection':
                            document.getElementById("login_sec").click();
                            jQuery('#mo_2fa_upgrade_tour').css('z-index',1);
                            break;
                        case '#mo_wpns_support_layout_tour':
                            break;
                    }
                }
                else if(data.ismalware == 'yes')
                {
                    jQuery(data.anchor_id).css('position','');  
                    switch(data.anchor_id){
                        case '#scan_status_table':
                            document.getElementById("scan_set").click();
                            break;
                        case '#mo2f_select_scanning_files':
                            document.getElementById("report_scan").click();
                            break;
                        case '#scan_report_table':
                            document.getElementById("malware_view").click();
                            break;
                        case '#mo2f_scan_dash':
                            jQuery('#mo_2fa_upgrade_tour').css('z-index',1);
                            break;
                        case '#mo_wpns_support_layout_tour':
                            break;
                    }
                }
                
                else if(data.advcblock == 'yes')
                {
                    jQuery(data.anchor_id).css('position','');  
                    if(data.anchor_id == '#mo2f_ip_lookup')
                    {
                        document.getElementById("adv_block_subtab").click();
                    }
                    else if(data.anchor_id =='#mo2f_browser_blocking')
                    {
                         $('html, body').animate({ // scroll page to pointer
                            scrollTop: $pointer.offset().top+30
                        }, 100, function() { // when scroll complete
                            
                            MAP.last_pointer = $pointer;
                            var $widget = $pointer.pointer('widget');
                            MAP.setNext( $widget, MAP.current_pointer.data );
                            $pointer.pointer( 'open' ); // open
                        });
                  
                    }
                    else if(data.anchor_id =='#mo2f_country_blocking')
                        jQuery('#mo_2fa_upgrade_tour').css('z-index',1);
                    
                         
                }
                else if(data.isBackup =='yes')
                {
                    jQuery(data.anchor_id).css('position','');  
                    switch(data.anchor_id){
                        case '#mo2f_select_files_backup':
                            document.getElementById("schdule").click();
                            break;
                        case '#mo2f_schedule_backup_status':
                             document.getElementById("report").click();
                             break;
                        case '#backup_report_table':
                            jQuery('#mo_2fa_upgrade_tour').css('z-index',1);
                            document.getElementById('backup_set').click();
                            break;
                        case '#mo_wpns_support_layout_tour':
                            break;
                    }
                }

                if ( MAP.hasNext( data ) ) {
                    MAP.setPlugin( MAP.js_pointers[data.next].data );

                }
            });
        }
    };

    $(MAP.pointers).each(function(index, pointer) { // loop pointers data 
        
        if( ! $().pointer ) return;      // do nothing if pointer plugin isn't available
        MAP.js_pointers[pointer.id] = { data: pointer };
        var $target = $(pointer.anchor_id);
        
        if ( $target.length) { // anchor exists and is visible?
            MAP.visible_pointers.push(pointer.id);
            if ( ! MAP.first_pointer ) {
                MAP.first_pointer = pointer;
            }
        }
        if ( index === ( MAP.pointers.length - 1 ) && MAP.first_pointer ) {
            $(document).trigger( 'MOAdminPointers.setup_done', MAP.first_pointer );
        }
    });

} )(jQuery, MOAdminPointers); // MOAdminPointers is passed by `wp_localize_script`