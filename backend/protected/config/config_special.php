<?php
$actual_link = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];

function getMagetanConfig(){
    return array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'kabupaten_id' => '20',
        'kabupaten' => 'magetan',
        'lokasi' => '-7.6498599,111.3295616',
        'zoom' => "11",
        'antrian' => FALSE,
        'luar_gedung' => array(18, 19, 20),
        'support_kode_lama' => TRUE,
        'enable_captcha' => FALSE,
        'recaptcha_public_key' => "6Ld3QvkSAAAAAGJNY5FTviiP64mpn2FJdL3Sxrz-",
        'recaptcha_private_key' => "6Ld3QvkSAAAAAJFy2y0yC-pvg3gsIQ8-q07DIkP5",
        'version' => '1.0.4'
    );
}

function getGresikConfig(){
    return array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'kabupaten_id' => '25',
        'kabupaten' => 'gresik',
        'lokasi' => '-7.1811016,112.6086986',
        'zoom' => "11",
        'antrian' => FALSE,
        'luar_gedung' => array(18, 19, 20),
        'support_kode_lama' => TRUE,
        'enable_captcha' => FALSE,
        'recaptcha_public_key' => "6Ld3QvkSAAAAAGJNY5FTviiP64mpn2FJdL3Sxrz-",
        'recaptcha_private_key' => "6Ld3QvkSAAAAAJFy2y0yC-pvg3gsIQ8-q07DIkP5",
        'version' => '1.0.4'
    );
}

function getTrialConfig(){
    return array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'kabupaten_id' => '78',
        'kabupaten' => 'trial',
        'lokasi' => '-7.3054051,112.7449717',
        'zoom' => "11",
        'antrian' => FALSE,
        'luar_gedung' => array(18, 19, 20),
        'support_kode_lama' => TRUE,
        'enable_captcha' => FALSE,
        'recaptcha_public_key' => "6Ld3QvkSAAAAAGJNY5FTviiP64mpn2FJdL3Sxrz-",
        'recaptcha_private_key' => "6Ld3QvkSAAAAAJFy2y0yC-pvg3gsIQ8-q07DIkP5",
        'version' => '1.0.4'
    );
}

if ($_SERVER["SERVER_NAME"] == "localhost") {
    return getMagetanConfig();
}else if ($_SERVER["SERVER_NAME"] == "simpus.magetankab.go.id") {
    return getMagetanConfig();
}else if ($_SERVER["SERVER_NAME"] == "simpus.dinkes.gresikkab.go.id") {
    return getGresikConfig();
}else {
    return getTrialConfig();
}
?>