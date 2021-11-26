<?php
    $dir                = dirname(__FILE__);
    $dir                = str_replace('\\', "/", $dir);
    $dir                = explode('WAF', $dir);
    $dir                = $dir[0]; 
    $sqlInjectionFile   = $dir.DIRECTORY_SEPARATOR.'signature/APSQLI.php';
    $xssFile            = $dir.DIRECTORY_SEPARATOR.'signature/APXSS.php';
    $lfiFile            = $dir.DIRECTORY_SEPARATOR.'signature/APLFI.php';
    $configfilepath     = explode('wp-content', $dir);
    $configfile         = $configfilepath[0].DIRECTORY_SEPARATOR.'wp-includes/mo-waf-config.php';
    $missingFile        = 0;

    if(file_exists($configfile))
    {
        include_once($configfile);
    }
    else
    {
         $missingFile   = 1;
    }
    include_once($sqlInjectionFile);
    include_once($xssFile);
    include_once($lfiFile);

    function get_ipaddress()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
    function is_crawler()
    {
        $USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $Botsign = array('bot','apache','crawler','elinks','http', 'java', 'spider','link','fetcher','scanner','grabber','collector','capture','seo','.com');
        foreach ($Botsign as $key => $value) 
        {
            if(preg_match('/'.$value.'/', $USER_AGENT)) 
            {
                return true;
            }
        }   
        return false;
    }
    function is_fake_googlebot($ipaddress)
    {
        $USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/Googlebot/', $USER_AGENT))
        {
            if(is_fake('Googlebot',$USER_AGENT,$ipaddress))
            {
                header('HTTP/1.1 403 Forbidden');
                include_once("mo-error.html");
                exit;
            }
        }
    }
    function is_fake($crawler,$USER_AGENT,$ipaddress)
    {  
        // $hostName   =   gethostbyaddr($ipaddress);
        // $hostIP     =   gethostbyname($hostName);
        // if(is_numeric(get_option('mo_wpns_iprange_count')))
        //     $range_count = intval(get_option('mo_wpns_iprange_count'));
        // for($i = 1 ; $i <= $range_count ; $i++){ 
        //     $blockedrange  = get_option('mo_wpns_iprange_range_'.$i);
        //     $rangearray = explode("-",$blockedrange);
        //     if(sizeof($rangearray)==2){
        //         $lowip = ip2long(trim($rangearray[0]));
        //         $highip = ip2long(trim($rangearray[1]));
        //         if(ip2long($userIp)>=$lowip && ip2long($userIp)<=$highip){
        //             $mo_wpns_config = new MoWpnsHandler();
        //             $mo_wpns_config->block_ip($userIp, MoWpnsConstants::IP_RANGE_BLOCKING, true);
        //             return true;
        //         }
        //     }
        // }
        // return false;
    }
?>