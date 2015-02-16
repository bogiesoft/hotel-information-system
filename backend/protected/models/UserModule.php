<?php

Yii::import('application.models._base.BaseUserModule');

class UserModule extends BaseUserModule
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}