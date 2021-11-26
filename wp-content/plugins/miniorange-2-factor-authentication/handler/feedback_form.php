<?php
class FeedbackHandler
{
    function __construct()
    {
        add_action('admin_init', array($this, 'mo_wpns_feedback_actions'));
    }

    function mo_wpns_feedback_actions()
    {

        global $moWpnsUtility, $mo2f_dirName;

        if (current_user_can('manage_options') && isset($_POST['option'])) {
            switch ($_REQUEST['option']) {
                case "mo_wpns_skip_feedback": 
                case "mo_wpns_rating":             
                case "mo_wpns_feedback":                    
                  $this->wpns_handle_feedback($_POST);                     break;
                case "mo_wpns_backup_download":
                  $this->mo2f_backup_download($_POST);
                   break;

            }
        }
    }


    function wpns_handle_feedback($postdata)
    {
       
		if(MO2F_TEST_MODE){
			deactivate_plugins(dirname(dirname(__FILE__ ))."\\miniorange_2_factor_settings.php");
                return;
		}
		
        $user = wp_get_current_user();
        $feedback_option = $_POST['option'];
        if ($feedback_option != "mo_wpns_rating") 
        {
           $message = 'Plugin Deactivated';
        }
        

        $deactivate_reason_message = array_key_exists('wpns_query_feedback', $_POST) ? htmlspecialchars($_POST['wpns_query_feedback']) : false;
        $activation_date = get_site_option('mo2f_activated_time'); 
        $current_date = time();
        $diff = $activation_date - $current_date;
        if($activation_date == false){
            $days = 'NA';
        }
        else{
            $days = abs(round($diff / 86400)); 
        }

        if ($feedback_option != "mo_wpns_rating") 
        {
            $reply_required = '';
            if (isset($_POST['get_reply']))
                $reply_required = htmlspecialchars($_POST['get_reply']);

            if (empty($reply_required)) {
                $reply_required = "don't reply";
                $message .= ' &nbsp; [Reply:<b style="color:red";>' . $reply_required . '</b>,';
            } else {
                $reply_required = "yes";
                $message .= '[Reply:' . $reply_required . ',';
            }
        }
        else
        {
           $message ='[' ;
        }
        $message .= 'D:' . $days . ',';
        if(MoWpnsUtility::get_mo2f_db_option('mo_wpns_2fa_with_network_security', 'get_option')){
            $message .= '2FA+NS]'; 
        }
        else{
            $message .= '2FA]';   
        }

        $message .= ', Feedback : ' . $deactivate_reason_message . '';

        if (isset($_POST['rate']))
            $rate_value = htmlspecialchars($_POST['rate']);
		else
			$rate_value = "--";
        $message .= ', [Rating :' . $rate_value . ']';
        if (empty($reply_required))
        $message .= MoWpnsUtility::mo_2fa_send_configuration();
        else
        $message .= MoWpnsUtility::mo_2fa_send_configuration(true);
        $email = isset($_POST['query_mail'])? $_POST['query_mail']: '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = get_option('mo2f_email');
            if (empty($email))
                $email = $user->user_email;
        }
        $phone = get_option('mo_wpns_admin_phone');
        $feedback_reasons = new MocURL();
        global $moWpnsUtility;
        if (!is_null($feedback_reasons)) {
            if (!$moWpnsUtility->is_curl_installed()) {
                deactivate_plugins(dirname(dirname(__FILE__ ))."\\miniorange_2_factor_settings.php");
                wp_redirect('plugins.php');
            } else {
                $submited = json_decode($feedback_reasons->send_email_alert($email, $phone, $message, $feedback_option), true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    if (is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR') {
                        do_action('wpns_show_message',$submited['message'],'ERROR');

                    } else {
                        if ($submited == false) {
                            do_action('wpns_show_message','Error while submitting the query.','ERROR');
                        }
                    }
                }

            if($feedback_option =='mo_wpns_feedback' || $feedback_option =='mo_wpns_skip_feedback')
            {
                deactivate_plugins(dirname(dirname(__FILE__ ))."\\miniorange_2_factor_settings.php");
            }
                do_action('wpns_show_message','Thank you for the feedback.','SUCCESS');

            }
        }
    }

    function mo2f_backup_download($postdata){
    global $wpnsDbQueries;
    
    $nonce = $postdata['download_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'mo-wpns-download-nonce' ) ){
         do_action('wpns_show_message',MoWpnsMessages::showMessage('NONCE_ERROR'),'ERROR');
          return;
        }

     ob_start();
     if(current_user_can('administrator')){
        $file_name=$postdata['file_name'];
        $file_path=$postdata['file_path'];
        $file = explode('/', $file_name);
        $file_name = $file[0];
        $id = $file[1];
        $status = file_exists($file_path.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$file_name);
        if($status){
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$file_name);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($file_path.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$file_name));
        while (ob_get_level()) {
            ob_end_clean();
            @readfile($file_path.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$file_name);
            exit;
        }
      }else{
        $wpnsDbQueries->delete_file($id);
        do_action('wpns_show_message',MoWpnsMessages::showMessage('DELETE_FILE'),'ERROR');
        return;
      } 
    }else{
        do_action('wpns_show_message',MoWpnsMessages::showMessage('NOT_ADMIN'),'ERROR');
        return;
    }
    
    }

}new FeedbackHandler();
