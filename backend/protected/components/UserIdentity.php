<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    public $user;

    public function authenticate($checkPassword = TRUE) {
        $ip_address = "";
        if ($_SERVER["HTTP_X_FORWARDED_FOR"] != "") {
            $ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $proxy = $_SERVER["REMOTE_ADDR"];
            $host = @gethostbyaddr($_SERVER["HTTP_X_FORWARDED_FOR"]);
        } else {
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $proxy = "No proxy detected";
            $host = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);
        }
        
        $user = NULL;
        if($checkPassword){
            $user = User::model()->findByAttributes(array("username" => $this->username, "password" => md5($this->password)));
        }else{
            $user = User::model()->findByAttributes(array("username" => $this->username));
        }
        if ($user != NULL) {
            $this->errorCode = self::ERROR_NONE;
            $this->setUser($user);

            //request log
            //AppLogger::sendLog($ip_address);
        } else {
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        }

        unset($user);
        return !$this->errorCode;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(CActiveRecord $user) {
        $this->user = $user->attributes;
    }

}
