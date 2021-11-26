<?php
global $mo2f_dirName;
$setup_dirName = $mo2f_dirName.'views'.DIRECTORY_SEPARATOR.'twofa'.DIRECTORY_SEPARATOR.'link_tracer.php';
 include $setup_dirName;
add_action('admin_footer','mo_wpns_schedule_backup');
?>


	<div class="mo_wpns_setting_layout" id = "mo2f_schedule_backup_status">
	<br>
	<table class="mo_wpns_settings_table font_class">
		<tr>
			<th>Scheduled file backup </th>
			<th>Scheduled database backup </th>
			<th><a href='<?php echo $two_factor_premium_doc['Scheduled database'];?>' target="_blank"><span class="dashicons dashicons-text-page" style="font-size:23px;color:#269eb3;margin-top: 0.5em;float: right;"></span></a></th>
		</tr>
		<tr><td>&nbsp;</td><td></td></tr>
		<tr>
			
             <td><b>Scheduled Status :</b><?php 
             if(MoWpnsUtility::get_mo2f_db_option('scheduled_file_backup', 'site_option')){ 
				 ?><span class="mo_green" >Enabled</span><?php
				  }  else{
				  ?><span class="mo_green">Disabled</span><?php
				   } 
			?></td>
			<td><b>Scheduled Status :</b><?php 
			if(MoWpnsUtility::get_mo2f_db_option('scheduled_db_backup', 'site_option')){ 
				?><span class="mo_green" >Enabled</span><?php
				 }  else{
				  ?><span class="mo_green">Disabled</span>
				<?php }
			 ?></td>

		</tr>
		
		<tr>
			<td><b>Last Backup :</b><?php 
			if($file_backup_time !== 0) echo $file_backup_time ; 
			?></td>	
			<td><b>Last Backup :</b><?php 
			if($db_eb_backup_time !== 0) echo $db_eb_backup_time ; 
			?></td>

		</tr>
		<tr>
			<td><b>Next Backup :</b><?php
			 if($file_schedule_status == 0){ echo 'N/A';
			    }  else{ echo $file_day.' '.$file_date.' '.$file_time ; 
			 }
		     ?></td>
			<td><b>Next Backup :</b>
				<?php if($db_backup_status == 0){ echo 'N/A';
			}  else{ echo $db_day.' '.$db_date.' '.$db_time ; 
				 } 
			?></td>

		</tr>
	</table>
	
</div>
<div class="mo_wpns_setting_layout text_size"  id= "mo2f_auto_dbbackup">

	<form id="" method="post" action="">
		<br>
		    <p class="text_size"><b>To automatically create a backup select the following option and save the settings</b></p>
			<input type="checkbox" name="enable_backup_schedule" id="enable_backup_schedule" value="1"<?php checked(get_site_option('enable_backup_schedule') == 1);?>> Enable Backup Schedule<br><br>

	<br>
	     <p class="text_size"><b>Create a backup after every</b></p>
		    <table class="mo_wpns_settings_table " >
		    	<tr>
		    		<td>
		    			<input type="radio"  name="backup_time" value="12" id="hours"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_wpns_backup_time', 'site_option') === '12')?>>12 Hours 
		    		</td>
		    		<td>	
						<input type="radio" name="backup_time" value="24" id="daily"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_wpns_backup_time', 'site_option') === '24')?>> Day
					</td>
					<td>	
						<input type="radio" name="backup_time" value="168" id="weekly"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_wpns_backup_time', 'site_option') === '168')?>>Week
					</td>
				 </tr>
				 <tr>
				  <td>	
						<input type="radio" name="backup_time" value="360" id="fortnight"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_wpns_backup_time', 'site_option') === '360')?>> Fortnight
				</td>
				<td>		
						<input type="radio" name="backup_time" value="720" id="month"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_wpns_backup_time', 'site_option') === '720')?>> Month
		    	</td>
		    	</tr>
		    </table>	    
	   <br>
	   <p class="text_size"><b>Choose the following folder to backup</b></p>
		    <table class="mo_wpns_settings_table ">
		    	<tr>
		    		<td>
						<input type="checkbox" name="mo_schedule_file_backup_plugins" id="mo_schedule_plugins"  value="1"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_file_backup_plugins', 'site_option') == 1);?>> WordPress Plugins folder
					</td>
					<td>	
						<input type="checkbox" name="mo_schedule_file_backup_themes" id="mo_schedule_themes" value="1"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_file_backup_themes','site_option') == 1);?>> WordPress Themes folder
                    </td>
                </tr>
                <tr>
                    <td>    
						<input type="checkbox" name="mo_schedule_file_backup_wp_files" onclick="check1()" value="1"<?php checked(get_site_option('mo_file_backup_wp_files') == 1);?>> WordPress Files
					</td>
					<td>	
						<input type="checkbox" name="mo_schedule_database_backup" id="mo_database_backup" value="1"<?php checked(MoWpnsUtility::get_mo2f_db_option('mo_schedule_database_backup', 'site_option') == 1);?>> Database

			       </td>
		    	</tr>
		    </table>	    
     
	    	
	<br>
	<p class="text_size">After checking the <b>enable backup schedule</b> checkbox, a backup will be created once you click on save setting and another backup will be created automatically after the scheduled time you select.</p>
	<input type = "hidden" id = "wpns_schedule_backup_url" value="<?php echo wp_create_nonce('wpns-schedule-backup') ?>" >
<input type="button"  class="mo_wpns_scan_button"  name="save_schedule_settings" id="save_schedule_settings" value ="Save Settings" style="width:120px;" />

	
</div>	
</form>	

<?php
 function mo_wpns_schedule_backup(){
 ?><script type="text/javascript">
	
   	 jQuery(document).ready(function(){
	 jQuery('#save_schedule_settings').click(function(){
	 	var data={
			'action':'mo_wpns_backup_redirect',
			'call_type':'submit_schedule_settings_form',
			'backup_plugin':jQuery('input[name= "mo_schedule_file_backup_plugins"]:checked').val(),
			'backup_themes':jQuery('input[name= "mo_schedule_file_backup_themes"]:checked').val(),
			'backup_wp_files':jQuery('input[name= "mo_schedule_file_backup_wp_files"]:checked').val(),
			'database':jQuery('input[name= "mo_schedule_database_backup"]:checked').val(),
			'backup_time':jQuery('input[name= "backup_time"]:checked').val(),
			'local_storage':jQuery('input[name= "local_storage"]:checked').val(),
			'enable_backup_schedule':jQuery('input[name= "enable_backup_schedule"]:checked').val(),
			'nonce' : jQuery('#wpns_schedule_backup_url').val(),
			
		};
		

		jQuery.post(ajaxurl, data, function(response){
		
			if (response == "folder_error"){
                error_msg(" Please select at least one folder to backup");
			}else if(response=="success"){
                success_msg(" Backup Configuration Saved Successfully");
			}
			else if(response=="disable"){
				 jQuery(".add_remove_disable").attr("disabled","disabled");
                		error_msg(" Automatic Backup Disabled");
			}else if(response==="invalid_hours"){
                		error_msg(" Please select valid hours");
			}else if(response==="ERROR"){
                		error_msg("There was an error in procession your request");
			}			

		});
  
       });
	});



	function check1() {
		 if(jQuery('input[name= "mo_schedule_file_backup_wp_files"]:checked').val()){
     			disable_checkbox();
		 }else{
             		enable_checkbox();
		 }
	}
     if(jQuery('input[name= "mo_schedule_file_backup_wp_files"]:checked').val()){
         disable_checkbox();
     }else{
         enable_checkbox();
     }

     function disable_checkbox(){
         jQuery('input[name="mo_schedule_file_backup_plugins"]').attr('disabled', true);
         jQuery('input[name="mo_schedule_file_backup_themes"]').attr('disabled', true);
         jQuery('#mo_schedule_plugins').prop('checked', false); // Unchecks it
         jQuery('#mo_schedule_themes').prop('checked', false); // Unchecks it
     }

     function enable_checkbox(){
         jQuery('input[name="mo_schedule_file_backup_plugins"]').removeAttr('disabled');
         jQuery('input[name="mo_schedule_file_backup_themes"]').removeAttr('disabled');
     }

   </script>
<?php } 
?>
