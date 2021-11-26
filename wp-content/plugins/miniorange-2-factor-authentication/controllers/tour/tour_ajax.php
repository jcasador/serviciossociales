<?php
class Mo_wpns_Tour
{
	function __construct(){
		add_action( 'admin_init'  , array( $this, 'mo_wpns_save_tour_details' ) );

	}

	public function mo_wpns_save_tour_details(){
		if(isset($_REQUEST['page']))
		{
			switch ($_REQUEST['page']) {
				case 'mo_2fa_two_fa':
					if(!MoWpnsUtility::get_mo2f_db_option('mo2f_two_factor_tour', 'get_option'))
						update_option('mo2f_two_factor_tour',1);

					break;
				case 'mo_2fa_waf':
					if(!get_option('mo2f_tour_firewall'))
						update_option('mo2f_tour_firewall',1);
					break;
				case 'mo_2fa_login_and_spam':
					if(!get_option('mo2f_tour_loginSpam'))
						update_option('mo2f_tour_loginSpam',1);
					break;

				case 'mo_2fa_backup':
					if(!get_option('mo2f_tour_backup'))
				    	update_option('mo2f_tour_backup',1);

					break;
				case 'mo_2fa_malwarescan':
					if(!get_option('mo2f_tour_malware_scan'))
						update_option('mo2f_tour_malware_scan',1);

					break;
				case 'mo_2fa_advancedblocking':
					if(!get_option('mo2f_tour_advance_blocking'))
				    update_option('mo2f_tour_advance_blocking',1);
					break;
			
				default:
					break;
			}
		}
		add_action('wp_ajax_mo_wpns_tour', array( $this, 'mo_wpns_tour' ));
		
	}

	public function mo_wpns_tour(){
		switch($_POST['call_type'])
		{
			case "wpns_enable_tour":
				update_option('skip_tour', 0);
				break;
			case "skip_entire_plugin_tour":
				$this->handle_skip_entire_plugin();
				break;
			case 'entire_plugin_tour_started':
				$this->entire_plugin_tour_started();
				break;
			case "mo2f_close_tour_details":
				$this->mo2f_close_tour_details();
				break;
			case "mo2f_visit_page_tour_details":
				$this->mo2f_visit_page_tour_details();
				break;
			case "mo2f_last_visit_tab":
				$this->mo2f_last_visit_tab();
				break;
		}
	}


	function mo2f_last_visit_tab()
	{
		$lasttab = sanitize_text_field($_POST['tab']);
     	update_option('mo2f_tour_tab',$lasttab);
	}
	function mo2f_visit_page_tour_details()
	{
		$currentPointer = '';
		if(isset($_POST['index']))
		$currentPointer = sanitize_text_field($_POST['index']); 

		if(strpos($currentPointer, 'support') != false)
		{
			exit;
		}
		$uid 			= get_current_user_id();
       	$visited 		= get_user_meta($uid,'mo2f_visited_pointers',true);
       	$visited 		= $visited.',custom_admin_pointers4_8_52_'.$currentPointer; 
       	update_user_meta($uid,'mo2f_visited_pointers',$visited);
  	}
  	function entire_plugin_tour_started()
  	{
  		update_option('mo2f_tour_started',3);
        exit;
  	}

	function handle_skip_entire_plugin(){
        update_option('mo2f_two_factor_tour',-1);
    	update_option('mo2f_tour_firewall',-1);
 		update_option('mo2f_tour_malware_scan',-1);
 		update_option('mo2f_tour_advance_blocking',-1);
 		update_option('mo2f_tour_backup',-1);
 		update_option('mo2f_tour_loginSpam',-1);
    	update_option('mo2f_tour_started',3);
        exit;
	}

	
    function mo2f_close_tour_details()
    {
    	$uid  = get_current_user_id();
       	delete_user_meta($uid,'mo2f_visited_pointers');
    	$page = $_POST['page'];
    	$page = sanitize_text_field($page[0]);
    	update_option('mo2f_tour_tab','');
    	update_option("yeah",1);
    	switch ($page) {
    		case 'toplevel_page_mo_2fa_two_fa':
    			update_option('mo2f_two_factor_tour',-1);		
    			break;
    		case 'miniorange-2-factor_page_mo_2fa_waf':
    			update_option('mo2f_tour_firewall',-1);
 				break;
    		case 'miniorange-2-factor_page_mo_2fa_malwarescan':
    			update_option('mo2f_tour_malware_scan',-1);
 				break;
    		case 'miniorange-2-factor_page_mo_2fa_advancedblocking':
    			update_option('mo2f_tour_advance_blocking',-1);
 				break;
    		case 'miniorange-2-factor_page_mo_2fa_backup':
    			update_option('mo2f_tour_backup',-1);
 				break;
    		case 'miniorange-2-factor_page_mo_2fa_login_and_spam':
    			update_option('mo2f_tour_loginSpam',-1);
    			break;
    	}

    }

}
new Mo_wpns_Tour();
?>