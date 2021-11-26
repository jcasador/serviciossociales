<?php 
	
	global $moWpnsUtility,$mo2f_dirName;
	$mo_wpns_handler 	= new MoWpnsHandler();

	if(current_user_can( 'manage_options' )  && isset($_POST['option']))
	{
		switch($_POST['option'])
		{
			case "mo_wpns_manual_block_ip":
				wpns_handle_manual_block_ip($_POST['IP']);			break;
			case "mo_wpns_unblock_ip":
				wpns_handle_unblock_ip($_POST['id']);			break;
			case "mo_wpns_whitelist_ip":
				wpns_handle_whitelist_ip($_POST['IP']);				break;
			case "mo_wpns_remove_whitelist":
				wpns_handle_remove_whitelist($_POST['id'] );	break;
		}
	}

	$blockedips 		= $mo_wpns_handler->get_blocked_ips();
	$whitelisted_ips 	= $mo_wpns_handler->get_whitelisted_ips();
	$path 			= dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'loader.gif';
	$path 			= explode('plugins', $path);
	$img_loader_url	= plugins_url().$path[1];

	$page_url			= "";
	$license_url		= add_query_arg( array('page' => 'mo_2fa_upgrade'), $_SERVER['REQUEST_URI'] );


/** IP BLOCKING RELATED FUNCTIONS **/

	// Function to handle Manual Block IP form submit
	function wpns_handle_manual_block_ip($ip)
	{
		
		global $moWpnsUtility;	

		if( $moWpnsUtility->check_empty_or_null( $ip) )
		{
			//Improper message
			echo("empty IP");
			exit;
		} 
		if(!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',$ip))
		{
			echo("INVALID_IP_FORMAT");
			exit;
		}
		else
		{
			$ipAddress 		= sanitize_text_field( $ip );
			$mo_wpns_config = new MoWpnsHandler();
			$isWhitelisted 	= $mo_wpns_config->is_whitelisted($ipAddress);
			if(!$isWhitelisted)
			{
				if($mo_wpns_config->is_ip_blocked($ipAddress)){
					//Change message
					echo("already blocked");	
					exit;
				} else{
					$mo_wpns_config->block_ip($ipAddress, MoWpnsConstants::BLOCKED_BY_ADMIN, true);
					//not in structures
					?>
					<table id="blockedips_table1" class="display">
				<thead><tr><th>IP Address&emsp;&emsp;</th><th>Reason&emsp;&emsp;</th><th>Blocked Until&emsp;&emsp;</th><th>Blocked Date&emsp;&emsp;</th><th>Action&emsp;&emsp;</th></tr></thead>
				<tbody>
<?php					
				$mo_wpns_handler 	= new MoWpnsHandler();
				$blockedips 		= $mo_wpns_handler->get_blocked_ips();
				$whitelisted_ips 	= $mo_wpns_handler->get_whitelisted_ips();
				global $mo2f_dirName;
				foreach($blockedips as $blockedip)
				{
	echo 			"<tr class='mo_wpns_not_bold'><td>".$blockedip->ip_address."</td><td>".$blockedip->reason."</td><td>";
					if(empty($blockedip->blocked_for_time)) 
	echo 				"<span class=redtext>Permanently</span>"; 
					else 
	echo 				date("M j, Y, g:i:s a",$blockedip->blocked_for_time);
	echo 			"</td><td>".date("M j, Y, g:i:s a",$blockedip->created_timestamp)."</td><td><a  onclick=unblockip('".$blockedip->id."')>Unblock IP</a></td></tr>";
				} 
	?>
					</tbody>
					</table>
					<script type="text/javascript">
						jQuery("#blockedips_table1").DataTable({
						"order": [[ 3, "desc" ]]
						});
					</script>
					<?php
					exit;
				}
			}
			else
			{
				// Change message
				echo("IP_IN_WHITELISTED");
				exit;
			}
		}
	}


	// Function to handle Manual Block IP form submit
	function wpns_handle_unblock_ip($entryID)
	{
		global $moWpnsUtility;
		
		if( $moWpnsUtility->check_empty_or_null($entryID))
		{
			// Change message
			echo("UNKNOWN_ERROR");
			exit;
		}
		else
		{
			$entryid 		= sanitize_text_field($entryID);
			$mo_wpns_config = new MoWpnsHandler();
			$mo_wpns_config->unblock_ip_entry($entryid);
			//not is structure
					?>
				<table id="blockedips_table1" class="display">
				<thead><tr><th>IP Address&emsp;&emsp;</th><th>Reason&emsp;&emsp;</th><th>Blocked Until&emsp;&emsp;</th><th>Blocked Date&emsp;&emsp;</th><th>Action&emsp;&emsp;</th></tr></thead>
				<tbody>
<?php					
				$mo_wpns_handler 	= new MoWpnsHandler();
				$blockedips 		= $mo_wpns_handler->get_blocked_ips();
				$whitelisted_ips 	= $mo_wpns_handler->get_whitelisted_ips();
				global $mo2f_dirName;
				foreach($blockedips as $blockedip)
				{
	echo 			"<tr class='mo_wpns_not_bold'><td>".$blockedip->ip_address."</td><td>".$blockedip->reason."</td><td>";
					if(empty($blockedip->blocked_for_time)) 
	echo 				"<span class=redtext>Permanently</span>"; 
					else 
	echo 				date("M j, Y, g:i:s a",$blockedip->blocked_for_time);
	echo 			"</td><td>".date("M j, Y, g:i:s a",$blockedip->created_timestamp)."</td><td><a onclick=unblockip('".$blockedip->id."')>Unblock IP</a></td></tr>";
				} 
	?>
					</tbody>
					</table>
					<script type="text/javascript">
						jQuery("#blockedips_table1").DataTable({
						"order": [[ 3, "desc" ]]
						});
					</script>
					<?php
			
			exit;
		}
	}


	// Function to handle Whitelist IP form submit
	function wpns_handle_whitelist_ip($ip)
	{
		global $moWpnsUtility;
		if( $moWpnsUtility->check_empty_or_null($ip))
		{
			//change message
			echo("EMPTY IP");
			exit;
		}
		if(!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',$ip))
		{			//change message
				echo("INVALID_IP");
				exit;
		}
		else
		{
			$ipAddress = sanitize_text_field($ip);
			$mo_wpns_config = new MoWpnsHandler();
			if($mo_wpns_config->is_whitelisted($ipAddress))
			{
				//change message
				echo("IP_ALREADY_WHITELISTED");
				exit;
			}
			else
			{
				$mo_wpns_config->whitelist_ip($ip);
				//Structures issues
				$mo_wpns_handler 	= new MoWpnsHandler();
				$whitelisted_ips 	= $mo_wpns_handler->get_whitelisted_ips();
					
			?>
				<table id="whitelistedips_table1" class="display">
				<thead><tr><th >IP Address</th><th >Whitelisted Date</th><th >Remove from Whitelist</th></tr></thead>
				<tbody>
				<?php
					foreach($whitelisted_ips as $whitelisted_ip)
					{
						echo "<tr class='mo_wpns_not_bold'><td>".$whitelisted_ip->ip_address."</td><td>".date("M j, Y, g:i:s a",$whitelisted_ip->created_timestamp)."</td><td><a  onclick=removefromwhitelist('".$whitelisted_ip->id."')>Remove</a></td></tr>";
					} 

	
				?>
				</tbody>
				</table>
			<script type="text/javascript">
				jQuery("#whitelistedips_table1").DataTable({
				"order": [[ 1, "desc" ]]
				});
			</script>

	<?php
			exit;
			}
		}
	}


	// Function to handle remove whitelisted IP form submit
	function wpns_handle_remove_whitelist($entryID)
	{
		global $moWpnsUtility;
		if( $moWpnsUtility->check_empty_or_null($entryID))
		{
			//change Message
			echo("UNKNOWN_ERROR");
			exit;
		}
		else
		{
			$entryid = sanitize_text_field($entryID);
			$mo_wpns_config = new MoWpnsHandler();
			$mo_wpns_config->remove_whitelist_entry($entryid);
			//structures
				$mo_wpns_handler 	= new MoWpnsHandler();
				$whitelisted_ips 	= $mo_wpns_handler->get_whitelisted_ips();
					
			?>
				<table id="whitelistedips_table1" class="display">
				<thead><tr><th >IP Address</th><th >Whitelisted Date</th><th >Remove from Whitelist</th></tr></thead>
				<tbody>
			<?php
					foreach($whitelisted_ips as $whitelisted_ip)
					{
						echo "<tr class='mo_wpns_not_bold'><td>".$whitelisted_ip->ip_address."</td><td>".date("M j, Y, g:i:s a",$whitelisted_ip->created_timestamp)."</td><td><a onclick=removefromwhitelist('".$whitelisted_ip->id."')>Remove</a></td></tr>";
					} 

	
			?>
				</tbody>
				</table>
			<script type="text/javascript">
				jQuery("#whitelistedips_table1").DataTable({
				"order": [[ 1, "desc" ]]
				});
			</script>

		<?php
			exit;
		}
	}

	