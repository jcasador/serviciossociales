<?php
class Mo_wpns_file_db_backup{

	function __construct(){
		add_action( 'admin_init'  , array( $this, 'mo_wpns_file_db_backup_functions' ) );
}

public function mo_wpns_file_db_backup_functions(){
		add_action('wp_ajax_mo_wpns_backup_redirect', array( $this, 'mo_wpns_backup_redirect' ));
}

public function mo_wpns_backup_redirect(){
	      
     switch($_POST['call_type'])
		  {
			case "submit_backup_settings_form":
				$this->mo_wpns_save_backup_config_form($_POST);
				break;
			case "submit_schedule_settings_form":
        $this->mo_wpns_save_schedule_backup_config_form($_POST);
        break;
      case "delete_backup":
         $this->delete_backup($_POST);
         break;
		 }
}

public function mo_wpns_save_backup_config_form($postData){
		$nonce = $postData['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'wpns-backup-settings' ) ){
      wp_send_json('ERROR');
    }
    
    if(! isset($postData['backup_plugin']) && ! isset($postData['backup_themes']) && ! isset($postData['backup_wp_files'])  && ! isset($postData['database'])){
      wp_send_json('folder_error');
    } 

    isset($postData['backup_plugin']) ?  update_site_option( 'mo_file_manual_backup_plugins', sanitize_text_field($postData['backup_plugin'])) : update_site_option( 'mo_file_manual_backup_plugins', 0);

    isset($postData['backup_themes']) ? update_site_option( 'mo_file_manual_backup_themes', sanitize_text_field($postData['backup_themes'])) : update_site_option( 'mo_file_manual_backup_themes', 0);

    isset($postData['backup_wp_files']) ? update_site_option( 'mo_file_manual_backup_wp_files', sanitize_text_field($postData['backup_wp_files'])) : update_site_option( 'mo_file_manual_backup_wp_files', 0);

    isset($postData['database']) ? update_site_option( 'mo_database_backup', sanitize_text_field($postData['database'])) : update_site_option( 'mo_database_backup', 0);

    if(isset($postData['backup_plugin']) || isset($postData['backup_themes']) || isset($postData['backup_wp_files'])){
      $handler_obj = new MoBackupSite();
      update_site_option('file_backup_created_time',date("l").' , '.date("d-m-Y") .'  '.date("h:i"));
      $handler_obj->file_manual_backup();
     }
     if(isset($postData['database'])) {
      $handler_obj = new MoBackupSite();
      update_site_option('db_backup_created_time',date("l").' , '.date("d-m-Y") .'  '.date("h:i"));
      $handler_obj->backupDB();
     }
   wp_send_json('created_backup');
}

function mo_wpns_save_schedule_backup_config_form($postData){
    $nonce = $postData['nonce'];
        if ( ! wp_verify_nonce( $nonce, 'wpns-schedule-backup' ) ){
          wp_send_json('ERROR');
          
        }

    $handler_obj = new MoBackupSite;
      if(!isset($postData['backup_plugin']) && ! isset($postData['backup_themes']) && ! isset($postData['backup_wp_files']) && ! isset($postData['database']))
      {
      wp_send_json('folder_error');
      
     } 

    isset($postData['backup_plugin']) ?  update_site_option( 'mo_file_backup_plugins', sanitize_text_field($postData['backup_plugin'])) : update_site_option( 'mo_file_backup_plugins', 0);

    isset($postData['backup_themes']) ? update_site_option( 'mo_file_backup_themes', sanitize_text_field($postData['backup_themes'])) : update_site_option( 'mo_file_backup_themes', 0);

    isset($postData['backup_wp_files']) ? update_site_option( 'mo_file_backup_wp_files', sanitize_text_field($postData['backup_wp_files'])) : update_site_option( 'mo_file_backup_wp_files', 0);

    isset($postData['database']) ? update_site_option( 'mo_schedule_database_backup', sanitize_text_field($postData['database'])) : update_site_option( 'mo_schedule_database_backup', 0);

    if($postData['backup_time']==='12'||$postData['backup_time']==='24'||$postData['backup_time']==='168'||$postData['backup_time']==='360'||$postData['backup_time']==='720')
    {
      isset($postData['backup_time']) ? update_site_option( 'mo_wpns_backup_time', sanitize_text_field($postData['backup_time'])) : update_site_option( 'mo_wpns_backup_time', 0);
    }else{
      wp_send_json('invalid_hours');
     
    }

    isset($postData['enable_backup_schedule']) ? update_site_option( 'enable_backup_schedule', sanitize_text_field($postData['enable_backup_schedule'])) : update_site_option( 'enable_backup_schedule', 0);

    isset($postData['local_storage']) ? update_site_option( 'storage_type', sanitize_text_field($postData['local_storage'])) : update_site_option( 'storage_type', 0); 
     
     if(get_site_option('enable_backup_schedule') === '1'){
     
        if(isset($postData['backup_plugin']) || isset($postData['backup_themes']) || isset($postData['backup_wp_files'])){
            $handler_obj-> file_backup_deactivate();
             if (!wp_next_scheduled( 'mo_eb_file_cron_hook')) {
                 wp_schedule_event( time(), 'file_eb_backup_time', 'mo_eb_file_cron_hook' );
                }
                update_site_option('file_backup_created_time',date("l").' , '.date("d-m-Y") .'  '.date("h:i"));
                update_site_option('scheduled_file_backup',1);
        } 
        else
                $handler_obj-> file_backup_deactivate();

        if(MoWpnsUtility::get_mo2f_db_option('mo_schedule_database_backup', 'site_option') === '1'){
               $handler_obj->bl_deactivate();
                if ( ! wp_next_scheduled( 'mo_eb_bl_cron_hook' ) ) {
                    wp_schedule_event( time(), 'db_eb_backup_time', 'mo_eb_bl_cron_hook' );
                } 
                update_site_option('db_backup_created_time',date("l").' , '.date("d-m-Y") .'  '.date("h:i"));
                update_site_option('scheduled_db_backup',1);
          }
          else
               $handler_obj->bl_deactivate();

        wp_send_json('success');
               
     }else{
       $handler_obj-> file_backup_deactivate();
        $handler_obj->bl_deactivate();
        update_site_option('scheduled_db_backup',0);
        update_site_option('scheduled_file_backup',0);
        wp_send_json('disable');
        
     }
}



function delete_backup($postData){
   
      $nonce = $postData['nonce'];
        if ( ! wp_verify_nonce( $nonce, 'delete_entry' ) ){
          wp_send_json('ERROR');
        
        }
    
    if(current_user_can('administrator')){ 
      global $wpnsDbQueries;
      $id = $postData['id'];
      $row_exist = (int)$wpnsDbQueries->row_exist($id);
      $status = file_exists($postData["folder_name"].DIRECTORY_SEPARATOR. $postData['file_name']);
       if($status){
          unlink($postData["folder_name"].DIRECTORY_SEPARATOR. $postData['file_name']);
            if($row_exist)
              $wpnsDbQueries->delete_file($id);
          wp_send_json('success');
         
        }else{
         $wpnsDbQueries->delete_file($id);
          wp_send_json('notexist');
      } 
  }
}
}new Mo_wpns_file_db_backup();
?>