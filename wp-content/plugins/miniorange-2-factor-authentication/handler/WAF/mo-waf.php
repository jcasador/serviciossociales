<?php
	$dir        = dirname(__FILE__);
    $dir        = str_replace('\\', "/", $dir);
    $dir        = explode('WAF', $dir);
    $wafInclude = $dir[0].'WAF/waf-include.php';
    $wafdb      = $dir[0].'WAF/database/mo-waf-db.php';
    $errorPage  = $dir[0].'mo-error.html';
    $blockPage  = $dir[0].'mo-block.html';

    include_once($wafInclude);
    include_once($wafdb);

    global $dbcon,$prefix;	
    $connection = dbconnection();
    if($connection)
	{
        $wafLevel = get_option_value('WAF');
        if($wafLevel=='HtaccessLevel')
        {
            $ipaddress = get_ipaddress();
            if(is_ip_blocked($ipaddress))
            {
                if(!is_ip_whitelisted($ipaddress))
                {
                    header('HTTP/1.1 403 Forbidden');
                    include_once($blockPage);
                    exit;
                }
            }
            $fileName = setting_file();

            if($fileName != 'notMissing')
            {
                include_once($fileName);
            }
            if(isset($RateLimiting) && $RateLimiting == 1)
            {
                if(!is_crawler())
                {
                    if(isset($RequestsPMin) && isset($actionRateL))
                        applyRateLimiting($RequestsPMin,$actionRateL,$ipaddress,$errorPage);
                }
            }
            if(isset($RateLimitingCrawler) && $RateLimitingCrawler == 1)
            {
                if(is_crawler())
                {
                    if(is_fake_googlebot($ipaddress))
                    {
                        header('HTTP/1.1 403 Forbidden');
                        include_once($errorPage);
                         exit;
                    }
                    if($RateLimitingCrawler == '1')
                    {
                        applyRateLimitingCrawler($ipaddress,$fileName,$errorPage); 
                    }

                }
            }
            $attack = array();
            if(isset($SQL) && $SQL==1)
            {
                array_push($attack,"SQL");
            }
            if(isset($XSS) && $XSS==1)
            {
                array_push($attack,"XSS");
            }
            if(isset($LFI) && $LFI==1)
            {
                array_push($attack,"LFI");
            }
			
            $attackC        = $attack;
            $ParanoiaLevel  = 1;
            $annomalyS      = 0;
            $SQLScore       = 0;
            $XSSScore       = 0;
            $limitAttack    = get_option_value("limitAttack");

            foreach ($attackC as $key1 => $value1)
            {
                for($lev=1;$lev<=$ParanoiaLevel;$lev++)
                {
                    if(isset($regex[$value1][$lev]))
                    {	$ooo = 0;
                        for($i=0;$i<sizeof($regex[$value1][$lev]);$i++)
                        {
                            foreach ($_REQUEST as $key => $value) {

                                if($regex[$value1][$lev][$i] != "")
                                {
                                    if(is_string($value))
                                    {
                                        if(preg_match($regex[$value1][$lev][$i], $value))
                                        {
                                           
                                            if($value1 == "SQL")
                                            {
                                                $SQLScore += $score[$value1][$lev][$i];
                                            }
                                            elseif ($value1 == "XSS")
                                            {
                                                $XSSScore += $score[$value1][$lev][$i];
                                            }
                                            else
                                            {
                                                $annomalyS += $score[$value1][$lev][$i];
                                            }

                                            if($annomalyS>=5 || $SQLScore>=10 || $XSSScore >=10)
                                            {
                                                $attackCount = log_attack($ipaddress,$value1,$value);
                                                if($attackCount>$limitAttack)
                                                {
                                                    if(!is_ip_whitelisted($ipaddress))
                                                    {
                                                        block_ip($ipaddress,'Attack limit Exceeded');         //Attack Limit Exceed
                                                    }
                                                }

                                                header('HTTP/1.1 403 Forbidden');
                                                include_once($errorPage);
                                                 exit;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    
    function applyRateLimiting($reqLimit,$action,$ipaddress,$errorPage)
    {
        global $dbcon, $prefix;
        $rate = CheckRate($ipaddress);
        if($rate>$reqLimit)
        {
            $lastAttack     = getRLEattack($ipaddress)+60;
            $current_time   = time();
            if($current_time > $lastAttack)
            {
                log_attack($ipaddress,'RLE','RLE');
            }
            if($action != 'ThrottleIP')
            {
               if(!is_ip_whitelisted($ipaddress))
                {
                    block_ip($ipaddress,'RLE');     //Rate Limit Exceed
                }
            }
            header('HTTP/1.1 403 Forbidden');
            include_once($errorPage);
             exit;        
        }
    }
    
    function applyRateLimitingCrawler($ipaddress,$filename,$errorPage)
    {
        if(file_exists($filename))
        {
            include($filename);
        }
        global $dbcon,$prefix;
        $USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        if(isset($RateLimitingCrawler))
        {
            if(isset($RateLimitingCrawler) && $RateLimitingCrawler=='1')
            {
                if(isset($RequestsPMinCrawler) && isset($actionRateLCrawler) )
                {
                    $reqLimit   = $RequestsPMinCrawler;
                    $rate       = CheckRate($ipaddress);
                    if($rate>=$reqLimit)
                    {
                        $action         = $actionRateLCrawler;
                        $lastAttack     = getRLEattack($ipaddress)+60;
                        $current_time   = time();
                        if($current_time>$lastAttack)
                        {
                            log_attack($ipaddress,'RLECrawler',$USER_AGENT);
                        }
                        if($action != 'ThrottleIP')
                        {
                           if(!is_ip_whitelisted($ipaddress))
                            {
                                block_ip($ipaddress,'RLECrawler');      //Rate Limit Exceed for Crawler
                            }
                        }
                        header('HTTP/1.1 403 Forbidden');
                        include_once($errorPage);
                         exit;
                    } 
                }
            } 
        }
    }

	
	$dbcon->close();
?>