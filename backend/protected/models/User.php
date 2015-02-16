<?php

Yii::import('application.models._base.BaseUser');

class User extends BaseUser {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function login() {
        $user = User::model()->findByPk(Yii::app()->user->id);
        $user->last_login = date("Y-m-d H:i:s");
        $user->save();
    }

    public static function logout() {
        $user = User::model()->findByPk(Yii::app()->user->id);
        $user->last_logout = date("Y-m-d H:i:s");
        $user->save();
    }

}
