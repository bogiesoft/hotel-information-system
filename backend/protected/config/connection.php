<?php
$actual_link = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];

if ($_SERVER["SERVER_NAME"] == "localhost") {
    return array(
        'connectionString' => 'mysql:host=localhost;dbname=hotel',
        'emulatePrepare' => true,
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    );
}else {
    return array(
        'connectionString' => 'mysql:host=localhost;dbname=hotel',
        'emulatePrepare' => true,
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    );
}
?>