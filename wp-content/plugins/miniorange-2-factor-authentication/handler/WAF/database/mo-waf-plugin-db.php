<?php
	
	include_once('mo-waf-db-common.php');
	function setting_file()
	{
		global $prefix,$dbcon;
        $dir_name    = dirname(__FILE__);
        $dir_name1   = explode('wp-content', $dir_name);
        $dir_name    = $dir_name1[0];
        $filepath    = str_replace('\\', '/', $dir_name1[0]);
        $fileName    = $filepath.'/wp-includes/mo-waf-config.php';
        $missingFile = 0;
        if(!file_exists($fileName))
        {
            $missingFile = 1;
        }
        if($missingFile==1)
        {
        	$file 	= fopen($fileName, "a+");
			$string = "<?php".PHP_EOL;
			$string	.= '$SQL='.get_option("SQLInjection").';'.PHP_EOL;
			$string .= '$XSS='.get_option("XSSAttack").';'.PHP_EOL;
			$string .= '$RFI='.get_option("RFIAttack").';'.PHP_EOL;
			$string .= '$LFI='.get_option("LFIAttack").';'.PHP_EOL;
			$string .= '$RCE='.get_option("RCEAttack").';'.PHP_EOL;
			$string .= '$RateLimiting='.get_option("Rate_limiting").';'.PHP_EOL;
			$string .= '$RequestsPMin='.get_option("Rate_request").';'.PHP_EOL;

			if(get_option('actionRateL') == 0)
				$string .= '$actionRateL="ThrottleIP";'.PHP_EOL;
			else
				$string .= '$actionRateL="BlockIP";'.PHP_EOL;
		
			$string .= '?>'.PHP_EOL;
			fwrite($file, $string);
			fclose($file);
			return $fileName;
        }
        return "notMissing";

	}
	
	function getRLEAttack($ipaddress)
	{
		global $wpdb;
		$query 	 = "select time from ".$wpdb->base_prefix."wpns_attack_logs where ip ='".$ipaddress."' ORDER BY time DESC LIMIT 1;";
		$results = $wpdb->get_results($query);
		return $results[0]->time;
	}
	function log_attack($ipaddress,$value1,$value)
    {
        global $wpdb;
        $value      = htmlspecialchars($value);
        $query      = 'insert into '.$wpdb->base_prefix.'wpns_attack_logs values ("'.$ipaddress.'","'.$value1.'",'.time().',"'.$value.'");';
        $results 	= $wpdb->get_results($query);
		$query      = "select count(*) as count from ".$wpdb->base_prefix."wpns_attack_logs where ip='".$ipaddress."' and input != 'RLE';";
        $results 	= $wpdb->get_results($query);
        return $results[0]->count;
    }
  	

	function CheckRate($ipaddress)
	{
		global $wpdb;
		$time 		= 60;
		clearRate($time);
        insertRate($ipaddress);
	    $query = "select count(*) as count from ".$wpdb->base_prefix."wpns_ip_rate_details where ip='".$ipaddress."';";
		$results = $wpdb->get_results($query);

	    if(isset($results[0]->count))
	    {
	    	return $results[0]->count;
	    }
	    return 0;
	    
	}
	function clearRate($time)
	{
		global $wpdb;
		$query = "delete from ".$wpdb->base_prefix."wpns_ip_rate_details where time<".(time()-$time);
	    $results = $wpdb->get_results($query);
	}
	function insertRate($ipaddress)
	{
		global $wpdb;
		$query = "insert into ".$wpdb->base_prefix."wpns_ip_rate_details values('".$ipaddress."',".time().");";
		$results = $wpdb->get_results($query);
	}

?>