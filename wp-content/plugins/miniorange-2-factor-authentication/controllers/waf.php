<?php
    global $moWpnsUtility,$mo2f_dirName;
	$mo_wpns_handler 	= new MoWpnsHandler();
	$sqlC 			= $mo_wpns_handler->get_blocked_attacks_count("SQL");
	$rceC 			= $mo_wpns_handler->get_blocked_attacks_count("RCE");
	$rfiC 			= $mo_wpns_handler->get_blocked_attacks_count("RFI");
	$lfiC 			= $mo_wpns_handler->get_blocked_attacks_count("LFI");
	$xssC 			= $mo_wpns_handler->get_blocked_attacks_count("XSS");
	$totalAttacks		= $sqlC+$lfiC+$rfiC+$xssC+$rceC;
	$manualBlocks 		= $mo_wpns_handler->get_manual_blocked_ip_count();
	$realTime		= 0;
	$countryBlocked 	= $mo_wpns_handler->get_blocked_countries();
	$IPblockedByWAF 	= $mo_wpns_handler->get_blocked_ip_waf();
	$totalIPBlocked 	= $manualBlocks+$realTime+$IPblockedByWAF;
	$mo_waf 		= get_site_option('WAFEnabled');
	if(!get_option('mo2f_tour_firewall'))
		update_option('mo2f_tour_firewall',1);
	if($mo_waf)
	{
		$mo_waf = false;
	}
	else
	{
		$mo_waf = true;	
	}


	$path 			= dirname(dirname(__FILE__)).'/includes/images/loader.gif';
	$path 			= explode('plugins', $path);


	$img_loader_url	= plugins_url().'/'.$path[1];
	if($totalIPBlocked>999)
	{
		$totalIPBlocked = strval(intval($totalIPBlocked/1000)).'k+';
	}
	
	if($totalAttacks>999)
	{
		$totalAttacks = strval(intval($totalAttacks/1000)).'k+';
	}
	update_site_option('mo2f_visit_waf',true);

    include $mo2f_dirName . 'views'.DIRECTORY_SEPARATOR.'waf.php';
    



