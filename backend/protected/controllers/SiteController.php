<?php

class SiteController extends Controller {

    public $baseName = "Home";
    public $actionName = "List Data";
    
    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    
    protected function beforeAction($action) {
        $controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;

        if ($controller == "site" && $action == "login") {
            return TRUE;
        }
        
        if ($controller == "site" && $action == "logout") {
            return TRUE;
        }

        if (Yii::app()->user == NULL) {
            $this->redirect(Yii::app()->request->baseUrl . "/site/login");
        } else {
            if (Yii::app()->user->getId() != NULL) {
                $log = new UserLog();
                $log->time = date("Y-m-d H:i:s");
                $log->user_id = Yii::app()->user->id;
                $log->user_level_id = Yii::app()->user->level_id;
                $log->path = $_SERVER["REQUEST_URI"];
                $log->data = json_encode($_POST);
                $log->is_ajax = 0;
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $log->is_ajax = 1;
                }
                $log->save();
                return TRUE;
            } else {
                $this->redirect(Yii::app()->request->baseUrl . "/site/login");
            }
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->render('index');
    }
    
    public function actionDashboard($id) {
        $this->render('index', array("puskesmas_id"=>$id));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $this->layout = "//layouts/login";

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            
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
            if(Yii::app()->params["enable_captcha"] && LogIpBlacklist::getCount($ip_address) >= 3){
                Yii::import('ext.recaptcha.recaptchalib', TRUE);
                $resp = recaptcha_check_answer (Yii::app()->params["recaptcha_private_key"],
                                              $_SERVER["REMOTE_ADDR"],
                                              $_POST["recaptcha_challenge_field"],
                                              $_POST["recaptcha_response_field"]);

                if (!$resp->is_valid) {
                  // What happens when the CAPTCHA was entered incorrectly
                  die ("Kode Verifikasi Salah !, Mohon Ulangi Kembali." .
                       "No. " . md5(date("Y-m-d H:i:s")) . " (Pesan Kesalahan : " . $resp->error . ")");
                } else {
                    // validate user input and redirect to the previous page if valid
                    if ($model->validate() && $model->login())
                        $this->redirect(Yii::app()->user->returnUrl);
                }
            }else{
                if ($model->validate() && $model->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            
        }

        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        User::logout();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}
