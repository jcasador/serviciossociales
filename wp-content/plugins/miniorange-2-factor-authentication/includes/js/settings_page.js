jQuery(document).ready(function () {
	
    $ = jQuery;

	//show and hide instructions
    $("#auth_help").click(function () {
        $("#auth_troubleshoot").toggle();
    });
	$("#conn_help").click(function () {
        $("#conn_troubleshoot").toggle();
    });
	
	$("#conn_help_user_mapping").click(function () {
        $("#conn_user_mapping_troubleshoot").toggle();
    });
	
	//show and hide attribute mapping instructions
    $("#toggle_am_content").click(function () {
        $("#show_am_content").toggle();
    });

	 //Instructions
    $("#mo_wpns_help_curl_title").click(function () {
        $("#mo_wpns_help_curl_desc").slideToggle(600);
    });
	
    $("#mo_wpns_issue_in_scanning_QR").click(function () {
        $("#mo_wpns_issue_in_scanning_QR_solution").slideToggle(600);
    });
	
    $("#mo_wpns_help_get_back_to_account").click(function () {
        $("#mo_wpns_help_get_back_to_account_solution").slideToggle(600);
    });
	
    $("#mo_wpns_help_multisite").click(function () {
        $("#mo_wpns_help_multisite_solution").slideToggle(600);
    });
	
    $("#mo_wpns_help_adv_user_ver_title").click(function () {
        $("#mo_wpns_help_adv_user_ver_desc").slideToggle(600);
    });
    $("#mo_wpns_help_forgot_password").click(function () {
        $("#mo_wpns_help_forgot_password_solution").slideToggle(600);
    });
    $("#mo_wpns_help_MFA_propmted").click(function () {
        $("#mo_wpns_help_MFA_propmted_solution").slideToggle(600);
    });
	
    $("#mo_wpns_help_redirect_back").click(function () {
        $("#mo_wpns_help_redirect_back_solution").slideToggle(600);
    });
     $("#mo_wpns_help_alternet_login").click(function () {
        $("#mo_wpns_help_alternet_login_solution").slideToggle(600);
    });
     $("#mo_wpns_help_lost_ability").click(function () {
        $("#mo_wpns_help_lost_ability_solution").slideToggle(600);
    });
     $("#mo_wpns_help_translate").click(function () {
        $("#mo_wpns_help_translate_solution").slideToggle(600);
    });
     $("#mo_wpns_help_particular_use_role").click(function () {
        $("#mo_wpns_help_particular_use_role_solution").slideToggle(600);
    });
     $("#mo_wpns_help_enforce_MFA").click(function () {
        $("#mo_wpns_help_enforce_MFA_solution").slideToggle(600);
    });
     $("#mo_wpns_help_reset_MFA").click(function () {
        $("#mo_wpns_help_reset_MFA_solution").slideToggle(600);
    });
  
    
    $(".backup_codes_dismiss").click(function(){
        ajaxCall("dismisscodeswarning",".backupcodes-notice",true);
    });

    $(".smtpsetup").click(function(){
         ajaxCall("dissmissSMTP",".smtpsetup-notice",true);
    });

    $(".whitelist_self").click(function(){
        ajaxCall("whitelistself",".whitelistself-notice",true);
    });

    $(".new_plugin_dismiss").click(function(){
        ajaxCall("dismissplugin",".new_plugin_theme-notice",true);
    });


     $(".dismiss_website_backup_notice").click(function(){
        ajaxCall("dismissbackup",".create_backup-notice",true);
    });

   
    $(".dismiss_brute_force_notice").click(function(){
        ajaxCall("dismissbruteforce",".brute_force-notice",true);
    });

    $(".dismiss_google_recaptcha_notice").click(function(){
        ajaxCall("dismissrecaptcha",".google_recaptcha-notice",true);
    });
    $(".dismiss_firewall_notice").click(function(){
        ajaxCall("dismissfirewall",".firewall-notice",true);
    });

    $(".wpns_premium_option :input").attr("disabled",true);

});


function ajaxCall(option,element,hide)
{
    jQuery.ajax({
            url: "",
            type: "GET",
            data: "option="+option,
            crossDomain: !0,
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function(o) {
                if (hide!=undefined)
                    jQuery(element).slideUp();
            },
            error: function(o, e, n) {}
        });
}

function success_msg(msg){ 
jQuery('#wpns_nav_message').empty();
jQuery('#wpns_nav_message').append("<div id='notice_div' class='overlay_success'><div class='popup_text'>&nbsp&nbsp"+msg+"</div></div>");
window.onload =  nav_popup();
}

function error_msg(msg){
jQuery('#wpns_nav_message').empty();
jQuery('#wpns_nav_message').append("<div id='notice_div' class='overlay_error'><div class='popup_text'>&nbsp&nbsp"+msg+"</div></div>");
window.onload =  nav_popup();
}

function nav_popup() {
  document.getElementById("notice_div").style.width = "40%";
  setTimeout(function(){ jQuery('#notice_div').fadeOut('slow'); }, 3000);
}