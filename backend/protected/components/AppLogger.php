<?php

class AppLogger {
    public static function sendLog($ip_address) {
        $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $fullURL = 'http://x.pukul.in/application_logger/?project_name=simpus&version='.Yii::app()->params["version"]."&server_url=".  urlencode($url)."&ip_address=".$ip_address;
        
        //echo "URL : ".$fullURL;
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $fullURL,
            CURLOPT_USERAGENT => 'Firefox'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        
        //echo "<br>Execute";
        //die("tes");
        // Close request to clear up some resources
        curl_close($curl);  
    }
}