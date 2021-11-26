<?php

 $file_backup_time = MoWpnsUtility::get_mo2f_db_option('file_backup_created_time', 'site_option');
 $db_eb_backup_time = MoWpnsUtility::get_mo2f_db_option('db_backup_created_time', 'site_option');
 $file_schedule_status = MoWpnsUtility::get_mo2f_db_option('scheduled_file_backup', 'site_option');
 $db_backup_status = MoWpnsUtility::get_mo2f_db_option('scheduled_db_backup', 'site_option');
 $next_file_backup_hours = MoWpnsUtility::get_mo2f_db_option('mo_wpns_backup_time', 'site_option');
 $next_db_backup_hours = MoWpnsUtility::get_mo2f_db_option('mo_wpns_backup_time', 'site_option');
 $img_loader_url		= plugins_url('backup-wordpress'.DIRECTORY_SEPARATOR .'includes'.DIRECTORY_SEPARATOR .'images'.DIRECTORY_SEPARATOR .'loader.gif');
 $page_url			= "";
 $file_next_backup_timestamp = wp_next_scheduled( 'mo_eb_file_cron_hook' );
 $db_next_backup_timestamp   = wp_next_scheduled( 'mo_eb_bl_cron_hook' );

 $file_date = date('d-m-Y', $file_next_backup_timestamp);
 $file_time = date('H:i', $file_next_backup_timestamp);
 $file_day  = date('l',$file_next_backup_timestamp);

 $db_date = date('d-m-Y', $db_next_backup_timestamp);
 $db_time = date('H:i', $db_next_backup_timestamp);
 $db_day  = date('l',$db_next_backup_timestamp);

include_once $mo2f_dirName . 'views'.DIRECTORY_SEPARATOR.'backup'.DIRECTORY_SEPARATOR.'backup_schdule.php';